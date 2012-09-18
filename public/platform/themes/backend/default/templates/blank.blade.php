<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html lang="en">
<!--<![endif]-->
<head>

	<!-- Basic Page Needs -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="description" content="@get.settings.site.tagline">
	<meta name="author" content="Cartalyst LLC">
	<meta name="base_url" content="{{ Platform::url() }}">
	<meta name="admin_url" content="{{ Platform::url(ADMIN) }}">

	<!-- Page Title -->
	<title>
		@yield('title')
	</title>

	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- Mobile Specific Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Queue Styles -->
	{{ Theme::queue_asset('style', 'css/style.less') }}

	<!-- Release Styles -->
	{{ Theme::release_assets('styles') }}

	<!-- Styles -->
	@yield('styles')

	<!-- Apply Style Options -->
	@widget('platform.themes::options.css')

	<link rel="shortcut icon" href="{{ Theme::asset('img/favicon.png') }}">
	<link rel="apple-touch-icon-precomposed" href="{{ Theme::asset('img/apple-touch-icon-precomposed.png') }}">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ Theme::asset('img/apple-touch-icon-72x72-precomposed.png') }}">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{ Theme::asset('img/apple-touch-icon-114x114-precomposed.png') }}">

</head>
<body>

<!-- Queue Scripts -->
{{ Theme::queue_asset('jquery', 'js/jquery-1.8.1.min.js') }}
{{ Theme::queue_asset('admin', 'js/admin.js') }}
{{ Theme::queue_asset('url', 'js/url.js') }}

<!-- Release Scripts -->
{{ Theme::release_assets('scripts') }}

<!-- Scripts -->
@yield('scripts')

</body>
</html>
