@php
$color = session('color') ?: 'green';
@endphp

@if (session('status'))
    <div
        {{ $attributes->class(['mb-4 p-4 border rounded shadow', "bg-$color-100 text-$color-700 border-$color-200"]) }}>
        {{ session('status') }}
    </div>
@endif
