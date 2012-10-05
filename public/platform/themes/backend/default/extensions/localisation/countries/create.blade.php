@layout('templates.default')

@section('title')
	{{ Lang::line('localisation::countries/general.title')->get() }}
@endsection

@section('content')
<section id="countries">
	<header class="head row-fluid">
		<div class="span6">
			<h1>{{ Lang::line('localisation::countries/general.title') }}</h1>
			<p>{{ Lang::line('localisation::countries/general.description.create') }}</p>
		</div>
		<nav class="tertiary-navigation span6">
			@widget('platform.menus::menus.nav', 2, 1, 'nav nav-pills pull-right', ADMIN)
		</nav>
	</header>

	<hr />

	<form action="{{ URL::to_admin('localisation/countries/create/') }}" id="edit-form" class="form-horizontal" method="POST" accept-char="UTF-8">
		<input type="hidden" name="{{ Session::csrf_token }}" value="{{ Session::token() }}">
		<fieldset>
			<div class="control-group">
				<label class="control-label" for="name">* Name:</label>
				<div class="controls">
					<input type="text" name="name" id="name" value="{{ Input::old('name'); }}" placeholder="* Country Name" required>
					<span class="help-block"></span>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="iso_code_2">* ISO Code 2:</label>
				<div class="controls">
					<input type="text" name="iso_code_2" id="iso_code_2" value="{{ Input::old('iso_code_2'); }}" placeholder="* ISO Code 2" required>
					<span class="help-block"></span>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="iso_code_3">* ISO Code 3:</label>
				<div class="controls">
					<input type="text" name="iso_code_3" id="iso_code_3" value="{{ Input::old('iso_code_3'); }}" placeholder="* ISO Code 3" required>
					<span class="help-block"></span>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="iso_code_numeric_3">* ISO Code Num. 3:</label>
				<div class="controls">
					<input type="text" name="iso_code_numeric_3" id="iso_code_numeric_3" value="{{ Input::old('iso_code_numeric_3'); }}" placeholder="* ISO Code Numeric 3" required>
					<span class="help-block"></span>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="region">* Region:</label>
				<div class="controls">
					<input type="text" name="region" id="region" value="{{ Input::old('region'); }}" placeholder="* Region">
					<span class="help-block"></span>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="subregion">* Sub Region:</label>
				<div class="controls">
					<input type="text" name="subregion" id="subregion" value="{{ Input::old('subregion'); }}" placeholder="* Sub Region">
					<span class="help-block"></span>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="status">* Status:</label>
				<div class="controls">
					<select name="status" id="status">
						<option value="1">Enabled</option>
						<option value="0">Disabled</option>
					</select>
					<span class="help-block"></span>
				</div>
			</div>
		</fieldset>

		<hr />

		<div class="form-actions">
			<a class="btn btn-large" href="{{ URL::to_admin('localisation/countries') }}">{{ Lang::line('button.cancel') }}</a>
			<button class="btn btn-large btn-primary" type="submit" name="save" id="save" value="1">{{ Lang::line('button.update') }}</button>
			<button class="btn btn-large btn-primary" type="submit" name="save_exit" id="save_exit" value="1">{{ Lang::line('button.update_exit') }}</button>
		</div>
	</form>
</section>
@endsection