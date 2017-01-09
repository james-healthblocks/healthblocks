@section('table')
            <div class="twelve wide column">
                @include('components.database-filters')
                    @yield('filters')
                <table class="ui eight column celled table selectable" id="database-table" width="100%">
                    <thead>
                        <tr>
                        @foreach($columns as $column)
                        	<th column-name='{{ isset($column["id"]) ? $column["id"] : "" }}'
                                rowspan='{{ isset($column["rowspan"]) ? $column["rowspan"] : 1 }}'
                                colspan='{{ isset($column["colspan"]) ? $column["colspan"] : 1 }}'>
                                {{ $column["label"] }}
                            </th>
 	                    @endforeach
 	                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
@stop