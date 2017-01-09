@extends('base.base')
@section('content')
            <section class="content-header">
                <h1>
                    Import Reached Data
                    <!-- <small>Optional description</small> -->
                </h1>
            </section>

            <!-- Main content -->
            <section class="content">
                {{ Form::open(array('url' => '/reached/import/csv', 'class' => 'ui form active tab column', 'files' => true,  'method' => "post", 'enctype' => "multipart/form-data", 'id' => 'importform')) }}
                <div class="content">
<!-- <div class="ui active inverted dimmer">
<div class="ui indeterminate text loader">Preparing Files</div>
</div> -->
                    <div class="ui message" id="message-block">
                        <i class="close icon" id="message-icon"></i>
                        <p id="message-p"></p>
                    </div>

                    <div class="field" id="import_content">
                        <label>File</label>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_origin" value="reached">
                        {{ Form::file('uploadfile', array('accept' => '.csv', 'required')) }}
                        
                        @if ($errors->has('uploadfile'))
                            <span class="help-block">
                                <strong>{{ $errors->first('uploadfile') }}</strong>
                            </span>
                        @endif
                    </div>

                    {{ Form::close() }}
                    
                    <div class='ui divider'></div>
                    <div class="ui teal progress" id="importbar">
                        <div class="bar"></div>
                        <div class="label">% Completed</div>
                    </div>
                </div>
                <div class="actions">
                    {{ Form::submit('Import', array('class'=>'ui button teal right floated', 'form' => 'importform', 'id' => 'import-button')) }}
                </div>

            </section>
            <!-- /.content -->

            @include('components.import-js')
                @yield('script')

            <script type="text/javascript">
                $(document).ready(function(){
                    $('.message .close').on('click', function() {
                        $("#message-block").fadeOut();
                    });
                });
            </script>
@stop