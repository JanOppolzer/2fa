@extends('layout')

@section('content')
    {!! $qrCode !!}
    
    <x-buttons.back href="{{ $back }}" />
@endsection
