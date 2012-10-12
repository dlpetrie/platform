{{ Form::open() }}
    {{ Form::token() }}

    {{ Form::hidden('extension', 'localisation') }}

    <fieldset>
        <div>
            <label for="site-country">{{ Lang::line('localisation::form.settings.fields.country') }}</label>
            {{ Form::select('site:country', countries(), Platform::get('localisation.site.country'), array('id' => 'site-country')) }}
            <span class="help"></span>
        </div>

        <div>
            <label for="site-language">{{ Lang::line('localisation::form.settings.fields.language') }}</label>
            {{ Form::select('site:language', languages(), Platform::get('localisation.site.language'), array('id' => 'site-language')) }}
            <span class="help"></span>
        </div>

        <div>
            <label for="site-currency">{{ Lang::line('localisation::form.settings.fields.currency') }}</label>
            {{ Form::select('site:currency', currencies(), Platform::get('localisation.site.currency'), array('id' => 'site-currency')) }}
            <span class="help"></span>
        </div>

        <div>
            <label for="site-timezone">{{ Lang::line('localisation::form.settings.fields.timezone') }}</label>
            {{ Form::select('site:timezone', timezones(), Platform::get('localisation.site.timezone'), array('id' => 'site-timezone')) }}
            <span class="help"></span>
        </div>
    </fieldset>

    <hr />

    <div class="actions">
        <button class="btn btn-large btn-primary" type="submit">{{ Lang::line('button.update') }}</button>
    </div>
{{ Form::close() }}