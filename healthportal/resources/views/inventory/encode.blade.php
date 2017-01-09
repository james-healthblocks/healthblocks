@extends('base.base')
@section('content')
            <section class="content-header">
                <h1>
                    Inventory Data Sheet
                    <!-- <small>Optional description</small> -->
                </h1>
            </section>

            <!-- Main content -->
            <section class="content">
                {{ Form::open(array('url' => '', 'class' => 'form form-inline form-multiline', 'role' => '')) }}

                <div class="ui grid container">
                    <div class="four wide column inventory-controller">
                        <div class="ui form column">
                            <div class="two fields">
                                <div class="field">
                                    {{ Form::label('month', 'Month', ['class' => '']) }}
                                    <!-- <select class="ui search fluid selection dropdown" id="month">
                                    </select> -->
                                    {{ Form::selectMonth('month', $month, ['class' => 'ui search fluid dropdown inventory-control', 'id' => 'month', 'placeholder' => '' ]) }}
                                </div>
                                <div class="field">
                                    {{ Form::label('year', 'Year', ['class' => '']) }}
                                    {{ Form::selectYear('year', $thisYear, $startYear, $thisYear, ['class' => 'ui search fluid dropdown inventory-control', 'id' => 'year', 'placeholder' => '']) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="eight wide column">
                        <div class="ui form">
                            <div class="field spacer">
                                {{ Form::label('firstname', 'Category', ['class' => '']) }}
                                <select class="ui dropdown inventory-control" id="inventory_categories">
                                    <option value=""> </option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category }}">{{ $category }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    @include('components.message-block')
                        @yield('message')

                    <div class="ui grid container loading-container">
                        <div class="twelve wide column">
                            <div class="ui active centered inline large text loader">Loading</div>
                        </div>
                    </div>

                    <div class="ui grid inventory-table-container">
                        <div class="twelve wide column">
                            <button class="ui primary button right floated spaced save-inventory">Save</button>
                            <table class="ui eight column fixed celled table selectable inventory-table">
                                <thead>
                                    <tr class="inventory-column-headers">
                                        <th class="three wide column">Type</th>
                                        <th>Batch No.</th>
                                        <th class="two wide column">Expiry Date</th>
                                        <th>Beginning Balance</th>
                                        <th>Physical Count/ Current Stock on Hand</th>
                                        <th class="two wide column">Remarks</th>
                                        <th class="two wide column">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="service-client-count-row" row-number="0" id="new-row-control">
                                        <td class="three wide column button-wrap">
                                            <button class="ui circular add icon mini button add-row">
                                                <i class="plus icon"></i>
                                            </button>
                                            <select class="ui search selection dropdown typeDropdown" id="inventory_types">
                                                <option value=""></option>
                                            </select>
                                        </td>
                                        <td class="selectable-cell data-cell" data="batchno">
                                            <input type="text" class='for-selectable'></input>
                                        </td>
                                        <td class="selectable-cell data-cell" data="expiry_date">
                                            <input type="text" class='for-selectable datepicker'></input>
                                        </td>
                                        <td class="read-only data-cell" data="start_amt">
                                            <input type="text" value="0" class='for-selectable read-only' disabled></input>
                                        </td>
                                        <td class="read-only data-cell" data="on_hand">
                                            <input type="text" value="0" class='for-selectable read-only' disabled></input>
                                        </td>
                                        <td class="selectable-cell remarks data-cell" data="remarks">
                                            <input type="text" class='for-selectable'></input>
                                        </td>
                                        <td class="selectable-cell" data="action">
                                            <div class="ui left labeled input">
                                                <div class="ui dropdown label action-dropdown add" row-number="0">
                                                    <div class="text action-dropdown-text" data-value="add"><i class="plus icon"></i></div>
                                                    <div class="menu">
                                                        <div class="item" data-value="add"><i class="plus icon"></i></div>
                                                        <div class="item" data-value="sub"><i class="minus icon"></i></div>
                                                    </div>
                                                </div>
                                                <input class="for-selectable amount-input" row-number="0">
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <button class="ui primary button right floated save-inventory">Save</button>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </section>
            <!-- /.content -->

            @include('components.message-js')
                @yield('message-js')
            <script src="/js/inventory-dataentry.js"></script>
@stop