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
	<h1>Administration</h1>
	<p class="step">An account to rule them all</p>
	<div class="breadcrumbs">
		<ul class="nav">
			<li><span>Step 1:</span> Prepare installation</li>
			<li><span>Step 2:</span> Database Credentials</li>
			<li class="active"><span>Step 3:</span> Administration</li>
			<li><span>Step 4:</span> Complete</li>
		</ul>
	</div>
@endsection


@section('content')
<div class="grid contain">
	<h2>Now you need an administrator, create your initial user and you're almost ready to rock.</h2>

	<form id="user-form" class="form-horizontal" method="POST" accept-char="UTF-8">
	<input type="hidden" name="{{ Session::csrf_token }}" value="{{ Session::token() }}">
		<fieldset>
			<legend>{{ Lang::line('installer::installer.user.legend') }}</legend>

			<!-- User First Name -->
			<div>
				<label for="first_name">First Name:</label>
				<input type="text" name="first_name" id="first_name" value="" placeholder="First Name" required>
				<span class="help">First name of admin.</span>
			</div>


			<!-- User Last Name -->
			<div>
				<label for="last_name">Last Name:</label>
				<input type="text" name="last_name" id="last_name" value="" placeholder="Last Name" required>
				<span class="help">Last name of admin.</span>
			</div>

			<!-- User Email Addres -->
			<div>
				<label for="email">Email Address:</label>
				<input type="email" name="email" id="email" value="" placeholder="Email Address" required>
				<span class="help">Email address of admin.</span>
			</div>

			<!-- User Password -->
			<div>
				<label for="password">Password:</label>
				<input type="password" name="password" id="password" placeholder="Password" required>
				<span class="help">Password for admin.</span>
			</div>

			<!-- User Password Confirm -->
			<div>
				<label for="password_confirmation">Confirm Password:</label>
				<input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password" required>
				<span class="help">Password confirmation for admin.</span>
			</div>

		</fieldset>

		<div class="actions">
			<button type="submit" class="btn btn-large">
				Continue to Step 4
			</button>
		</div>
	{{ Form::close() }}
</div>
@endsection
