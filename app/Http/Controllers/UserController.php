<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);

        return view('users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user, UserService $userService)
    {
        $this->authorize('view', $user);

        return view('users.show', [
            'user' => $user,
            'tokenSeeds' => $userService->checkToken($user),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user, UserService $userService)
    {
        $this->authorize('update', $user);

        abort_if($userService->checkToken($user), 500);

        $qrCode = $userService->getQrCode($user);

        return view('users.qr', compact('qrCode'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user, UserService $userService)
    {
        $this->authorize('delete', $user);

        abort_unless($userService->checkToken($user), 500);

        $userService->disableTotp($user);

        return redirect()
            ->route('users.show', $user)
            ->with('status', __('common.2fa_disabled'));
    }
}
