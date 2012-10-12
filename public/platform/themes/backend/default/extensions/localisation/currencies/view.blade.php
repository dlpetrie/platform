@layout('templates.default')

@section('title')
    {{ Lang::line('localisation::currencies/general.title')->get() }}
@endsection

@section('content')
<section id="currencies">
    <header class="head row-fluid">
        <div class="span6">
            <h1>{{ Lang::line('localisation::currencies/general.title')->get() }}</h1>
            <p>{{ Lang::line('localisation::currencies/general.description.view', array('currency' => $currency['name']))->get() }}</p>
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
                <label class="control-label" for="name">{{ Lang::line('localisation::currencies/table.name')->get() }}</label>
                <div class="controls">
                    <input type="text" name="name" id="name" value="{{ Input::old('name', $currency['name']); }}" placeholder="{{ Lang::line('localisation::currencies/table.name')->get() }}" required>
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="code">{{ Lang::line('localisation::currencies/table.code')->get() }}</label>
                <div class="controls">
                    <input type="text" name="code" id="code" value="{{ Input::old('code', $currency['code']); }}" placeholder="{{ Lang::line('localisation::currencies/table.code')->get() }}" required>
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="status">{{ Lang::line('localisation::currencies/table.status')->get() }}</label>
                <div class="controls">
                    {{ Form::select('status', general_statuses(), $currency['status']); }}
                    <span class="help-block"></span>
                </div>
            </div>
        </fieldset>

        <hr />

        <div class="form-actions">
            <a class="btn btn-large" href="{{ URL::to_admin('localisation/currencies') }}">{{ Lang::line('button.cancel')->get() }}</a>
            <button class="btn btn-large btn-primary" type="submit" name="save" id="save" value="1">{{ Lang::line('button.update')->get() }}</button>
            <button class="btn btn-large btn-primary" type="submit" name="save_exit" id="save_exit" value="1">{{ Lang::line('button.update_exit')->get() }}</button>
            @if ( ! $currency['default'])
            <a class="btn btn-large btn-danger" href="{{ URL::to_admin('localisation/currencies/delete/' . $currency['slug']) }}">{{ Lang::line('button.delete')->get() }}</a>
        	@endif
        </div>
    {{ Form::close() }}
</section>
@endsection