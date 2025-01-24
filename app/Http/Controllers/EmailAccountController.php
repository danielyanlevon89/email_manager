<?php

namespace App\Http\Controllers;

use App\Enums\Encryption;
use App\Http\Requests\EmailAccountRequest;
use App\Models\EmailAccount;
use App\Models\EmailTemplate;
use App\Tables\EmailAccounts;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use ProtoneMedia\Splade\Facades\Toast;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Webklex\PHPIMAP\ClientManager;

class EmailAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('email_accounts.index', [
            'accounts' => EmailAccounts::class
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $encryption = Encryption::toArray();
        $templatesLabels =  EmailTemplate::where('user_id', Auth::id())->pluck('name','id')->toArray();
        return view('email_accounts.create', compact('encryption','templatesLabels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmailAccountRequest $request)
    {
        EmailAccount::create($request->validated());

        Toast::title(__('New Account Created Successfully'))->autoDismiss(2);

        return to_route('email_accounts.index');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmailAccount $emailAccount)
    {
        if($emailAccount->user_id != Auth::id())
        {
            abort(404);
        }
        $encryption = Encryption::toArray();

        $templatesLabels =  EmailTemplate::where('user_id', Auth::id())->pluck('name','id')->toArray();

        return view('email_accounts.edit', compact('emailAccount','encryption','templatesLabels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmailAccountRequest $request, EmailAccount $emailAccount)
    {
        if($emailAccount->user_id != Auth::id())
        {
            abort(404);
        }

        if (
                session()->exists('chosen_email_account') &&
                session()->get('chosen_email_account','') == $emailAccount->id &&
                $request->validated()['is_active'] == false
            )
        {
           Toast::info(__('Please Choose Account'))->autoDismiss(2);
           session()->remove('chosen_email_account');
        }

        $emailAccount->update($request->validated());

        Toast::title(__('Account Updated Successfully'))->autoDismiss(2);

        return to_route('email_accounts.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmailAccount $emailAccount)
    {
        if($emailAccount->user_id != Auth::id())
        {
            abort(404);
        }

        if(session()->get('chosen_email_account','') == $emailAccount->id)
        {
            Toast::danger(__('Can\'t Delete Active Account'))->autoDismiss(2);
            return back();
        }

        $emailAccount->delete();

        Toast::title(__('Account Deleted Successfully'))->autoDismiss(2);

        return back();
    }



}
