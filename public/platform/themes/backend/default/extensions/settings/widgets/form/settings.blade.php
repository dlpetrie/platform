<form action="{{ URL::to_secure(ADMIN.'/settings') }}" id="general-form" class="form-horizontal" method="POST" accept-char="UTF-8">
    <input type="hidden" name="{{ Session::csrf_token }}" value="{{ Session::token() }}">

    <input type="hidden" name="extension" value="settings" />

    <fieldset>

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

    <p class="messages"></p>
    <hr>

    <div class="actions">
        <button class="btn btn-large btn-primary" type="submit">{{ Lang::line('button.update') }}</button>
    </div>
    
</form>