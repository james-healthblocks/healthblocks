@extends('base.base')
@section('content')
            <section class="content-header">
                <h1>
                    Account Management
                    <!-- <small>Optional description</small> -->
                </h1>
            </section>

            <!-- Main content -->
            <section class="content">
                {{ Form::open(array('url' => 'account/profile')) }}
                    {{ csrf_field() }}

                    <div class="ui centered grid form">
                        <div class="six wide column">
                            @if (session('message'))
                                <div class="ui positive message" id="message-block">
                                    <i class="close icon"></i>
                                    <p>{{ session('message') }}</p>
                                </div>
                            @endif

                            <div class="field">
                                <label for="name" class="col-md-4 control-label">Name</label>
                                {{ Form::text('name', Auth::user()->name, array('id'=>'name', 'class'=>'form-control', 'placeholder' => 'Juan Dela Cruz')) }}

                                @if ($errors->has('name'))
                                    <div class="ui pointing red basic label">
                                        {{ $errors->first('name') }}
                                    </div>
                                @endif
                            </div>

                            <div class="field {{ $errors->has('email') ? 'error' : '' }}">
                                <label for="email" class="col-md-4 control-label">E-Mail Address</label>
                                {{ Form::email('email', Auth::user()->email, array('id'=>'name','class'=>'form-control','placeholder' => 'username@domain.com', 'disabled')) }}

                                @if ($errors->has('email'))
                                    <div class="ui pointing red basic label">
                                        {{ $errors->first('email') }}
                                    </div>
                                @endif
                            </div>

                            <div class="field {{ $errors->has('password') ? 'error' : '' }}">
                                <label for="password" class="col-md-4 control-label">Password</label>
                                {{ Form::password('password', array('class'=>'form-control', 'placeholder' => 'Password')) }}

                                @if ($errors->has('password'))
                                    <div class="ui pointing red basic label">
                                        {{ $errors->first('password') }}
                                    </div>
                                @endif
                            </div>

                            <div class="field {{ $errors->has('password_confirmation') ? 'error' : '' }}">
                                <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>
                                {{ Form::password('password_confirmation', array('class'=>'form-control', 'placeholder' => 'Confirm Password')) }}

                                @if ($errors->has('password_confirmation'))
                                    <div class="ui pointing red basic label">
                                        {{ $errors->first('password_confirmation') }}
                                    </div>
                                @endif
                            </div>

                            @if ( Auth::check() && (Auth::user()->role == config("constants.SHC_ADMIN") || (Auth::user()->role == config("constants.CENTRAL_ADMIN"))) )
                            <div class="field {{ $errors->has('question') ? 'error' : '' }}">
                                {{ Form::label('question', 'Security Question', ['class' => '']) }}
                                {{ Form::select('question', $questions, Auth::user()->sq_id, ['class' => 'ui dropdown']) }}

                                @if ($errors->has('question'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('question') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="field {{ $errors->has('question') ? 'error' : '' }}">
                            {{ Form::label('answer', 'Answer', ['class' => '']) }}
                            {{ Form::text('answer', Auth::user()->answer, array('placeholder' => '')) }}

                                @if ($errors->has('answer'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('answer') }}</strong>
                                    </span>
                                @endif
                            </div>
                            @endif

                            {{ Form::submit('Edit account', array('class'=>'ui button primary right floated')) }}
                        </div>
                    </div>

                {{ Form::close() }}

            </section>
            <!-- /.content -->

            <script type="text/javascript">
                $(document).ready(function(){
                    $('.message .close').on('click', function() {
                        $("#message-block").fadeOut();
                    });
                });
            </script>
@stop