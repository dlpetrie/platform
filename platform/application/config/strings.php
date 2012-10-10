
RAWFORKNEW
<?php
/**
 * Part of the Platform application.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.  It is also available at
 * the following URL: http://www.opensource.org/licenses/BSD-3-Clause
 *
 * @package    Platform
 * @version    1.0.3
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011 - 2012, Cartalyst LLC
 * @link       http://cartalyst.com
 */
/*
 * --------------------------------------------------------------------------
 * Return the configuration.
 * --------------------------------------------------------------------------
 */
return array(
	/*
	 * --------------------------------------------------------------------------
	 * String Inflection
	 * --------------------------------------------------------------------------
	 *
	 * This array contains the singular and plural forms of words. It's used by
	 * the "singular" and "plural" methods on the Str class to convert a given
	 * word from singular to plural and vice versa.
	 *
	 * Note that the regular expressions are only for inflecting English words.
	 * To inflect a non-English string, simply add its singular and plural
	 * form to the array of "irregular" word forms.
	 *
	 */
	'plural' => array(
		'/(quiz)$/i' => "$1zes",
		'/^(ox)$/i' => "$1en",
		'/([m|l])ouse$/i' => "$1ice",
		'/(matr|vert|ind)ix|ex$/i' => "$1ices",
		'/(x|ch|ss|sh)$/i' => "$1es",
		'/([^aeiouy]|qu)y$/i' => "$1ies",
		'/(hive)$/i' => "$1s",
		'/(?:([^f])fe|([lr])f)$/i' => "$1$2ves",
		'/(shea|lea|loa|thie)f$/i' => "$1ves",
		'/sis$/i' => "ses",
		'/([ti])um$/i' => "$1a",
		'/(tomat|potat|ech|her|vet)o$/i' => "$1oes",
		'/(bu)s$/i' => "$1ses",
		'/(alias)$/i' => "$1es",
		'/(octop)us$/i' => "$1i",
		'/(ax|test)is$/i' => "$1es",
		'/(us)$/i' => "$1es",
		'/s$/i' => "s",
		'/$/' => "s"
	),
	'singular' => array(
		'/(quiz)zes$/i' => "$1",
		'/(matr)ices$/i' => "$1ix",
		'/(vert|ind)ices$/i' => "$1ex",
		'/^(ox)en$/i' => "$1",
		'/(alias)es$/i' => "$1",
		'/(octop|vir)i$/i' => "$1us",
		'/(cris|ax|test)es$/i' => "$1is",
		'/(shoe)s$/i' => "$1",
		'/(o)es$/i' => "$1",
		'/(bus)es$/i' => "$1",
		'/([m|l])ice$/i' => "$1ouse",
		'/(x|ch|ss|sh)es$/i' => "$1",
		'/(m)ovies$/i' => "$1ovie",
		'/(s)eries$/i' => "$1eries",
		'/([^aeiouy]|qu)ies$/i' => "$1y",
		'/([lr])ves$/i' => "$1f",
		'/(tive)s$/i' => "$1",
		'/(hive)s$/i' => "$1",
		'/(li|wi|kni)ves$/i' => "$1fe",
		'/(shea|loa|lea|thie)ves$/i' => "$1f",
		'/(^analy)ses$/i' => "$1sis",
		'/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/i' => "$1$2sis",
		'/([ti])a$/i' => "$1um",
		'/(n)ews$/i' => "$1ews",
		'/(h|bl)ouses$/i' => "$1ouse",
		'/(corpse)s$/i' => "$1",
		'/(us)es$/i' => "$1",
		'/(us|ss)$/i' => "$1",
		'/s$/i' => ""
	),
	'irregular' => array(
		'child'  => 'children',
		'foot'   => 'feet',
		'goose'  => 'geese',
		'man'    => 'men',
		'move'   => 'moves',
		'person' => 'people',
		'sex'    => 'sexes',
		'tooth'  => 'teeth'
	),
	'uncountable' => array(
		'audio',
		'equipment',
		'deer',
		'fish',
		'gold',
		'information',
		'money',
		'rice',
		'police',
		'series',
		'sheep',
		'species'
	),
	/*
	 * --------------------------------------------------------------------------
	 * ASCII Characters
	 * --------------------------------------------------------------------------
	 *
	 * This array contains foreign characters and their 7-bit ASCII equivalents.
	 * The array is used by the "ascii" method on the Str class to get strings
	 * ready for inclusion in a URL slug.
	 *
	 * Of course, the "ascii" method may also be used by you for whatever your
	 * application requires. Feel free to add any characters we missed, and be
	 * sure to let us know about them!
	 *
	 */
	'ascii' => array(
		'/Ã¦|Ç½/' => 'ae',
		'/Å�/' => 'oe',
		'/Ã�|Ã�|Ã�|Ã�|Ã�|Ã�|Çº|Ä�|Ä�|Ä�|Ç�|Ð�/' => 'A',
		'/Ã |Ã¡|Ã¢|Ã£|Ã¤|Ã¥|Ç»|Ä�|Ä�|Ä�|Ç�|Âª|Ð°/' => 'a',
		'/Ð�/' => 'B',
		'/Ð±/' => 'b',
		'/Ã�|Ä�|Ä�|Ä�|Ä�|Ð¦/' => 'C',
		'/Ã§|Ä�|Ä�|Ä�|Ä�|Ñ�/' => 'c',
		'/Ã�|Ä�|Ä�|Ð�/' => 'Dj',
		'/Ã°|Ä�|Ä�|Ð´/' => 'dj',
		'/Ã�|Ã�|Ã�|Ã�|Ä�|Ä�|Ä�|Ä�|Ä�|Ð�|Ð�|Ð­/' => 'E',
		'/Ã¨|Ã©|Ãª|Ã«|Ä�|Ä�|Ä�|Ä�|Ä�|Ðµ|Ñ�|Ñ�/' => 'e',
		'/Ð¤/' => 'F',
		'/Æ�|Ñ�/' => 'f',
		'/Ä�|Ä�|Ä |Ä¢|Ð�/' => 'G',
		'/Ä�|Ä�|Ä¡|Ä£|Ð³/' => 'g',
		'/Ä¤|Ä¦|Ð¥/' => 'H',
		'/Ä¥|Ä§|Ñ�/' => 'h',
		'/Ã�|Ã�|Ã�|Ã�|Ä¨|Äª|Ä¬|Ç�|Ä®|Ä°|Ð�/' => 'I',
		'/Ã¬|Ã­|Ã®|Ã¯|Ä©|Ä«|Ä­|Ç�|Ä¯|Ä±|Ð¸/' => 'i',
		'/Ä´|Ð�/' => 'J',
		'/Äµ|Ð¹/' => 'j',
		'/Ä¶|Ð�/' => 'K',
		'/Ä·|Ðº/' => 'k',
		'/Ä¹|Ä»|Ä½|Ä¿|Å�|Ð�/' => 'L',
		'/Äº|Ä¼|Ä¾|Å�|Å�|Ð»/' => 'l',
		'/Ð�/' => 'M',
		'/Ð¼/' => 'm',
		'/Ã�|Å�|Å�|Å�|Ð�/' => 'N',
		'/Ã±|Å�|Å�|Å�|Å�|Ð½/' => 'n',
		'/Ã�|Ã�|Ã�|Ã�|Ã�|Å�|Å�|Ç�|Å�|Æ |Ã�|Ç¾|Ð�/' => 'O',
		'/Ã¶|Ã²|Ã³|Ã´|Ãµ|Å�|Å�|Ç�|Å�|Æ¡|Ã¸|Ç¿|Âº|Ð¾/' => 'o',
		'/Ð�/' => 'P',
		'/Ð¿/' => 'p',
		'/Å�|Å�|Å�|Ð /' => 'R',
		'/Å�|Å�|Å�|Ñ�/' => 'r',
		'/Å�|Å�|Å�|È�|Å |Ð¡/' => 'S',
		'/Å�|Å�|Å�|È�|Å¡|Å¿|Ñ�/' => 's',
		'/Å¢|È�|Å¤|Å¦|Ð¢/' => 'T',
		'/Å£|È�|Å¥|Å§|Ñ�/' => 't',
		'/Ã�|Ã�|Ã�|Å¨|Åª|Å¬|Å®|Ã�|Å°|Å²|Æ¯|Ç�|Ç�|Ç�|Ç�|Ç�|Ð£/' => 'U',
		'/Ã¹|Ãº|Ã»|Å©|Å«|Å­|Å¯|Ã¼|Å±|Å³|Æ°|Ç�|Ç�|Ç�|Ç�|Ç�|Ñ�/' => 'u',
		'/Ð�/' => 'V',
		'/Ð²/' => 'v',
		'/Ã�|Å¸|Å¶|Ð«/' => 'Y',
		'/Ã½|Ã¿|Å·|Ñ�/' => 'y',
		'/Å´/' => 'W',
		'/Åµ/' => 'w',
		'/Å¹|Å»|Å½|Ð�/' => 'Z',
		'/Åº|Å¼|Å¾|Ð·/' => 'z',
		'/Ã�|Ç¼/' => 'AE',
		'/Ã�/'=> 'ss',
		'/Ä²/' => 'IJ',
		'/Ä³/' => 'ij',
		'/Å�/' => 'OE',
		'/Ð§/' => 'Ch',
		'/Ñ�/' => 'ch',
		'/Ð®/' => 'Ju',
		'/Ñ�/' => 'ju',
		'/Ð¯/' => 'Ja',
		'/Ñ�/' => 'ja',
		'/Ð¨/' => 'Sh',
		'/Ñ�/' => 'sh',
		'/Ð©/' => 'Shch',
		'/Ñ�/' => 'shch',
		'/Ð�/' => 'Zh',
		'/Ð¶/' => 'zh'
	)
);
