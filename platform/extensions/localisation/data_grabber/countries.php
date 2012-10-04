$txt = file_get_contents('countries.txt');
$teste = explode("\n", $txt);

foreach ( $teste as $test ):
	@list($country_name, $alt_spell, $cca2, $cca3, $ccn3, $crap1, $crap2, $region, $subregion, $cdh_id, $ss, $dd) = explode(';', $test);

	$countries[ trim( $cca2 ) ] = array(
		'country_name' => trim( $country_name ),
		'cca2' => trim( $cca2 ),
		'cca3' => trim( $cca3 ),
		'ccn3' => trim( $ccn3 ),
		'region' => trim( $region ),
		'subregion' => trim( $subregion ),
		'cdh_id' => trim( $cdh_id ),
	);

endforeach;