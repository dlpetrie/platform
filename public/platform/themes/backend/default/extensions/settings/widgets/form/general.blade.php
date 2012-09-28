
<form action="{{ URL::to_secure(ADMIN.'/settings') }}" id="general-form" class="form-horizontal" method="POST" accept-char="UTF-8">
	<input type="hidden" name="{{ Session::csrf_token }}" value="{{ Session::token() }}">

	<input type="hidden" name="extension" value="settings" />

	<fieldset>

		<div>
			<label for="site-title">{{ Lang::line('settings::form.title') }}</label>
			<input type="text" id="title" name="site:title" value="{{ array_get($settings, 'site.title') }}">
			<span class="help"></span>
		</div>

		<div>
			<label for="site-tagline">{{ Lang::line('settings::form.tagline') }}</label>
			<input type="text" id="tagline" name="site:tagline" value="{{ array_get($settings, 'site.tagline') }}">
			<span class="help"></span>
		</div>

		<div>
			<label for="site-email">{{ Lang::line('settings::form.site_email') }}</label>
			<input type="text" id="email" name="site:email" value="{{ array_get($settings, 'site.email') }}">
			<span class="help"></span>
		</div>

	</fieldset>

	<p class="messages"></p>
	<hr>

	<div class="actions">
		<button class="btn btn-large btn-primary" type="submit">{{ Lang::line('button.update') }}</button>
	</div>
	
</form>
