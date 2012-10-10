@layout('templates.default')

<!-- Title -->
@section('title')
	@get.settings.site.title
@endsection

<!-- Queue Styles | e.g Theme::queue_asset('name', 'path_to_css', 'dependency') -->

<!-- Styles -->
@section ('styles')
@endsection

<!-- Queue Scripts | e.g. Theme::queue_asset('name', 'path_to_js', 'dependency') -->

<!-- Scripts -->
@section('scripts')
@endsection

<!-- Content -->
@section('content')
<section id="home">

	<!-- Main hero unit for a primary marketing message or call to action -->
	<div class="introduction hero-unit">
		<h1>@get.settings.site.title</h1>
		<p>an application bootstrap on Laravel. The fundamentals + a few essentials included. It's well documented, feature awesome, open source, and always free.</p>
		<p><a class="btn btn-primary btn-large">Learn more &raquo;</a></p>
	</div>

	<!-- Example row of columns -->
	<div class="features row">
		<div class="span4">
			<h2>Solid Foundation</h2>
			<p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
		</div>
		<div class="span4">
			<h2>Well Organized</h2>
			<p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
		</div>
		<div class="span4">
			<h2>Pretty Smart</h2>
			<p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
		</div>
	</div>

</section>
@endsection
