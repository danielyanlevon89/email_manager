<?php

namespace App\Services;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EmailStructureService extends Mailable
{
    use Queueable, SerializesModels;

    public $mailData;

    /**
     * Create a new message instance.
     */
    public function __construct($mailData)
    {
        $this->mailData = $mailData;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address($this->mailData['from']['address'], $this->mailData['from']['name']),
            to: $this->mailData['to'],
            cc: $this->mailData['cc'],
            subject: $this->mailData['subject']
        );
    }

    /**
     * Get the message headers.
     */
    public function headers(): Headers
    {
        if($this->mailData['message_id'])
        {
            return new Headers(
                text: [
                    'In-Reply-To' => $this->mailData['message_id'],
                    'References' => $this->mailData['message_id'],
                ],
            );
        } else
        {
            return new Headers();
        }

    }


    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(htmlString: $this->mailData['content']);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {

        if(!isset($this->mailData['attachments_path']) || !$this->mailData['attachments_path'] || Storage::directoryMissing('public/attachments/sendemail/'.$this->mailData['attachments_path']))
        {
           return [];
        }

        $files = [];
        $attachments = Storage::allFiles('public/attachments/sendemail/'.$this->mailData['attachments_path']);

        foreach ($attachments as $attachment)
        {
            $files[] = Attachment::fromPath('storage/app/'.$attachment);

        }

        return $files;

    }

}
