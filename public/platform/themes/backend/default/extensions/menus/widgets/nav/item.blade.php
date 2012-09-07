<li class="{{ in_array($item['id'], $active_path) ? 'active' : null }}">

	@if (URL::valid($item['uri']))
		<a href="{{ $item['uri'] }}"}}
	@else
		<a href="{{ URL::to(($before_uri ? $before_uri.'/' : null).$item['uri'], $item['secure']) }}" target="{{ ($item['target'] == 0) ? '_self' : '_blank' }}">
	@endif

		@if ($item['class'])
			<i class="{{ $item['class'] }}"></i>
		@endif
		<span>
			{{ $item['name'] }}
		</span>

	</a>

	@if ($item['children'])
		<ul>
			@foreach ($item['children'] as $child)
				@render('menus::widgets.nav.item', array('item' => $child, 'active_path' => $active_path, 'before_uri' => $before_uri))
			@endforeach
		</ul>
	@endif
</li>
