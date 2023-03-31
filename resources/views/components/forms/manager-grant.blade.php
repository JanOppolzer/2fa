<form class="inline-block" action="{{ route('users.grant_manager', $user) }}" method="post">
    @csrf

    <x-button>{{ __('users.grant_manager') }}</x-button>

</form>
