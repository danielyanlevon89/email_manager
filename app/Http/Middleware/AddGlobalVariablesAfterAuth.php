<?php

namespace App\Http\Middleware;

use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\IncomingEmailsController;
use App\Http\Controllers\OutgoingEmailsController;
use App\Models\EmailAccount;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use ProtoneMedia\Splade\Facades\Toast;
use Symfony\Component\HttpFoundation\Response;

class AddGlobalVariablesAfterAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {

        $emailAccounts = EmailAccount::imapActive()->get();
        view()->share('emailAccounts', $emailAccounts);

        (new IncomingEmailsController)->getIncomingEmailsCount();
        (new OutgoingEmailsController)->getOutgoingEmailsCount();
        (new EmailTemplateController)->getTemplates();

        return $next($request);
    }



}
