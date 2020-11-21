@extends('layouts.base')

@section('body')
    @yield('content')
    
    @yield('extra-js')
    @isset($slot)
        {{ $slot }}
    @endisset
@endsection
