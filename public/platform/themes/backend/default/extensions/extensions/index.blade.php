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
