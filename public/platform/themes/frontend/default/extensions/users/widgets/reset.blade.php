<form action="{{ URL::to_secure('/reset_password') }}" id="password-reset-form" class="form-horizontal" method="POST" accept-char="UTF-8">
<input type="hidden" name="{{ Session::csrf_token }}" value="{{ Session::token() }}">
	<fieldset>

		<legend>{{ Lang::line('users::form.auth.reset.legend') }}</legend>
		<p class="summary">{{ Lang::line('users::form.auth.reset.summary') }}</p>
		<hr>

		<!-- Email Address -->
		<div>
			<label for="email">{{ lang::line('users::form.auth.login.email') }}:</label>
			<input type="email" name="email" id="email" value="{{ Input::old('email') }}" placeholder="{{ lang::line('users::form.auth.login.email') }}" required>
			<span class="help">{{ lang::line('users::form.auth.reset.email_help') }}</span>
		</div>

		<!-- Password -->
		<div>
			<label for="password">{{ lang::line('users::form.auth.reset.password') }}:</label>
			<input type="password" name="password" id="password" placeholder="{{ lang::line('users::form.auth.reset.password') }}" required>
			<span class="help">{{ lang::line('users::form.auth.reset.password_help') }}</span>
		</div>



	</fieldset>

	<p class="messages" data-wait="{{ Lang::line('users::users.login.wait') }}" data-redirecting="{{ Lang::line('users::users.login.redirect') }}"></p>

	<hr>

	<div class="actions">
		<button class="btn" type="submit"/>{{ Lang::line('users::form.auth.reset.submit') }}</button>
		<p class="sub-actions">
			<a href="{{ URL::to_secure('/login') }}">{{ Lang::line('users::form.auth.reset.cancel') }}</a>
		</p>
	</div>
{{ Form::close() }}
