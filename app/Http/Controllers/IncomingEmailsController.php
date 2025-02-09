<?php

namespace App\Http\Controllers;

use App\Models\EmailAccount;
use App\Models\IncomingEmail;
use App\Tables\IncomingEmails;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use ProtoneMedia\Splade\Facades\Splade;


class IncomingEmailsController extends Controller
{

    public function getIncomingEmailsCount()
    {
        $query = IncomingEmail::where('user_id',Auth::id())
            ->where('email_accounts_id',session()->get('chosen_email_account','0'));
        $allIncomingEmailsCount = $query->count();
        $newIncomingEmailsCount = $query->whereNull('seen_at')->count();

        $incomingEmailsCount = $allIncomingEmailsCount;
        if($newIncomingEmailsCount)
        {
            $incomingEmailsCount = $newIncomingEmailsCount.'/'.$allIncomingEmailsCount;
        }


        view()->share('incomingEmailsCount', $incomingEmailsCount);
    }

    public function getIncomingEmailsCountFromEmailAddress(EmailAccount $account,$email)
    {
        return IncomingEmail::where('user_id',$account->user_id)
            ->where('email_accounts_id',$account->id)
            ->where('from',$email)
            ->count();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(request()->has('ids') && request()->get('ids') !== null)
        {
            $ids = explode(',',request()->get('ids'));

            if($ids[0]=='*')
            {
                $bulkEmails = Str::removeDublicatesFromString(IncomingEmail::where('user_id',Auth::id())
                    ->where('email_accounts_id',session()->get('chosen_email_account','0'))
                    ->pluck('from')->implode(','));
            } else {
                $bulkEmails = Str::removeDublicatesFromString(IncomingEmail::whereIn('id', $ids)->pluck('from')->implode(','));
            }

            view()->share('emailToReply', $bulkEmails ?? '');
            view()->share('openModal', 1);
        }

        return view('incoming_emails.index', [
            'incoming_emails' => IncomingEmails::class
        ]);
    }

    public function setAsSeen(IncomingEmail $incomingEmail)
    {

        if(!$incomingEmail->seen_at)
        {

            $incomingEmail->seen_at = Carbon::now()->toDateTimeString();
            $incomingEmail->save();
        }

    }

}
