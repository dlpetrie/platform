@layout('installer::template')

@section('title')
{{ Lang::line('installer::general.title')->get() }} | {{ Lang::line('installer::general.step_1.title')->get() }}
@endsection

@section('navigation')
	<h1>{{ Lang::line('installer::general.step_1.title') }}</h1>
	<p class="step">{{ Lang::line('installer::general.step_1.tagline') }}</p>
	<div class="breadcrumbs">
		<ul class="nav">
			<ul class="nav">
			<li class="active"><span>{{ Lang::line('installer::general.step_1.step') }}</span> {{ Lang::line('installer::general.step_1.step_description') }}</li>
			<li><span>{{ Lang::line('installer::general.step_2.step') }}</span> {{ Lang::line('installer::general.step_2.step_description') }}</li>
			<li><span>{{ Lang::line('installer::general.step_3.step') }}</span> {{ Lang::line('installer::general.step_3.step_description') }}</li>
			<li><span>{{ Lang::line('installer::general.step_4.step') }}</span> {{ Lang::line('installer::general.step_4.step_description') }}</li>
		</ul>
	</div>
@endsection

@section('content')
<div class="contain">

	<div id="native">
		<h2>{{ Lang::line('installer::general.step_1.description') }}</h2>
		<hr>
		<div class="permissions" id="permissions-pass">
		<div data-template>
			<code class="alert alert-success">[%.%]</code>
		</div>
		</div>
		<div class="permissions" id="permissions-fail">
			<div data-template>
				<code class="alert alert-error">[%.%]</code>
			</div>
		</div>
	</div>
	<hr>
	<form id="filesystem-form" class="form-horizontal" method="POST" accept-char="UTF-8">
		<input type="hidden" name="{{ Session::csrf_token }}" value="{{ Session::token() }}">

		<!-- FTP Credentials -->
		<div id="ftp">
			<fieldset>
				<legend>FTP Credentials</legend>
				<p>While using FTP is optional, it is highly recommended.  Using FTP for the filesystem will help prevent permission issues with creating and writing to directories and files.  If FTP fails it will fallback to the native PHP driver.</p>
				<div>
					<label for="ftp_enable">Use FTP</label>
					<input type="checkbox" name="ftp_enable" id="ftp_enable" value="1">&nbsp;enable
				</div>
				<div>
					<label for="server">Server</label>
					<input type="text" name="ftp_server" id="ftp_server" placeholder="server">
				</div>
				<div>
					<label for="user">User</label>
					<input type="text" name="ftp_user" id="ftp_user" placeholder="user">
				</div>
				<div>
					<label for="password">Password</label>
					<input type="password" name="ftp_password" id="ftp_password" placeholder="password">
				</div>
				<div>
					<label for="port">Port</label>
					<input type="text" name="ftp_port" id="ftp_port" value="21" placeholder="port">
				</div>
				<div>
					<label for=""></label>
					<a href="{{ URL::to('installer/ftp_test') }}" class="btn btn-medium" id="ftp-test">Test FTP</a> <span id="ftp-status"></span>
				</div>
			</fieldset>
		</div>

		<!-- Form Actions -->
		<div class="actions">
			<button type="submit" id="continue-btn" class="btn btn-large {{ (count($permissions['fail']) > 0) ? 'disabled' : null }}">
				{{ Lang::line('installer::button.next') }}
			</button>
		</div>
	</form>

</div>
@endsection
