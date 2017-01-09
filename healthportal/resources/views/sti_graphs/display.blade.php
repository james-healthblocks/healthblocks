@extends('base.base')
@section('content')
            <section class="content-header">
                <h1>
                    STI Positivity Rates
                    <!-- <small>Optional description</small> -->
                </h1>
            </section>

            <!-- Main content -->
            <section class="content">
                @include('components.message-block')
                        @yield('message')

                <div class="container {{ count($sti_list) > 5 ? 'centered' : '' }}">
                @foreach($sti_list as $value => $label)
                    <button class="ui button graph-button" value="{{ $value }}">{{ $label }}</button>
                @endforeach
                </div>
                
                <div class="container" id="graph-container" width='100%'>
                    <span class="graph-note">Select an STI to display graph</span>
                </div>

                <section class="graph-table-section">
                    <div class="container" id="graph-data-table">
                    </div>
                </section>

                {{ Form::open(array('url' => '/sti_graphs/downloadPDF', 'class' => 'hiddenPageParams', 'role' => '')) }}
                    {{ Form::hidden('info', json_encode($info), ['class' => 'hidden']) }}
                    {{ Form::hidden('data', json_encode($data), ['class' => 'hidden']) }}
                    {{ Form::hidden('sti_list', json_encode($sti_list), ['class' => 'hidden']) }}
                    {{ Form::hidden('months', json_encode($months), ['class' => 'hidden']) }}
                    {{ Form::hidden('rawData', json_encode($rawData), ['class' => 'hidden']) }}
                {{ Form::close() }}

                {{ Form::open(array('url' => '/sti_graphs/downloadPDF', 'class' => 'hiddenParams', 'role' => '')) }}
                    {{ Form::hidden('months', json_encode($months), ['class' => 'hidden']) }}
                    <button class="ui green button right floated hide-first" id="pdf-download">Download Report PDF</button>
                {{ Form::close() }}
                <button class="ui green button right floated hide-first" id="png-download">Download Graph as Image</button>
            </section>
            <!-- /.content -->

            @include('components.message-js')
                @yield('message-js')

            @include('components.sti-graphs-js')
                @yield('sti-graphs-js')
@stop