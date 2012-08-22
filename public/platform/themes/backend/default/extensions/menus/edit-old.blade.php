@layout('templates.default')

<!-- Page Title -->
@section('title')
	{{ Lang::line('menus::general.title') }}
@endsection

<!-- Queue Styles -->
{{ Theme::queue_asset('menus', 'menus::css/menus.less', 'style') }}
{{ Theme::queue_asset('toggle', 'css/bootstrap/less/toggle.less', 'style') }}

<!-- Styles -->
@section ('styles')
@endsection

<!-- Queue Scripts -->
{{ Theme::queue_asset('jquery-helpers', 'js/jquery/helpers.js', 'jquery') }}
{{ Theme::queue_asset('bootstrap-tab', 'js/bootstrap/tab.js', 'jquery') }}
{{ Theme::queue_asset('bootstrap-toggle', 'js/bootstrap/toggle.js', 'jquery') }}
{{ Theme::queue_asset('jquery-ui', 'js/jquery/ui-1.8.18.min.js', 'jquery') }}
{{ Theme::queue_asset('jquery-nestedsortable', 'js/jquery/nestedsortable-1.3.4.js', 'jquery') }}
{{ Theme::queue_asset('jquery-nestysortable', 'js/jquery/nestysortable-1.0.js', 'jquery') }}
{{ Theme::queue_asset('menussortable', 'menus::js/menussortable-1.0.js', 'jquery') }}
{{ Theme::queue_asset('validate', 'js/validate.js', 'jquery') }}

<!-- Scripts -->
@section('scripts')
<script>
	$(document).ready(function() {

		$('#platform-menu').menuSortable({

			// Array of ALL existing
			// slugs. Just so we don't
			// have any clashes
			persistedSlugs : {{ $persisted_slugs }},

			// Define Nesty Sortable dependency for the menu sortable.
			nestySortable: {
				fields           : [
					{
						name        : 'name',
						newSelector : '#new-child-name'
					},
					{
						name        : 'slug',
						newSelector : '#new-child-slug'
					},
					{
						name        : 'uri',
						newSelector : '#new-child-uri'
					},
					{
						name        : 'secure',
						newSelector : '#new-child-secure'
					},
					{
						name        : 'type',
						newSelector : '#new-child-type'
					},
					{
						name        : 'target',
						newSelector : '#new-child-target'
					},
				],
				childTemplate         : {{ $child_template }},
				lastChildId           : {{ $last_child_id }}
			}
		});

		// Toggle Checkboxes.
		$('.basic').toggle({
			style: {
				enabled: 'success',
				disabled: 'danger'
			}
		});

		//validate forms
		//Validate.setup($("#platform-menu"));

	});
	</script>
@endsection

@section('content')

