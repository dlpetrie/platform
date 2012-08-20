{{ Form::open(ADMIN.'/settings/general', 'POST', array('class' => 'form-horizontal')) }}

	{{ Form::token() }}

	<div class="well">
		<fieldset>

			<label for="site-title">{{ Lang::line('settings::form.title') }}</label>
			<input type="text" id="title" name="site:title" value="@get.settings.site.title">

			<label for="site-tagline">{{ Lang::line('settings::form.tagline') }}</label>
			<input type="text" id="tagline" name="site:tagline" value="@get.settings.site.tagline">

			<label for="site-email">{{ Lang::line('settings::form.site_email') }}</label>
			<input type="text" id="email" name="site:email" value="@get.settings.site.email">

		</fieldset>
	</div>

	<button class="btn btn-large btn-primary" type="submit">{{ Lang::line('button.update') }}</button>
{{ Form::close() }}
