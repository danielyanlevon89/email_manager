<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Webklex\PHPIMAP\ClientManager;

class CheckCredentials implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private $data)
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {

            $transport = new EsmtpTransport(
                $this->data->smtp_host,
                $this->data->smtp_port,
                ($this->data->smtp_encryption == 'tls') ? true : false
            );

            $transport->setUsername($this->data->smtp_username);
            $transport->setPassword($this->data->smtp_password);
            $transport->start();

            $this->data->smtp_validation = true;
            $this->data->save();

            Log::info("SMTP credentials is valid: {$this->data->smtp_host}");
        } catch (\Exception $e) {

            $this->data->smtp_validation = false;
            $this->data->save();

            Log::error("SMTP credentials invalid: {$this->data->smtp_host}");
        }


        $cm = new ClientManager($options = []);

        $client = $cm->make([
            'host' => $this->data->imap_host,
            'port' => $this->data->imap_port,
            'encryption' => $this->data->imap_encryption,
            'username' => $this->data->imap_username,
            'password' => $this->data->imap_password,
        ]);

        try {
            $client->connect();

            if ($client->isConnected()) {
                $this->data->imap_validation = true;
                $this->data->save();
                Log::info("IMAP credentials is valid: {$this->data->imap_host}");

            } else {
                $this->data->imap_validation = false;
                $this->data->save();
                Log::info("IMAP credentials invalid: {$this->data->imap_host}");
            }

        } catch (\Exception $e) {
            $this->data->imap_validation = false;
            $this->data->save();
            Log::info("IMAP credentials invalid: {$this->data->imap_host}");

        }

    }
}
