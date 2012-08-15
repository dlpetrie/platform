{{ Form::open('register', 'POST', array('id' => 'register-form', 'class' => 'form-horizontal')) }}
{{ Form::token() }}
	<fieldset>
		<legend>{{ Lang::line('users::users.general.register') }}</legend>

		<!-- First Name -->
		<div>
			{{ Form::label('first_name', lang::line('users::users.general.first_name')) }}
			{{ Form::text('first-name', Input::old('first_name'), array('placeholder' => lang::line('users::users.general.first_name'), 'required')) }}
			<span class="help">Type your first name.</span>
		</div>

		<!-- Last Name -->
		<div>
			{{ Form::label('last-name', lang::line('users::users.general.last_name')) }}
			{{ Form::text('last-name', Input::old('last_name'), array('placeholder' => lang::line('users::users.general.last_name'), 'required')) }}
			<span class="help">Type your last name.</span>
		</div>

		<!-- Email Address -->
		<div>
			{{ Form::label('email', lang::line('users::users.general.email')) }}
			{{ Form::email('email', Input::old('email'), array('id' => 'email', 'placeholder' => lang::line('users::users.general.email'), 'required')) }}
			<span class="help">Type in your email address.</span>
		</div>

		<!-- Email Confirm -->
		<div>
			{{ Form::label('email-confirm', lang::line('users::users.general.email_confirmation')) }}
			{{ Form::email('email-confirm', null, array('id' => 'email-confirm', 'placeholder' => lang::line('users::users.general.email_confirmation'), 'required')) }}
			<span class="help">Confirm your email address.</span>
		</div>

		<!-- Password -->
		<div>
			{{ Form::label('password', lang::line('users::users.general.password')) }}
			{{ Form::password('password', array('placeholder' => lang::line('users::users.general.password'), 'required')) }}
			<span class="help">Type your password.</span>
		</div>

		<!-- Password Confirm -->
		<div>
			{{ Form::label('password-confirm', lang::line('users::users.general.password_confirmation')) }}
			{{ Form::password('password-confirm', array('placeholder' => lang::line('users::users.general.password_confirmation'), 'required')) }}
			<span class="help">confirm your password.</span>
		</div>
	</fieldset>

	<p class="errors"></p>

	<div class="actions">
		<button class="btn" type="submit">{{ Lang::line('users::users.button.register') }}</button>
	</div>
{{ Form::close() }}
