@extends('base.base')
@section('content')
            <section class="content-header">
                <h1>
                    Manage Users
                    <!-- <small>Optional description</small> -->
                </h1>
            </section>

            <!-- Main content -->
            <section class="content">
            <!-- Your Page Content Here -->

                <div class="ui grid">
                    <div class="twelve wide column">
                        <a class="compact ui green right floated button spaced" href="{{action('AccountController@user', ['mode' => 1])}}" >
                            Add User
                        </a>

                        <table class="ui eight column fixed celled table selectable" width="100%">
                            <thead>
                                <tr>
                                    <th style="width:40px;">
                                    </th>
                                    <th style="width:50px;">
                                        ID
                                        
                                    </th>
                                    <th>
                                        Email
                                        
                                    </th>
                                    <th>
                                        Name

                                    </th>
                                    <th>
                                        Role
                                    </th>

                                    @if ( Auth::check() && (Auth::user()->role == config("constants.CENTRAL_ADMIN")) )
                                    <th>
                                        Region
                                        
                                    </th>
                                    <th>
                                        Province

                                    </th>
                                    <th>
                                        City/Municipality
                                    </th>
                                    @endif

                                    <th style="width:175px;">
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>

                                    <td style="width:40px;" >
                                        <div class="ui fitted checkbox">
                                            <input type="checkbox" >
                                            <label></label>
                                        </div>
                                    </td>
                                    <td>
                                        {{$user->id}}
                                    </td>
                                    <td>
                                        {{$user->email}}
                                    </td>
                                    <td>
                                        {{$user->name}}
                                    </td>
                                    <td>
                                        {{$user->rolename}}
                                    </td>

                                    @if ( Auth::check() && (Auth::user()->role == config("constants.CENTRAL_ADMIN")) )
                                    <td>
                                        {{ $user->region? $regions[$user->region] : ''}}
                                    </td>
                                    <td>
                                        {{ $user->province? $provinces[$user->province] : ''}}
                                    </td>
                                    <td>
                                        {{ $user->municipality? $cities[$user->municipality] : ''}}
                                    </td>
                                    @endif

                                    <td class="centered">
                                        <a class="compact ui orange button" href="{{action('AccountController@user', ['mode' => 2, 'id' => $user->id])}}" >
                                            Edit
                                        </a>
                                        <a class="compact ui red button" href="{{action('AccountController@delete', ['id' => $user->id])}}" >
                                            Delete
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                @if (session('message'))
                    <div style="font-weight:bold; color:green;">
                        {{ session('message') }}
                    </div>
                @endif
            </section>
            <!-- /.content -->

@stop