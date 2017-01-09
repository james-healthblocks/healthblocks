@extends('base.base')
@section('content')
            <section class="content-header">
                <h1>
                    STI Positivity Rates
                    <small>Generate Graphs</small>
                </h1>
            </section>

            <!-- Main content -->
            <section class="content">
                @include('components.message-block')
                        @yield('message')

                {{ Form::open(array('url' => '/sti_graphs/display', 'class' => 'monthYear', 'role' => '')) }}
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
                                    {{ Form::selectYear('startYear', $thisYear, $thisYear-1, $thisYear, ['class' => 'ui search fluid dropdown inventory-control date-field', 'id' => 'startYear', 'placeholder' => '']) }}
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
                                    {{ Form::selectYear('endYear', $thisYear, $thisYear-1, $thisYear, ['class' => 'ui search fluid dropdown inventory-control date-field', 'id' => 'endYear', 'placeholder' => '']) }}
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
                    <div class="ui form twelve wide column reports-controller reports-filter">
                        <div class="fields no-border">
                            <div class="twelve wide field">
                                <label>STI's</label>
                                @foreach($sti as $i => $sti)
                                <div class="filter checkboxes">
                                    <div class="ui checkbox filter-field" field="sti">
                                        {{ Form::checkbox('sti['.$sti['value'].']', '1') }}
                                        <label>{{ $sti['text'] }}</label>
                                    </div>
                                </div>
                                @endforeach
                                <div class="filter checkboxes">
                                    <div class="ui checkbox select-all" field="sti" select-all="sti">
                                        <input type="checkbox" value="all">
                                        <label>Select All</label>
                                    </div>
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

            <script type="text/javascript">
                $(document).ready(function(){
                    $(document).on('click', '.select-all', function(){
                        field = $(this).attr('select-all');

                        if($(this).checkbox('is checked')){
                            $("div[field='"+ field +"']").checkbox('check');
                        }else{
                            $("div[field='"+ field +"']").checkbox('uncheck');
                        }
                    });

                    $(document).on('click', '.ui.checkbox.filter-field', function(){
                        $selectAll = $(".ui.checkbox.select-all[field='sti']");

                        if($selectAll.checkbox('is checked')){
                            $selectAll.checkbox('uncheck');
                        }
                    });
                });
            </script>
@stop