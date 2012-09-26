@layout('templates.default')

@section('title')
    {{ Lang::line('extensions::general.title') }}
@endsection

@section('content')
<section id="extensions">
    <header class="row-fluid">
        <div class="span12">
            <h1>{{ Lang::line('extensions::general.title') }}</h1>
            <p>{{ Lang::line('extensions::general.description.index') }}</p>
        </div>
        <nav class="actions span8 pull-right"></nav>
    </header>

    <hr />

    <div id="table">
        <div class="row-fluid">
            <div class="table-wrapper">
                <table id="installed-extension-table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="span2">{{ Lang::line('extensions::table.name') }}</th>
                            <th class="span1">{{ Lang::line('extensions::table.version') }}</th>
                            <th class="span4">{{ Lang::line('extensions::table.description') }}</th>
                            <th class="span2">{{ Lang::line('extensions::table.actions') }}</th>
                        </tr>
                    <thead>
                    <tbody>
                        @foreach ( $extensions as $extension )
                        <tr>
                            <td><a href="{{ URL::to(ADMIN . '/extensions/view/' . array_get($extension, 'info.slug') ) }}">{{ array_get($extension, 'info.name') }}</a></td>
                            <td>{{ array_get($extension, 'info.version') }}</td>
                            <td>
                                {{ array_get($extension, 'info.description') }}
                                @if ( ! Platform::extensions_manager()->is_installed( array_get($extension, 'info.slug') ) and ! Platform::extensions_manager()->can_install( array_get($extension, 'info.slug') ) )
                                    <span class="pull-right label label-warning">{{ Lang::line('general.required')->get() }}: {{ implode(', ', Platform::extensions_manager()->required_extensions( array_get($extension, 'info.slug') ) ) }}</span>
                                @endif
                                @if ( Platform::extensions_manager()->has_update( array_get($extension, 'info.slug') ) )
                                    <span class="pull-right label label-info">{{ Lang::line('extensions::table.has_updates')->get() }}</span>
                                @endif
                            </td>
                            <td>
                                @if ( Platform::extensions_manager()->is_installed( array_get($extension, 'info.slug') ) )
                                    @if ( Platform::extensions_manager()->can_uninstall( array_get($extension, 'info.slug') ) )
                                        <a class="btn" href="{{ URL::to(ADMIN . '/extensions/uninstall/' . array_get($extension, 'info.slug') ) }}">{{ Lang::line('extensions::button.uninstall')->get() }}</a>
                                    @else
                                        <a class="btn disabled">{{ Lang::line('extensions::button.uninstall')->get() }}</a>
                                    @endif

                                    @if ( Platform::extensions_manager()->is_enabled( array_get($extension, 'info.slug') ) )
                                        @if ( Platform::extensions_manager()->can_disable( array_get($extension, 'info.slug') ) )
                                            <a class="btn" href="{{ URL::to(ADMIN . '/extensions/disable/' . array_get($extension, 'info.slug') ) }}">{{ Lang::line('extensions::button.disable')->get() }}</a>
                                        @else
                                            <a class="btn disabled">{{ Lang::line('extensions::button.disable')->get() }}</a>
                                        @endif
                                    @else
                                        @if ( Platform::extensions_manager()->can_enable( array_get($extension, 'info.slug') ) )
                                            <a class="btn" href="{{ URL::to(ADMIN . '/extensions/enable/' . array_get($extension, 'info.slug') ) }}">{{ Lang::line('extensions::button.enable')->get() }}</a>
                                        @else
                                            <a class="btn disabled">{{ Lang::line('extensions::button.enable')->get() }}</a>
                                        @endif
                                    @endif
                                @else
                                    @if ( Platform::extensions_manager()->can_install( array_get($extension, 'info.slug') ) )
                                        <a class="btn" href="{{ URL::to(ADMIN . '/extensions/install/' . array_get($extension, 'info.slug') ) }}">{{ Lang::line('extensions::button.install')->get() }}</a>
                                    @else
                                        <a class="btn disabled">{{ Lang::line('extensions::button.install')->get() }}</a>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection