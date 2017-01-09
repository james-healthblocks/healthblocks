@extends('layouts.app')

@section('pagename')
    Reset Password
@endsection

<!-- Main Content -->
@section('content')
    <form class="ui large form" role="form" method="POST" action="{{ url('/password/email') }}">
            {{ csrf_field() }}
            <div class="ui segment">
            @if (session('message'))
                <div class="ui yellow message" id="message-block">
                    {{ session('message') }}
                </div>
            @endif
                <div class="field{{ $errors->has('email') ? ' error' : '' }}">
                    @if ($errors->has('email'))
                    <div class="ui left pointing red basic label error-label">
                        {{ $errors->first('email') }}
                    </div>
                    @endif
                    <div class="ui left icon input">
                        <i class="user icon"></i>
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="E-mail Address" required autofocus>
                    </div>
                </div>

                <button class="ui fluid large primary submit button" type="submit">Check Email</button>
            </div>

            <div class="ui error message"></div>

        </form>

        <div class="ui message">
            <a class="btn btn-link" href="{{ url('/login') }}">
                Return to Login screen
            </a>
        </div>
    </div>

@endsection
