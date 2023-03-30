<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(User $user)
    {
        $this->authorize('admin');

        $user->manager = true;
        $user->update();

        return to_route('users.show', $user)
            ->with(__('users.manager_granted', ['name' => $user->name]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize('admin');

        $user->manager = false;
        $user->update();

        return to_route('users.show', $user)
            ->with(__('users.manager_revoked', ['name' => $user->name]))
            ->with('color', 'red');
    }
}
