<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<style type="text/css">
			body{
				font-family: 'Noto Sans', sans-serif;
				width: 100%;
				margin: 0;
			}

			.container{
				width: 100%;
			}

			.legend{
				width: 40%;
				position: absolute;
				right: 0;
			}

			.legend > table td{
				padding: 0.76923077em 0.76923077em !important;
			}

			.bold{
				font-weight: bold;
			}

			.spacer{
				border-top: 1px solid white !important;
				border-bottom: 1px solid white !important;
			}

			/*.container > table{
				width: 100%;
				font-size: 8px;
				font-weight: normal;
				table-layout: fixed;
			}*/


			/*******************************
			             Table
			*******************************/


			/* Prototype */
			.ui.table {
			  width: 100%;
			  background: #FFFFFF;
			  margin: 1em 0em;
			  box-shadow: none;
			  text-align: center;
			  color: rgba(0, 0, 0, 0.87);
			  border-collapse: collapse;
			  border-spacing: 0px;
			}
			.ui.table:first-child {
			  margin-top: 0em;
			}
			.ui.table:last-child {
			  margin-bottom: 0em;
			}


			/*******************************
			             Parts
			*******************************/


			/* Table Content */
			.ui.table th,
			.ui.table td {
				-webkit-transition: background 0.1s ease, color 0.1s ease;
				transition: background 0.1s ease, color 0.1s ease;
			}

			/* Headers */
			.ui.table thead {
				box-shadow: none;
			}

			.ui.table thead th {
				cursor: auto;
				background: #F9FAFB;
				text-align: inherit;
				color: rgba(0, 0, 0, 0.87);
				padding: 0.92307692em 0.7em;
				vertical-align: inherit;
				font-style: none;
				font-weight: bold;
				text-transform: none;
			}

			/* Table Cells */
			.ui.table td {
				padding: 0.2em 0.2em;
				text-align: inherit;
			}

			.ui.table td:first-child{
				padding: 0.76923077em 0.76923077em;
			}

			/* Small */
			.ui.small.table {
				font-size: 0.5em;
			}

			/* Standard */
			.ui.table {
				font-size: 1em;
			}

			.ui.fixed.table {
				table-layout: fixed;
			}
			.ui.fixed.table th,
			.ui.fixed.table td {
				overflow: hidden;
				text-overflow: ellipsis;
			}

			.ui.fixed.table > tbody > tr > td:first-child{
				text-align: left;
			}

			.ui.table > thead > tr > th,
			.ui.table > tbody > tr > td{
				border-bottom: 0.5px solid #000000;
				border-left: 0.5px solid #000000;
			}

			.ui.table > thead > tr > th:last-child,
			.ui.table > tbody > tr > td:last-child{
				border-right: 0.5px solid #000000;
			}

			.ui.table > thead > tr:first-child > th {
				border-top: 0.5px solid black;
			}

			.legend > .ui.table > tbody > tr:first-child > td {
				border-top: 0.5px solid black;
			}

			.header{
				margin: 0 auto;
				text-align: center;
				display: block;
				margin-left: -1em;
			}

			.logo{
				height: 70px;
				display: inline-block;
				margin-bottom: -4px;
			}

			.info{
				display: inline-block;
				width: 50%;
				margin-left: -1.5em;
			}

			.info > h1{
				font-size: 1.3em;
				text-transform: uppercase;
				font-weight: bold;
				line-height: .8em;

			}
			.info > h2{
				font-size: 1em;
				text-transform: uppercase;
				font-weight: bold;
				color: #696969;
				line-height: .5em;
			}
			.info > h3{
				font-size: .8em;
				line-height: .5em;
			}

			.timestamp{
				font-size: .5em;
				position: absolute;
				bottom: 0;
				color: #696969;
				font-style: italic;
			}

		</style>
	</head>

	<body>

		<div class="container">
			<div class="header">
				<img src="{{ $dohLogo }}" class="logo">
				<div class="info">
					<h1>Social Hygiene Clinic Report</h1>
					<h2>STI Positivity Rate - {{ $sti }}</h2>
					<h3>{{ $range }}</h3>
				</div>
			</div>
			<div class="svg">
				<div style="width: 100%; text-align: center;">
				<?php
			        echo $svg;
				?>
				</div>
			</div>
			<table class="ui small table fixed" style="width:100%;">
				<thead>
					<tr>
						<th rowspan="3" style="width:7%;"></th>
						@foreach($months as $month)
						<th colspan="{{$count}}">{{ $month }}</th>
						@endforeach
					</tr>
					<tr>
						@foreach($months as $month)
							@if($count > 2)
						<th colspan="1" rowspan="2" style="width: {{100/$count}}%">
							{{count($months) > 6 ? "M" : "Males"}}
						</th>
							@endif
						<th colspan="2" style="width: {{100/$count}}%">
							{{count($months) > 6 ? "F" : "Females"}}
						</th>
						@endforeach
					</tr>
					<tr>
						@foreach($months as $month)
						<th style="width: 50%">{{count($months) > 6 ? "P" : "Pregnant"}}</th>
						<th style="width: 50%">{{count($months) > 6 ? "NP" : "Non-pregnant"}}</th>
						@endforeach
					</tr>
				</thead>
				<tbody>
					<tr class="{{count($months) > 6 ? '' : 'data-cells'}}">
						<td>No. of Positive Results</td>
						@foreach($months as $i=>$month)
							@if($count > 2)
						<td>{{ $data->raw_male[$i] }}</td>
							@endif
						<td>{{ $data->raw_pregnant[$i] }}</td>
						<td>{{ $data->raw_not_pregnant[$i] }}</td>
						@endforeach
					</tr>
					<tr class="{{count($months) > 6 ? '' : 'data-cells'}}">
						<td>No. of Tests Done</td>
						@foreach($months as $i=>$month)
							@if($count > 2)
						<td>{{ $data->tested_male[$i] }}</td>
							@endif
						<td colspan="2">{{ $data->tested_female[$i] }}</td>
						@endforeach
					</tr>
					<tr class="{{count($months) > 6 ? '' : 'data-cells'}}">
						<td>Positivity Rate (%)</td>
						@foreach($months as $i=>$month)
							@if($count > 2)
						<td>{{ $data->rate_male[$i] }}</td>
							@endif
						<td>{{ $data->rate_pregnant[$i] }}</td>
						<td>{{ $data->rate_not_pregnant[$i] }}</td>
						@endforeach
					</tr>
				</tbody>
			</table>
			<div class="legend" style="margin-top: 1em;">
				@if(count($months) > 6)
				<table class="ui small table">
				  <tbody>
				    <tr>
				    	@if($count > 2)
				      <td class="bold">M</td>
				      <td>Males</td>
				      <td class="spacer"></td>
				      	@endif
				      <td class="bold">F</td>
				      <td>Females</td>
				      <td class="spacer"></td>
				      <td class="bold">P</td>
				      <td>Pregnant Females</td>
				      <td class="spacer"></td>
				      <td class="bold">NP</td>
				      <td>Non-Pregnant Females</td>
				    </tr>
				</tbody></table>
				@endif
			</div>
			<div class="timestamp">
				Report Generated at {{ $timestamp }}
			</div>
		</div>
	</body>
	
    
</html>