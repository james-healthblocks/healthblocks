@extends('base.base')
@section('content')

            <div class="ui small modal" id="importmodal">

                <!-- <i class="close icon"></i> -->
                <div class="header">
                    Import File
                </div>
                    {{ Form::open(array('url' => '/client/import', 'class' => 'ui form active tab column', 'files' => true,  'method' => "post", 'enctype' => "multipart/form-data", 'id' => 'importform')) }}
                <div class="content">
                    <div class="ui message" id="message-block">
                        <i class="close icon" id="message-icon"></i>
                        <p id="message-p"></p>
                    </div>
                    
                    <div class="field" id="import_content">
                        <label>File</label>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        {{ Form::file('uploadfile', array('accept' => '.bin, .csv', 'required')) }}
                        
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
                    <!-- <div class="ui button teal" id='import-button' >Upload</div> -->
                    <div class="ui button" id='cancel-button'>Cancel</div>
                </div>
            </div>

            <section class="content-header">
                <div class="ui grid container">
                    <div class="six wide column">
                        <h1>
                            Client Database View
                            <!-- <small>Optional description</small> -->
                        </h1>
                    </div>
                    <div class="six wide column">
                        <div class="content-header-button">
                            <div class="header-controls">
                                {{ Form::open(array('url' => '/export/1', 'class' => '', 'enctype' => 'multipart/form-data')) }}

                                {{ Form::hidden('hdnfilters', null, array('id' => 'hdnfilters')) }}
                                {{ Form::hidden('hdnsearch', null, array('id' => 'hdnsearch')) }}

                                    <div class="ui action input">
                                        <select class="ui selection dropdown" name="exporttype" id="exportselect">
                                            <option value="csv"> CSV </option>
                                            <option value="xls"> XLS </option>
                                            <option value="encrypted"> Encrypted </option>
                                        </select>

                                        {{ Form::submit('Export', array('class'=>'ui teal button')) }}
                                   
                                    </div>

                                    <a class="ui green button import-toggle" data-mode="1">
                                        Import
                                    </a>
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="ui grid">
                    @include('components.database-table')
                        @yield('table')
                </div>

            </section>
            <!-- /.content -->
            
            @include('components.database-js')
                @yield('js')

            @include('components.import-js')
                @yield('script')

@stop