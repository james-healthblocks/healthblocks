@section('inventory-report-table')

                <table class="ui celled table report-table">
                    <thead>
                        <tr>
                            <td colspan="{{ $info['monthCount']+4 }}"><h1>SOCIAL HYGIENE CLINIC REPORT</h1></td>
                        </tr>
                        <tr>
                            <td colspan="{{ $info['monthCount']+4 }}"><span class="secondary">INVENTORY </span></td>
                        </tr>
                        <tr>
                            <td colspan="{{ $info['monthCount']+4 }}">{{ $info["startMonth"] }} {{ $info["startYear"] }} — {{ $info["endMonth"] }} {{ $info["endYear"] }}</td>
                        </tr>
                        <tr>
                            <td colspan="{{ $info['monthCount']+4 }}" class="bottom-border">{{ $info["municipality"] }}, {{ $info["province"] }}, {{ $info["region"] }}</td>
                        </tr>
                        <tr>
                            <td colspan="{{ $info['monthCount']+4 }}" class="report-table-hidden-row-html"></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="2"></td>
                            @foreach($info["months"] as $month)
                            <td class="report-table-label">{{ strtoupper($month) }}</td>
                            @endforeach
                            <td class="report-table-label">TOTAL</td>
                            <td class="report-table-label">REMARKS</td>
                        </tr>
                        @foreach($data as $categoryLabel => $category)
                            @if(count($category) > 0)
                        <tr class="report-table-h1">
                            <td colspan="{{ $info['monthCount']+4 }}">{{ $info['categoryCounter'] = $info['categoryCounter']+1 }}. {{ strtoupper($categoryLabel) }}</td>
                        </tr>
                            <?php $itemCounter = 1; ?>
                            @foreach($category as $itemName => $item)
                        <tr class="report-table-h2">
                            <td colspan="{{ $info['monthCount']+4 }}">{{ $info['categoryCounter'] }}.{{ $itemCounter++ }} {{ $itemName }}</td>
                        </tr>
                            @if($html == true)
                        <tr class="report-table-info">
                            <td colspan="{{ $info['monthCount']+3 }}" class="report-table-hidden-row"></td>
                            <td rowspan="9">{{ $item["remarks"] }}</td>
                        </tr>
                            @endif
                        <tr class="report-table-info">
                            <td class="report-table-blank"></td>
                            <td class="report-table-h3">Beginning Balance</td>
                            @foreach($item["info"] as $itemInfo)
                            <td class="centered">{{ $itemInfo["startAmt"] }}</td>
                            @endforeach

                            @if($html == false)
                            <td rowspan="8" class="hasBorders">{{ $item["remarks"] }}</td>
                            @endif
                        </tr>
                        <tr class="report-table-info">
                            <td class="report-table-blank"></td>
                            <td class="report-table-h3">Procured/Received</td>
                            @foreach($item["info"] as $itemInfo)
                            <td class="centered">{{ $itemInfo["procured"] }}</td>
                            @endforeach
                        </tr>
                        <tr class="report-table-info">
                            <td class="report-table-blank"></td>
                            <td class="report-table-h3">Distributed/Dispensed/Losses</td>
                            @foreach($item["info"] as $itemInfo)
                            <td class="centered">{{ $itemInfo["distributed"] }}</td>
                            @endforeach
                        </tr>
                        <tr class="report-table-info">
                            <td class="report-table-blank"></td>
                            <td class="report-table-h3">Stock expiring in 3 months</td>
                            @foreach($item["info"] as $itemInfo)
                            <td class="centered">{{ $itemInfo["expiring"] }}</td>
                            @endforeach
                        </tr>
                        <tr class="report-table-info">
                            <td class="report-table-blank"></td>
                            <td class="report-table-h3">Physical Count/Current Stock on Hand</td>
                            @foreach($item["info"] as $itemInfo)
                            <td class="centered">{{ $itemInfo["onHand"] }}</td>
                            @endforeach
                        </tr>
                        <tr class="report-table-info">
                            <td class="report-table-blank"></td>
                            <td class="report-table-h3">Average Monthly Consumption</td>
                            @foreach($item["info"] as $itemInfo)
                            <td class="centered">{{ $itemInfo["avgMonthlyCons"] }}</td>
                            @endforeach
                        </tr>
                        <tr class="report-table-info">
                            <td class="report-table-blank"></td>
                            <td class="report-table-h3">Maximum Stock Requirement</td>
                            @foreach($item["info"] as $itemInfo)
                            <td class="centered">{{ $itemInfo["maxStockReq"] }}</td>
                            @endforeach
                        </tr>
                        <tr class="report-table-info">
                            <td class="report-table-blank"></td>
                            <td class="report-table-h3">Quantity Required for Replenishment</td>
                            @foreach($item["info"] as $itemInfo)
                            <td class="centered">{{ $itemInfo["replishmentReqQuant"] }}</td>
                            @endforeach
                        </tr>
                            @endforeach
                        <tr>
                            <td colspan="{{ $info['monthCount']+4 }}" class="report-table-divider"></td>
                        </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>

@stop