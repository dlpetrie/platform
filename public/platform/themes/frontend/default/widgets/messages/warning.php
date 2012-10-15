<div class="messages">
	<ul class="warning">
		@foreach (Session::get('platform.warnings', array()) as $plat_warning)
			<li>{{ $plat_warning }}</li>
		@endforeach
	</ul>
</div>