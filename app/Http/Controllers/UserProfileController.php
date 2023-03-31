<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class UserProfileController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(): RedirectResponse
    {
        return to_route('users.show', auth()->user());
    }
}
