<div class="messages">
	<ul class="warning">
		@foreach (Session::get('platform.warning', array()) as $plat_warning)
			<li>{{ $plat_warning }}</li>
		@endforeach
	</ul>
</div>