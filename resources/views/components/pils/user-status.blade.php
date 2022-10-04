<span
    {{ $attributes->class([
        'px-2 text-xs font-semibold rounded-full',
        'bg-green-100 text-green-800' => $user->active,
        'bg-red-100 text-red-800' => !$user->active,
    ]) }}>{{ $user->active ? __('common.active') : __('common.inactive') }}</span>
