@layout('templates.default')

<!-- Title -->
@section('title')
	@get.settings.site.title - {{ Lang::line('users::form.auth.login.legend') }}
@endsection

<!-- Queue Styles | e.g Theme::queue_asset('name', 'path_to_css', 'dependency') -->

<!-- Styles -->
@section ('styles')
@endsection

<!-- Queue Scripts | e.g. Theme::queue_asset('name', 'path_to_js', 'dependency') -->
{{ Theme::queue_asset('platform-validate', 'js/vendor/platform-validate.js', 'jquery') }}
{{ Theme::queue_asset('login', 'users::js/login.js', 'jquery') }}

<!-- Scripts -->
@section('scripts')
	<script>
		$(document).ready(function() {
			Validate.setup($("#login-form"));
		});
	</script>
@endsection

<!-- Content -->
@section('content')
	<section id="login" class="well">
		@widget('platform.users::form.login')
	</section>
@endsection
