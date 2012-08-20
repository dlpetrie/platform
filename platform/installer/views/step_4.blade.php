@layout('installer::template')

@section('navigation')
	<h1>Administration</h1>
	<p class="step">An account to rule them all</p>
	<div class="breadcrumbs">
		<ul class="nav">
			<ul class="nav">
			<li><span>{{ Lang::line('installer::general.step_1.step') }}</span> {{ Lang::line('installer::general.step_1.step_description') }}</li>
			<li><span>{{ Lang::line('installer::general.step_2.step') }}</span> {{ Lang::line('installer::general.step_2.step_description') }}</li>
			<li><span>{{ Lang::line('installer::general.step_3.step') }}</span> {{ Lang::line('installer::general.step_3.step_description') }}</li>
			<li class="active"><span>{{ Lang::line('installer::general.step_4.step') }}</span> {{ Lang::line('installer::general.step_4.step_description') }}</li>
		</ul>
	</div>
@endsection

@section('content')
<div class="grid contain">

	<h2>Installation Complete</h2>

	<p class="lead">Congratulations - you've installed Platform! <strong>But wait! Not so fast</strong>, we highly recommend you delete the following folders now that you have completed the installation process.</p>
	<pre><code>platform/installer/*</code></pre>
	<pre><code>public/installer/*</code></pre>

	<h2>Platform License Agreement</h2>
	<pre style="word-break: break-word;"><?=$license?></pre>
	<pre style="word-break: break-word;"><?=$laravel_license?></pre>

	<div class="actions">
		<p>
			<a href="{{ URL::base() }}" class="btn btn-large">I Agree, Continue to the Home Page</a>
		</p>
	</div>

</div>

@endsection
