@layout('installer::template')

@section('scripts')
<script>
	$(document).ready(function() {

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

		Validate.setup($("#user-form"));

	});
</script>
@endsection

@section('navigation')
	<h1>{{ Lang::line('installer::general.step_3.title') }}</h1>
	<p class="step">{{ Lang::line('installer::general.step_3.tagline') }}</p>
	<div class="breadcrumbs">
		<ul class="nav">
			<ul class="nav">
			<li><span>{{ Lang::line('installer::general.step_1.step') }}</span> {{ Lang::line('installer::general.step_1.step_description') }}</li>
			<li><span>{{ Lang::line('installer::general.step_2.step') }}</span> {{ Lang::line('installer::general.step_2.step_description') }}</li>
			<li class="active"><span>{{ Lang::line('installer::general.step_3.step') }}</span> {{ Lang::line('installer::general.step_3.step_description') }}</li>
			<li><span>{{ Lang::line('installer::general.step_4.step') }}</span> {{ Lang::line('installer::general.step_4.step_description') }}</li>
		</ul>
	</div>
@endsection


@section('content')
<div class="grid contain">
	<h2>{{ Lang::line('installer::general.step_3.description') }}</h2>
	<hr>
	<form id="user-form" class="form-horizontal" method="POST" accept-char="UTF-8">
	<input type="hidden" name="{{ Session::csrf_token }}" value="{{ Session::token() }}">
		<fieldset>
			<legend>{{ Lang::line('installer::form.user.legend') }}</legend>
			<!-- User First Name -->
			<div>
				<label for="first_name">{{ Lang::line('installer::form.user.first_name') }}</label>
				<input type="text" name="first_name" id="first_name" value="" placeholder="{{ Lang::line('installer::form.user.first_name') }}" required>
				<span class="help">{{ Lang::line('installer::form.user.first_name_help') }}</span>
			</div>


			<!-- User Last Name -->
			<div>
				<label for="last_name">{{ Lang::line('installer::form.user.last_name') }}</label>
				<input type="text" name="last_name" id="last_name" value="" placeholder="{{ Lang::line('installer::form.user.last_name') }}" required>
				<span class="help">{{ Lang::line('installer::form.user.last_name_help') }}</span>
			</div>

			<!-- User Email Addres -->
			<div>
				<label for="email">{{ Lang::line('installer::form.user.email') }}</label>
				<input type="email" name="email" id="email" value="" placeholder="{{ Lang::line('installer::form.user.email') }}" required>
				<span class="help">{{ Lang::line('installer::form.user.email_help') }}</span>
			</div>

			<!-- User Password -->
			<div>
				<label for="password">{{ Lang::line('installer::form.user.password') }}</label>
				<input type="password" name="password" id="password" placeholder="{{ Lang::line('installer::form.user.password') }}" required>
				<span class="help">{{ Lang::line('installer::form.user.password_help') }}</span>
			</div>

			<!-- User Password Confirm -->
			<div>
				<label for="password_confirmation">{{ Lang::line('installer::form.user.password_confirm') }}</label>
				<input type="password" name="password_confirmation" id="password_confirmation" placeholder="{{ Lang::line('installer::form.user.password_confirm') }}" required>
				<span class="help">{{ Lang::line('installer::form.user.password_confirm_help') }}</span>
			</div>

		</fieldset>

		<div class="actions">
			<button type="submit" class="btn btn-large">{{ Lang::line('installer::button.next') }}</button>
		</div>
	{{ Form::close() }}
</div>
@endsection
