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

            <!-- Your Page Content Here -->
                <div class="ui centered grid form with-border">
                    <div class="six wide column">
                    @if ($mode == 1)
                        {{ Form::model($user, array('url' => '/account/create', 'class' => 'ui form')) }}
                    @endif
                    @if ($mode == 2)
                        {{ Form::model($user, array('url' => '/account/user/' . $user->id, 'class' => 'ui form')) }}
                    @endif
                        {{ csrf_field() }}

                        <div class='field'>
                            {{ Form::label('name', 'Name') }}
                            {{ Form::text('name', null, array('placeholder' => 'Juan Dela Cruz')) }}
                            @if ($errors->has('name'))
                                <div class="ui pointing red basic label">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                        </div>
                        <div class='field'>
                            {{ Form::label('email', 'E-Mail Address') }}
                            @if ($mode == 1)
                                {{ Form::email('email', null, array('placeholder' => 'username@domain.com')) }}
                            @endif
                            @if ($mode == 2)
                                {{ Form::email('email', null, array('placeholder' => 'username@domain.com', 'disabled')) }}
                            @endif

                            @if ($errors->has('email'))
                                <div class="ui pointing red basic label">
                                    {{ $errors->first('email') }}
                                </div>
                            @endif
                        </div>

                        <div class="two fields">
                            <div class='field'>
                                {{ Form::label('password', 'Password') }}
                                {{ Form::password('password', array('class'=>'form-control', 'placeholder' => 'Password')) }}
                                
                                @if ($errors->has('password'))
                                    <div class="ui pointing red basic label">
                                        {{ $errors->first('password') }}
                                    </div>
                                @endif
                            </div>
                            <div class='field'>
                                {{ Form::label('password-confirm', 'Confirm Password') }}
                                {{ Form::password('password_confirmation', array('class'=>'form-control', 'placeholder' => 'Password')) }}

                                @if ($errors->has('password_confirmation'))
                                    <div class="ui pointing red basic label">
                                        {{ $errors->first('password_confirmation') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class='three fields'>
                            <div class="field">
                                {{ Form::label('role', 'User Access', ['class' => '']) }}
                                {{ Form::select('role', $roles, null, ['class' => 'ui dropdownrole']) }}
                            </div>
                        </div>

                        <div id="divsq">
                            @if ($user->role == config("constants.SHC_ADMIN") || $user->role == config("constants.CENTRAL_ADMIN") || $mode == 1 )
                            <div class='two fields'>
                                <div class="field">
                                    {{ Form::label('question', 'Security Question', ['class' => '']) }}
                                    {{ Form::select('sq_id', $questions, null, ['class' => 'ui dropdown']) }}
                                </div>
                                <div class="field">
                                    {{ Form::label('answer', 'Answer', ['class' => '']) }}
                                    {{ Form::text('answer', null, array('placeholder' => '')) }}

                                    @if ($errors->has('answer'))
                                        <div class="ui pointing red basic label">
                                            {{ $errors->first('answer') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                        
                      
                        <div class='three fields' id='addr-fields' style="display: none;">
                            <div class='field'>
                                {{ Form::label('municipality', 'City/Municipality') }}
                                {{ Form::select('municipality', $cities, null, ['class' => 'ui dropdown']) }}
                            </div>
                            <div class='field'>
                                {{ Form::label('province', 'Province') }}
                                {{ Form::select('province', $provinces, null, ['class' => 'ui dropdown']) }}
                            </div>
                            <div class='field'>
                                {{ Form::label('region', 'Region') }}
                                {{ Form::select('region', $regions, null, ['class' => 'ui dropdown']) }}
                            </div>
                        </div>

                        @if ( Auth::check() && (Auth::user()->role == config("constants.CENTRAL_ADMIN")) )
                        @endif

                            @if ($mode == 1)
                                {{ Form::submit('Add user', array('class'=>'ui button primary right floated')) }}
                            @endif
                            @if ($mode == 2)
                                {{ Form::submit('Edit user', array('class'=>'ui button primary right floated')) }}
                            @endif
                    {{ Form::close() }}
                    </div> 
                </div>

            </section>

            <section class='footer'>
                <script type='text/javascript' src='/js/create-users.js'></script>
            </section>
            <!-- /.content -->


<script type="text/javascript">
    $(document).ready(function() {

        var role = $(".dropdownrole").dropdown('get value');
        if (!(role == {{config("constants.SHC_ADMIN")}} || role == {{config("constants.CENTRAL_ADMIN")}})) {
            $("#divsq").empty();
        }

        $(document).on('change', '.dropdownrole', function(){
            var role = $(".dropdownrole").dropdown('get value');

            if(role == {{config("constants.SHC_ADMIN")}} || role == {{config("constants.CENTRAL_ADMIN")}}) {
                $("#divsq").empty();
                $("#divsq").append('<div class="two fields">' +
                            '<div class="field">' + 
                                '{{ Form::label("question", "Security Question", ["class" => ""]) }}' +
                                '{{ Form::select("sq_id", $questions, null, ["class" => "ui dropdown"]) }}' +
                            '</div>' +
                            '<div class="field">' +
                                '{{ Form::label("answer", "Answer", ["class" => ""]) }}' +
                                '{{ Form::text("answer", null, array("placeholder" => "")) }}' +
                            '</div>' +
                            '</div>');
            }
            else {
                $("#divsq").empty();
            }
        });
    });
</script>

@stop