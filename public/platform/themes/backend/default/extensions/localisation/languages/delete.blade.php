@layout('templates.default')

@section('title')
    {{ Lang::line('localisation::languages/general.title')->get() }}
@endsection

@section('content')
<section id="languages">
    <header class="head row-fluid">
        <div class="span6">
            <h1>{{ Lang::line('localisation::languages/general.title')->get() }}</h1>
            <p>{{ Lang::line('localisation::languages/general.description.delete', array('language' => $language['name']))->get() }}</p>
        </div>
        <nav class="tertiary-navigation span6">
            @widget('platform.menus::menus.nav', 2, 1, 'nav nav-pills pull-right', ADMIN)
        </nav>
    </header>

    <hr />

    {{ Form::open() }}
        {{ Form::token() }}

        <div class="alert alert-error">
            <h3>{{ Lang::line('general.warning')->get() }}</h3>
            
            @if ( $language['default'] )
            <p>{{ Lang::line('localisation::languages/message.delete.single.being_used', array('language' => $language['name']))->get() }}</p>
            @else
            <p>{{ Lang::line('localisation::languages/message.delete.single.confirm', array('language' => $language['name']))->get() }}</p>
            
            <button class="btn btn-danger"><i class="icon-ok icon-white"></i> Delete</button> 
            <a href="{{ URL::to_admin('localisation/languages') }}" class="btn btn-success"><i class="icon-remove icon-white"></i> {{ Lang::line('button.cancel')->get() }}</a>
            @endif
        </div>
    {{ Form::close() }}
</section>
@endsection