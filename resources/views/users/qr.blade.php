@extends('layout')
@section('title', __('users.show', ['name' => auth()->user()->name]))

@section('content')
    <p>{{ __('users.scan_qr') }}</p>

    <div class="p-2 flex justify-center">{!! $qrCode !!}</div>
    
    <x-buttons.back href="/profile" />
@endsection
