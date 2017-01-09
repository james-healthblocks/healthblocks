<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <link rel="shortcut icon" href="{{ asset('img/healthblocks-logo-withbg.png') }}">

        <title>HealthBlocks &mdash; HealthPortal</title>

        @include('base.css')
        @include('base.js')
    </head>

    <body class="hold-transition skin sidebar-mini">
        <div class="wrapper">

        @include('base.header')

            @include('base.sidebar')

            <div class="content-wrapper">
                @include('components.menu')
                    @yield('shcreps')
                @yield('content')
            </div>

        </div>
        @include('sync.modal')
    </body>
</html>
