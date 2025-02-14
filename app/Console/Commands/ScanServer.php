<?php

namespace App\Console\Commands;

use App\Models\EmailAccount;
use App\Services\ImapParsingService;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Log;

class ScanServer extends Command
{

    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:scan-server';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scanning SMTP server and sync info with emails database';

    /**
     * Execute the console command.
     */
    public function handle(ImapParsingService $imapParsingService)
    {
        Log::info("Imap Parsing script running");
        EmailAccount::imapActive()->each(function ($account) use ($imapParsingService) {
            $imapParsingService->scanImapServer($account);
        });

    }
}
