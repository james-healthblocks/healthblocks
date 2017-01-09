			<!-- SCRIPTS -->

		{{ Html::script('/lib/jquery/jquery.min.js') }}
		{{ Html::script('/lib/semantic/dist/semantic.min.js') }}
		{{ Html::script('/lib/datepicker/bootstrap-datepicker.js') }}
		{{ Html::script('/lib/AdminLTE/app.js') }}
		{{ Html::script('/lib/moment/moment.js') }}
		{{ Html::script('/lib/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js') }}

		{{ Html::script('/js/clearInputs.js') }}
		{{ Html::script('/js/sidebarScroll.js') }}

		@if(isset($portal))
		{{ Html::script('/js/disableInputs.js') }}
		@endif

		@if(Request::is('sti_graphs/*'))
{{ Html::script('/lib/highcharts/code/highcharts.js') }}
{{ Html::script('/lib/highcharts/code/modules/exporting.js') }}
{{ Html::script('/lib/highcharts/code/modules/offline-exporting.js') }}
{{ Html::script('/js/shcreps-chart-theme.js') }}
		@endif

