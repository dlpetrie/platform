<div class="messages">
	<ul class="errors">
		@foreach (Session::get('platform.errors', array()) as $plat_errors)
			<li>{{ $plat_errors }}</li>
		@endforeach
	</ul>
</div>