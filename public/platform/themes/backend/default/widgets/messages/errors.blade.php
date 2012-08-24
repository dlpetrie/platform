<div class="messages">
	@foreach (Session::get('platform.errors', array()) as $plat_errors)
		<div class="alert alert-error">{{ $plat_errors }}</div>
	@endforeach
</div>