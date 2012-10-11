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
		<p>An application bootstrap for Laravel. The fundamentals + a few essentials included. It's well documented, feature awesome, open source, and always free.</p>
		<p><a href="http://www.getplatform.com" class="btn btn-primary btn-large" target="_blank">Learn more &raquo;</a></p>
	</div>

	<!-- Example row of columns -->
	<div class="features row">
		<div class="span4">
			<h2>Develop</h2>
			<p>Platform is Core light and built upon a strong PHP framework with great documentation and a fantastic community, Laravel.</p>
		</div>
		<div class="span4">
			<h2>Design</h2>
			<p>Powerful theme system that utilizes the blade template engine giving front end developers a solid separation between logic and markup. </p>
		</div>
		<div class="span4">
			<h2>Extend</h2>
			<p>You won’t find complex and tangled control structures; everything in Platform is an extension and completely modular.</p>
		</div>
	</div>

</section>
@endsection
