<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class ProviderController extends Controller
{
    function redirect ($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    function callback ($provider)
    {

        try
        {
            $socialUser = Socialite::driver($provider)->user();

            $getUser = User::where('email',$socialUser->email);
            if($getUser->exists() && $getUser->first()->provider != $provider)
            {
                return redirect('/login')->withErrors(['email'=>__('This email uses different method to login')]);
            }

            $user = User::updateOrCreate([
                'provider_id' => $socialUser->id,
                'provider' => $provider,
            ], [
                'first_name' => $socialUser->name??$socialUser->email,
                'last_name' => $socialUser->name??$socialUser->email,
                'username' => $socialUser->nickname??$socialUser->email,
                'email' => $socialUser->email,
                'provider_token' => $socialUser->token
            ]);

            $password = Str::random(12);


            if($user->wasRecentlyCreated)
            {
                $password = Str::random(12);
                $user->update(['password' => $password]);
                try
                {
                    $user->setOpenPasswordAttribute($password);
                } catch ( \Exception $e  )
                {
                    dd($e);
                }
                $user->sendEmailVerificationNotification();
            }


            Auth::login($user);

            return redirect('/dashboard');

        } catch ( \Exception $e )
        {
            return redirect('/login');
        }


    }
}
