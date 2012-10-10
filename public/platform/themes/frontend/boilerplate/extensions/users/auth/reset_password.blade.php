@layout('templates.default')

@section('title')
	Platform Reset Password
@endsection

{{ Theme::queue_asset('validate', 'js/validate.js', 'jquery') }}
{{ Theme::queue_asset('reset_password', 'users::js/reset_password.js', 'jquery') }}

@section('scripts')
<script>
	$(document).ready(function() {
		Validate.setup($("#password-reset-form"));
	});
</script>
@endsection

@section('content')
<section id="reset">
	@widget('platform.users::form.reset')
</section>
@endsection
