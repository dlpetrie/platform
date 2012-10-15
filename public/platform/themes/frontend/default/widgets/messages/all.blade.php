<div class="messages">
	<ul class="success">
		@foreach (Session::get('platform.success', array()) as $plat_success)
			<li>{{ $plat_success }}</li>
		@endforeach
	</ul>
	<ul class="errors">
		@foreach (Session::get('platform.errors', array()) as $plat_errors)
			<li>{{ $plat_errors }}</li>
		@endforeach
	</ul>
	<ul class="warning">
		@foreach (Session::get('platform.warnings', array()) as $plat_warning)
			<li>{{ $plat_warning }}</li>
		@endforeach
	</ul>
	<ul class="info">
		@foreach (Session::get('platform.info', array()) as $plat_info)
			<li>{{ $plat_info }}</li>
		@endforeach
	</ul>
</div>