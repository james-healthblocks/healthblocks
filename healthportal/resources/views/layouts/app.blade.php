<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('img/healthblocks-logo-withbg.png') }}">

    <title>HealthBlocks &mdash; HealthPortal</title>

    @include('base.css')
    @include('base.js')

    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>

    <div class="ui middle aligned center aligned grid">
        <div class="column">
                <h2 class="ui teal image header">
                <img src="/img/healthblocks-logo.png" class="image"><br>
                </h2>
                <div class="content">
                    <span class="logo-text-2 health">health</span><span class="logo-text-2 block">blocks</span><br>
                    <div class="ui hidden divider"></div>
                    <span class="logo-text health">health</span><span class="logo-text block">portal</span>
                </div>
            <div class="content page-name">
                @yield('pagename')
            </div>
    @yield('content')
    </div>

    <!-- Scripts -->
    <script src="/js/app.js"></script>
</body>
</html>
