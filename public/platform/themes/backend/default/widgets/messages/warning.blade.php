<div class="messages">
	@foreach (Session::get('platform.warning', array()) as $plat_warning)
		<div class="alert alert-warning">{{ $plat_warning }}</div>
	@endforeach
</div>