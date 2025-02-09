<?php

namespace App\Services;


use App\Models\EmailAccount;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use ProtoneMedia\Splade\Facades\Toast;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Webklex\PHPIMAP\ClientManager;

class EmailAccountService
{
    public function checkSmtpConnection($id){

        $result = [];
        $result['type'] ='error';

        $smtpAccount = EmailAccount::findOrFail($id);

        try {

            $transport = new EsmtpTransport(
                $smtpAccount->smtp_host,
                $smtpAccount->smtp_port,
                ($smtpAccount->smtp_encryption == 'tls') ? true : false
            );

            $transport->setUsername($smtpAccount->smtp_username);
            $transport->setPassword($smtpAccount->smtp_password);
            $transport->start();
            $result['message'] ='SMTP is connected';
            $result['type'] ='success';

            exit(json_encode($result));

        } catch (\Exception $e) {

            $result['message'] =$e->getMessage();
            exit(json_encode($result));

        }

    }

    public function checkImapConnection($id)
    {

        $result = [];
        $result['type'] ='error';

        $cm = new ClientManager($options = []);
        $imapAccount = EmailAccount::findOrFail($id);


        $client = $cm->make([
            'host' => $imapAccount->imap_host,
            'port' => $imapAccount->imap_port,
            'encryption' => $imapAccount->imap_encryption,
            'username' => $imapAccount->imap_username,
            'password' => $imapAccount->imap_password,
        ]);

        try
        {
            $client->connect();

            if($client->isConnected())
            {
                $result['message'] ='IMAP is connected';
                $result['type'] ='success';
            } else {
                $result['message'] ='IMAP is not connected';
            }

            exit(json_encode($result));

        }
        catch (\Exception $e)
        {
            $result['message'] =$e->getMessage();
            exit(json_encode($result));

        }

    }

    public function  setEmailAccount($id)
    {
        $emailAddress = EmailAccount::findOrFail($id)->email_address;

        session(['chosen_email_account' => $id]);
        session(['chosen_email_account_address' => $emailAddress]);


        Toast::success(__('Account Has Successfully Chosen'))->autoDismiss(2);

        return to_route('dashboard');
    }
    public function  unsetEmailAccount()
    {

        session()->remove('chosen_email_account');
        session()->remove('chosen_email_account_address');

        Toast::info(__('Please Choose Account'))->autoDismiss(2);

        return to_route('dashboard');
    }

}
