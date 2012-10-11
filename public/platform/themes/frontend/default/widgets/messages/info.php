<div class="messages">
	<ul class="info">
		@foreach (Session::get('platform.info', array()) as $plat_info)
			<li>{{ $plat_info }}</li>
		@endforeach
	</ul>
</div>