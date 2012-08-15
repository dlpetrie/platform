@layout('templates.blank')

<!-- Page Title -->
@section('title')
	Platform Register
@endsection

<!-- Queue Styles -->
{{ Theme::queue_asset('auth', 'users::css/auth.less', 'style') }}

<!-- Styles -->
@section ('styles')
@endsection

<!-- Queue Scripts -->
{{ Theme::queue_asset('validate', 'js/validate.js', 'jquery') }}

<!-- Scripts -->
@section('scripts')
<script>
	$(document).ready(function() {


		//Match Email
		var email = document.getElementById("email"),
			emailConfirm = document.getElementById("email-confirm");

		$('#email, #email-confirm').keyup(function() {
			if(emailConfirm.value !== email.value) {
				emailConfirm.setCustomValidity("Your email doesn't match");
			} else {
				emailConfirm.setCustomValidity("");
			}
		});

		//Match Email
		var password = document.getElementById("password"),
			passwordConfirm = document.getElementById("password-confirm");

		$('#password, #password-confirm').keyup(function() {
			if(passwordConfirm.value !== password.value) {
				passwordConfirm.setCustomValidity("Your password doesn't match");
			} else {
				passwordConfirm.setCustomValidity("");
			}
		});

		Validate.setup($("#register-form"));
	});
</script>
@endsection

<!-- Page Content -->
@section('content')

<section id="register">
	@widget('platform.users::form.register')
</section>

@endsection
