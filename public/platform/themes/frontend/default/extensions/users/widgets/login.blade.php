{{ Form::open('login', 'POST', array('id' => 'login-form', 'class' => 'form-horizontal')) }}
{{ Form::token() }}
	<fieldset>
		<legend>{{ Lang::line('users::users.general.login') }}</legend>

		<!-- Email Address -->
		<div>
			{{ Form::label('email', lang::line('users::users.general.email')) }}
			{{ Form::email('email', Input::old('email'), array('id' => 'email', 'placeholder' => lang::line('users::users.general.email'), 'required')) }}
			<span class="help">Type in your email address.</span>
		</div>

		<!-- Password -->
		<div>
			{{ Form::label('password', lang::line('users::users.general.password')) }}
			{{ Form::password('password', array('placeholder' => lang::line('users::users.general.password'), 'required')) }}
			<span class="help">Type your password.</span>
		</div>

		<p class="help-block"><a href="{{ URL::to_secure('/reset_password') }}">{{ Lang::line('users::users.general.reset_password') }}</a></p>

	</fieldset>


	<p class="errors"></p>

	<p id="login-feedback" data-wait="{{ Lang::line('users::users.login.wait') }}" data-redirecting="{{ Lang::line('users::users.login.redirect') }}"></p>

	<div class="actions">
		<button class="btn" type="submit">{{ Lang::line('users::users.button.login') }}</button>
	</div>
{{ Form::close() }}
