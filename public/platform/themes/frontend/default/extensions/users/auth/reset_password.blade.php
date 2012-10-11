@layout('templates.default')

<!-- Title -->
@section('title')
	@get.settings.site.title - {{ Lang::line('users::form.auth.reset.legend') }}
@endsection

<!-- Queue Styles | e.g Theme::queue_asset('name', 'path_to_css', 'dependency') -->

<!-- Styles -->
@section ('styles')
@endsection

<!-- Queue Scripts | e.g. Theme::queue_asset('name', 'path_to_js', 'dependency') -->
{{ Theme::queue_asset('platform-validate', 'js/vendor/platform-validate.js', 'jquery') }}
{{ Theme::queue_asset('reset_password', 'users::js/reset_password.js', 'jquery') }}

<!-- Scripts -->
@section('scripts')
	<script>
		$(document).ready(function() {
			Validate.setup($("#password-reset-form"));
		});
	</script>
@endsection

<!-- Content -->
@section('content')
	<section id="reset" class="well">
		@widget('platform.users::form.reset')
	</section>
@endsection
