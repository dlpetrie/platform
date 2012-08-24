@layout('templates.default')

<!-- Page Title -->
@section('title')
	{{ Lang::line('menus::general.title') }}
@endsection

<!-- Queue Styles -->
{{ Theme::queue_asset('menus', 'menus::css/menus.less', 'style') }}

<!-- Styles -->
@section ('styles')
@endsection

<!-- Queue Scripts -->
{{ Theme::queue_asset('jquery-helpers', 'js/jquery/helpers.js', 'jquery') }}
{{ Theme::queue_asset('bootstrap-tab', 'js/bootstrap/tab.js', 'jquery') }}
{{ Theme::queue_asset('bootstrap-toggle', 'js/bootstrap/toggle.js', 'jquery') }}
{{ Theme::queue_asset('jquery-ui', 'js/jquery/ui-1.8.18.min.js', 'jquery') }}
{{ Theme::queue_asset('jquery-nestedsortable', 'js/jquery/nestedsortable-1.3.5.js', 'jquery') }}
{{ Theme::queue_asset('tempo', 'js/tempo-1.8.min.js') }}
{{ Theme::queue_asset('jquery-nestysortable', 'js/jquery/nestysortable-1.0.js', 'jquery') }}
{{ Theme::queue_asset('menussortable', 'menus::js/menussortable-1.0.js', 'jquery') }}
{{ Theme::queue_asset('validate', 'js/validate.js', 'jquery') }}

<!-- Scripts -->
@section('scripts')
<script>

function dump(arr,level) {
	var dumped_text = "";
	if(!level) level = 0;

	//The padding given at the beginning of the line.
	var level_padding = "";
	for(var j=0;j<level+1;j++) level_padding += "    ";

	if(typeof(arr) == 'object') { //Array/Hashes/Objects
		for(var item in arr) {
			var value = arr[item];

			if(typeof(value) == 'object') { //If it is an array,
				dumped_text += level_padding + "'" + item + "' ...\n";
				dumped_text += dump(value,level+1);
			} else {
				dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
			}
		}
	} else { //Stings/Chars/Numbers etc.
		dumped_text = "===>"+arr+"<===("+typeof(arr)+")";
	}
	return dumped_text;
}

	$(document).ready(function() {

		// Toggle Checkboxes.
		$('.toggle').toggle({
			style: {
				enabled: 'success',
				disabled: 'danger'
			}
		});

		// Menu sortable plugin
		$('#menu').menuSortable({

			// Array of ALL existing
			// slugs. Just so we don't
			// have any clashes
			persistedSlugs: {{ json_encode($persisted_slugs) }},

			// Define Nesty Sortable dependency for the menu sortable.
			nestySortable: {
				fields           : [
					{
						name        : 'name',
						newSelector : '#new-child-name'/*,
						inputType   : 'text'*/
					},
					{
						name        : 'slug',
						newSelector : '#new-child-slug'/*,
						inputType   : 'text'*/
					},
					{
						name        : 'uri',
						newSelector : '#new-child-uri'/*,
						inputType   : 'text'*/
					},
					{
						name        : 'secure',
						newSelector : '#new-child-secure'/*,
						inputType   : 'checkbox'*/
					},
					{
						name        : 'target',
						newSelector : '#new-child-target'/*,
						inputType   : 'select'*/
					},
					{
						name        : 'visibility',
						newSelector : '#new-child-visibility'/*,
						inputType   : 'select'*/
					}
				],
				lastItemId           : {{ $last_child_id }}
			}
		})
	});
</script>
@endsection

