@extends('base.base')
@section('content')
            <section class="content-header">
                <h1>
                    Past Client Records: {{ $firstname }} {{ $lastname }}
                </h1>
            </section>

            <section class="content">
                <div class='ui grid'>
                    <div class="one wide column"></div>
                    <div class="ten wide column">
                        <div class='row search-list'>
                            <div class="ui relaxed celled middle aligned selection list">
                                <a href="{{ route('newConsult', array('uic' => $uic, 'id' => $id)) }}" class="item new-client">
                                    <i class="large add user middle aligned icon"></i>
                                    <div class="content">
                                        <div class="header">New Consultation</div>
                                        <div class="description">Create New Record</div>
                                    </div>
                                </a>
                            @foreach ($results as $result)
                                <div class="item">
                                    <div class='right floated'>
                                        @if ($result->image)
                                        <a class='ui icon button' href='{{ asset($result->image) }}' target="_blank"><i class="image icon"></i></a>
                                        @endif
                                        <a class='ui button' href="{{ route('editConsult', array('uic' => $result['uic'], 'client_id' => $result['client_id'], 'pk' => $result['id'])) }}">Edit Record</a>
                                    </div>
                                    <i class="large user middle aligned icon"></i>
                                    <div class="content">
                                        <div class="header">{{ $result['consult_date'] }}</div>
                                    </div>
                                </div>
                            @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </section>
@stop