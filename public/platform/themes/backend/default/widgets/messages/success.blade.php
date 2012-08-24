<div class="messages">
	@foreach (Session::get('platform.success', array()) as $plat_success)
		<div class="alert alert-success">{{ $plat_success }}</div>
	@endforeach
</div>