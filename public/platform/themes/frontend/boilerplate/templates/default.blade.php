<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>@yield('title')</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <!-- Learn: documentation address -->
		{{ Theme::queue_asset('style', 'css/main.less') }}

		{{ Theme::release_assets('styles') }}

		<!-- Styles -->
		@yield('styles')

		<style>
            @widget('platform.themes::options.css')
        </style>

		{{ Theme::queue_asset('modernizr', 'js/vendor/modernizr-2.6.1-respond-1.1.0.min.js') }}

		<link rel="shortcut icon" href="{{ Theme::asset('img/favicon.png') }}">
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ Theme::asset('img/apple-touch-icon-144x144-precomposed.png') }}">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{ Theme::asset('img/apple-touch-icon-114x114-precomposed.png') }}">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ Theme::asset('img/apple-touch-icon-72x72-precomposed.png') }}">
		<link rel="apple-touch-icon-precomposed" href="{{ Theme::asset('img/apple-touch-icon-precomposed.png') }}">

    </head>
    <body>
		<!--[if lt IE 7]>
		<p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.</p>
		<![endif]-->

		<div class="navbar navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
					<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>
					<a class="brand" href="{{ URL::to('') }}">@get.settings.site.title</a>
					<div class="nav-collapse collapse">
						@widget('platform.menus::menus.nav', 'main', 1, 'nav')
					</div><!--/.nav-collapse -->
				</div>
			</div>
		</div>

		<div class="container">

			@widget('platform.application::messages.all')
			@yield('content')

			<hr>

			<footer>
				<p>Created, developed, and designed by @Cartalyst</p>
				<p>The BSD 3-Clause License - Copyright &copy; 2011-2012, Cartalyst LLC</p>
			</footer>

		</div> <!-- /container -->

	<!-- Placed at the end of the document so the pages load faster -->
	{{ Theme::queue_asset('jquery', 'js/vendor/jquery-1.8.2.min.js') }}
	{{ Theme::queue_asset('plugins', 'js/plugins.js') }}
	{{ Theme::queue_asset('main', 'js/main.js') }}

	{{ Theme::queue_asset('bootstrap-transition', 'js/bootstrap/transition.js') }}
	{{ Theme::queue_asset('bootstrap-alert', 'js/bootstrap/alert.js') }}
	{{ Theme::queue_asset('bootstrap-modal', 'js/bootstrap/modal.js') }}
	{{ Theme::queue_asset('bootstrap-dropdown', 'js/bootstrap/dropdown.js') }}
	{{ Theme::queue_asset('bootstrap-scrollspy', 'js/bootstrap/scrollspy.js') }}
	{{ Theme::queue_asset('bootstrap-tab', 'js/bootstrap/tab.js') }}
	{{ Theme::queue_asset('bootstrap-tooltip', 'js/bootstrap/tooltip.js') }}
	{{ Theme::queue_asset('bootstrap-popover', 'js/bootstrap/popover.js') }}
	{{ Theme::queue_asset('bootstrap-button', 'js/bootstrap/button.js') }}
	{{ Theme::queue_asset('bootstrap-collapse', 'js/bootstrap/collapse.js') }}
	{{ Theme::queue_asset('bootstrap-typeahead', 'js/bootstrap/typeahead.js') }}

	{{ Theme::release_assets('scripts') }}

	@yield('scripts')

    </body>
</html>
