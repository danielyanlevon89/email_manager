<?php

namespace App\Services;


use App\Models\BlackList;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class BlackListService
{
    public function checkContentContainBlackListWord($account, $subject, $body)
    {
        $blackListsArray = BlackList::where('user_id', $account->user_id)->pluck('word')->toArray();

        $containsSubject = Str::contains($subject, $blackListsArray, ignoreCase: true);
        if ($containsSubject) {
            Log::warning('Email subject contain word from blacklist. Account: ' . $account->email_address);
        }

        $containsBody = Str::contains($body, $blackListsArray, ignoreCase: true);
        if ($containsBody) {
            Log::warning('Email body contain word from blacklist. Account: ' . $account->email_address);
        }

        if ($containsBody || $containsSubject) {
            return true;
        }
        return false;
    }
}
