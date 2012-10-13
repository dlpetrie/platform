{{ Form::open() }}
    {{ Form::token() }}

    {{ Form::hidden('extension', 'localisation') }}

    <fieldset>
        <div>
            <label for="site-country">{{ Lang::line('localisation::form.settings.fields.country')->get() }}</label>
            {{ Form::select('site:country', countries(), Platform::get('localisation.site.country'), array('id' => 'site-country')) }}
            <span class="help"></span>
        </div>

        <div>
            <label for="site-language">{{ Lang::line('localisation::form.settings.fields.language')->get() }}</label>
            {{ Form::select('site:language', languages(), Platform::get('localisation.site.language'), array('id' => 'site-language')) }}
            <span class="help"></span>
        </div>

        <div>
            <label for="site-currency">{{ Lang::line('localisation::form.settings.fields.currency')->get() }}</label>
            {{ Form::select('site:currency', currencies(), Platform::get('localisation.site.currency'), array('id' => 'site-currency')) }}
            <span class="help"></span>
        </div>

        <div>
            <label for="site-currency-auto-update">{{ Lang::line('localisation::form.settings.fields.currency_auto')->get() }}</label>
            {{ Form::select('site:currency_auto_update', currencies_update_interval(), Platform::get('localisation.site.currency_auto_update'), array('id' => 'site-currency-auto-update')) }}
            <span class="help"></span>
        </div>

        <div>
            <label for="site-timezone">{{ Lang::line('localisation::form.settings.fields.timezone')->get() }}</label>
            {{ Form::select('site:timezone', timezones(), Platform::get('localisation.site.timezone'), array('id' => 'site-timezone')) }}
            <span class="help"></span>
        </div>
    </fieldset>

    <hr />

    <div class="actions">
        <button class="btn btn-large btn-primary" type="submit">{{ Lang::line('button.update')->get() }}</button>
    </div>
{{ Form::close() }}