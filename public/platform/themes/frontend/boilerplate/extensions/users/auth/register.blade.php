@layout('templates.default')

@section('title')
	Platform Register
@endsection

{{ Theme::queue_asset('validate', 'js/validate.js', 'jquery') }}

@section('scripts')
<script>
	$(document).ready(function() {


		//Match Email
		var email = document.getElementById("email"),
			emailConfirm = document.getElementById("email_confirmation");

		$('#email, #email_confirmation').keyup(function() {
			if(emailConfirm.value !== email.value) {
				emailConfirm.setCustomValidity("Your email doesn't match");
			} else {
				emailConfirm.setCustomValidity("");
			}
		});

		//Match Email
		var password = document.getElementById("password"),
			passwordConfirm = document.getElementById("password_confirmation");

		$('#password, #password_confirmation').keyup(function() {
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

@section('content')
<section id="register">
	@widget('platform.users::form.register')
</section>
@endsection
