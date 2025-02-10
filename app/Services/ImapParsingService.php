<?php

namespace App\Services;

use App\Http\Controllers\IncomingEmailsController;
use App\Jobs\SendMail;
use App\Models\EmailAccount;
use App\Models\IncomingEmail;
use App\Models\OutgoingEmail;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Webklex\PHPIMAP\ClientManager;
use Carbon\Carbon;

class ImapParsingService
{
    use DispatchesJobs;
    private $folders = ['INBOX','Sent'];
    private $incomingItemsCount = 0;
    private $outgoingItemsCount = 0;
    private $incomingEmails = [];
    private $outgoingEmails = [];




    public function scanImapServer(EmailAccount $account)
    {

        $cm = new ClientManager($options = []);
        $client = $cm->make([
            'host' => $account->imap_host,
            'port' => $account->imap_port,
            'encryption' => $account->imap_encryption,
            'username' => $account->imap_username,
            'password' => $account->imap_password,
        ]);

        try
        {
            $client->connect();

            if($client->isConnected())
            {

                foreach($this->folders as $folder)
                {

                    if($folder == 'INBOX')
                    {
                        $this->addIncomingEmails($account,$client);
                    } elseif ($folder == 'Sent') {
                        $this->addOutgoingEmails($account,$client);
                    }

                }

                $account->imap_last_execute_items_count = $this->incomingItemsCount + $this->outgoingItemsCount;
                $account->imap_last_execute_time = Carbon::now();
                $account->save();
            }
        }
        catch (\Exception $e)
        {
            Log::error(message:"cannot connect to $account->imap_host IMAP server");
        }
    }

    private function addIncomingEmails(EmailAccount $account, $client)
    {
        $inboxFolder = $client->getFolderByName('INBOX');

        if(!$inboxFolder)
        {
            Log::error('INBOX folder not exist for '.$account->email_address.' account');
            return false;
        }
        $query = $inboxFolder->messages();

        $query
            ->to($account->email_address)
            ->since(Carbon::parse($account->imap_last_execute_time)->subDays($account->imap_scan_days_count))
            ->limit(limit: $account->imap_result_limit)
            ->fetchOrderDesc()->chunked(function($messages, $chunk)  use($account){;
                $messages->each(function($message,$key) use($account)
        {

               set_time_limit (60);
            /** @var \Webklex\PHPIMAP\Message $message */

             if(IncomingEmail::where([
                 ['user_id',  $account->user_id],
                 ['email_accounts_id',  $account->id],
                 ['from', Str::getEmailAddressesFromString($message->getAttributes()['fromaddress'])],
                 ['to', Str::getEmailAddressesFromString($message->getAttributes()['toaddress'])],
                 ['subject', iconv_mime_decode($message->getAttributes()["subject"],ICONV_MIME_DECODE_CONTINUE_ON_ERROR,'UTF-8')],
                 ['email_date', Carbon::createFromDate((string) $message->getAttributes()['date'])->toDateTimeString()],
             ])->count() == 0) {
                 $this->incomingEmails['user_id'] = $account->user_id;
                 $this->incomingEmails['email_accounts_id'] = $account->id;
                 $this->incomingEmails['created_at'] = Carbon::now();
                 $this->incomingEmails['updated_at'] = Carbon::now();
                 $this->incomingEmails['from'] = Str::getEmailAddressesFromString($message->getAttributes()['fromaddress']);
                 $this->incomingEmails['to'] = Str::getEmailAddressesFromString($message->getAttributes()['toaddress']);
                 $this->incomingEmails['cc'] = isset($message->getAttributes()['ccaddress']) ? Str::getEmailAddressesFromString($message->getAttributes()['ccaddress']) : '';
                 $this->incomingEmails['subject'] = iconv_mime_decode($message->getAttributes()["subject"], ICONV_MIME_DECODE_CONTINUE_ON_ERROR, 'UTF-8');
                 $this->incomingEmails['message_id'] = $message->getAttributes()["message_id"] ?? '';
                 $this->incomingEmails['reply_message_id'] = $message->getAttributes()["references"] ?? '';
                 $this->incomingEmails['body'] = $message->hasHTMLBody() ? $message->getHTMLBody() : $message->getTextBody();
                 $this->incomingEmails['email_date'] = Carbon::createFromDate((string)$message->getAttributes()['date'])->toDateTimeString();
                 $this->incomingEmails['has_attachment'] = $message->hasAttachments();


                 DB::beginTransaction();
                 try
                 {
                     $incomingEmailId = IncomingEmail::insertGetId($this->incomingEmails);
                     if ($message->hasAttachments()) {
                         $attachments = $message->getAttachments();

                         foreach ($attachments as $attachment) {
                             try {
                                 if (Storage::directoryMissing('public/attachments/incoming/' . $incomingEmailId)) {
                                     Storage::makeDirectory('public/attachments/incoming/' . $incomingEmailId);
                                 }
                                 $filename = iconv_mime_decode($attachment->getName(), ICONV_MIME_DECODE_CONTINUE_ON_ERROR, 'UTF-8');
                                 $attachment->save($path = storage_path('app/public/attachments/incoming/' . $incomingEmailId.'/'), $filename);

                             } catch (\Exception $e) {
                                 Log::error($e->getMessage());
                             }
                         }
                     }
                     $this->incomingItemsCount++;
                     DB::commit();

                     $conversationemailsCount = (new IncomingEmailsController)->getIncomingEmailsCountFromEmailAddress($account,$this->incomingEmails['from']);

                     if($account->auto_reply_is_active )
                     {
                         Str::of($this->incomingEmails['from'])->explode(',')->each(function (string $item, int $key) use ($account)
                         {

                             $emailData = [
                                 'mail_to' => $item,
                                 'message_id' => $this->incomingEmails['message_id'] ?? null,
                                 'subject' => str_contains($this->incomingEmails['subject'], 'Re:') ? $this->incomingEmails['subject'] : 'Re: '.$this->incomingEmails['subject'],
                                 'body' => $account->auto_reply .
                                     '<br><br><br>
                                        <div dir="ltr">'.$this->incomingEmails['email_date'].' &lt;<a href="mailto:'.$this->incomingEmails['from'].'" target="_blank">'.$this->incomingEmails['from'].'</a>&gt;:<br></div>
                                        <blockquote style="border-left:1px solid #0857A6; margin:10px; padding:0 0 0 10px;">'
                                     . $this->incomingEmails['body'] .
                                     '</blockquote>',
                             ];

                             SendMail::dispatch(
                                 $emailData,
                                 $account->id
                             );

                         });
                     }


                 } catch (\Exception $e) {
                     DB::rollback();
                 }
             }
        });
            }, $chunk_size = 10, $start_chunk = 1);
    }

