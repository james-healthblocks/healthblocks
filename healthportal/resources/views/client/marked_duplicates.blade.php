@extends('base.base')
@section('content')
            <section class="content-header">
                <h1>
                    Duplicates Handling
                    <small>Previously Marked as Duplicate</small>
                </h1>
                <div class="breadcrumb">
                    <a href="/client/duplicates">Back</a>
                </div>
            </section>

            <!-- Main content -->
            <section class="content" id='dupContent'>
                <table class='ui celled table'>
                    <thead>
                        <tr>
                            <th>New Client ID</th>
                            <th>Duplicate Client ID</th>
                            <th>Is Duplicate</th>
                            <th>Reason</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($duplicates as $duplicate)
                        <tr>
                            <td>{{ $duplicate['newest_version' ]}}</td>
                            <td>{{ $duplicate['client_id'] }}</td>
                            <td>{{ $duplicate['duplicate']?'Yes':'No' }}</td>
                            <td>{{ $duplicate['reason'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
            <!-- /.content -->
@stop