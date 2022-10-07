@props(['color' => 'blue'])

<button
    {{ $attributes->class([
        'inline-block px-4 py-2 rounded shadow',
        'hover:bg-red-200 text-red-600 bg-red-300' => $color === 'red',
        'hover:bg-indigo-200 text-indigo-600 bg-indigo-300' => $color === 'indigo',
        'hover:bg-lime-200 text-lime-600 bg-lime-300' => $color === 'lime',
        'hover:bg-blue-200 text-blue-600 bg-blue-300' => $color === 'blue',
    ])}}>
    {{ $slot }}
</button>
