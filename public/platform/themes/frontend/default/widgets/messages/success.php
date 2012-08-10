<div class="messages">
	<ul class="success">
		@foreach (Session::get('platform.success', array()) as $plat_success)
			<li>{{ $plat_success }}</li>
		@endforeach
	</ul>
</div>