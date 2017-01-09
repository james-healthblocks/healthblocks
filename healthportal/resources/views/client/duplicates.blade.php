@extends('base.base')
@section('content')
            <section class="content-header">
                <h1>
                    Duplicates Handling
                    <small>Potential Duplicate Clients</small>
                </h1>
                <div class="breadcrumb">
                    <a href="/client/duplicates/marked">Marked Duplicates</a>
                </div>
            </section>

            <!-- Main content -->
            <section class="content" id='dupContent'>
                Fetching Potential Duplicates...
            </section>
            <script>
                var cities = {!! json_encode($cities) !!};
                var content = $('#dupContent');
            </script>
            <script src="/js/duplicates.js"></script>
            <!-- /.content -->
@stop