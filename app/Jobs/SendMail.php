<?php
namespace App\Jobs;

use App\Models\EmailAccount;
use App\Services\EmailStructureService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;


class SendMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private array $data,private  $account_id = null)
    {

        $this->data['mail_to'] = explode(",", $this->data['mail_to']);
        $this->data['mail_cc'] = isset($this->data['mail_cc']) ? explode(",", $this->data['mail_cc']) : '';

        if(!isset($data['reply_message_id']))
        {
            unset($this->data['reply_message_id']);
        }
        if(!isset($data['mail_cc']))
        {
            unset($this->data['mail_cc']);
        }
        if(!isset($data['attachments_path']))
        {
            unset($this->data['attachments_path']);
        }
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $account = EmailAccount::find($this->account_id);
        $mail = Mail::build([
            'transport' => 'smtp',
            'host' => $account->smtp_host ?? Config::get('mail.mailers.smtp.host'),
            'port' => $account->smtp_port ?? Config::get('mail.mailers.smtp.port'),
            'encryption' => $account->smtp_encryption ?? Config::get('mail.mailers.smtp.encryption'),
            'username' => $account->smtp_username ?? Config::get('mail.mailers.smtp.username'),
            'password' => $account->smtp_password ?? Config::get('mail.mailers.smtp.password'),
            'from' => [
                'address' => $account->email_address ?? env('MAIL_FROM_ADDRESS'),
                'name' => $account->email_from ?? env('MAIL_FROM_NAME')
            ]
        ]);

        try {

            $mail->send(new EmailStructureService([
                    'from' => [
                        'address' => $account->email_address ?? env('MAIL_FROM_ADDRESS'),
                        'name' => $account->email_from ?? env('MAIL_FROM_NAME')
                    ],
                    'to' => $this->data['mail_to'],
                    'cc' => $this->data['mail_cc'] ??'',
                    'replyTo' => $this->data['reply_message_id'] ?? '',
                    'subject' => $this->data['subject'],
                    'content' => $this->data['body'],
                    'attachments_path' => $this->data['attachments_path'] ?? '',
                ]));

            Log::info("Email sent from account: {$account->email_address}",$this->data['mail_to']);
        } catch (\Exception $e) {
            Log::error("Failed to send email from account: {$account->email_address} message: ".$e->getLine() .$e->getFile(). $e->getMessage());
        }

    }
}
