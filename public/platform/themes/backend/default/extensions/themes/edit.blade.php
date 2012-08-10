@layout('templates.default')

<!-- Page Title -->
@section('title')
	{{ Lang::line('themes::themes.title') }}
@endsection

<!-- Queue Styles -->
{{ Theme::queue_asset('themes','themes::css/themes.less', 'style') }}

<!-- Styles -->
@section ('styles')
@endsection

<!-- Queue Scripts -->
{{ Theme::queue_asset('themes','themes::js/themes.js', 'jquery') }}

<!-- Scripts -->
@section('scripts')
@endsection

<!-- Page Content -->
@section('content')
<section id="themes">

	<header class="row">
			<div class="span6">
				<h1>{{ $theme['name'] }}</h1>
				<p>{{ $theme['description'] }} by {{ $theme['author'] }} v{{ $theme['version'] }}</p>
			</div>
			<nav class="actions span6 pull-right">
			</nav>
	</header>

	<hr>

	<div class="theme row">
		<div class="span12">

			@if (count($theme['options']))
				{{ Form::open(null, 'POST', array('id' => 'theme-options', 'class' => 'form-horizontal')) }}

					{{ Form::token() }}

					<div class="well">
						@foreach ($theme['options'] as $id => $option)
						<fieldset>
							<legend>{{ $option['text'] }}</legend>
							@foreach ($option['styles'] as $style => $value)
								<label>{{ $style }}</label>
								<input type="text" name="options[{{$id}}][styles][{{$style}}]" value="{{ $value }}">
							@endforeach
						</fieldset>
						@endforeach
					</div>

		            <button class="btn btn-large btn-primary" type="submit">
		            	{{ Lang::line('buttons.update') }}
		            </button>
		            <a class="btn btn-large" href="{{ URL::to_secure(ADMIN.'/themes/'.$type) }}">{{ Lang::line('buttons.cancel') }}</a>

				{{ Form::close() }}

			@else

			<div class="unavailable">
				{{ Lang::line('themes::themes.message.no_options') }}
			</div>

			@endif

		</div>
	</div>

</section>

@endsection
