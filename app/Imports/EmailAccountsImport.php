<?php

namespace App\Imports;

use App\Models\EmailAccount;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmailAccountsImport implements ToModel, WithHeadingRow
{

    public function model(array $row)
    {
        return new EmailAccount([
            'email_address' => $row['email_address'],
            'email_from' => $row['email_from'],
            'is_active' => $row['is_active'],
            'auto_reply_is_active' => $row['auto_reply_is_active'],
            'imap_host' => $row['imap_host'],
            'imap_port' => $row['imap_port'],
            'imap_encryption' => $row['imap_encryption'],
            'imap_username' => $row['imap_username'],
            'imap_password' => $row['imap_password'],
            'imap_scan_days_count' => $row['imap_scan_days_count'],
            'smtp_host' => $row['smtp_host'],
            'smtp_port' => $row['smtp_port'],
            'smtp_encryption' => $row['smtp_encryption'],
            'smtp_username' => $row['smtp_username'],
            'smtp_password' => $row['smtp_password'],
            'smtp_send_email_count_in_minute' => $row['smtp_send_email_count_in_minute'],
        ]);
    }

    public function headingRow(): int
    {
        return 1;
    }
}
