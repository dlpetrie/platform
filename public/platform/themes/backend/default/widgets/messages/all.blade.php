<div class="messages">
	@foreach (Session::get('platform.success', array()) as $plat_success)
		<div class="alert alert-success">{{ $plat_success }}</div>
	@endforeach
	@foreach (Session::get('platform.errors', array()) as $plat_errors)
		<div class="alert alert-error">{{ $plat_errors }}</div>
	@endforeach
	@foreach (Session::get('platform.warnings', array()) as $plat_warning)
		<div class="alert alert-warning">{{ $plat_warning }}</div>
	@endforeach
	@foreach (Session::get('platform.info', array()) as $plat_info)
		<div class="alert alert-info">{{ $plat_info }}</div>
	@endforeach
</div>