@extends('base.base')
@section('content')
            <section class="content-header">
                <h1>
                    Services Data Entry
                    <!-- <small>Optional description</small> -->
                </h1>
            </section>

            <!-- Main content -->
            <section class="content">
                {{ Form::open(array('url' => 'foo/bar', 'class' => 'form form-inline form-multiline', 'role' => '')) }}
                    <div class="ui form">
                        <div class="three fields">
                            <div class="four wide field ui grid">
                                <div class="inline field">
                                    {{ Form::label('activity-date', 'Date of Activity', ['class' => '']) }}
                                    {{ Form::text('activity-date', null, ['class' => 'datepicker']) }}
                                </div>

                                <div class="inline field" style="margin-top: .5em;">
                                    {{ Form::label('activity-venue', 'Venue of Activity', ['class' => '']) }}
                                    <select class="ui search selection dropdown typeDropdown" id="activity-venue">
                                        <option value=""></option>
                                        @foreach($service_venue as $venue)
                                        <option value="{{ $venue['value'] }}">{{ $venue['text'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="eight wide field ui grid">
                                <div class="field two wide column label-column">
                                    {{ Form::label('riskgroup', 'Type of Service Provided', ['class' => '']) }}
                                </div>
                                <div class="ui ten wide column grid">
                                    @foreach($service_type as $i => $service)
                                    <div class="six wide column checkboxes">
                                        <div class="ui checkbox service-type-checkbox">
                                            {{ Form::checkbox('service_type',$i+1) }}
                                            {{ Form::label('service_type', $service["label"], ['class' => '']) }} 
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    </div>

                    @include('components.message-block')
                        @yield('message')

                    <div class="six wide column services-content">
                        <table class="ui eight column fixed celled table selectable services-table">
                            <thead>
                                <tr class="service-column-headers">
                                    <th class="three wide column" rowspan="2">Client Group</th>
                                </tr>
                                <tr class="service-sex-headers">
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="service-client-count-row" row-number="0">
                                    <td class="three wide column button-wrap">
                                        <button class="ui circular add icon mini button add-row">
                                            <i class="plus icon"></i>
                                        </button>
                                        <select class="ui dropdown clientDropdown" id="row-0">
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <button class="ui primary button right floated" id="save-service">Save</button>
                        <button class="ui orange button right floated" id="clear">Create New</button>
                    </div>
                {{ Form::close() }}
            </section>
            <!-- /.content -->

            @include('components.message-js')
                @yield('message-js')

            <script type="text/javascript">
                var service_types = {!! json_encode($service_type) !!};
                var sex = {!! json_encode($sex) !!};
                var client_types = {!! json_encode($client_type) !!};
                var tgm = {{ $tg["m"] }};
                var tgw = {{ $tg["w"] }};
            </script>

            <script src="/js/services-dataentry.js"></script>
@stop