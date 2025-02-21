<?php

namespace App\Http\Controllers;

use App\Enums\Encryption;
use App\Http\Requests\EmailAccountRequest;
use App\Models\EmailAccount;
use App\Models\EmailTemplate;
use App\Tables\EmailAccounts;
use Illuminate\Support\Facades\Auth;
use ProtoneMedia\Splade\Facades\Toast;

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

        $emailAccount->delete();

        Toast::title(__('Account Deleted Successfully'))->autoDismiss(2);

        return back();
    }


    public function setActive(EmailAccount $emailAccount)
    {
            $emailAccount->is_active = true;
            $emailAccount->save();

    }
    public function setNoActive(EmailAccount $emailAccount)
    {
        $emailAccount->is_active = false;
        $emailAccount->save();

    }

    public function enableAutoReply(EmailAccount $emailAccount)
    {
        $emailAccount->auto_reply_is_active = true;
        $emailAccount->save();

    }
    public function disableAutoReply(EmailAccount $emailAccount)
    {
        $emailAccount->auto_reply_is_active = false;
        $emailAccount->save();

    }

}
