@extends('errors.minimal')

@section('title', 'Acceso denegado')
@section('code', '403')
@section('message')
@php
    $msg = $exception->getMessage();
    if (empty($msg) || $msg === 'Forbidden' || $msg === 'This action is unauthorized.') {
        $msg = 'Esta acción no está autorizada.';
    } else {
        $msg = __($msg);
    }
@endphp
{{ $msg }}
@endsection
