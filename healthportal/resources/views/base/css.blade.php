		<!-- Styles -->

		<!-- Semantic UI 2.2 -->
		{{ Html::style("/lib/semantic/dist/semantic.min.css") }}
		<!-- Font Awesome -->
		{{ Html::style("/css/font-awesome.min.css") }}
		<!-- Ionicons -->
		<!-- AdminLTE Theme style -->
		{{ Html::style("/lib/datatables.net-dt/css/jquery.dataTables.css") }}
		{{ Html::style("/lib/datepicker/datepicker3.css") }}
		{{ Html::style("/lib/perfect-scrollbar/css/perfect-scrollbar.min.css") }}

		{{ Html::style("/lib/AdminLTE/AdminLTE.css") }}

		@if(Request::is('login') || Request::is('password/email'))
		{{ Html::style("/css/splash-healthportal.css") }}
		@else
		{{ Html::style("/css/skin-healthportal.css") }}
		@endif
