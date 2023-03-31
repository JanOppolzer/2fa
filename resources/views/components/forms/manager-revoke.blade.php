<form class="inline-block" action="{{ route('users.revoke_manager', $user) }}" method="post">
    @csrf
    @method('delete')

    <x-button>{{ __('users.revoke_manager') }}</x-button>

</form>
