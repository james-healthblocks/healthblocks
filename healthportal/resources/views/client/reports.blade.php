@extends('base.base')
@section('content')
            <section class="content-header">
                <h1>
                    Client and STI Report
                    <!-- <small>Optional description</small> -->
                </h1>
            </section>

            <!-- Main content -->
            <section class="content">
                @include('components.message-block')
                        @yield('message')

                {{ Form::open(array('url' => '/client/reports/display', 'class' => 'fullDate', 'role' => '')) }}
                <div class="ui grid container reports-controlbox">
                    <div class="twelve wide column reports-controller">
                        <div class="ui form column">
                            <div class="two fields no-border">
                                <div class="field date-field">
                                    <label>From</label>
                                    {{ Form::text('activity-date-start', null, ['class' => 'form-control datepicker']) }}
                                </div>
                                <div class="field date-field">
                                    <label>To</label>
                                    {{ Form::text('activity-date-end', null, ['class' => 'form-control datepicker']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="twelve wide column reports-controller">
                        <div class="ui form column">
                            <div class="three fields no-border">
                                <div class="field">
                                    {{ Form::label('municipality', 'City/Municipality', ['class' => '']) }}
                                    {{ Form::select('municipality', $cities, null, ['class' => 'ui search selection dropdown', 'id' => 'munc']) }}
                                </div>
                                <div class="field">
                                    {{ Form::label('province', 'Province', ['class' => '']) }}
                                    {{ Form::select('province', $provinces, null, ['class' => 'ui search selection dropdown', 'id' => 'prov']) }}
                                </div>
                                <div class="field">
                                    {{ Form::label('region', 'Region', ['class' => '']) }}
                                    {{ Form::select('region', $regions, null, ['class' => 'ui search selection fluid dropdown', 'id' => 'reg']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="twelve wide column reports-controller">
                        <div class="ui form column">
                            <div class="three fields no-border">
                                <div class="field"></div>
                                <div class="field">
                                    {{ Form::label('physician', 'Physician Name', ['class' => '']) }}
                                    {{ Form::text('physician', null, ['class' => 'form-control ']) }}
                                </div>
                                <div class="field"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <button class="ui green large button right floated reports-generate-button">Generate Report</button>
                {{ Form::close() }}
            </section>
            <!-- /.content -->


            @include('components.message-js')
                @yield('message-js')

            @include('components.reports-js')
                @yield('reports-js')

@stop