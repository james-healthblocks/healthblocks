@section('client-report-table')
	
                <table class="ui celled table report-table">
                    <thead>
                        <tr>
                            <td colspan="26"><h1>Social Hygiene Clinic Report</h1></td>
                        </tr>
                        <tr>
                            <td colspan="26"><span class="secondary">Sexually Transmitted Infections</span></td>
                        </tr>
                        <tr>
                            <td colspan="26">{{ $info["startDate"] }} — {{ $info["endDate"] }}</td>
                        </tr>
                        <tr>
                            <td colspan="26" class="">{{ $info["municipality"] }}, {{ $info["province"] }}, {{ $info["region"] }}</td>
                        </tr>
                        <tr>
                            <td colspan="26" class="bottom-border">Prepared By: {{ $info["physician"] }} </td>
                        </tr>
                        <tr>
                            <td colspan="26" class="report-table-hidden-row-html"></td>
                        </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <td colspan="26" class="report-table-divider"></td>
                        </tr>

                        <!-- SECTION 1 -->
                        <tr class="report-table-h1">
                            <td colspan="26">1. {{ $info["sections"][0] }} </td>
                        </tr>

                        <tr>
                            <td rowspan="2" colspan="7" class="report-table-label hasBorders">Client Description</td>
                            @foreach($info["client_groups"] as $index => $cg)
                                @if(isset($cg["cols"]))

                                    <td colspan="2" class="report-table-label hasBorders @if($index == 0) totalcol @endif">{{$cg["text"]}}</td>

                                @else
                                    <td rowspan="2" class="report-table-label hasBorders">{{$cg["text"]}}</td>

                                @endif
                            @endforeach
                        </tr>
                        <tr> 
                            @if($html == false)
                                    <td colspan="7" class="hasBorders"> </td>
                            @endif
                            @for($i = 0; $i < 3; $i++)
                                <td class="report-table-label hasBorders @if($i == 0) totalcol @endif">Female</td>
                                <td class="report-table-label hasBorders @if($i == 0) totalcol @endif">Male</td>
                            @endfor
                            @if($html == false)
                                    <td colspan="4" class="hasBorders"> </td>
                            @endif
                            @for($i = 0; $i < 4; $i++)
                                <td class="report-table-label hasBorders">Female</td>
                                <td class="report-table-label hasBorders">Male</td>
                            @endfor
                        </tr>

                        @foreach($data["s1"] as $s1)
                        <tr class="report-table-info"> 
                            
                            @if (isset($s1["data"]))
                                <td colspan="1">1. {{ $info['sectionCounter'] = $info['sectionCounter'] + 1 }}</td>
                                <td colspan="6"> {{ $s1["text"] }} </td>
                                @foreach($s1["data"] as $idx => $s1d)
                                    <td class="centered @if($idx == 0 || $idx == 1) totalcol @endif"> {{ $s1d }} </td>
                                @endforeach
                            @else
                                <td rowspan="11">1. {{ $info['sectionCounter'] = $info['sectionCounter'] + 1 }}</td>
                                <td rowspan="11"> {{ $s1["text"] }} </td>
                                <td rowspan="6" colspan="3">AGE GROUP</td>

                                @foreach($s1["age_groups"] as $key => $age_group)
                                    @if ($key > 0)
                                        <tr class="report-table-info">
                                        @if($html == false)
                                            <td colspan="2" class="report-table-label"> </td>
                                            <td colspan="3" class="report-table-label"> </td>
                                        @endif
                                    @endif

                                    <td colspan="2"> {{ $age_group["text"] }} </td>
                                    @foreach($age_group["data"] as $idx => $agd)
                                        <td class="centered @if($idx == 0 || $idx == 1) totalcol @endif"> {{ $agd }} </td>
                                    @endforeach

                                    @if ($key > 0)
                                        </tr>
                                    @endif
                                @endforeach

                                <tr class="report-table-info">
                                @if($html == false)
                                    <td colspan="2" class="report-table-label"> </td>
                                @endif
                                <td rowspan="5" colspan="3">CLIENT TYPE</td>
                                
                                @foreach($s1["client_types"] as $key => $client_type)
                                    @if ($key > 0)
                                        <tr class="report-table-info">
                                        @if($html == false)
                                            <td colspan="2" class="report-table-label"> </td>
                                            <td colspan="3" class="report-table-label"> </td>
                                        @endif
                                    @endif

                                    <td colspan="2"> {{ $client_type["text"] }} </td>
                                    @foreach($client_type["data"] as $idx => $ctd)
                                        <td class="centered @if($idx == 0 || $idx == 1) totalcol @endif"> {{ $ctd }} </td>
                                    @endforeach
                                    
                                    @if ($key > 0)
                                        </tr>
                                    @endif
                                @endforeach

                                </tr>
                            @endif
                        </tr>
                        @endforeach

                        <!-- SECTION 2 -->
                        <tr>
                            <td colspan="26" class="report-table-divider"></td>
                        </tr>

                        <tr class="report-table-h1">
                            <td colspan="26">2. {{ $info["sections"][1] }} </td>
                        </tr>

                        <tr>
                            <td rowspan="2" colspan="6" class="report-table-label">Activities</td>
                            @foreach($info["client_groups"] as $index => $cg)
                                @if(isset($cg["cols"]))

                                    <td colspan="2" class="report-table-label @if($index == 0) totalcol @endif">{{$cg["text"]}}</td>

                                @else
                                    <td rowspan="2" class="report-table-label">{{$cg["text"]}}</td>

                                @endif
                            @endforeach
                            <td colspan="1" rowspan=2 class=""></td>
                        </tr>

                        <tr> 
                            @if($html == false)
                                    <td colspan="6" class="report-table-label"> </td>
                            @endif
                            @for($i = 0; $i < 3; $i++)
                                <td class="report-table-label @if($i == 0) totalcol @endif">Female</td>
                                <td class="report-table-label @if($i == 0) totalcol @endif">Male</td>
                            @endfor
                            @if($html == false)
                                    <td colspan="4" class="report-table-label"> </td>
                            @endif
                            @for($i = 0; $i < 4; $i++)
                                <td class="report-table-label">Female</td>
                                <td class="report-table-label">Male</td>
                            @endfor
                        </tr>

                        <?php $info['sectionCounter'] = 0; ?>
                        @foreach($data["s2"] as $s2)
                        <tr class="report-table-info"> 
                                <td colspan="1">2. {{ $info['sectionCounter'] = $info['sectionCounter'] + 1 }}</td>
                                <td colspan="5"> {{ $s2["text"] }} </td>
                                @foreach($s2["data"] as $idx => $s2d)
                                    <td class="centered @if($idx == 0 || $idx == 1) totalcol @endif"> {{ $s2d }} </td>
                                @endforeach
                        </tr>
                        @endforeach

                        <!-- SECTION 3_1 -->
                        <?php $info['sectionCounter'] = 0; ?>
                        <tr>
                            <td colspan="26" class="report-table-divider"></td>
                        </tr>

                        <tr class="report-table-h1">
                            <td colspan="26">3. {{ $info["sections"][2] }} </td>
                        </tr>

                        <tr>
                            <td colspan="2" rowspan="2" class="report-table-label">Clients</td>
                            @foreach($info["sti"]["types_1"] as $type)

                                    <td colspan="{{ $type['cols'] }}" class="report-table-label">3. {{ $info['sectionCounter'] = $info['sectionCounter'] + 1 }} {{ $type['text'] }}</td>

                            @endforeach
                            <td colspan="2" rowspan=2 class=""></td>
                        </tr>
                        <tr> 
                            @if($html == false)
                                    <td colspan="2" class="report-table-label"> </td>
                            @endif
                            @foreach($info["sti"]["columns_1"] as $column)
                                    <td class="report-table-label">{{ $column['text'] }}</td>
                            @endforeach

                        </tr>

                        @foreach($data["s3"] as $s3)
                        <tr class="report-table-info"> 
                            @if (isset($s3["data"]))
                                <td colspan="2" class="totalrow">{{ $s3["text"] }}</td>

                                @foreach($s3["data"] as $s3d)
                                    <td class="centered totalrow"> {{ $s3d }} </td>
                                @endforeach
                            @else
                                <td rowspan="{{ $s3['rows'] }}"> {{ $s3["text"] }} </td>
                                @foreach($s3["groups"] as $key => $group)
                                    @if ($key > 0)
                                        <tr class="report-table-info"> 
                                        @if($html == false)
                                            <td colspan="1" class="report-table-label"> </td>
                                        @endif
                                    @endif

                                    <td class="@if(isset($group['class'])) {{ $group['class'] }} @endif"> {{ $group["text"] }} </td>

                                    @foreach($group["data"] as $s3d)
                                        <td class="centered @if(isset($group['class'])) {{ $group['class'] }} @endif"> {{ $s3d }} </td>
                                    @endforeach

                                    @if ($key > 0)
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                        </tr>
                        @endforeach

                        <!-- SECTION 3_2 -->
                        <tr>
                            <td colspan="26" class="report-table-divider"></td>
                        </tr>
                        
                        <tr>
                            <td colspan="2" rowspan="2" class="report-table-label">Clients</td>
                            @foreach($info["sti"]["types_2"] as $type)

                                <td colspan="{{ $type['cols'] }}" class="report-table-label">3. {{ $info['sectionCounter'] = $info['sectionCounter'] + 1 }} {{ $type['text'] }}</td>

                            @endforeach
                            <td colspan="16" rowspan=2 class=""></td>
                        </tr>
                        <tr> 
                            @if($html == false)
                                <td colspan="2" class="report-table-label"> </td>
                            @endif
                            @foreach($info["sti"]["columns_2"] as $column)
                                <td class="report-table-label">{{ $column['text'] }}</td>
                            @endforeach

                        </tr>

                        <tr class="report-table-info">
                            <td rowspan="{{ $data['s4']['rows'] }}"> {{ $data["s4"]["text"] }} </td>
                            @foreach($data["s4"]["groups"] as $key => $group)
                                @if ($key > 0)
                                    <tr class="report-table-info">
                                    @if($html == false)
                                        <td colspan="1" class="report-table-label"> </td>
                                    @endif
                                @endif

                                <td class="@if(isset($group['class'])) {{ $group['class'] }} @endif"> {{ $group["text"] }} </td>

                                @foreach($group["data"] as $s4d)
                                    <td class="centered @if(isset($group['class'])) {{ $group['class'] }} @endif"> {{ $s4d }} </td>
                                @endforeach
                                
                                @if ($key > 0)
                                    </tr>
                                @endif
                            @endforeach
                        </tr>
                    </tbody>
                </table>

@stop