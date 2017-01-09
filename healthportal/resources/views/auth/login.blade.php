@extends('layouts.app')

@section('pagename')
    Login
@endsection

@section('content')

        <form class="ui large form" role="form" method="POST" action="{{ url('/login') }}">
            {{ csrf_field() }}
            <div class="ui segment">
            @if (session('passwordmessage'))
                <div class="ui green message" id="message-block">
                    {{ session('passwordmessage') }}
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
                <div class="field{{ $errors->has('password') ? ' error' : '' }}">
                    @if ($errors->has('password'))
                    <div class="ui left pointing red basic label error-label">
                        {{ $errors->first('password') }}
                    </div>
                    @endif
                    <div class="ui left icon input">
                        <i class="lock icon"></i>
                        <input id="password" type="password" class="form-control" name="password" placeholder="Password" required>
                    </div>
                </div>
                <button class="ui fluid large primary submit button" type="submit">Login</button>
            </div>

            <div class="ui error message"></div>

        </form>

        <div class="ui message">
            <a class="btn btn-link" href="{{ url('/password/email') }}">
                Forgot Your Password?
            </a>
        </div>
    </div>

@endsection
