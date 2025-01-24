<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class EmailAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'imap_host',
        'imap_port',
        'imap_encryption',
        'imap_username',
        'imap_password',
        'is_active',
        'user_id',
        'imap_scan_days_count',
        'imap_result_limit',

        'smtp_host',
        'smtp_port',
        'smtp_send_email_count_in_minute',
        'smtp_last_execute_time',
        'smtp_last_execute_items_count',
        'smtp_encryption',
        'smtp_username',
        'smtp_password',

        'email_address',
        'email_from',
        'auto_reply',
        'auto_reply_is_active',
    ];


    public function __construct(array $attributes = [])
    {

        $this->attributes['user_id'] = Auth::id();
        parent::__construct($attributes);
    }

    /**
     * Scope a query to only include active users.
     */
    public function scopeImapActive(Builder $query): void
    {
        $query->where('is_active', 1);
    }
}
