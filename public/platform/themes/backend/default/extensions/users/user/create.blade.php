@layout('templates.default')

<!-- Page Title -->
@section('title')
	{{ Lang::line('users::general.users.create.title') }}
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

<section id="users">

	<header class="head row">
		<div class="span4">
			<h1>{{ Lang::line('users::general.users.create.title') }}</h1>
			<p>{{ Lang::line('users::general.users.create.description') }}</p>
		</div>
	</header>

	<hr>

	<div class="row">
		<div class="span12">
			@widget('platform.users::admin.user.form.create')
		</div>
	</div>

</section>

@endsection
