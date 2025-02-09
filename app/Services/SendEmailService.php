<?php

namespace App\Services;

use App\Jobs\SendMail;
use Illuminate\Http\Request;
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

        $attachments_path = time().'-'.uniqid($request->mail_to, true);


        Str::of($request->mail_to)->explode(',')->each(function (string $item, int $key) use ($attachments_path,$request)
        {
            $dispatchData = [
                'mail_to' => $item,
                'mail_cc' => $request->mail_cc ?? null,
                'reply_message_id' => $request->reply_message_id ?? null,
                'subject' => $request->subject,
                'body' => $request->body,
                'attachments_path' => $request->attachment ? $attachments_path : null,
            ];

            if($request->attachment)
            {
                foreach ($request->attachment as $attachment) {

                    try
                    {
                        if(Storage::directoryMissing('public/attachments/sendemail/'.$attachments_path))
                        {
                            Storage::makeDirectory('public/attachments/sendemail/'.$attachments_path);
                        }

                        Storage::putFile('public/attachments/sendemail/'.$attachments_path, $attachment);
                    } catch (\Exception $e) {
                        Log::error('file save error: '.$e->getMessage());
                    }
                }
            }

            SendMail::dispatch(
                $dispatchData,
                session()->get('chosen_email_account','0')
            );
        });


        $result['message'] =__('Mail successfully added into queue processing');
        $result['type'] ='success';

        exit(json_encode($result));
    }
}
