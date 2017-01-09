@extends('base.base')
@section('content')
        @if(!empty($dashboard))
            <section class="content-header">
                <h1>
                    Welcome to your HealthPortal!
                </h1>
            </section>
        @endif

            <section class="content-header">
                <h1>
                    Search Client Records
                </h1>
            </section>

            <section class="content">
                {{ Form::open(array('url' => 'client/records', 'class' => 'form form-inline form-multiline', 'role' => '')) }}
                <div class='ui grid'>
                    <div class="one wide column"></div>
                    <div class="ten wide column">
                        <div class="ui action huge input fluid row">
                            {{ Form::text('uic', $uic, ['class' => '', 'placeholder' => 'Enter UIC or Name']) }}
                            <button class="ui button">Search</button>
                        </div>
                        @if (isset($results) and !($isUIC))
                        <div class='row'>
                            <div class="ui pointing red basic label">
                                Enter a valid UIC to add a new Client (e.g. ABCD0101011970)
                            </div>
                        </div>
                        @endif
                        @if (isset($results))
                        <div class='row search-list'>
                            <div class='ui relaxed celled middle aligned selection list'>
                            @if ($isUIC)
                                <a href="{{ route('newClient', $uic) }}" class="item new-client">
                                    <i class="large add user middle aligned icon"></i>
                                    <div class="content">
                                        <div class="header">New Client</div>
                                        <div class="description">Create New Record</div>
                                    </div>
                                </a>
                            @endif
                            @if (count($results)>0)
                            <h3>Local Patient Records</h3>
                                    @foreach ($results as $result)
                                <div class="item">
                                    <div class="right floated">
                                        <a class="ui button" href="{{ route('listConsult', array('uic' => $result['uic'], 'id' => $result['client_id'])) }}">View Existing Records</a>
                                        <a class="ui button" href="{{ route('newConsult', array('uic' => $result['uic'], 'id' => $result['client_id'])) }}">Add New Record</a>
                                    </div>
                                    <i class='large user middle aligned icon'></i>
                                    <div class="content">
                                        <div class="header">{{ $result['firstname'] }} {{ $result['lastname'] }}</div>
                                    </div>
                                </div>
                                    @endforeach
                            @else
                                @if(empty($health_network))
                                    <div class='item'>
                                        <div class='centered'>No Search Results</div>
                                    </div>
                                @endif
                            @endif
                            </div>
                        </div>
                        @endif
                        @if (isset($health_network))
                        <h3>Patient Records From Other Portals</h3>
                        <div class='row search-list'>
                            <div class='ui relaxed celled middle aligned selection list'>
                            @foreach ($health_network as $key=>$data)
                                <a class="item" href="{{ '/portal/' . $uic . '/' . $key }}">
                                    <div class="content">
                                        <div class="header"><h3>{{ $data['name'] }}</h3></div>
                                        <ul class='record-list'>
                                        @foreach ($data['info'] as $v)
                                            <li>
                                                <strong>{{ date('F d, Y (H:i)', $v['txn_date']) }} </strong>
                                                @if(!empty($v['remarks']))
                                                    <span class="italic">&mdash; {{ $v['remarks'] }}</span>
                                                @endif
                                            </li>

                                        @endforeach
                                        </ul>
                                    </div>
                                </a>
                            @endforeach
                            </div>
                        </div>
                        @endif                     
                    </div>
                </div>
                
                {{ Form::close() }}
            </section>
@stop