@layout('templates.default')

<!-- Page Title -->
@section('title')
	@get.settings.site.title
@endsection

<!-- Queue Styles | e.g Theme::queue_asset('name', 'path_to_css', 'dependency')-->

<!-- Styles -->
@section ('styles')
@endsection

<!-- Queue Scripts | e.g. Theme::queue_asset('name', 'path_to_js', 'dependency')-->

<!-- Scripts -->
@section('scripts')
@endsection

<!-- Page Content -->
@section('content')
<section id="home">

	<div class="points row-fluid">
		<div class="span4">
			<h3>Modular Extensions</h3>
			<p>
				Every extension in Platform is completely modular and found in one place.
			</p>
		</div>
		<div class="span4">
			<h3>Awesome API</h3>
			<p>
				Create, read, update and delete from any extension with a powerful internal API.
			</p>
		</div>
		<div class="span4">
			<h3>Widgets &amp; Plugins</h3>
			<p>
				Create simple or complex widgets and call them from anywhere within your views.
			</p>
		</div>
	</div>
	<div class="points row-fluid">
		<div class="span4">
			<h3>Authentication and Authorization</h3>
			<p>
				 A complete, feature rich auth system with a simple yet powerful permission system.
			</p>
		</div>
		<div class="span4">
			<h3>Dashboard Ready</h3>
			<p>
				A powerful and default admin UI with all the bells and whistles using Twitter bootstrap.
			</p>
		</div>
		<div class="span4">
			<h3>Bootstrap Ready</h3>
			<p>
				We chose bootstrap for both our front and backend themes. But use whatever you want.
			</p>
		</div>
	</div>
</section>
@endsection
