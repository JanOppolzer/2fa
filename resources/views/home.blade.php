@extends('layout')
@section('title', __('common.dashboard'))

@section('content')
    <p class="mb-6">
        {!! __('welcome.howto') !!}
    </p>
    <p>
        {!! __('welcome.contact') !!} <a href="mailto:info@eduid.cz" class="hover:underline text-blue-500">info@eduid.cz</a>.
    </p>
@endsection
