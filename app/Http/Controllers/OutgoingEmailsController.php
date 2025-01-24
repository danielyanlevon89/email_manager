<?php

namespace App\Http\Controllers;



use App\Models\OutgoingEmail;
use App\Tables\OutgoingEmails;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OutgoingEmailsController extends Controller
{


    public function getOutgoingEmailsCount()
    {
        $outgoingEmailsCount = OutgoingEmail::where('user_id',Auth::id())
            ->where('email_accounts_id',session()->get('chosen_email_account','0'))
            ->count();
        view()->share('outgoingEmailsCount', $outgoingEmailsCount);
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
                $bulkEmails = Str::removeDublicatesFromString(OutgoingEmail::where('user_id',Auth::id())
                    ->where('email_accounts_id',session()->get('chosen_email_account','0'))
                    ->pluck('from')->implode(','));
            } else {
                $bulkEmails = Str::removeDublicatesFromString(OutgoingEmail::whereIn('id', $ids)->pluck('from')->implode(','));
            }

            view()->share('emailToReply', $bulkEmails ?? '');
            view()->share('openModal', 1);
        }

        return view('outgoing_emails.index', [
            'outgoing_emails' => OutgoingEmails::class
        ]);
    }
}
