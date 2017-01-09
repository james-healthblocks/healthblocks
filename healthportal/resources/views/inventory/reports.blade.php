@extends('base.base')
@section('content')
            <section class="content-header">
                <h1>
                    Inventory Report
                    <!-- <small>Optional description</small> -->
                </h1>
            </section>

            <!-- Main content -->
            <section class="content">
                @include('components.message-block')
                        @yield('message')

                {{ Form::open(array('url' => '/inventory/reports/display', 'class' => 'monthYear', 'role' => '')) }}
                <div class="ui grid container reports-controlbox">
                    <div class="six wide column reports-controller">
                        <div class="ui form column">
                            <div class="two fields no-border">
                                <label class="inline-label">From</label>
                                <div class="field">
                                    {{ Form::label('month', 'Month', ['class' => '']) }}
                                    <!-- <select class="ui search fluid selection dropdown" id="month">
                                    </select> -->
                                    {{ Form::selectMonth('startMonth', $month, ['class' => 'ui search fluid dropdown inventory-control date-field', 'id' => 'startMonth', 'placeholder' => '' ]) }}
                                </div>
                                <div class="field">
                                    {{ Form::label('year', 'Year', ['class' => '']) }}
                                    {{ Form::selectYear('startYear', $thisYear, $startYear, $thisYear, ['class' => 'ui search fluid dropdown inventory-control date-field', 'id' => 'startYear', 'placeholder' => '']) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="six wide column reports-controller">
                        <div class="ui form column">
                            <div class="two fields no-border">
                                <label class="inline-label">To</label>
                                <div class="field">
                                    {{ Form::label('month', 'Month', ['class' => '']) }}
                                    <!-- <select class="ui search fluid selection dropdown" id="month">
                                    </select> -->
                                    {{ Form::selectMonth('endMonth', $month, ['class' => 'ui search fluid dropdown inventory-control date-field', 'id' => 'endMonth', 'placeholder' => '' ]) }}
                                </div>
                                <div class="field">
                                    {{ Form::label('year', 'Year', ['class' => '']) }}
                                    {{ Form::selectYear('endYear', $thisYear, $startYear, $thisYear, ['class' => 'ui search fluid dropdown inventory-control date-field', 'id' => 'endYear', 'placeholder' => '']) }}
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