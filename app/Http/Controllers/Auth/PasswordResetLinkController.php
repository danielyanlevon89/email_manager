<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $inputType = filter_var($request->input('input_type'),FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $request->merge([$inputType => $request->input('input_type')]);


        $request->validate([
            'email' => ['required_without:username', 'string', 'email','exists:users,email'],
            'username' => ['required_without:email', 'string', 'exists:users,username'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only($inputType)
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only($inputType))
                        ->withErrors([$inputType => __($status)]);
    }
}
