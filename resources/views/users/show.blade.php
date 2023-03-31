@extends('layout')
@section('title', __('users.show', ['name' => $user->name]))

@section('content')
    <div class="sm:rounded-lg mb-6 overflow-hidden bg-white shadow">
        <div class="sm:px-6 px-4 py-5">
            <h3 class="text-lg font-semibold">{{ __('users.profile') }}</h3>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 even:bg-white px-4 py-5">
                    <dd>{{ __('common.name') }}</dd>
                    <dt class="gap-x-2 flex">
                        <span>{{ $user->name }}</span>
                        <span>
                            <x-pils.user-admin :user="$user" />
                        </span>
                        <span>
                            <x-pils.user-manager :user="$user" />
                        </span>
                    </dt>
                </div>
                <div class="bg-gray-50 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 even:bg-white px-4 py-5">
                    <dd>{{ __('common.uniqueid') }}</dd>
                    <dt><code class="text-sm text-pink-500">{{ $user->uniqueid }}</code></dt>
                </div>
                <div class="bg-gray-50 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 even:bg-white px-4 py-5">
                    <dd>{{ __('common.email') }}</dd>
                    <dt><a class="hover:underline text-blue-600" href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                    </dt>
                </div>
                <div class="bg-gray-50 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 even:bg-white px-4 py-5">
                    <dd>{{ __('common.2fa_status') }}</dd>
                    <dt>
                        @if ($tokenSeeds)
                            @can('delete', $user)
                                <x-forms.2fa-disable :user="$user" />
                            @else
                                {{ __('common.enabled') }}
                            @endcan
                        @else
                            <x-forms.2fa-enable :user="$user" />
                        @endif
                    </dt>
                </div>
            </dl>
        </div>
    </div>
    <div>
        @can('admin')

            <x-back href="{{ route('users.index') }}" />

            @if ($user->manager)
                <x-forms.manager-revoke :user="$user" />
            @else
                <x-forms.manager-grant :user="$user" />
            @endif

            @if ($user->admin)
                <x-forms.admin-revoke :user="$user" />
            @else
                <x-forms.admin-grant :user="$user" />
            @endif
        @else
            <x-back href="{{ route('home') }}" />

        @endcan
    </div>
@endsection
