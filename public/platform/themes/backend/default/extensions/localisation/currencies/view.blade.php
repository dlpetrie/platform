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
    </header>

    <hr />

    {{ Form::open() }}
        {{ Form::token() }}
        <fieldset>
            <div class="control-group">
                <label class="control-label" for="name">{{ Lang::line('localisation::currencies/table.name')->get() }}</label>
                <div class="controls">
                    <input type="text" name="name" id="name" value="{{ Input::old('name', $currency['name']); }}" placeholder="{{ Lang::line('localisation::currencies/table.name')->get() }}" required />
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="code">{{ Lang::line('localisation::currencies/table.code')->get() }}</label>
                <div class="controls">
                    <input type="text" name="code" id="code" value="{{ Input::old('code', $currency['code']); }}" placeholder="{{ Lang::line('localisation::currencies/table.code')->get() }}" required />
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="symbol_left">{{ Lang::line('localisation::currencies/table.symbol_left')->get() }}</label>
                <div class="controls">
                    <input type="text" name="symbol_left" id="symbol_left" value="{{ Input::old('symbol_left', $currency['symbol_left']); }}" placeholder="{{ Lang::line('localisation::currencies/table.symbol_left')->get() }}" />
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="symbol_right">{{ Lang::line('localisation::currencies/table.symbol_right')->get() }}</label>
                <div class="controls">
                    <input type="text" name="symbol_right" id="symbol_right" value="{{ Input::old('symbol_right', $currency['symbol_right']); }}" placeholder="{{ Lang::line('localisation::currencies/table.symbol_right')->get() }}" />
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="decimal_place">{{ Lang::line('localisation::currencies/table.decimal_place')->get() }}</label>
                <div class="controls">
                    <input type="text" name="decimal_place" id="decimal_place" value="{{ Input::old('decimal_place', $currency['decimal_place']); }}" placeholder="{{ Lang::line('localisation::currencies/table.decimal_place')->get() }}" required />
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="rate">{{ Lang::line('localisation::currencies/table.rate')->get() }}</label>
                <div class="controls">
                    <input type="text" name="rate" id="rate" value="{{ Input::old('rate', $currency['rate']); }}" placeholder="{{ Lang::line('localisation::currencies/table.rate')->get() }}" required />
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