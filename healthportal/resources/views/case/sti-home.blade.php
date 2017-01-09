@extends('base.base')
@section('content')
            <!-- Main content -->
            <section class="content">
            </section>
            <!-- /.content -->

            @include('components.message-js')
                @yield('message-js')

            <script type="text/javascript">
                $(".ui.dropdown").dropdown();
            </script>

@stop