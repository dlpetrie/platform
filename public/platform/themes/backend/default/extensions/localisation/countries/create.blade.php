@layout('templates.default')

@section('title')
    {{ Lang::line('localisation::countries/general.title')->get() }}
@endsection

@section('content')
<section id="countries">
    <header class="head row-fluid">
        <div class="span6">
            <h1>{{ Lang::line('localisation::countries/general.title')->get() }}</h1>
            <p>{{ Lang::line('localisation::countries/general.description.create')->get() }}</p>
        </div>
        <nav class="tertiary-navigation span6">
            @widget('platform.menus::menus.nav', 2, 1, 'nav nav-pills pull-right', ADMIN)
        </nav>
    </header>

    <hr />

    {{ Form::open() }}
        {{ Form::token() }}
        <fieldset>
            <div class="control-group">
                <label class="control-label" for="name">{{ Lang::line('localisation::countries/table.name')->get() }}</label>
                <div class="controls">
                    <input type="text" name="name" id="name" value="{{ Input::old('name'); }}" placeholder="{{ Lang::line('localisation::countries/table.name')->get() }}" required>
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="iso_code_2">{{ Lang::line('localisation::countries/table.iso_code_2')->get() }}</label>
                <div class="controls">
                    <input type="text" name="iso_code_2" id="iso_code_2" value="{{ Input::old('iso_code_2'); }}" placeholder="{{ Lang::line('localisation::countries/table.iso_code_2')->get() }}" required>
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="iso_code_3">{{ Lang::line('localisation::countries/table.iso_code_3')->get() }}</label>
                <div class="controls">
                    <input type="text" name="iso_code_3" id="iso_code_3" value="{{ Input::old('iso_code_3'); }}" placeholder="{{ Lang::line('localisation::countries/table.iso_code_3')->get() }}" required>
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="iso_code_numeric_3">{{ Lang::line('localisation::countries/table.iso_code_numeric_3')->get() }}</label>
                <div class="controls">
                    <input type="text" name="iso_code_numeric_3" id="iso_code_numeric_3" value="{{ Input::old('iso_code_numeric_3'); }}" placeholder="{{ Lang::line('localisation::countries/table.iso_code_numeric_3')->get() }}" required>
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="region">{{ Lang::line('localisation::countries/table.region')->get() }}</label>
                <div class="controls">
                    <input type="text" name="region" id="region" value="{{ Input::old('region'); }}" placeholder="{{ Lang::line('localisation::countries/table.region')->get() }}">
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="subregion">{{ Lang::line('localisation::countries/table.subregion')->get() }}</label>
                <div class="controls">
                    <input type="text" name="subregion" id="subregion" value="{{ Input::old('subregion'); }}" placeholder="{{ Lang::line('localisation::countries/table.subregion')->get() }}">
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="status">{{ Lang::line('localisation::countries/table.status')->get() }}</label>
                <div class="controls">
                    {{ Form::select('status', general_statuses()); }}
                    <span class="help-block"></span>
                </div>
            </div>
        </fieldset>

        <hr />

        <div class="form-actions">
            <a class="btn btn-large" href="{{ URL::to_admin('localisation/countries') }}">{{ Lang::line('button.cancel')->get() }}</a>
            <button class="btn btn-large btn-primary" type="submit" name="save" id="save" value="1">{{ Lang::line('button.update')->get() }}</button>
            <button class="btn btn-large btn-primary" type="submit" name="save_exit" id="save_exit" value="1">{{ Lang::line('button.update_exit')->get() }}</button>
        </div>
    {{ Form::close() }}
</section>
@endsection