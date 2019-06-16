@extends('jarboe::layouts.main')

@push('styles')
    <style>
        body {
            background: url("/vendor/jarboe/img/6-reversed.jpg") #fff !important;
            background-size: cover !important;
            overflow-x: hidden;
            overflow-y: hidden;
            min-height: 100vh;
        }
        div#content {
            overflow-x: hidden;
            overflow-y: hidden;
            height: 100vh;
        }
    </style>

@endpush

@section('content')

    <h1>{{ __('jarboe::common.errors.401_title') }}</h1>

@endsection
