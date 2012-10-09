<form action="{{ URL::to_secure(ADMIN.'/settings') }}" id="general-form" class="form-horizontal" method="POST" accept-char="UTF-8">
    <input type="hidden" name="{{ Session::csrf_token }}" value="{{ Session::token() }}">

    <input type="hidden" name="extension" value="themes" />

    <fieldset>
        <div>
            <label for="theme-frontend">{{ Lang::line('themes::form.frontend') }}</label>
            {{ Form::select('theme:frontend', $frontend_themes, array_get($settings, 'theme.frontend'), array('id' => 'theme-frontend')) }}
            <span class="help"></span>
        </div>

        <div>
            <label for="theme-backend">{{ Lang::line('themes::form.backend') }}</label>
            {{ Form::select('theme:backend', $backend_themes, array_get($settings, 'theme.backend'), array('id' => 'theme-backend')) }}
            <span class="help"></span>
        </div>
    </fieldset>

    <p class="messages"></p>

    <hr />

    <div class="actions">
        <button class="btn btn-large btn-primary" type="submit">{{ Lang::line('button.update') }}</button>
    </div>
</form>