@section('content')

	<section id="menus-edit">


		<header class="head row">
			<div class="span4">
				<h1>{{ Lang::line('menus::general.update.title') }}</h1>
				<p>{{ Lang::line('menus::general.update.description') }}</p>
			</div>
		</header>

		<form method="POST" method="POST" accept-char="UTF-8" autocomplete="off" id="menu">

			<div class="tabbable">

				<ul class="nav nav-tabs">
					<li class="{{ ($menu_slug) ? 'active' : null }}">
						<a href="#menus-edit-children" data-toggle="tab">{{ Lang::line('menus::general.tabs.children') }}</a>
					</li>
					<li class="{{ ( ! $menu_slug) ? 'active' : null }}">
						<a href="#menus-edit-root" data-toggle="tab">{{ Lang::line('menus::general.tabs.root') }}</a>
					</li>
				</ul>

				<div class="tab-content">
					<div id="menus-edit-children" class="tab-pane {{ ($menu_slug) ? 'active' : null }}">

						<div class="row-fluid">
							<div class="span3" id="menu-new-child">

								<div class="well well-small">
									<fieldset>
										<legend>{{ Lang::line('menus::form.create.child.legend') }}</legend>

										<!-- Item Name -->
										<div class="control-group">
											<input type="text" id="new-child-name" class="input-block-level" value="" placeholder="{{ Lang::line('menus::form.child.name') }}" required>
										</div>

										<!-- Slug -->
										<div class="control-group">
											<input type="text" id="new-child-slug" class="input-block-level" value="" placeholder="{{ Lang::line('menus::form.child.slug') }}" required>
										</div>

										<!-- URI -->
										<div class="control-group">
											<input type="text" id="new-child-uri" class="input-block-level" value="" placeholder="{{ Lang::line('menus::form.child.uri') }}">
										</div>

										<!-- Secure -->
										<div class="control-group">
											<label>
												{{ Lang::line('menus::form.child.secure') }}
											</label>
											<div class="toggle basic success" data-enabled="{{ Lang::line('general.enabled') }}" data-disabled="{{ Lang::line('general.disabled') }}" data-toggle="toggle">
												<input type="checkbox" value="1" id="new-child-secure" class="checkbox">
												<label class="check" for="new-child-secure"></label>
											</div>
										</div>

										<!-- Target -->
										<div class="control-group">
											<label>{{ Lang::line('menus::form.child.target.title') }}</label>
											<select id="new-child-target" class="input-block-level">
												<option value="{{ Platform\Menus\Menu::TARGET_SELF }}" selected>{{ Lang::line('menus::form.child.target.self') }}</option>
												<option value="{{ Platform\Menus\Menu::TARGET_BLANK }}">{{ Lang::line('menus::form.child.target.blank') }}</option>
												<option value="{{ Platform\Menus\Menu::TARGET_PARENT }}">{{ Lang::line('menus::form.child.target.parent') }}</option>
												<option value="{{ Platform\Menus\Menu::TARGET_TOP }}">{{ Lang::line('menus::form.child.target.top') }}</option>
											</select>
										</div>

										<!-- Visibility -->
										<div class="control-group">
											<label for="new-child-visibility">{{ Lang::line('menus::form.child.visibility.title') }}</label>
											<select id="new-child-visibility" class="input-block-level">
												<option value="{{ Platform\Menus\Menu::VISIBILITY_ALWAYS }}" selected>{{ Lang::line('menus::form.child.visibility.always') }}</option>
												<option value="{{ Platform\Menus\Menu::VISIBILITY_LOGGED_IN }}">{{ Lang::line('menus::form.child.visibility.logged_in') }}</option>
												<option value="{{ Platform\Menus\Menu::VISIBILITY_LOGGED_OUT }}">{{ Lang::line('menus::form.child.visibility.logged_out') }}</option>
												<option value="{{ Platform\Menus\Menu::VISIBILITY_ADMIN }}">{{ Lang::line('menus::form.child.visibility.admin') }}</option>
											</select>
										</div>

										<div class="form-actions">
											<button type="button" class="btn btn-primary children-add-new">
												{{ Lang::line('menus::button.add_child') }}
											</button>
										</div>

									</fieldset>
								</div>

							</div>
							<!-- /end - menu-new-child -->

							<div class="span9">

								<ol class="menu-children">
									@if (isset($menu['children']))
										@foreach ($menu['children'] as $child)
											@render('menus::edit.child', array('child' => $child))
										@endforeach
									@endif
								</ol>

								<div class="new-child-template-container hide">
									<ul class="new-child-template">
										@render('menus::edit.child', array('child' => array(), 'template' => true))
									</ul>
								</div>

							</div>
						</div>

					</div>
					<div id="menus-edit-root" class="tab-pane {{ ( ! $menu_slug) ? 'active' : null }}">

						<br>

						<fieldset class="form-horizontal">

							<div class="control-group">
								<label class="control-label" for="menu-name">
									{{ Lang::line('menus::form.root.name') }}
								</label>
								<div class="controls">
									<input type="text" name="name" id="menu-name" value="{{ array_get($menu, 'name') }}" {{ (array_key_exists('user_editable', $menu) and ( ! array_get($menu, 'user_editable'))) ? 'disabled' : 'required'}}>
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="menu-slug">
									{{ Lang::line('menus::form.root.slug') }}
								</label>
								<div class="controls">
									<input type="text" name="slug" id="menu-slug" value="{{ array_get($menu, 'slug') }}"  {{ (array_key_exists('user_editable', $menu) and ( ! array_get($menu, 'user_editable'))) ? 'disabled' : 'required'}}>
								</div>
							</div>

						</fieldset>

					</div>
				</div>

			</div>
			<!-- /end - tabbable -->

			<div class="form-actions">
				<button type="submit" class="btn btn-primary btn-save-menu">
					{{ Lang::line('button.'.(($menu_slug) ? 'update' : 'create')) }}
				</button>

				{{ HTML::link_to_secure(ADMIN.'/menus', Lang::line('button.cancel'), array('class' => 'btn')) }}
			</div>

		</form>



	</section>

@endsection
