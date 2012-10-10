<form action="{{ URL::to_secure('/reset_password') }}" id="password-reset-form" class="form-horizontal" method="POST" accept-char="UTF-8">
<input type="hidden" name="{{ Session::csrf_token }}" value="{{ Session::token() }}">
	<fieldset>

		<legend>{{ Lang::line('users::form.auth.reset.legend') }}</legend>
		<p class="summary">{{ Lang::line('users::form.auth.reset.summary') }}</p>
		<hr>

		<!-- Email Address -->
		<div class="control-group">
			<label class="control-label" for="email">{{ lang::line('users::form.auth.login.email') }}:</label>
			<div class="controls">
				<input type="email" name="email" id="email" value="{{ Input::old('email') }}" placeholder="{{ lang::line('users::form.auth.login.email') }}" required>
				<span class="help-block">{{ lang::line('users::form.auth.reset.email_help') }}</span>
			</div>
		</div>

		<!-- Password -->
		<div class="control-group">
			<label class="control-label" for="password">{{ lang::line('users::form.auth.reset.password') }}:</label>
			<div class="controls">
				<input type="password" name="password" id="password" placeholder="{{ lang::line('users::form.auth.reset.password') }}" required>
				<span class="help-block">{{ lang::line('users::form.auth.reset.password_help') }}</span>
			</div>
		</div>



	</fieldset>

	<p class="messages" data-wait="{{ Lang::line('users::messages.auth.wait') }}" data-redirecting="{{ Lang::line('users::messages.auth.redirect') }}"></p>

	<hr>

	<div class="form-actions">
		<a class="btn" href="{{ URL::to_secure('/login') }}">{{ Lang::line('users::form.auth.reset.cancel') }}</a>
		<button class="btn btn-primary" type="submit"/>{{ Lang::line('users::form.auth.reset.submit') }}</button>
	</div>
</form>
