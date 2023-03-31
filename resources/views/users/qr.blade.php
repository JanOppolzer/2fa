@extends('layout')
@section('title', __('users.show', ['name' => auth()->user()->name]))

@section('content')
    <p>{{ __('users.scan_qr') }}</p>

    <div class="flex justify-center p-2">{!! $qrCode !!}</div>

    <x-back href="/profile" />
@endsection
