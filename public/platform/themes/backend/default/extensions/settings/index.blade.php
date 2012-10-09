@layout('templates.default')

@section('title')
    {{ Lang::line('settings::general.title')->get() }}
@endsection

{{ Theme::queue_asset('bootstrap-tab', 'js/bootstrap/tab.js', 'jquery') }}

@section('content')
<section id="settings">
    <header class="row-fluid">
        <div class="span6">
            <h1>{{ Lang::line('settings::general.title') }}</h1>
            <p>{{ Lang::line('settings::general.description') }}</p>
        </div>
        <nav class="actions span6 pull-right"></nav>
    </header>

    <hr />

    <div class="tabbable">
        <ul class="nav nav-tabs">
            @foreach ( $settings as $extension => $data )
            <li{{ ( $extension === 'settings' ? ' class="active"' : '' ) }}><a href="#tab_{{ $extension }}" data-toggle="tab">{{ Lang::line($extension . '::form.settings.title')->get() }}</a></li>
            @endforeach
        </ul>
        <div class="tab-content">
            @foreach ( $settings as $extension => $data )
            <div class="tab-pane{{ ( $extension === 'settings' ? ' active' : '' ) }}" id="tab_{{ $extension }}">
                @widget('platform.' . $extension . '::settings.index', $data)
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection