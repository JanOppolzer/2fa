<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View
    {
        $this->authorize('viewAny', User::class);

        return view('users.index');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(User $user, UserService $userService): View
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user, UserService $userService): View
    {
        $this->authorize('update', $user);

        abort_if($userService->checkToken($user), 500);

        $qrCode = $userService->getQrCode($user);

        return view('users.qr', compact('qrCode'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user, UserService $userService): RedirectResponse
    {
        $this->authorize('delete', $user);

        abort_unless($userService->checkToken($user), 500);

        $userService->disableTotp($user);

        return redirect()
            ->route('users.show', $user)
            ->with('status', __('common.2fa_disabled'));
    }
}
