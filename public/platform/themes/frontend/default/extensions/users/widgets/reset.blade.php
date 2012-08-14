{{ Form::open('reset_password', 'POST', array('id' => 'password-reset-form', 'class' => 'form-horizontal')) }}
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
			{{ Form::label('password', lang::line('users::users.general.new_password')) }}
			{{ Form::password('password', array('placeholder' => lang::line('users::users.general.new_password'), 'required')) }}
			<span class="help">Type your new password.</span>
		</div>

		<p class="help-block"><a href="{{ URL::to_secure('/reset_password') }}">{{ Lang::line('users::users.general.reset_password') }}</a></p>

		<p class="help-block">{{ Lang::line('users::users.general.reset_help') }}</p>

	</fieldset>


	<p class="errors"></p>

	<p id="reset-feedback" data-wait="{{ Lang::line('users::users.reset.wait') }}" data-redirecting="{{ Lang::line('users::users.reset.redirect') }}"></p>

	<div class="actions">
		<button class="btn" type="submit"/>{{ Lang::line('users::users.button.reset_password') }}</button>
	</div>
{{ Form::close() }}
