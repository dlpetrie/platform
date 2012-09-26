<<<<<<< HEAD
@layout('templates.default')

<!-- Page Title -->
@section('title')
	{{ Lang::line('extensions::general.title') }}
@endsection

<!-- Queue Styles | e.g Theme::queue_asset('name', 'path_to_css', 'dependency')-->

<!-- Styles -->
@section ('styles')
@endsection

<!-- Queue Scripts | e.g. Theme::queue_asset('name', 'path_to_js', 'dependency')-->

<!-- Scripts -->
@section('scripts')
@endsection

<!-- Page Content -->
@section('content')
<section id="extensions">

	<header class="row">
			<div class="span4">
				<h1>{{ Lang::line('extensions::general.title') }}</h1>
				<p>{{ Lang::line('extensions::general.description') }}</p>
			</div>
			<nav class="actions span8 pull-right">
			</nav>
	</header>

	<hr>

    <div id="table">
    	<div class="row-fluid">
			<div>
				<div class="table-wrapper">
					<table id="installed-extension-table" class="table table-bordered">
						<thead>
							<tr>
								<th>{{ Lang::line('extensions::table.name') }}</th>
								<th>{{ Lang::line('extensions::table.slug') }}</th>
								<th>{{ Lang::line('extensions::table.author') }}</th>
								<th>{{ Lang::line('extensions::table.description') }}</th>
								<th>{{ Lang::line('extensions::table.version') }}</th>
								<th>{{ Lang::line('extensions::table.is_core') }}</th>
								<th>{{ Lang::line('extensions::table.enabled') }}</th>
								<th>{{ Lang::line('extensions::table.has_updates') }}</th>
								<th class="span3">{{ Lang::line('extensions::table.actions') }}</th>
							</tr>
						<thead>
						<tbody>
							@include('extensions::partials.table_extensions')
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<hr>

	<div id="table">
    	<div class="row">
			<div>
				<div class="table-wrapper">
			    	<table id="uninstalled-extension-table" class="table table-bordered">
						<thead>
							<tr>
								<th>{{ Lang::line('extensions::table.name') }}</th>
								<th>{{ Lang::line('extensions::table.slug') }}</th>
								<th>{{ Lang::line('extensions::table.author') }}</th>
								<th>{{ Lang::line('extensions::table.description') }}</th>
								<th>{{ Lang::line('extensions::table.version') }}</th>
								<th>{{ Lang::line('extensions::table.actions') }}</th>
							</tr>
						</thead>
						<tbody>
							@forelse ($uninstalled as $extension)
								<tr>
									<td>
										{{ array_get($extension, 'info.name') }}
									</td>
									<td>
										{{ array_get($extension, 'info.slug') }}
									</td>
									<td>
										{{ array_get($extension, 'info.author') }}
									</td>
									<td>
										{{ array_get($extension, 'info.description') }}
									</td>
									<td>
										{{ array_get($extension, 'info.version') }}
									</td>
									<td>
										<a class="btn" href="{{ URL::to_secure(ADMIN.'/extensions/install/'.array_get($extension, 'info.slug')) }}" onclick="return confirm('Are you sure you want to install the \'{{ e(array_get($extension, 'info.name')) }}\' extension?');">{{ Lang::line('extensions::button.install') }}</a>
									</td>
								</tr>
							@empty
								<tr>
									<td colspan="6">
										Good news! All extensions have been installed!
									</td>
								</tr>
							@endforelse
						</tbody>
					</table>
					</div>
				</div>
			</div>
		</div>

</section>
@endsection
=======
@layout('templates.default')

@section('title')
    {{ Lang::line('extensions::general.title') }}
@endsection

@section('scripts')
{{ Theme::asset('js/bootstrap/dropdown.js') }}
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
                            <th class="span1">{{ Lang::line('extensions::table.actions') }}</th>
                            <th class="span4">{{ Lang::line('extensions::table.description') }}</th>
                        </tr>
                    <thead>
                    <tbody>
                        @foreach ( $extensions as $extension )
                        <tr>
                            <td><a href="{{ URL::to(ADMIN . '/extensions/view/' . array_get($extension, 'info.slug') ) }}">{{ array_get($extension, 'info.name') }}</a></td>
                            <td>{{ array_get($extension, 'info.version') }}</td>
                            <td>
                                @if ( Platform::extensions_manager()->is_installed( array_get($extension, 'info.slug') ) )
                                    @if ( Platform::extensions_manager()->can_uninstall( array_get($extension, 'info.slug') ) )
                                        <a class="btn" href="{{ URL::to(ADMIN . '/extensions/uninstall/' . array_get($extension, 'info.slug') ) }}">{{ Lang::line('extensions::button.uninstall')->get() }}</a>
                                    @else
                                        <a class="btn disabled">{{ Lang::line('extensions::button.uninstall')->get() }}</a>
                                    @endif
                                @else
                                    @if ( Platform::extensions_manager()->can_install( array_get($extension, 'info.slug') ) )
                                        <a class="btn" href="{{ URL::to(ADMIN . '/extensions/install/' . array_get($extension, 'info.slug') ) }}">{{ Lang::line('extensions::button.install')->get() }}</a>
                                    @else
                                        <a class="btn disabled">{{ Lang::line('extensions::button.install')->get() }}</a>
                                    @endif
                                @endif
                            </td>
                            <td>
                                {{ array_get($extension, 'info.description') }}
                                @if ( ! Platform::extensions_manager()->is_installed( array_get($extension, 'info.slug') ) and ! Platform::extensions_manager()->can_install( array_get($extension, 'info.slug') ) )
                                    <span class="pull-right label label-warning">{{ Lang::line('general.required')->get() }}: {{ implode(', ', Platform::extensions_manager()->required_extensions( array_get($extension, 'info.slug') ) ) }}</span>
                                @endif
                                @if ( Platform::extensions_manager()->has_update( array_get($extension, 'info.slug') ) )
                                    <span class="pull-right label label-info">{{ Lang::line('extensions::table.has_updates')->get() }}</span>
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
>>>>>>> refs/heads/feature/installation-dependencies-refactor
