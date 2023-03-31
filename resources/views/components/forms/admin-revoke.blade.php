<form class="inline-block" action="{{ route('users.revoke_admin', $user) }}" method="post">
    @csrf
    @method('delete')

    <x-button color="indigo">{{ __('users.revoke_admin') }}</x-button>

</form>
