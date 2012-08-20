@layout('templates.default')

<!-- Page Title -->
@section('title')
	{{ Lang::line('users::general.users.update.title') }}
@endsection

<!-- Queue Styles | e.g Theme::queue_asset('name', 'path_to_css', 'dependency')-->

<!-- Styles -->
@section ('styles')
@endsection

<!-- Queue Scripts -->
{{ Theme::queue_asset('validate', 'js/validate.js', 'jquery') }}
{{ Theme::queue_asset('bootstrap-tab','js/bootstrap/tab.js', 'jquery') }}

<!-- Scripts -->
@section('scripts')
<script>
	$(document).ready(function() {

		//Match Password
		var password = document.getElementById("password"),
			passwordConfirm = document.getElementById("password_confirmation");

		$('#password, #password_confirmation').keyup(function() {
			if(passwordConfirm.value !== password.value) {
				passwordConfirm.setCustomValidity("Your password doesn't match");
			} else {
				passwordConfirm.setCustomValidity("");
			}
		});

		Validate.setup($("#edit-form"));
	});
</script>
@endsection

<!-- Page Content -->
@section('content')
<section id="users-edit">

	<header class="head row">
		<div class="span4">
			<h1>{{ Lang::line('users::general.users.update.title') }}</h1>
			<p>{{ Lang::line('users::general.users.update.description') }}</p>
		</div>
	</header>

	<hr>

	<div class="tabbable">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#general" data-toggle="tab">{{ Lang::line('users::general.tabs.general') }}</a></li>
			<li><a href="#permissions" data-toggle="tab">{{ Lang::line('users::general.tabs.permissions') }}</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="general">
				@widget('platform.users::admin.user.form.edit', $id)
			</div>
			<div class="tab-pane" id="permissions">
				@widget('platform.users::admin.user.form.permissions', $id)
			</div>
		</div>
	</div>
</section>
@endsection
