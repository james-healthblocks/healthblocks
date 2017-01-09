<html>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<style type="text/css">
		.report-table > thead > tr > td > h1{
		  text-align: center;
		  text-transform: uppercase; 
		}

		.report-table > thead > tr > td{
		  text-align: center;
		}

		.report-table > thead > tr > td > .secondary{
		  color: #696969;
		  text-transform: uppercase;
		  font-weight: bold; 
		  font-size: 14pt;
		}

		.report-table > thead > tr > td.bottom-border{
		}

		.report-table-h1{
		  text-transform: uppercase;
		  font-weight: bold;
		  font-size: 12pt;
		}

		.report-table-h2{
		  font-weight: bold;
		  font-size: 11pt;
		}

		.report-table-bold{
		  font-weight: bold;
		}

		.ui.celled.table tr td.report-table-h3{
		  font-weight: bold;
		  font-size: 10pt;
		}

		tr > td.report-table-label,
		tr.report-table-label{
		  text-transform: uppercase;
		  text-align: center;
		  font-weight: bold;
		}

		tr.report-table-info:hover{
		  background-color: #EDEDED !important;
		}

		.ui.celled.table tr td.report-table-spacer{
		}

		.ui.celled.table tr td.report-table-hidden-row{
		  height: 0px !important;
		  padding: 0px;
		  border: 0 !important;
		}

		.report-table > tbody > tr.table-report-divider > td{
		  background: #faffea;
		}

		td.centered{
		  text-align: center;
		}

		tr.report-table-info > td.centered,
		tr.report-table-info > td.hasBorders{
		  border: 1px solid #000000;
		  wrap-text: true;
		}

	</style>
	<?php $html = false; ?>
	@include('inventory.reportsTable')
       @yield('inventory-report-table')

    
</html>