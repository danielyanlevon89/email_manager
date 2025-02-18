<?php

namespace App\Services;

use App\Jobs\SendMail;
use App\Models\BlackList;
use App\Models\EmailAccount;
use App\Models\Link;
use App\Models\OutgoingEmail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ProtoneMedia\Splade\Facades\Toast;

class SendEmailService
{
    public function sendEmail(Request $request)
    {


        $request->validate([
            'mail_to' => 'emails|required',
            'mail_cc' => 'emails|nullable',
            'subject' => 'string|required',
            'body' => 'string|required',
            'attachment.*' => 'file|nullable',
        ],
            [
                'mail_to.emails' => __('Wrong Email Format'),
                'mail_to.required' => __('Email To is Required'),
                'mail_cc.emails' => __('Wrong Email Format'),
            ]);

        $attachments_path = time() . '-' . uniqid($request->mail_to, true);


        Str::of($request->mail_to)->explode(',')->each(function (string $item, int $key) use ($attachments_path, $request) {
            $dispatchData = [
                'mail_to' => $item,
                'mail_cc' => $request->mail_cc ?? null,
                'message_id' => $request->message_id ?? null,
                'subject' => $request->subject,
                'body' => $request->body,
                'attachments_path' => $request->attachment ? $attachments_path : null,
            ];

            if ($request->attachment) {
                foreach ($request->attachment as $attachment) {

                    try {
                        if (Storage::directoryMissing('public/attachments/sendemail/' . $attachments_path)) {
                            Storage::makeDirectory('public/attachments/sendemail/' . $attachments_path);
                        }

                        Storage::putFile('public/attachments/sendemail/' . $attachments_path, $attachment);
                    } catch (\Exception $e) {
                        Log::error('file save error: ' . $e->getMessage());
                    }
                }
            }

            SendMail::dispatch(
                $dispatchData,
                session()->get('chosen_email_account', '0')
            );
        });


        $result['message'] = __('Mail successfully added into queue processing');
        $result['type'] = 'success';

        exit(json_encode($result));
    }


    public function addSentEmail(EmailAccount $account, $emailData)
    {
        $outgoingEmail = [];
        $outgoingEmail['user_id'] = $account->user_id;
        $outgoingEmail['email_accounts_id'] = $account->id;
        $outgoingEmail['created_at'] = Carbon::now();
        $outgoingEmail['updated_at'] = Carbon::now();
        $outgoingEmail['from'] = $emailData['from']['address'];
        $outgoingEmail['to'] = isset($emailData['to']) ? implode(',', $emailData['to']) : '';
        $outgoingEmail['cc'] = $emailData['cc'] ? implode(',', $emailData['cc']) : '';
        $outgoingEmail['subject'] = $emailData["subject"];
        $outgoingEmail['reply_message_id'] = $emailData["message_id"] ?? '';
        $outgoingEmail['body'] = $emailData['content'];
        $outgoingEmail['email_date'] = Carbon::now();
        $outgoingEmail['has_attachment'] = $emailData['attachments_path'] ? true : false;

        DB::beginTransaction();
        try {
            $outgoingEmailId = OutgoingEmail::insertGetId($outgoingEmail);
            if ($outgoingEmail['has_attachment']) {

                try {
                    if (Storage::directoryMissing('public/attachments/outgoing/' . $outgoingEmailId)) {
                        Storage::makeDirectory('public/attachments/outgoing/' . $outgoingEmailId);
                    }
                    Storage::move('public/attachments/sendemail/' . $emailData['attachments_path'], 'public/attachments/outgoing/' . $outgoingEmailId);

                } catch (\Exception $e) {
                    Log::error($e->getMessage());
                }

            }

            DB::commit();
        } catch (\Exception $e) {
            Log::error("Failed to add send email into database. message: " . $e->getLine() . $e->getFile() . $e->getMessage());
            DB::rollback();
        }


    }

    public function replaceEmailDataKeywords($account, $emailData)
    {
        $emailData['content'] = Str::swap([
            '{sender_email}' => $emailData['from']['address'] ?? '',
            '{sender_name}' => $emailData['from']['name'] ?? '',
            '{recipient_email}' => $emailData['to'][0] ?? '',
            '{recipient_name}' => $emailData['to_name'] ?? '',
            '{url}' => Link::where('user_id', $account->user_id)->inRandomOrder()->first()->url ?? '',
            '{email_date}' => date('Y-m-d'),
        ], $emailData['content']);

        $emailData['subject'] = Str::swap([
            '{name}' => $emailData['to_name'] ?? '',
        ], $emailData['subject']);

        return $emailData;

    }
}
