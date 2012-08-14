@layout('templates.blank')

<!-- Page Title -->
@section('title')
	Platform Login
@endsection

<!-- Queue Styles -->
{{ Theme::queue_asset('auth', 'users::css/auth.less', 'style') }}

<!-- Styles -->
@section ('styles')
@endsection

<!-- Queue Scripts -->
{{ Theme::queue_asset('validate', 'js/validate.js', 'jquery') }}
{{ Theme::queue_asset('login', 'users::js/login.js', 'jquery') }}

<!-- Scripts -->
@section('scripts')
<script>
	$(document).ready(function() {
		Validate.setup($("#login-form"));
	});
</script>
@endsection

<!-- Page Content -->
@section('content')

<section id="login">
	@widget('platform.users::form.login')
</section>

@endsection
