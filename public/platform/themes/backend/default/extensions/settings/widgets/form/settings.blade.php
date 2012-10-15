<form action="{{ URL::to_secure(ADMIN.'/settings') }}" id="general-form" class="form-horizontal" method="POST" accept-char="UTF-8">
    <input type="hidden" name="{{ Session::csrf_token }}" value="{{ Session::token() }}">

    <input type="hidden" name="extension" value="settings" />

    <fieldset>

    	<legend>Site</legend>

        <div>
            <label for="site-title">{{ Lang::line('settings::form.settings.fields.title') }}</label>
            <input type="text" id="site-title" name="site:title" value="{{ array_get($settings, 'site.title') }}" required />
            <span class="help"></span>
        </div>

        <div>
            <label for="site-tagline">{{ Lang::line('settings::form.settings.fields.tagline') }}</label>
            <input type="text" id="site-tagline" name="site:tagline" value="{{ array_get($settings, 'site.tagline') }}" required />
            <span class="help"></span>
        </div>

        <div>
            <label for="site-email">{{ Lang::line('settings::form.settings.fields.email') }}</label>
            <input type="email" id="site-email" name="site:email" value="{{ array_get($settings, 'site.email') }}" required />
            <span class="help"></span>
        </div>

    </fieldset>

    <fieldset>

		<legend>Filesystem Messages</legend>

		<div>
			<h4>Frontend</h4>

			<div>
				<label for="filesysem-frontend-fallback-message">{{ Lang::line('settings::form.settings.fields.filesystem_fallback') }}</label>
				{{ Form::select('filesystem:frontend_fallback_message', $filesystem_options, $settings['filesystem']['frontend_fallback_message'], array('id' => 'frontend-fallback-message')) }}
			</div>

			<div>
				<label for="filesysem-frontend-failed-message">{{ Lang::line('settings::form.settings.fields.filesystem_failed') }}</label>
				{{ Form::select('filesystem:frontend_failed_message', $filesystem_options, $settings['filesystem']['frontend_failed_message'], array('id' => 'frontend-failed-message')) }}
			</div>

		</div>

		<div>
			<h4>Backend</h4>

			<div>
				<label for="filesysem-backend-fallback-message">{{ Lang::line('settings::form.settings.fields.filesystem_fallback') }}</label>
				{{ Form::select('filesystem:backend_fallback_message', $filesystem_options, $settings['filesystem']['backend_fallback_message'], array('id' => 'filesystem-fallback-message')) }}
			</div>

			<div>
				<label for="filesysem-backend-failed-message">{{ Lang::line('settings::form.settings.fields.filesystem_failed') }}</label>
				{{ Form::select('filesystem:backend_failed_message', $filesystem_options, $settings['filesystem']['backend_failed_message'], array('id' => 'filesystem-failed-message')) }}
			</div>

		</div>

    </fieldset>

    <p class="messages"></p>
    <hr>

    <div class="actions">
        <button class="btn btn-large btn-primary" type="submit">{{ Lang::line('button.update') }}</button>
    </div>

</form>