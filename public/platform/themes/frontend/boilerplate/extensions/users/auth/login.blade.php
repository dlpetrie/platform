@layout('templates.default')

@section('title')
	Platform Login
@endsection

{{ Theme::queue_asset('validate', 'js/validate.js', 'jquery') }}
{{ Theme::queue_asset('platform_url', 'js/url.js', 'jquery') }}
{{ Theme::queue_asset('login', 'users::js/login.js', 'jquery') }}

@section('scripts')
	<script>
		$(document).ready(function() {
			Validate.setup($("#login-form"));
		});
	</script>
@endsection

@section('content')
	<section id="login">
		@widget('platform.users::form.login')
	</section>
@endsection
