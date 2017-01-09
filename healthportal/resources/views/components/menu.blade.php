@section('shcreps')
	<?php
		//$referrer = parse_url(Request::server('HTTP_REFERER'));
		//preg_match("/^\/case-management\/sti/", $referrer['path']) || 
		if(Request::is("case-management/sti*")){
			$inCaseManagement = true;
			$menu = Sidebar::shcreps_menu();
			$url = "/case-management/sti/";
		}else{
			$inCaseManagement = false;
		}
	?>

	@if($inCaseManagement)
		<section class="content-header">
	        <h1>
	            Sexually Transmitted Infections
	            <small>Case Management</small>
	        </h1>

		<div class="ui menu">
		@foreach($menu as $item)
			@if(isset($item["submenu"]))
				@if(count($item["submenu"] > 0))
					<div class="ui dropdown link item no-reset">
						<span class="text">{{ $item["label"] }}</span>
						<i class="dropdown icon"></i>
						<div class="menu">
					@foreach($item["submenu"] as $page)
							@if(isset($page["pages"]))
								@if(count($page["pages"] > 0))
									<div class="item">
										<i class="dropdown icon"></i>
										<span class="text">{{ $page["label"] }}</span>
										<div class="menu">
										@foreach($page["pages"] as $morepage)
											<a href="{{ $url.$page['prefix'].'/'.$morepage['url'] }}"><div class="item">{{ $morepage["label"] }}</div></a>
										@endforeach
										</div>
									</div>
								@endif
							@else
								<a href="{{ $item['prefix'] == '' ? $url.$page['url'] : $url.$item['prefix'].'/'.$page['url'] }}" class="item">{{ $page["label"] }}</a>
							@endif
					@endforeach
						</div>
					</div>
				@endif
			@else
				<a class="item">
					{{ $item["label"] }}
				</a>
			@endif
		@endforeach
		</div>
		<script type="text/javascript">$(".ui.dropdown").dropdown();</script>
	</section>
	@endif
@stop