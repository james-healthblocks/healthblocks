@section('reached-report-table')
	
                <table class="ui celled table report-table">
                    <thead>
                        <tr>
                            <td colspan="2"><h1>Social Hygiene Clinic Report</h1></td>
                        </tr>
                        <tr>
                            <td colspan="2"><span class="secondary">OUTREACH</span></td>
                        </tr>
                        <tr>
                            <td colspan="2">{{ $info["startDate"] }} — {{ $info["endDate"] }}</td>
                        </tr>
                        <tr>
                            <td colspan="2" class="">{{ $info["municipality"] }}, {{ $info["province"] }}, {{ $info["region"] }}</td>
                        </tr>
                        <tr>
                            <td colspan="2" class="bottom-border">Prepared By: {{ $info["physician"] }} </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="report-table-hidden-row-html"></td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $sectionLabel => $section)
                            
                            @if(isset($section["count"]))
                                <tr class="report-table-h1">
                                    <td class="hasBorders">{{ $info['sectionCounter'] = $info['sectionCounter'] + 1 }}. {{ $sectionLabel }} </td>
                                    <td class="hasBorders"> {{ $section["count"] }} </td>
                                </tr>
                            @else
                                <tr class="report-table-h1">
                                    <td class="hasBorders">{{ $info['sectionCounter'] = $info['sectionCounter'] + 1 }}. {{ $sectionLabel }} </td>
                                    <td class="report-table-spacer hasBorders"></td>
                                </tr>

                                @foreach($section as $ageGroupLabel => $ageGroup)

                                    <tr class="report-table-info">
                                        <td class="centered"> {{ $ageGroupLabel }} </td>
                                        <td class="centered"> {{ $ageGroup["count"] }} </td>
                                    </tr>

                                @endforeach
                            @endif
                        <tr>
                            <td colspan="2" class="report-table-divider"></td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>

@stop