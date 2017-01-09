@extends('base.base')
@section('content')
    <section class="content-header">
        <h1>
            Health Portal Records
            <small>{{ $uic }} / {{ $hpname }}</small>
            
            <div class="content-header-button">
                <button class="ui primary button button-submit">
                    Request Access
                </button>
            </div>

        </h1>
    </section>

    <section class="content">
        <div class='row search-list' style='margin-top: -20px;'>
            <div class='ui compact celled middle aligned selection list portal-items'>
            @foreach($results as $result)
                <a class='item' href="{{ '/portal/' . $uic . '/' . $hpid . '/' . $result['txn_date'] }}">
                    <i class="file text outline middle aligned icon"></i>
                    <div class="content portal-list">
                        <div class="header">{{ date('F d, Y (H:i)', $result['txn_date']) }}</div>
                        <div class="description">{{ $result['remarks'] }}</div>
                    </div>
                </a>
            @endforeach
            </div>
        </div>
    </section>

    <script type="text/javascript">
        $('.button-submit').on('click', function(){
            $(this).addClass('loading');
            location.reload(); 
        });
    </script>
@stop