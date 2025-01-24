<?php

namespace App\Http\Requests;

use App\Enums\Encryption;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmailAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [
            'name' => ['required', 'max:60'],
            'email_address' => ['required',  'max:60','email'],
            'email_from' => ['required',  'max:60'],
            'auto_reply_is_active' => ['required',  'boolean'],
            'auto_reply' => ['required'],

            'imap_host' => ['required', 'max:60'],
            'imap_port' => ['required', 'max:9999',"min:1","numeric"],
            'imap_scan_days_count' => ['required', 'max:9999',"min:1","numeric"],
            'imap_result_limit' => ['required', 'max:9999',"min:1","numeric"],
            'imap_encryption' => ['required',  'max:60',Rule::in(Encryption::toArray())],
            'imap_username' => ['required',  'max:60'],
            'imap_password' => ['required',  'max:60'],
            'is_active' => ['required','boolean'],

            'smtp_host' => ['required', 'max:60'],
            'smtp_port' => ['required', 'max:9999',"min:1","numeric"],
            'smtp_send_email_count_in_minute' => ['required', 'max:9999',"min:1","numeric"],
            'smtp_encryption' => ['required',  'max:60',Rule::in(Encryption::toArray())],
            'smtp_username' => ['required',  'max:60'],
            'smtp_password' => ['required',  'max:60'],

        ];
    }
}
