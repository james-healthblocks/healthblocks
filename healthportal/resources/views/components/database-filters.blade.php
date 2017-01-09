@section('filters')
            <div class="ui vertically divided grid ui form" id="filters">
	            @foreach($filters as $filter)
	            	<div class="row">
	            		@if($filter['row'])
	            			@foreach($filter['fields'] as $field)
	            			@if($field['type'] == 'daterange')
	            			<div class="two wide column label-column">
	            				<label>{{ $filter['row'] }}</label>
	            				<div class="ui slider checkbox filter-field date-field display-all" field="{{ $field['fieldname'] }}">
									<input name="display-all-dates" type="checkbox">
									<label>Display All</label>
								</div>
	            			</div>
	            			
	            			<div class="ten wide column">
	                            <div class="two fields no-border">
	                                <div class="field date-field">
	                                    <label>From</label>
	                                    {{ Form::text('startdate', null, ['class' => 'filter-field datepicker date-field', 'field' => $field['fieldname']]) }}
	                                </div>
	                                <div class="field date-field">
	                                    <label>To</label>
	                                    {{ Form::text('enddate', null, ['class' => 'filter-field datepicker date-field', 'field' => $field['fieldname']]) }}
	                                </div>
	                            </div>
		                    </div>
	            			@endif
	            			@endforeach
	            		@else
	            			<div class="twelve wide column">
		            			<div class="fields">
		            			@foreach($filter['fields'] as $field)
		            				<div class="six wide field">
		            					@if(isset($field['logic_toggle']))
		            						<div class="logic-toggle" field="{{ $field['fieldname'] }}">
		            							<div class="ui toggle checkbox" field="{{ $field['fieldname'] }}" data-tooltip="Helper Text" data-inverted="" >
		            								<span class="toggle-text"></span>
													<input name="public" type="checkbox">
													<label></label>
												</div>
		            						</div>
		            					@endif
		            					<label>{{ $field['label'] }}</label>
		            					@if($field['type'] == 'checkbox')
		            						@foreach($field['data'] as $option)
		            						<div class="filter checkboxes">
			            						<div class="ui checkbox filter-field {{ $field['fieldname']=='risk_groups' && $option['value']=='rg_no_known' ? 'no_rg' : '' }}" field="{{ $field['fieldname'] }}">
													<input type="checkbox" value="{{ $option['value'] }}">
													<label>{{ $option["text"] }}</label>
												</div>
											</div>
											@endforeach
		            					@elseif($field['type'] == 'dropdown' || $field['type'] == 'combo')
		            						<div class='ui selection dropdown filter-field {{ $field["type"] == "combo" ? "multiple" : "" }}' field="{{ $field['fieldname'] }}">
		            							<input name="{{ $field['fieldname'] }}" type="hidden">
		            							<i class="dropdown icon"></i>
		            							<div class="default text"></div>
		            							<div class='menu'>
		            								@foreach($field['data'] as $option)
		            									<div class='item' data-value='{{ isset($option["field_name"]) ? $option["field_name"]."-".$option["value"] : $option["value"] }}' field-name='{{ isset($option["field_name"]) ? $option["field_name"] : "" }}'>
		            										{{ $option['text'] }}
		            									</div>
		            								@endforeach
		            									@if($field["type"] != "combo")
		            										<div class='item' data-value="" field-name='{{ isset($option["field_name"]) ? $option["field_name"] : "" }}'>Display all</div>
		            									@endif
		            							</div>
		            						</div>
		            					@endif
		            				</div>
		            			@endforeach
		            			</div>
	            			</div>
	            		@endif
	            	</div>
	            @endforeach
            	<div class="row">
            		<div class="six wide column" id="pagination"></div>
            		<div class="six wide column" id="search-bar"></div>
            	</div>
            </div>
        	<div class="ui vertically divided grid form filter-display-toggle-wrap">
        		<div class="row">
	            	<div class="filter-display-toggle">
	            		<i class="ui icon chevron up"></i> <span class="action">Hide</span> Filters
	            	</div>
            	</div>
        	</div>
            <div class="ui divider"></div>
@stop