    private function addOutgoingEmails(EmailAccount $account, $client)
    {
        $sentFolder = $client->getFolderByName('Sent');

        if(!$sentFolder)
        {
            Log::error('Sent folder not exist for '.$account->email_address.' account');
            return false;
        }
        $query = $sentFolder->messages();

        $query
            ->from($account->email_address)
            ->since(Carbon::parse($account->imap_last_execute_time)->subDays($account->imap_scan_days_count))
            ->limit(limit: $account->imap_result_limit)
            ->fetchOrderDesc()
            ->chunked(function($messages, $chunk)  use($account){;
                $messages->each(function($message,$key) use($account)
        {

                set_time_limit (60);
            /** @var \Webklex\PHPIMAP\Message $message */

            if(OutgoingEmail::where([
                    ['user_id',  $account->user_id],
                    ['email_accounts_id',  $account->id],
                    ['from', Str::getEmailAddressesFromString($message->getAttributes()['fromaddress'])],
                    ['to', Str::getEmailAddressesFromString($message->getAttributes()['toaddress'])],
                    ['subject', iconv_mime_decode($message->getAttributes()["subject"],ICONV_MIME_DECODE_CONTINUE_ON_ERROR,'UTF-8')],
                    ['email_date', Carbon::createFromDate((string) $message->getAttributes()['date'])->toDateTimeString()],
                ])->count() == 0)
            {
                $this->outgoingEmails['user_id'] = $account->user_id;
                $this->outgoingEmails['email_accounts_id'] = $account->id;
                $this->outgoingEmails['created_at'] = Carbon::now();
                $this->outgoingEmails['updated_at'] = Carbon::now();
                $this->outgoingEmails['from'] = Str::getEmailAddressesFromString($message->getAttributes()['fromaddress']);
                $this->outgoingEmails['to'] = Str::getEmailAddressesFromString($message->getAttributes()['toaddress']);
                $this->outgoingEmails['cc'] = isset($message->getAttributes()['ccaddress']) ? Str::getEmailAddressesFromString($message->getAttributes()['ccaddress']) : '';
                $this->outgoingEmails['subject'] = iconv_mime_decode($message->getAttributes()["subject"], ICONV_MIME_DECODE_CONTINUE_ON_ERROR, 'UTF-8');
                $this->outgoingEmails['message_id'] = $message->getAttributes()["message_id"] ?? '';
                $this->outgoingEmails['reply_message_id'] = $message->getAttributes()["references"] ?? '';
                $this->outgoingEmails['body'] = $message->hasHTMLBody() ? $message->getHTMLBody() : $message->getTextBody();
                $this->outgoingEmails['email_date'] = Carbon::createFromDate((string)$message->getAttributes()['date'])->toDateTimeString();
                $this->outgoingEmails['has_attachment'] = $message->hasAttachments();

                DB::beginTransaction();
                try
                {
                    $outgoingEmailId = OutgoingEmail::insertGetId($this->outgoingEmails);
                    if($message->hasAttachments())
                    {
                        $attachments = $message->getAttachments();
                        foreach($attachments as $attachment)
                        {
                            try
                            {
                                if(Storage::directoryMissing('public/attachments/outgoing/'.$outgoingEmailId))
                                {
                                    Storage::makeDirectory('public/attachments/outgoing/'.$outgoingEmailId);
                                }

                                $filename = iconv_mime_decode($attachment->getName(),ICONV_MIME_DECODE_CONTINUE_ON_ERROR,'UTF-8');
                                $attachment->save($path = storage_path('app/public/attachments/outgoing/'.$outgoingEmailId.'/'), $filename);

                            } catch (\Exception $e) {
                                Log::error($e->getMessage());
                            }
                        }
                    }
                    $this->outgoingItemsCount++;
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                }

            }

        });
            }, $chunk_size = 10, $start_chunk = 1);
    }

}
