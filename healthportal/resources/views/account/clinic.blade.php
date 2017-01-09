@extends('base.base')
@section('content')
            <section class="content-header">
                <h1>
                    Clinic Information
                    <!-- <small>Optional description</small> -->
                </h1>
            </section>

            <!-- Main content -->
            <section class="content">
                {{ Form::model($shc, array('url' => Request::url(), 'class' => 'ui form', 'role' => '', 'files' => true)) }}
                    <div class='form-divider'>
                        HealthPortal Account Credentials
                    </div>
                    <div class='three fields'>
                        <div class='field'>
                            {{ Form::label('hp_id', 'HealthPortal ID') }}
                            {{ Form::text('hp_id') }}
                        </div>
                        <div class='field'>
                            {{ Form::label('wallet_addr', 'Wallet Address') }}
                            {{ Form::text('wallet_addr') }}
                        </div>
                    </div>
                    <div class='form-divider'>
                        HealthPortal Information
                    </div>
                    <div class='three fields'>
                        <div class='field {{ $shc->validated ? "disabled" : "" }}'>
                            {{ Form::label('clinicname', 'HealthPortal Name') }}
                            {{ Form::text('clinicname') }}
                        </div>
                        <div class='field'>
                            {{ Form::label('usrimage', 'HealthPortal Logo') }}
                            {{ Form::file('usrimage') }}
                        </div>
                        <div class='field'></div>

                    </div>
                    <div class='three fields'>
                        @if($shc->validated)
                        <div class='field disabled'>
                        @else
                        <div class='field'>
                        @endif
                            {{ Form::label('municipality', 'City/Municipality') }}
                            {{ Form::select('municipality', $cities, null, ['class' => 'ui search selection dropdown address address-city']) }}
                        </div>
                        @if($shc->validated)
                        <div class='field disabled'>
                        @else
                        <div class='field'>
                        @endif
                            {{ Form::label('province', 'Province') }}
                            {{ Form::select('province', $provinces, null, ['class' => 'ui search selection dropdown address address-province']) }}
                        </div>
                        @if($shc->validated)
                        <div class='field disabled'>
                        @else
                        <div class='field'>
                        @endif
                            {{ Form::label('region', 'Region') }}
                            {{ Form::select('region', $regions, null, ['class' => 'ui search selection dropdown address address-region']) }}
                        </div>
                    </div>
                {{ Form::submit('Save', ['class'=>'ui button primary right floated']) }}
                {{ Form::close() }}

            <!-- Your Page Content Here -->

            </section>
            <!-- /.content -->

            <script type="text/javascript">
                $(".ui.dropdown").dropdown();
                $(".ui.checkbox").checkbox();
            </script>
            <script type="text/javascript" src="/js/address-picker.js"></script>
@stop