@layout('templates.blank')

<!-- Page Title -->
@section('title')
	Platform Reset Password
@endsection

<!-- Queue Styles -->
{{ Theme::queue_asset('auth', 'users::css/auth.less', 'style') }}

<!-- Styles -->
@section ('styles')
@endsection

<!-- Queue Scripts -->
{{ Theme::queue_asset('validate', 'js/validate.js', 'jquery') }}
{{ Theme::queue_asset('reset_password', 'users::js/reset_password.js', 'jquery') }}

<!-- Scripts -->
@section('scripts')
<script>
	$(document).ready(function() {
		Validate.setup($("#password-reset-form"));
	});
</script>
@endsection

<!-- Page Content -->
@section('content')

<section id="reset">
	@widget('platform.users::form.reset')
</section>

@endsection
