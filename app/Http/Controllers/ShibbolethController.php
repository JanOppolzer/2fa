<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ShibbolethController extends Controller
{
    public function create()
    {
        if (is_null(request()->server('Shib-Handler'))) {
            return 'login';
        }

        return redirect(
            request()
                ->server('Shib-Handler')
                . '/Login?target='
                . action('\\' . __CLASS__ . '@store')
        );
    }

    public function store()
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

        if (!$user->active) {
            return redirect('blocked');
        }

        Auth::login($user);
        Session::regenerate();

        return redirect()->intended('/');
    }

    public function destroy()
    {
        Auth::logout();
        Session::flush();

        return redirect('/Shibboleth.sso/Logout');
    }
}
