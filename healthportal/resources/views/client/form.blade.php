@extends('base.base')
@section('content')
            <section class="content-header client-form-header">
                <h1>
                    Individual Client Record
                    <!-- <small>Optional description</small> -->
                    @if(!isset($portal))
                    <div class="content-header-button">
                        <div class='ui checkbox invalid-checkbox'>
                            <input type='checkbox'>
                            <label>Data Invalid</label>
                        </div>
                        <button class="ui primary button button-submit">
                            Save
                        </button>
                    </div>
                    @endif
                </h1>
            </section>
            <!-- Main content -->
            <section class="content">
                @if(count($errors) > 1)
                <div class="ui error message" id="message-block">
                    <i class="close icon"></i>
                    <div class="header">Invalid Input</div>
                    <ul class="list">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                    </ul>
                </div>
                @endif

                @if($errors and $errors->has('unique_warning'))
                <div class="ui red icon message">
                    <i class='warning icon'></i>
                    <div>
                        <div class="header">
                            Duplicate Entry Error
                        </div>
                        <p>
                        An ICR entry for this date already exists <a href="{{ $errors->get('unique_warning')[0] }}">here</a>
                        </p>
                    </div>
                </div>
                @endif

                {{ $data }}
                {{ Form::model($icr, array('url' => Request::url(), 'class' => 'form form-inline form-multiline', 'role' => '', 'files' => true)) }}
                    {{ Form::hidden('client_id', null, ['class' => '']) }}

                    @if(!isset($portal))
                    <div class="form-divider">
                        Portal Profile
                    </div>
                    <div class="ui form">
                        <div class="four fields">
                            <div class="field">
                                <span class="fixed-value">{{ $shc ? $shc->clinicname : 'None' }}</span>
                                {{ Form::label('shc', 'Name of SHC', ['class' => 'fixed-label']) }}
                                <!-- {{ Form::text('shc', null, ['class' => 'form-control']) }} -->
                            </div>
                            <div class="field">
                                <span class="fixed-value" id="fixed-munc" data-value="{{ $shc ? $shc->municipality : '' }}">{{ $shc ? $cities[$shc->municipality] : 'None' }}</span>
                                {{ Form::label('city', 'City/Municipality', ['class' => 'fixed-label']) }}
                                <!-- {{ Form::text('city', null, ['class' => 'form-control']) }} -->
                            </div>
                            <div class="field">
                                <span class="fixed-value" id="fixed-prov" data-value="{{ $shc ? $shc->province : '' }}">{{ $shc ? $provinces[$shc->province] : 'None' }}</span>
                                {{ Form::label('province', 'Province', ['class' => 'fixed-label']) }}
                                <!-- {{ Form::text('province', null, ['class' => 'form-control']) }} -->
                            </div>
                            <div class="field">
                                <span class="fixed-value" id="fixed-reg" data-value="{{ $shc ? $shc->region : '' }}">{{ $shc ? $regions[$shc->region] : 'None' }}</span>
                                {{ Form::label('region', 'Region', ['class' => 'fixed-label']) }}
                                <!-- {{ Form::text('region', null, ['class' => 'form-control']) }} -->
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="form-divider">
                        Individual Client Record
                    </div>

                    <div class="ui form">
                        <div class="three fields">
                            <div class="field id-field">
                                {{ Form::label('uic', 'UIC', ['class' => '']) }}
                                {{ Form::text('uic', null, ['class' => 'form-control', 'maxlength' => '14']) }}
                            </div>
                            <div class="field">
                                {{ Form::label('consult_date', 'Consult Date', ['class' => '']) }}
                                {{ Form::text('consult_date', null, ['class' => 'form-control datepicker']) }}
                            </div>
                            <div class="fields morespace">
                                <div class="field four wide column">
                                    {{ Form::label('consulttype', 'Consult Type', ['class' => '']) }}
                                </div>
                                <div class="field">
                                    <div class="ui radio checkbox">
                                        {{ Form::radio('consulttype','1') }}
                                        <label>Registration</label>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="ui radio checkbox">
                                        {{ Form::radio('consulttype','2') }}
                                        <label>Follow-up</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ui form">
                        <div class="four fields">
                            <div class="field">
                                {{ Form::label('usrimage', 'Client Image') }}
                                @if($icr->image)
                                <div class='field' id="imagefield">
                                    <div class='ui teal buttons'>
                                        <a class='ui fluid button' href="{{ asset($icr->image) }}" target="_blank">View User Image</a>
                                        <div class='ui floating dropdown icon button'>
                                            <i class="dropdown icon"></i>
                                            <div class="menu">
                                                <div class="item" id="replace-image"><i class="edit icon"></i> Replace Image</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class='field'>
                                    {{ Form::file('usrimage') }}
                                </div>
                                @endif
                            </div>
                            <div class="field">
                                {{ Form::label('firstname', 'First Name', ['class' => '']) }}
                                {{ Form::text('firstname', null, ['class' => 'form-control']) }}
                            </div>
                            <div class="field">
                                {{ Form::label('middlename', 'Middle Name', ['class' => '']) }}
                                {{ Form::text('middlename', null, ['class' => 'form-control']) }}
                            </div>
                            <div class="field">
                                {{ Form::label('lastname', 'Last Name', ['class' => '']) }}
                                {{ Form::text('lastname', null, ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="four fields">
                            <div class="field">
                                {{ Form::label('birthdate', 'Birth Date', ['class' => '']) }}
                                {{ Form::text('birthdate', 'Invalid UIC', ['class' => 'form-control', 'readonly' => '']) }}
                            </div>
                            <div class="field">
                                {{ Form::label('age', 'Age', ['class' => '']) }}
                                {{ Form::text('age', 'Invalid UIC', ['class' => 'form-control', 'readonly' => '']) }}
                            </div>
                            <div class="field">
                                {{ Form::label('sex', 'Sex', ['class' => '']) }}
                                {{ Form::select('sex', ['1' => 'Male', '2' => 'Female'], null, ['class' => 'ui dropdown sex_dropdown', 'placeholder' => '', 'id' => '']) }}
                            </div>
                            <div class='field'>
                                {{ Form::label('gender_identity', 'Gender Identity', ['class' => '' ]) }}
                                {{ Form::select('gender_identity', ['1' => 'Male', '2' => 'Female', '3' => 'TGW', '4' => 'TGM', '5' => 'Other'], null, ['class' => 'ui dropdown gi_dropdown', 'placeholder' => ''])}}
                            </div>
                        </div>

                        <div class="inline fields">
                            <div class="field two wide column">
                                {{ Form::label('is_resident', 'Is a resident of this city?', ['class' => '']) }}
                            </div>
                            <div class="field">
                                <div class="ui radio checkbox">
                                    {{ Form::radio('is_resident','1', null, ['class' => 'is_resident']) }}
                                    <label>Yes</label>
                                </div>
                            </div>
                            <div class="field">
                                <div class="ui radio checkbox">
                                    {{ Form::radio('is_resident', '0', null, ['class' => 'is_resident']) }}
                                    <label>No</label>
                                </div>
                            </div>
                            <div class="three fields">
                                <div class="field">
                                    {{ Form::label('municipality', 'Current City/Municipality', ['class' => '']) }}
                                    {{ Form::select('municipality', $cities, null, ['class' => 'ui search selection dropdown munc_select current address address-city']) }}
                                </div>
                                <div class="field">
                                    {{ Form::label('province', 'Current Province', ['class' => '']) }}
                                    {{ Form::select('province', $provinces, null, ['class' => 'ui search selection dropdown prov_select current address address-province']) }}
                                </div>
                                <div class="field">
                                    {{ Form::label('region', 'Current Region', ['class' => '']) }}
                                    {{ Form::select('region', $regions, null, ['class' => 'ui search selection dropdown region_select current address address-region']) }}
                                </div>
                            </div>
                        </div>

                        <div class="inline fields">
                            <div class="field two wide column">
                                {{ Form::label('is_perm_resident', 'Is a permanent resident of city specified above?', ['class' => '']) }}
                            </div>
                            <div class="field">
                                <div class="ui radio checkbox">
                                    {{ Form::radio('is_perm_resident', '1', null, ['class' => 'is_resident permanent']) }}
                                    <label>Yes</label>
                                </div>
                            </div>
                            <div class="field">
                                <div class="ui radio checkbox">
                                    {{ Form::radio('is_perm_resident', '0', null, ['class' => 'is_resident permanent']) }}
                                    <label>No</label>
                                </div>
                            </div>
                            <div class='three fields'>
                                <div class='field'>
                                    {{ Form::label('perm_municipality', 'Permanent City/Municipality', ['class' => '']) }}
                                    {{ Form::select('perm_municipality', $cities, null, ['class' => 'ui search selection dropdown munc_select perm address address-city']) }}
                                </div>
                                <div class='field'>
                                    {{ Form::label('perm_province', 'Permanent Province', ['class' => '']) }}
                                    {{ Form::select('perm_province', $provinces, null, ['class' => 'ui search selection dropdown prov_select perm address address-province']) }}
                                </div>
                                <div class='field'>
                                    {{ Form::label('perm_region', 'Permanent Region', ['class' => '']) }}
                                    {{ Form::select('perm_region', $regions, null, ['class' => 'ui search selection dropdown region_select perm address address-region']) }}
                                </div>
                            </div>
                        </div>

                        <div class="fields ui grid">
                            <div class="field two wide column">
                                {{ Form::label('riskgroup', 'Client Group', ['class' => '']) }}
                            </div>
                            <div class="ui ten wide column grid">
                            @foreach($risk_groups as $rg_value => $rg)
                                <div class="six wide column checkboxes">
                                    <div class="ui checkbox riskgroupboxes {{ array_key_exists('restriction', $rg) ? $rg['restriction'] : ''}}" field="riskgroup">
                                        {{ Form::checkbox($rg_value,'1') }}
                                        <label>{{ $rg['text'] }}</label>
                                    </div>
                                </div>
                            @endforeach
                                <div class="twelve wide row checkboxes">
                                    <div class="ui checkbox riskgroupboxes" field="riskgroup">
                                        {{ Form::checkbox('rg_others','1') }}
                                        <label>Others, please specify</label>
                                    </div>
                                    <div class="four wide column ui smaller input">
                                        {{ Form::text('rg_others_text', null, ['class' => 'form-control']) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="inline fields with-subgroup client-type">
                            <div class="field two wide column">
                                {{ Form::label('clienttype', 'Client Type', ['class' => '']) }}
                            </div>
                            <div class="field">
                                <div class="ui radio checkbox">
                                    {{ Form::radio('client_type','1', null, ['class' => 'client-type-option'] ) }}
                                    <label>Referral</label>
                                </div>
                            </div>
                            <div class="field">
                                <div class="ui radio checkbox">
                                    {{ Form::radio('client_type','2', null, ['class' => 'client-type-option'] ) }}
                                    <label>Mobile</label>
                                </div>
                            </div>
                            <div class="field">
                                <div class="ui radio checkbox">
                                    {{ Form::radio('client_type','3', null, ['class' => 'client-type-option'] ) }}
                                    <label>Walk-in</label>
                                </div>
                            </div>
                        </div>

                        <div class="fields ui grid subgroup client-type">
                                <div class="field two wide column">
                                </div>
                                <div class="ui ten wide column grid">
                                    <div class="field">
                                        <div class="ui radio checkbox">
                                            {{ Form::radio('client_ref','1') }}
                                            <label>Antenatal Clinic</label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox">
                                            {{ Form::radio('client_ref','2') }}
                                            <label>TB DOTS Facility</label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox">
                                            {{ Form::radio('client_ref','3') }}
                                            <label>Others, please specify</label>
                                        </div>
                                        <div class="ui smaller input radio-input">
                                            {{ Form::text('client_ref_reason', null, ['class' => 'form-control']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <div class="fields ui grid">
                            <div class="field two wide column">
                                {{ Form::label('reason', 'Consult Reason', ['class' => '']) }}
                            </div>
                            <div class="ui ten wide column grid">
                                <div class="six wide column checkboxes">
                                    <div class="ui checkbox">
                                        {{ Form::checkbox('cr_routine','1', null, ['id' => 'reason_1']) }} 
                                        {{ Form::label('reason_1', 'Routine', ['class' => 'inline-checkbox']) }}
                                    </div>
                                </div>
                                <div class="six wide column checkboxes">
                                    <div class="ui checkbox">
                                        {{ Form::checkbox('cr_sti_services','1', null, ['id' => 'reason_2']) }} 
                                        {{ Form::label('reason_2', 'STI Services', ['class' => 'inline-checkbox']) }} 
                                    </div>
                                </div>
                                <div class="six wide column checkboxes">
                                    <div class="ui checkbox">
                                        {{ Form::checkbox('cr_hiv_services','1', null, ['id' => 'reason_3']) }} 
                                        {{ Form::label('reason_3', 'HIV Services', ['class' => 'inline-checkbox']) }} 
                                    </div>
                                </div>
                                
                                <div class="six wide column checkboxes">
                                    <div class="ui checkbox">
                                        {{ Form::checkbox('cr_others','1') }}
                                        <label>Others, please specify</label>
                                    </div>
                                    <div class="five wide column ui smaller input">
                                        {{ Form::text('cr_others_text', null, ['class' => 'form-control']) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="two fields" id="establishment-fields">
                            <div class="field">
                                {{ Form::label('establishment', 'Name of Establishment', ['class' => '']) }}
                                {{ Form::text('establishment', null, ['class' => 'form-control']) }}
                            </div>
                            <div class="field">
                                {{ Form::label('est_type', 'Type of Establishment', ['class' => '']) }}
                                {{ Form::text('est_type', null, ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="inline fields" id="is_pregnant_fields">
                            <div class="field two wide column">
                                {{ Form::label('is_pregnant', 'Is Pregnant?', ['class' => '']) }}
                            </div>
                            <div class="field">
                                <div class="ui radio checkbox">
                                    {{ Form::radio('is_pregnant','1') }}
                                    <label>Yes</label>
                                </div>
                            </div>
                            <div class="field">
                                <div class="ui radio checkbox">
                                    {{ Form::radio('is_pregnant','0') }}
                                    <label>No</label>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="form-divider">
                        Tests
                    </div>

                    <div class="ui form">
                        <div class="fields tests" toggle-for="syph_screen">
                            <div class="six wide column">
                                <div class="ui checkbox">
                                    {{ Form::checkbox('syp_scr','1', null, ['id' => 'reason_2']) }} 
                                    {{ Form::label('syp_scr', 'Syphilis Screening', ['class' => 'checkbox-label']) }} 
                                </div>
                            </div>
                        </div>
                        <div class="fields ui grid test-entry" test-entry="syph_screen">
                            <div class="six wide column">
                                <div class="inline fields">
                                    <div class="field two wide column">
                                        {{ Form::label('syp_scr_res', 'Test Result', ['class' => '']) }}
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox test_result syph_screen">
                                            {{ Form::radio('syp_scr_res','1') }}
                                            <label>Reactive</label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox test_result syph_screen">
                                            {{ Form::radio('syp_scr_res','0') }}
                                            <label>Non-reactive</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="six wide column">
                                <div class="inline fields">
                                    <div class="field seven wide column">
                                        {{ Form::label('syp_scr_inf', 'Informed about syphilis screening test result?', ['class' => '']) }}
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox">
                                            {{ Form::radio('syp_scr_inf','1') }}
                                            <label>Yes</label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox">
                                            {{ Form::radio('syp_scr_inf','0') }}
                                            <label>No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="fields ui grid test-entry" test-entry="syph_screen">
                            <div class="six wide column">
                                <div class="inline fields">
                                    <div class="field seven wide column">
                                        {{ Form::label('syp_scr_prev', 'Previously tested reactive for syphilis', ['class' => '']) }}
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox prev_tested">
                                            {{ Form::radio('syp_scr_prev','1') }}
                                            <label>Yes</label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox prev_tested">
                                            {{ Form::radio('syp_scr_prev','0') }}
                                            <label>No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="six wide column">
                                <div class="inline fields prev_tested_cont">
                                    <div class="field seven wide column">
                                        {{ Form::label('syp_scr_prev_cont', 'Continued from last time or new case of STI?', ['class' => '']) }}
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox">
                                            {{ Form::radio('syp_scr_prev_cont','1') }}
                                            <label>Yes</label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox">
                                            {{ Form::radio('syp_scr_prev_cont','0') }}
                                            <label>No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="fields ui grid test-entry" test-entry="syph_screen">
                            <div class="field two wide column">
                                    {{ Form::label('syp_scr_treat', 'Syphilis Treatment', ['class' => '']) }} 
                            </div>
                            <div class="ui ten wide column grid">
                                <div class="field">
                                    <div class="ui radio checkbox">
                                        {{ Form::radio('syp_scr_treat','1') }}
                                        <label>Medications prescribed and provided by SHC</label>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="ui radio checkbox">
                                        {{ Form::radio('syp_scr_treat','2') }}
                                        <label>Prescribed medications only</label>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="ui radio checkbox">
                                        {{ Form::radio('syp_scr_treat', '0') }}
                                        <label>None given</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ui form">
                        <div class="fields tests" toggle-for="syph_confirm">
                            <div class="six wide column">
                                <div class="ui checkbox">
                                    {{ Form::checkbox('syp_conf','1', null, ['id' => 'reason_2']) }} 
                                    {{ Form::label('syp_conf', 'Syphilis Confirmatory', ['class' => 'checkbox-label']) }} 
                                </div>
                            </div>
                        </div>
                        <div class="fields ui grid test-entry" test-entry="syph_confirm">
                            <div class="six wide column">
                                <div class="inline fields">
                                    <div class="field two wide column">
                                        {{ Form::label('syp_conf_res', 'Test Result', ['class' => '']) }}
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox test_result">
                                            {{ Form::radio('syp_conf_res','1') }}
                                            <label>Positive</label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox test_result">
                                            {{ Form::radio('syp_conf_res','0') }}
                                            <label>Negative</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="six wide column">
                                <div class="inline fields">
                                    <div class="field seven wide column">
                                        {{ Form::label('syp_conf_inf', 'Informed about syphilis confirmatory test result?', ['class' => '']) }}
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox">
                                            {{ Form::radio('syp_conf_inf','1') }}
                                            <label>Yes</label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox">
                                            {{ Form::radio('syp_conf_inf','0') }}
                                            <label>No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="inline fields">
                            {{ Form::label('shc', 'STI Diagnosis', ['class' => '']) }}
                            <div class="field seven wide">
                                {{ Form::text('shc', null, ['class' => 'form-control']) }}
                            </div>
                        </div> -->
                        <div class="fields ui grid test-entry" test-entry="syph_confirm">
                            <div class="six wide column">
                                <div class="inline fields">
                                    <div class="field seven wide column">
                                        {{ Form::label('syp_conf_prev', 'Previously tested positive for syphilis', ['class' => '']) }}
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox prev_tested">
                                            {{ Form::radio('syp_conf_prev','1') }}
                                            <label>Yes</label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox prev_tested">
                                            {{ Form::radio('syp_conf_prev','0') }}
                                            <label>No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="six wide column">
                                <div class="inline fields prev_tested_cont">
                                    <div class="field seven wide column">
                                        {{ Form::label('syp_conf_prev_cont', 'Continued from last time or new case of STI?', ['class' => '']) }}
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox">
                                            {{ Form::radio('syp_conf_prev_cont','1') }}
                                            <label>Yes</label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox">
                                            {{ Form::radio('syp_conf_prev_cont','0') }}
                                            <label>No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="fields ui grid test-entry" test-entry="syph_confirm">
                            <div class="field two wide column">
                                    {{ Form::label('syp_conf_treat', 'Syphilis Treatment', ['class' => '']) }} 
                            </div>
                            <div class="ui ten wide column grid">
                                <div class="field">
                                    <div class="ui radio checkbox">
                                        {{ Form::radio('syp_conf_treat','1') }}
                                        <label>Medications prescribed and provided by SHC</label>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="ui radio checkbox">
                                        {{ Form::radio('syp_conf_treat','2') }}
                                        <label>Prescribed medications only</label>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="ui radio checkbox">
                                        {{ Form::radio('syp_conf_treat', '0') }}
                                        <label>None given</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ui form">
                        <div class="fields tests" toggle-for="gram_stain">
                            <div class="six wide column">
                                <div class="ui checkbox">
                                    {{ Form::checkbox('gram_stain','1', null, ['id' => 'reason_2']) }} 
                                    {{ Form::label('gram_stain', 'Test Type: Gram Stain', ['class' => 'checkbox-label']) }} 
                                </div>
                            </div>
                        </div>
                        <div class="fields ui grid test-entry" test-entry="gram_stain">
                            <div class="six wide column">
                                <div class="inline fields">
                                    <div class="field four wide column">
                                        {{ Form::label('reason', 'Test Result', ['class' => '']) }}
                                    </div>
                                    <div class="ui eight wide column grid">
                                        <div class="twelve wide column checkboxes">
                                            <div class="ui checkbox test_result gs_res disable-others" field="gramstain">
                                                {{ Form::checkbox('no_evidence_res','1', null, ['id' => 'reason_1']) }} 
                                                {{ Form::label('no_evidence_res', 'No remarkable pathologic and cytologic evidence', ['class' => 'inline-checkbox']) }}
                                            </div>
                                        </div>
                                        <div class="twelve wide column checkboxes">
                                            <div class="ui checkbox test_result gs_res" field="gramstain">
                                                {{ Form::checkbox('gono_res','1', null, ['id' => 'reason_2']) }} 
                                                {{ Form::label('gono_res', 'Presence of gram negative intracellular and extracellular diplococci', ['class' => 'inline-checkbox']) }} 
                                            </div>
                                        </div>
                                        <div class="twelve wide column checkboxes">
                                            <div class="ui checkbox test_result gs_res" field="gramstain">
                                                {{ Form::checkbox('ngi_res','1', null, ['id' => 'reason_3']) }} 
                                                {{ Form::label('ngi_res', 'Presence of +3 pus (for females) or +1 (for males) cell/bacilli/organism', ['class' => 'inline-checkbox']) }} 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="six wide column">
                                <div class="inline fields">
                                    <div class="field seven wide column">
                                        {{ Form::label('gram_stain_inf', 'Informed about Gram Stain screening test result?', ['class' => '']) }}
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox">
                                            {{ Form::radio('gram_stain_inf','1') }}
                                            <label>Yes</label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox">
                                            {{ Form::radio('gram_stain_inf','0') }}
                                            <label>No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="fields ui grid test-entry" test-entry="gram_stain">
                            <div class="six wide column">
                                <div class="inline fields">
                                    <div class="field seven wide column">
                                        {{ Form::label('gono_prev', 'Previously tested positive for gonorrhea', ['class' => '']) }}
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox prev_tested">
                                            {{ Form::radio('gono_prev','1') }}
                                            <label>Yes</label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox prev_tested">
                                            {{ Form::radio('gono_prev','0') }}
                                            <label>No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="six wide column">
                                <div class="inline fields prev_tested_cont">
                                    <div class="field seven wide column">
                                        {{ Form::label('gono_cont', 'Continued from last time or new case of STI?', ['class' => '']) }}
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox">
                                            {{ Form::radio('gono_cont','1') }}
                                            <label>Yes</label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox">
                                            {{ Form::radio('gono_cont','0') }}
                                            <label>No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="fields ui grid test-entry" test-entry="gram_stain">
                            <div class="field two wide column">
                                    {{ Form::label('gono_treat', 'Gonorrhea Treatment', ['class' => '']) }} 
                            </div>
                            <div class="ui ten wide column grid">
                                <div class="field">
                                    <div class="ui radio checkbox">
                                        {{ Form::radio('gono_treat','1') }}
                                        <label>Medications prescribed and provided by SHC</label>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="ui radio checkbox">
                                        {{ Form::radio('gono_treat','2') }}
                                        <label>Prescribed medications only</label>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="ui radio checkbox">
                                        {{ Form::radio('gono_treat', '0') }}
                                        <label>None given</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="fields ui grid test-entry" test-entry="gram_stain">
                            <div class="six wide column">
                                <div class="inline fields">
                                    <div class="field seven wide column">
                                        {{ Form::label('ngi_prev', 'Previously tested positive for NGI', ['class' => '']) }}
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox prev_tested">
                                            {{ Form::radio('ngi_prev','1') }}
                                            <label>Yes</label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox prev_tested">
                                            {{ Form::radio('ngi_prev','0') }}
                                            <label>No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="six wide column">
                                <div class="inline fields prev_tested_cont">
                                    <div class="field seven wide column">
                                        {{ Form::label('ngi_cont', 'Continued from last time or new case of STI?', ['class' => '']) }}
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox">
                                            {{ Form::radio('ngi_cont','1') }}
                                            <label>Yes</label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox">
                                            {{ Form::radio('ngi_cont','0') }}
                                            <label>No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="fields ui grid test-entry" test-entry="gram_stain">
                            <div class="field two wide column">
                                    {{ Form::label('ngi_treat', 'NGI Treatment', ['class' => '']) }} 
                            </div>
                            <div class="ui ten wide column grid">
                                <div class="field">
                                    <div class="ui radio checkbox">
                                        {{ Form::radio('ngi_treat','1') }}
                                        <label>Medications prescribed and provided by SHC</label>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="ui radio checkbox">
                                        {{ Form::radio('ngi_treat','2') }}
                                        <label>Prescribed medications only</label>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="ui radio checkbox">
                                        {{ Form::radio('ngi_treat', '0') }}
                                        <label>None given</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="fields ui grid test-entry" test-entry="gram_stain">
                            <div class="six wide column">
                                <div class="inline fields">
                                    <div class="field seven wide column">
                                        {{ Form::label('bacvag_insp', 'Inspected for Bacterial Vaginosis', ['class' => '']) }}
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox inspected female-only">
                                            {{ Form::radio('bacvag_insp','1') }}
                                            <label>Yes</label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox inspected female-only">
                                            {{ Form::radio('bacvag_insp','0') }}
                                            <label>No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="six wide column">
                                <div class="inline fields insp_with">
                                    <div class="field seven wide column">
                                        {{ Form::label('bacvag_res', 'With Bacterial Vaginosis', ['class' => '']) }}
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox test_result female-only">
                                            {{ Form::radio('bacvag_res','1') }}
                                            <label>Yes</label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox test_result female-only">
                                            {{ Form::radio('bacvag_res','0') }}
                                            <label>No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="fields ui grid test-entry" test-entry="gram_stain">
                            <div class="six wide column">
                                <div class="inline fields">
                                    <div class="field seven wide column">
                                        {{ Form::label('bacvag_prev', 'Previously tested positive for Bacterial Vaginosis', ['class' => '']) }}
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox prev_tested female-only">
                                            {{ Form::radio('bacvag_prev','1') }}
                                            <label>Yes</label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox prev_tested female-only">
                                            {{ Form::radio('bacvag_prev','0') }}
                                            <label>No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="six wide column">
                                <div class="inline fields prev_tested_cont">
                                    <div class="field seven wide column">
                                        {{ Form::label('bacvag_cont', 'Continued from last time or new case of STI?', ['class' => '']) }}
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox female-only">
                                            {{ Form::radio('bacvag_cont','1') }}
                                            <label>Yes</label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox female-only">
                                            {{ Form::radio('bacvag_cont','0') }}
                                            <label>No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="fields ui grid test-entry" test-entry="gram_stain">
                            <div class="field two wide column">
                                {{ Form::label('bacvag_treat', 'Bacterial Vaginosis Treatment', ['class' => '']) }}
                            </div>
                            <div class="ui ten wide column grid">
                                <div class="field">
                                    <div class="ui radio checkbox female-only">
                                        {{ Form::radio('bacvag_treat','1') }}
                                        <label>Medications prescribed and provided by SHC</label>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="ui radio checkbox female-only">
                                        {{ Form::radio('bacvag_treat','2') }}
                                        <label>Prescribed medications only</label>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="ui radio checkbox female-only">
                                        {{ Form::radio('bacvag_treat', '0') }}
                                        <label>None given</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ui form">
                        <div class="fields tests" toggle-for="wet_mount">
                            <div class="six wide column">
                                <div class="ui checkbox female-only">
                                    {{ Form::checkbox('wet_mount','1', null, ['id' => 'reason_2']) }} 
                                    {{ Form::label('wet_mount', 'Test Type: Wet Mount', ['class' => 'checkbox-label']) }} 
                                </div>
                            </div>
                        </div>
                        <div class="fields ui grid test-entry" test-entry="wet_mount">
                            <div class="six wide column">
                                <div class="inline fields">
                                    <div class="field three wide column">
                                        {{ Form::label('tri_res', 'Test Result', ['class' => '']) }}
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox test_result">
                                            {{ Form::radio('tri_res','1') }}
                                            <label>Trichomonas</label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox test_result">
                                            {{ Form::radio('tri_res','0') }}
                                            <label>No Trichomonas</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="six wide column">
                                <div class="inline fields">
                                    <div class="field seven wide column">
                                        {{ Form::label('tri_inf', 'Informed about Wet Mount screening test result?', ['class' => '']) }}
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox">
                                            {{ Form::radio('tri_inf','1') }}
                                            <label>Yes</label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox">
                                            {{ Form::radio('tri_inf','0') }}
                                            <label>No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="fields ui grid test-entry" test-entry="wet_mount">
                            <div class="six wide column">
                                <div class="inline fields">
                                    <div class="field seven wide column">
                                        {{ Form::label('tri_prev', 'Previously tested positive for trichomoniasis', ['class' => '']) }}
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox prev_tested">
                                            {{ Form::radio('tri_prev','1') }}
                                            <label>Yes</label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox prev_tested">
                                            {{ Form::radio('tri_prev','0') }}
                                            <label>No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="six wide column">
                                <div class="inline fields prev_tested_cont">
                                    <div class="field seven wide column">
                                        {{ Form::label('tri_cont', 'Continued from last time or new case of STI?', ['class' => '']) }}
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox">
                                            {{ Form::radio('tri_cont','1') }}
                                            <label>Yes</label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox">
                                            {{ Form::radio('tri_cont','0') }}
                                            <label>No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="fields ui grid test-entry" test-entry="wet_mount">
                            <div class="field two wide column">
                                    {{ Form::label('tri_treat', 'Trichomoniasis Treatment', ['class' => '']) }} 
                            </div>
                            <div class="ui ten wide column grid">
                                <div class="field">
                                    <div class="ui radio checkbox">
                                        {{ Form::radio('tri_treat','1') }}
                                        <label>Medications prescribed and provided by SHC</label>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="ui radio checkbox">
                                        {{ Form::radio('tri_treat','2') }}
                                        <label>Prescribed medications only</label>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="ui radio checkbox">
                                        {{ Form::radio('tri_treat','0') }}
                                        <label>None given</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ui form">
                        <div class="fields tests" toggle-for="HBsAg">
                            <div class="six wide column">
                                <div class="ui checkbox">
                                    {{ Form::checkbox('hbsag','1', null, ['id' => 'reason_2']) }} 
                                    {{ Form::label('hbsag', 'Test Type: HBsAg', ['class' => 'checkbox-label']) }} 
                                </div>
                            </div>
                        </div>
                        <div class="fields ui grid test-entry" test-entry="HBsAg">
                            <div class="six wide column">
                                <div class="inline fields">
                                    <div class="field two wide column">
                                        {{ Form::label('hepab_res', 'Test Result', ['class' => '']) }}
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox test_result hepab_res">
                                            {{ Form::radio('hepab_res','1') }}
                                            <label>Positive</label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox test_result hepab_res">
                                            {{ Form::radio('hepab_res','0') }}
                                            <label>Negative</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="six wide column">
                                <div class="inline fields">
                                    <div class="field seven wide column">
                                        {{ Form::label('hepab_inf', 'Informed about the HBsAG screening test result?', ['class' => '']) }}
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox">
                                            {{ Form::radio('hepab_inf','1') }}
                                            <label>Yes</label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox">
                                            {{ Form::radio('hepab_inf','0') }}
                                            <label>No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="fields ui grid test-entry" test-entry="HBsAg">
                            <div class="six wide column">
                                <div class="inline fields hep_vac">
                                    <div class="field seven wide column">
                                        {{ Form::label('hepab_vac', 'Hepatitis B Vaccine given (First dose)', ['class' => '']) }}
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox">
                                            {{ Form::radio('hepab_vac','1') }}
                                            <label>Yes</label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox">
                                            {{ Form::radio('hepab_vac','0') }}
                                            <label>No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="inline fields">
                            {{ Form::label('shc', 'STI Diagnosis', ['class' => '']) }}
                            <div class="field seven wide">
                                {{ Form::text('shc', null, ['class' => 'form-control']) }}
                            </div>
                        </div> -->
                    </div>
                    <div class="ui form">
                        <div class="fields tests" toggle-for="HepaC">
                            <div class="six wide column">
                                <div class="ui checkbox">
                                    {{ Form::checkbox('hepac','1', null, ['id' => 'reason_2']) }} 
                                    {{ Form::label('hepac', 'Test Type: Anti-HCV Antibody', ['class' => 'checkbox-label']) }} 
                                </div>
                            </div>
                        </div>
                        <div class="fields ui grid test-entry" test-entry="HepaC">
                            <div class="six wide column">
                                <div class="inline fields">
                                    <div class="field two wide column">
                                        {{ Form::label('hepac_res', 'Test Result', ['class' => '']) }}
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox test_result">
                                            {{ Form::radio('hepac_res','1') }}
                                            <label>Positive</label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox test_result">
                                            {{ Form::radio('hepac_res','0') }}
                                            <label>Negative</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="six wide column">
                                <div class="inline fields">
                                    <div class="field seven wide column">
                                        {{ Form::label('hepac_inf', 'Informed about the HBsAG screening test result?', ['class' => '']) }}
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox">
                                            {{ Form::radio('hepac_inf','1') }}
                                            <label>Yes</label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox">
                                            {{ Form::radio('hepac_inf','0') }}
                                            <label>No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ui form">
                        <div class="fields tests" toggle-for="inspected">
                            <div class="six wide column">
                                <div class="ui checkbox">
                                    {{ Form::checkbox('inspected','1', null, ['id' => 'reason_2']) }} 
                                    {{ Form::label('inspected', 'Test Type: Inspected For', ['class' => 'checkbox-label']) }} 
                                </div>
                            </div>
                        </div>
                        <div class="fields ui grid test-entry" test-entry="inspected">
                            <div class="six wide column">
                                <div class="inline fields">
                                    <div class="field seven wide column">
                                        {{ Form::label('gen_warts_insp', 'Inspected for Genital Warts', ['class' => '']) }}
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox inspected">
                                            {{ Form::radio('gen_warts_insp','1') }}
                                            <label>Yes</label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox inspected">
                                            {{ Form::radio('gen_warts_insp','0') }}
                                            <label>No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="six wide column">
                                <div class="inline fields insp_with">
                                    <div class="field seven wide column">
                                        {{ Form::label('gen_warts_res', 'With Genital Warts', ['class' => '']) }}
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox test_result">
                                            {{ Form::radio('gen_warts_res','1') }}
                                            <label>Yes</label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox test_result">
                                            {{ Form::radio('gen_warts_res','0') }}
                                            <label>No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="fields ui grid test-entry" test-entry="inspected">
                            <div class="six wide column">
                                <div class="inline fields">
                                    <div class="field seven wide column">
                                        {{ Form::label('gen_warts_prev', 'Previously tested positive for genital warts', ['class' => '']) }}
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox prev_tested">
                                            {{ Form::radio('gen_warts_prev','1') }}
                                            <label>Yes</label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox prev_tested">
                                            {{ Form::radio('gen_warts_prev','0') }}
                                            <label>No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="six wide column">
                                <div class="inline fields prev_tested_cont">
                                    <div class="field seven wide column">
                                        {{ Form::label('gen_warts_cont', 'Continued from last time or new case of STI?', ['class' => '']) }}
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox">
                                            {{ Form::radio('gen_warts_cont','1') }}
                                            <label>Yes</label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox">
                                            {{ Form::radio('gen_warts_cont','0') }}
                                            <label>No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="fields ui grid test-entry" test-entry="inspected">
                            <div class="six wide column">
                                <div class="inline fields">
                                    <div class="field seven wide column">
                                        {{ Form::label('anal_warts_insp', 'Inspected for Anal Warts', ['class' => '']) }}
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox inspected">
                                            {{ Form::radio('anal_warts_insp','1') }}
                                            <label>Yes</label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox inspected">
                                            {{ Form::radio('anal_warts_insp','0') }}
                                            <label>No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="six wide column">
                                <div class="inline fields insp_with">
                                    <div class="field seven wide column">
                                        {{ Form::label('anal_warts_res', 'With Anal Warts', ['class' => '']) }}
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox test_result">
                                            {{ Form::radio('anal_warts_res','1') }}
                                            <label>Yes</label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox test_result">
                                            {{ Form::radio('anal_warts_res','0') }}
                                            <label>No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="fields ui grid test-entry" test-entry="inspected">
                            <div class="six wide column">
                                <div class="inline fields">
                                    <div class="field seven wide column">
                                        {{ Form::label('anal_warts_prev', 'Previously tested positive for Anal warts', ['class' => '']) }}
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox prev_tested">
                                            {{ Form::radio('anal_warts_prev','1') }}
                                            <label>Yes</label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox prev_tested">
                                            {{ Form::radio('anal_warts_prev','0') }}
                                            <label>No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="six wide column">
                                <div class="inline fields prev_tested_cont">
                                    <div class="field seven wide column">
                                        {{ Form::label('anal_warts_cont', 'Continued from last time or new case of STI?', ['class' => '']) }}
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox">
                                            {{ Form::radio('anal_warts_cont','1') }}
                                            <label>Yes</label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox">
                                            {{ Form::radio('anal_warts_cont','0') }}
                                            <label>No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="fields ui grid test-entry" test-entry="inspected">
                            <div class="six wide column">
                                <div class="inline fields">
                                    <div class="field seven wide column">
                                        {{ Form::label('herpes_insp', 'Inspected for Herpes', ['class' => '']) }}
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox inspected">
                                            {{ Form::radio('herpes_insp','1') }}
                                            <label>Yes</label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox inspected">
                                            {{ Form::radio('herpes_insp','0') }}
                                            <label>No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="six wide column">
                                <div class="inline fields insp_with">
                                    <div class="field seven wide column">
                                        {{ Form::label('herpes_res', 'With Herpes', ['class' => '']) }}
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox test_result">
                                            {{ Form::radio('herpes_res','1') }}
                                            <label>Yes</label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox test_result">
                                            {{ Form::radio('herpes_res','0') }}
                                            <label>No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="fields ui grid test-entry" test-entry="inspected">
                            <div class="six wide column">
                                <div class="inline fields">
                                    <div class="field seven wide column">
                                        {{ Form::label('herpes_prev', 'Previously tested positive for Herpes', ['class' => '']) }}
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox prev_tested">
                                            {{ Form::radio('herpes_prev','1') }}
                                            <label>Yes</label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox prev_tested">
                                            {{ Form::radio('herpes_prev','0') }}
                                            <label>No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="six wide column">
                                <div class="inline fields prev_tested_cont">
                                    <div class="field seven wide column">
                                        {{ Form::label('herpes_cont', 'Continued from last time or new case of STI?', ['class' => '']) }}
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox">
                                            {{ Form::radio('herpes_cont','1') }}
                                            <label>Yes</label>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="ui radio checkbox">
                                            {{ Form::radio('herpes_cont','0') }}
                                            <label>No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="fields ui grid test-entry" test-entry="inspected">
                            <div class="field two wide column">
                                {{ Form::label('herpes_treat', 'Herpes Treatment', ['class' => '']) }}
                            </div>
                            <div class="ui ten wide column grid">
                                <div class="field">
                                    <div class="ui radio checkbox">
                                        {{ Form::radio('herpes_treat','1') }}
                                        <label>Medications prescribed and provided by SHC</label>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="ui radio checkbox">
                                        {{ Form::radio('herpes_treat','2') }}
                                        <label>Prescribed medications only</label>
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="ui radio checkbox">
                                        {{ Form::radio('herpes_treat', '0') }}
                                        <label>None given</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ui form">
                        <div class="fields ui grid">
                            <div class="field two wide column">
                                {{ Form::label('others', 'STI Diagnosis', ['class' => '']) }}
                            </div>
                            <div class="field ten wide column">
                                <textarea rows="2" id="STIDiagnosis" readonly=""></textarea>
                            </div>
                        </div> 
                    </div>

                    <div class="form-divider">
                        Referrals
                    </div>

                    <div class="ui form">
                        <div class="fields ui grid">
                            <div class="field two wide column">
                                {{ Form::label('referred_to', 'Referred client to', ['class' => '']) }}
                            </div>
                            <div class="ui ten wide column grid">
                                <div class="seven wide column checkboxes">
                                    <div class="ui checkbox">
                                        {{ Form::checkbox('ref_antenatal','1') }}
                                        <label>Antenatal Clinic</label>
                                    </div>
                                </div>
                                <div class="seven wide column checkboxes">
                                    <div class="ui checkbox">
                                        {{ Form::checkbox('ref_tb_dots','1') }}
                                        <label>TB DOTS</label>
                                    </div>
                                </div>
                                <div class="seven wide column checkboxes">
                                    <div class="ui checkbox">
                                        {{ Form::checkbox('ref_physician','1') }}
                                        <label>Private Physician</label>
                                    </div>
                                </div>
                                <div class="seven wide column checkboxes">
                                    <div class="ui checkbox">
                                        {{ Form::checkbox('ref_treat_hub','1') }}
                                        <label>Treatment Hub</label>
                                    </div>
                                    <div class="five wide column ui smaller input">
                                        {{ Form::text('ref_treat_hub_text', null, ['class' => 'form-control']) }}
                                    </div>
                                </div>
                                <div class="seven wide column checkboxes">
                                    <div class="ui checkbox">
                                        {{ Form::checkbox('ref_others','4') }}
                                        <label>Others, please specify</label>
                                    </div>
                                    <div class="five wide column ui smaller input">
                                        {{ Form::text('ref_others_text', null, ['class' => 'form-control']) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="fields ui grid">
                            <div class="field two wide column">
                                {{ Form::label('remarks', 'Other Services Provided/Remarks', ['class' => '']) }}
                            </div>
                            <div class="field ten wide column">
                                {{ Form::textarea('remarks', null, ['rows' => 2]) }}
                            </div>
                        </div> 
                    </div>

                {{ Form::hidden('invalid') }}
                @if(!isset($portal))
                {{ Form::submit('', ['class'=>'hidden-submit']) }}
                @endif
                {{ Form::close() }}
            </section>
            <!-- /.content -->

            @if(!isset($portal))
            <section class="footer">
                <div class='content-header-button'>
                    <h1>
                    <div class='ui checkbox invalid-checkbox'>
                        <input type='checkbox'>
                        <label>Data Invalid</label>
                    </div>
                    <button class="ui primary button button-submit">
                        Save
                    </button>
                    </h1>
                </div>
            </section>
            @endif

            <script type="text/javascript" src="/js/client-dataentry.js"></script>
            <script type="text/javascript" src="/js/checkbox-restrictions.js"></script>
            <script type='text/javascript'>
                $('.message .close').on('click', function() {
                    $("#message-block").fadeOut();
                });

                $('.button-submit').click(function (e) {
                    $('form').submit();
                });
            </script>
            <script type='text/javascript' src='/js/address-picker.js'></script>
@stop