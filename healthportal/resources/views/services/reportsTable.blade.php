@section('services-report-table')
	
                    <table class="ui celled table services report-table">
                        <thead>
                            <tr>
                                <td colspan="{{ $info['colCount'] }}"><h1>Social Hygiene Clinic Report</h1></td>
                            </tr>
                            <tr>
                                <td colspan="{{ $info['colCount'] }}"><span class="secondary">SHC Services</span></td>
                            </tr>
                            <tr>
                                <td colspan="{{ $info['colCount'] }}">{{ $info["startDate"] }} — {{ $info["endDate"] }}</td>
                            </tr>
                            <tr>
                                <td colspan="{{ $info['colCount'] }}" class="bottom-border">{{ $info["municipality"] }}, {{ $info["province"] }}, {{ $info["region"] }}</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="table-report-divider"><td colspan="{{ $info['colCount'] }}"></td></tr>
                            <tr class="report-table-h1">
                                <td colspan="{{ $info['colCount'] }}">3.1 NUMBER OF SHC SERVICE ENCOUNTERS</td>
                            </tr>
                            <tr class="report-table-label centered">
                                <td colspan="{{ $info['encActiviesColCount'] + 2 }}">HIV Prevention Activities</td>
                                <td colspan="{{ $info['encActiviesNoColCount'] }}">Total Number of Activities this Period</td>
                            </tr>

                            @foreach($data["encounters"] as $key => $encounter)
                            <tr class="report-table-info">
                                <td class="report-table-bold centered" colspan="2">3.1.{{ $key+1 }}</td>
                                <td class="report-table-bold" colspan="{{ $info['encActiviesColCount'] }}">{{ $encounter["label"] }}</td>
                                <td class="centered" colspan="{{ $info['encActiviesNoColCount'] }}">{{ $encounter["count"] }}</td>
                            </tr>
                            @endforeach

                            <tr class="table-report-divider"><td colspan="{{ $info['colCount'] }}"></td></tr>
                            <tr class="report-table-h1">
                                <td colspan="{{ $info['colCount'] }}">3.2 NUMBER OF SHC SERVICE ENCOUNTERS BY KEY AFFECTED POPULATION</td>
                            </tr>

                            <tr class="report-table-label">
                                <td colspan="4" rowspan="2">HIV Prevention Activities</td>
                                @foreach($data["clients"] as $client)
                                <td colspan="{{count($client['sex'])}}" rowspan="{{ count($client['sex']) > 1 ? 1 : 2 }}">
                                    {{ $client["label"] }}
                                </td>
                                @endforeach
                                <td colspan="2">Total</td>
                            </tr>
                            <tr class="report-table-label">
                                @foreach($data["clients"] as $client)
                                    @if(count($client["sex"]) > 1)
                                <td>Male</td>
                                <td>Female</td>
                                    @endif
                                @endforeach
                                <td>Male</td>
                                <td>Female</td>
                            </tr>

                            @foreach($data["encounters"] as $key => $encounter)
                            <tr class="report-table-info">
                                <td class="report-table-bold centered" colspan="1">3.2.{{ $key+1 }}</td>
                                <td class="report-table-bold" colspan="3">{{ $encounter["label"] }}</td>
                                @foreach($data["encountersByPop"][$encounter["label"]] as $clientCount)
                                    @foreach($clientCount as $cell)
                                <td class="centered">{{ $cell["count"] }}</td>
                                    @endforeach
                                @endforeach
                            </tr>
                            @endforeach

                        </tbody>
                    </table>

@stop