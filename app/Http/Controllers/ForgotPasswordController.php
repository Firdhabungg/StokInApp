<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function showRequestForm()
    {
        return view('auth.forgot-password');
    }
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(
            ['email' => 'required|email:dns,rfc'],
            [
                'email.required' => 'Email harus diisi',
                'email.email' => 'Format email tidak valid'
            ]
        );
        $response = Password::sendResetLink($request->only('email'));

        if ($response == Password::RESET_LINK_SENT) {
            return back()->with('status', __($response));
        } else {
            return back()->withErrors(['email' => __($response)]);
        }
    }
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email:dns',
            'password' => 'required|confirmed|min:8'
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = $password;
                $user->save();
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return redirect('/login')->with('status', __($status));
        } else {
            return back()->withErrors(['email' => [__($status)]]);
        }
    }
}
