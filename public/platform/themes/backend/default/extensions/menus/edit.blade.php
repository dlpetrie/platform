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

		// Toggle Checkboxes.
		$('.toggle').toggle({
			style: {
				enabled: 'success',
				disabled: 'danger'
			}
		});
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
				<div class="tab-pane {{ ($menu_slug) ? 'active' : null }}">
					
					<div class="row-fluid">
						<div class="span3" id="menu-new-child">
							
							<div class="well well-small form-horizontal">
								<fieldset>
									<legend>{{ Lang::line('menus::form.child.legend') }}</legend>

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
										<input type="text" name="new_child_slug" id="new-child-uri" class="input-block-level" value="" placeholder="{{ Lang::line('menus::form.child.uri') }}">
									</div>

									<div class="control-group">
										<label>
											{{ Lang::line('menus::form.child.secure') }}
										</label>
										<div class="toggle basic success" data-enabled="{{ Lang::line('general.enabled') }}" data-disabled="{{ Lang::line('general.disabled') }}" data-toggle="toggle">
											<input type="checkbox" value="1" id="new-child-secure" class="checkbox">
											<label class="check" for="new-child-secure"></label>
										</div>
									</div>

									<div class="control-group">
										<label for="new-child-visibility">{{ Lang::line('menus::form.child.visibility.title') }}</label>
										<select class="input-block-level">
											<option value="{{ Platform\Menus\Menu::VISIBILITY_ALWAYS }}" selected>{{ Lang::line('menus::form.child.visibility.always') }}</option>
											<option value="{{ Platform\Menus\Menu::VISIBILITY_LOGGED_IN }}">{{ Lang::line('menus::form.child.visibility.logged_in') }}</option>
											<option value="{{ Platform\Menus\Menu::VISIBILITY_LOGGED_OUT }}">{{ Lang::line('menus::form.child.visibility.logged_out') }}</option>
											<option value="{{ Platform\Menus\Menu::VISIBILITY_ADMIN }}">{{ Lang::line('menus::form.child.visibility.admin') }}</option>
										</select>
									</div>

								</fieldset>
							</div>

						</div>
						<div class="span9">
							sdf
						</div>
					</div>

				</div>
				<div class="tab-pane {{ ( ! $menu_slug) ? 'active' : null }}">
					Asdf
				</div>
			</div>

		</div>

	</section>

@endsection