<section id="menus-edit">

	<header class="row">
		<div class="span4">
			<h1>{{ Lang::line('menus::general.update.title') }}</h1>
			<p>{{ Lang::line('menus::general.update.description') }}</p>
		</div>
		<nav class="actions span8 pull-right"></nav>
	</header>

	<hr>

	<form action="{{ URL::to_secure(ADMIN.'/menus/edit/'.$menu_slug ?: null) }}" id="platform-menu" class="form-horizontal" method="POST" accept-char="UTF-8" autocomplete="off">
		<input type="hidden" name="{{ Session::csrf_token }}" value="{{ Session::token() }}">

		<div class="tabbable">
			<ul class="nav nav-tabs">
				<li class="{{ ($menu_slug) ? 'active' : null }}"><a href="#menus-edit-children" data-toggle="tab">{{ Lang::line('menus::general.tabs.children') }}</a></li>
				<li class="{{ ( ! $menu_slug) ? 'active' : null }}"><a href="#menus-edit-menu-options" data-toggle="tab">{{ Lang::line('menus::general.tabs.options') }}</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane {{ ($menu_slug) ? 'active' : null }}" id="menus-edit-children">

					<div class="clearfix">
						<a class="pull-right btn children-toggle-all">{{ Lang::line('menus::button.toggle_children_details') }} <i class="icon-edit"></i></a>
					</div>

					<div class="clearfix">

						<div class="platform-new-child">

							<div class="well">

								<fieldset>
									<legend>{{ Lang::line('menus::form.child.legend') }}</legend>
									<hr>

									<!-- Child Name -->
									<div>
										<input type="text" name="new_child_name" id="new-child-name" value="" placeholder="{{ Lang::line('menus::form.child.name') }}" required>
									</div>

									<!-- Slug -->
									<div>
										<input type="text" name="new_child_slug" id="new-child-slug" value="" placeholder="{{ Lang::line('menus::form.child.slug') }}" required>
									</div>

									<!-- URI -->
									<div>
										<input type="text" name="new_child_slug" id="new-child-uri" value="" placeholder="{{ Lang::line('menus::form.child.uri') }}">
									</div>

									<!-- Secure HTTPS -->
									<div>
										<p class="title">{{ Lang::line('menus::form.child.secure') }}</p>
										<div class="toggle basic success" data-enabled="ENABLED" data-disabled="DISABLED" data-toggle="toggle">
											<input type="checkbox" value="1" name="new_child_secure" id="new-child-secure" class="checkbox" />
											<label class="check" for="new_child_secure"></label>
										</div>
									</div>

									<!-- Target -->
									<div>
										<p class="title">{{ Lang::line('menus::form.child.target') }}</p>
										<div class="toggle basic success" data-enabled="ENABLED" data-disabled="DISABLED" data-toggle="toggle">
											<input type="checkbox" value="1" name="new_child_target" id="new-child-target" class="checkbox" />
											<label class="check" for="new_child_target"></label>
										</div>
									</div>

									<!-- Visability-->
									<div>
										{{ Form::label('new-child-type', Lang::line('menus::form.child.type')) }}
										{{ Form::select(null, array('0' => Lang::line('menus::form.child.show_always'), '1' => Lang::line('menus::form.child.logged_in'), '2' => Lang::line('menus::form.child.logged_out'), '3' => Lang::line('menus::form.child.admin')), '0', array('id' => 'new-child-type')) }}
									</div>

								</fieldset>
								<hr>
								<div class="actions">
									<button type="button" class="btn btn-large btn-primary children-add-new">{{ Lang::line('menus::button.add_child') }}</button>
								</div>

							</div>

						</div> <!-- /end - platform-new-child -->

						<ol class="platform-menu">
							@if (isset($menu['children']) and (is_array($menu['children'])))
								@foreach ($menu['children'] as $child)
									@render('menus::edit.child', array('child' => $child))
								@endforeach
							@endif
						</ol>

					</div>

				</div>
				<div class="tab-pane {{ ( ! $menu_slug) ? 'active' : null }}" id="menus-edit-menu-options">
					<fieldset>
						<div>
							{{ Form::label('menu-name', Lang::line('menus::form.child.name')) }}
							{{ Form::text('name', isset($menu['name']) ? $menu['name'] : null, array('id' => 'menu-name', 'placeholder' => Lang::line('menus::form.child.name'), (isset($menu['user_editable']) and ! $menu['user_editable']) ? 'disabled' : 'required')) }}
						</div>

						<div>
							{{ Form::label('menu-slug', Lang::line('menus::form.child.slug')) }}
							{{ Form::text('slug', isset($menu['slug']) ? $menu['slug'] : null, array('id' => 'menu-slug', 'placeholder' => Lang::line('menus::form.child.slug'), (isset($menu['user_editable']) and ! $menu['user_editable']) ? 'disabled' : 'required')) }}
						</div>
					</fieldset>

				</div>
			</div>
		</div>

		<div class="form-actions">

			<button type="submit" class="btn btn-primary btn-save-menu">
				{{ Lang::line('button.'.(($menu_slug) ? 'update' : 'create')) }}
			</button>

			{{ HTML::link_to_secure(ADMIN.'/menus', Lang::line('button.cancel'), array('class' => 'btn')) }}

		</div>

	</form>

</section>
@endsection
