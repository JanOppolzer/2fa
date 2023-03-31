<form action="{{ route('users.destroy', $user) }}" method="post">
    @csrf
    @method('delete')

    <x-button color="red">{{ __('common.disable_2fa') }}</x-button>

</form>
