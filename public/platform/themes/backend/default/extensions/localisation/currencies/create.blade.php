@layout('templates.default')

@section('title')
    {{ Lang::line('localisation::currencies/general.title')->get() }}
@endsection

@section('content')
<section id="currencies">
    <header class="head row-fluid">
        <div class="span6">
            <h1>{{ Lang::line('localisation::currencies/general.title')->get() }}</h1>
            <p>{{ Lang::line('localisation::currencies/general.description.create')->get() }}</p>
        </div>
        <nav class="tertiary-navigation span6">
            @widget('platform.menus::menus.nav', 2, 1, 'nav nav-pills pull-right', ADMIN)
        </nav>
    </header>

    <hr />

    <form action="{{ URL::to_admin('localisation/currencies/create/') }}" id="edit-form" class="form-horizontal" method="POST" accept-char="UTF-8">
        <input type="hidden" name="{{ Session::csrf_token }}" value="{{ Session::token() }}">
        <fieldset>
            <div class="control-group">
                <label class="control-label" for="name">{{ Lang::line('localisation::currencies/table.name')->get() }}</label>
                <div class="controls">
                    <input type="text" name="name" id="name" value="{{ Input::old('name'); }}" placeholder="{{ Lang::line('localisation::currencies/table.name')->get() }}" required>
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="code">{{ Lang::line('localisation::currencies/table.code')->get() }}</label>
                <div class="controls">
                    <input type="text" name="code" id="code" value="{{ Input::old('code'); }}" placeholder="{{ Lang::line('localisation::currencies/table.code')->get() }}" required>
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="locale">{{ Lang::line('localisation::currencies/table.locale')->get() }}</label>
                <div class="controls">
                    <input type="text" name="locale" id="locale" value="{{ Input::old('locale'); }}" placeholder="{{ Lang::line('localisation::currencies/table.locale')->get() }}" required>
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="status">{{ Lang::line('localisation::currencies/table.status')->get() }}</label>
                <div class="controls">
                    {{ Form::select('status', general_statuses()); }}
                    <span class="help-block"></span>
                </div>
            </div>
        </fieldset>

        <hr />

        <div class="form-actions">
            <a class="btn btn-large" href="{{ URL::to_admin('localisation/currencies') }}">{{ Lang::line('button.cancel')->get() }}</a>
            <button class="btn btn-large btn-primary" type="submit" name="save" id="save" value="1">{{ Lang::line('button.update')->get() }}</button>
            <button class="btn btn-large btn-primary" type="submit" name="save_exit" id="save_exit" value="1">{{ Lang::line('button.update_exit')->get() }}</button>
        </div>
    </form>
</section>
@endsection