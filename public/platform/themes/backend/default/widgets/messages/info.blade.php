<div class="messages">
	@foreach (Session::get('platform.info', array()) as $plat_info)
		<div class="alert alert-info">{{ $plat_info }}</div>
	@endforeach
</div>