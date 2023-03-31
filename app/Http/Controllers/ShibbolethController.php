<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ShibbolethController extends Controller
{
    public function create(): string|RedirectResponse
    {
        if (is_null(request()->server('Shib-Handler'))) {
            return 'login';
        }

        return redirect(
            request()
                ->server('Shib-Handler')
                .'/Login?target='
                .action('\\'.__CLASS__.'@store')
        );
    }

    public function store(): RedirectResponse|string
    {
        $mail = explode(';', request()->server('mail'));

        $user = User::updateOrCreate(
            ['uniqueid' => request()->server('uniqueId')],
            [
                'name' => request()->server('cn'),
                'email' => $mail[0],
            ]
        );

        $user->refresh();

        if (! $user->active) {
            return 'You are blocked.';
        }

        Auth::login($user);
        Session::regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    public function destroy(): RedirectResponse
    {
        Auth::logout();
        Session::flush();

        return redirect('/Shibboleth.sso/Logout');
    }
}
