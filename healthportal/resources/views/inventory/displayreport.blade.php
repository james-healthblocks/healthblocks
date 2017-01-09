@extends('base.base')
@section('content')
            <!-- Main content -->
            <section class="content">
                {{ Form::open(array('url' => '/inventory/reports/display/download', 'class' => '', 'role' => '')) }}
                {{ Form::text('info', json_encode($info), ['class' => 'hidden']) }}
                {{ Form::text('data', json_encode($data), ['class' => 'hidden']) }}
                <button class="ui green large button right floated reports-download-button downloadXLS">Download Excel File</button>
                <div class="report-table-wrap">
                    <?php $html = true ?>
                    @include('inventory.reportsTable')
                        @yield('inventory-report-table')
                </div>
                <button class="ui green large button right floated reports-download-button downloadXLS">Download Excel File</button>
                {{ Form::close() }}
            </section>
            <!-- /.content -->

            @include('components.message-js')
                @yield('message-js')
            <script type="text/javascript">
                $(".ui.dropdown").dropdown();
                $(document).ready(function(){
                    $('body').addClass('sidebar-collapse');
                });
            </script>
@stop