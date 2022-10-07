@props(['color' => 'blue'])

<button
    {{ $attributes->class([
        'inline-block px-4 py-2 rounded shadow',
        "hover:bg-$color-200 text-$color-600 bg-$color-300",
    ])}}>
    {{ $slot }}
</button>
