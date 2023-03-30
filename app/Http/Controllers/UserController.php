<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

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

        switch ($request->action) {
            case 'admin':
                $status = $userService->admin($user) ? 'granted' : 'revoked';
                $color = $status === 'granted' ? 'red' : 'green';

                return to_route('users.show', $user)
                    ->with('status', __("users.admin_$status", ['name' => $user->name]))
                    ->with('color', $color);

                break;

            case 'manager':
                $status = $userService->manager($user) ? 'granted' : 'revoked';
                $color = $status === 'granted' ? 'red' : 'green';

                return to_route('users.show', $user)
                    ->with('status', __("users.manager_$status", ['name' => $user->name]))
                    ->with('color', $color);

                break;

            default:
                $userService->getQrCode($user);

                return view('users.qr', ['qrCode' => $userService->getQrCode($user)]);

                break;
        }
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

        $userService->disableTotp($user);

        return redirect()
            ->route('users.show', $user)
            ->with('status', __('common.2fa_disabled'));
    }
}
