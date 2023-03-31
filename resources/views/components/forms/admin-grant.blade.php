<form class="inline-block" action="{{ route('users.grant_admin', $user) }}" method="post">
    @csrf

    <x-button color="indigo">{{ __('users.grant_admin') }}</x-button>

</form>
