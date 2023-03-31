<form action="{{ route('users.update', $user) }}" method="post">
    @csrf
    @method('patch')

    <x-button color="lime">{{ __('common.enable_2fa') }}</x-button>

</form>
