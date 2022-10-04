@extends('layout')

@section('content')
    <div class="sm:rounded-lg mb-6 overflow-hidden bg-white shadow">
        <div class="sm:px-6 px-4 py-5">
            <h3 class="text-lg font-semibold">{{ __('users.profile') }}</h3>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 even:bg-white px-4 py-5">
                    <dd>{{ __('common.name') }}</dd>
                    <dt>{{ $user->name }}</dt>
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
                            {{ __('common.enabled') }}
                        @else
                            <!-- dialog window to confirm enabling 2FA -->
                            <form action="{{ route('users.update', $user) }}" method="post">
                                @csrf
                                @method('patch')
                                <x-buttons.enable2fa href="#" />
                            </form>
                        @endif
                    </dt>
                </div>
            </dl>
        </div>
    </div>
    <div>
        @if (URL::previous() !== URL::current())
            <x-buttons.back href="{{ URL::previous() }}" />
        @endif
    </div>
@endsection
