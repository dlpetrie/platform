@layout('templates.default')

<!-- Title -->
@section('title')
	@get.settings.site.title - {{ Lang::line('users::form.auth.register.legend') }}
@endsection

<!-- Queue Styles | e.g Theme::queue_asset('name', 'path_to_css', 'dependency') -->

<!-- Styles -->
@section ('styles')
@endsection

<!-- Queue Scripts | e.g. Theme::queue_asset('name', 'path_to_js', 'dependency') -->
{{ Theme::queue_asset('platform-validate', 'js/vendor/platform-validate.js', 'jquery') }}

<!-- Scripts -->
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

			//Match Password
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

<!-- Content -->
@section('content')
	<section id="register" class="well">
		@widget('platform.users::form.register')
	</section>
@endsection
