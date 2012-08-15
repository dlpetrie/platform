@layout('installer::template')

@section('scripts')
<script>
	$(document).ready(function() {

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
	{{ Form::open(null, 'POST', array('id' => 'user-form', 'class' => 'form-horizontal')) }}
	{{ Form::token() }}
		<fieldset>
			<legend>{{ Lang::line('installer::installer.user.legend') }}</legend>

			<!-- User First Name -->
			<div>
				{{ Form::label('first_name', 'First Name:') }}
				{{ Form::text('first_name', null, array('placeholder' => 'First Name', 'required')) }}
				<span class="help">First name of admin.</span>
			</div>


			<!-- User Last Name -->
			<div>
				{{ Form::label('last_name', 'Last Name:') }}
				{{ Form::text('last_name', null, array('placeholder' => 'Last Name', 'required')) }}
				<span class="help">Last name of admin.</span>
			</div>

			<!-- User Email Addres -->
			<div>
				{{ Form::label('email', 'Email Address:') }}
				{{ Form::email('email', null, array('placeholder' => 'Email Address', 'required')) }}
				<span class="help">Email address of admin.</span>
			</div>

			<!-- User Password -->
			<div>
				{{ Form::label('password', 'Password:') }}
				{{ Form::password('password', array('id' => 'password', 'placeholder' => 'Password', 'required')) }}
				<span class="help">Password for admin.</span>
			</div>

			<!-- User Password Confirm -->
			<div>
				{{ Form::label('password_confirmation', 'Confirm Password:') }}
				{{ Form::password('password_confirmation', array('id' => 'password-confirm', 'placeholder' => 'Confirm Password', 'required')) }}
				<span class="help">Password confirmation for admin.</span>
			</div>

			<div class="messages alert"></div>

		</fieldset>

		<div class="actions">
			<button type="submit" class="btn btn-large" disabled>
				Continue to Step 4
			</button>
		</div>
	{{ Form::close() }}
</div>
@endsection
