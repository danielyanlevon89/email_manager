<?php

namespace App\Http\Middleware;


use App\Services\EmailAccountService;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use ProtoneMedia\Splade\Facades\Toast;
use Symfony\Component\HttpFoundation\Response;

class HasChosenEmailAccount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {

        if (! session()->exists('chosen_email_account'))
        {
           Toast::info(__('Please Choose Account'))->autoDismiss(2);
           return redirect('email_accounts');
        }

        return $next($request);
    }



}
