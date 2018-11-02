<?php
/**********************************************************************************
* Subs-BBCode-Language.php
***********************************************************************************
* This mod is licensed under the 2-clause BSD License, which can be found here:
*	http://opensource.org/licenses/BSD-2-Clause
***********************************************************************************
* This program is distributed in the hope that it is and will be useful, but*
* WITHOUT ANY WARRANTIES; without even any implied warranty of MERCHANTABILITY*
* or FITNESS FOR A PARTICULAR PURPOSE." => "" => "" => "" => "*
**********************************************************************************/
if (!defined('SMF'))
	die('Hacking attempt...');

//=================================================================================
// Admin hook functions to add the options and bbcode to the forum:
//=================================================================================
function SOSL_BBCode(&$bbc)
{
	global $user_info, $txt;

	// Format: [language=x]{parsed text}[/language]
	$bbc[] = array(
		'tag' => 'language',
		'type' => 'unparsed_commas',
		'before' => '<div class="SOSL_$1">',
		'after' => '</div>',
		'trim' => 'outside',
		'block_level' => true,
	);
	// Format: [language]{parsed text}[/language]
	$bbc[] = array(
		'tag' => 'language',
		'before' => '<div class="SOSL_' . strtolower($txt["lang_dictionary"]) . '">',
		'after' => '',
		'trim' => 'outside',
		'block_level' => true,
	);
}

function SOSL_Settings(&$config_vars)
{
	$config_vars[] = '';
	$config_vars[] = array('check', 'SOSL_installed');
	$config_vars[] = array('check', 'SOSL_no_sublangs');
	$config_vars[] = array('check', 'SOSL_natural');
}

//=================================================================================
// Function to determine which language DIV to show to the user:
//=================================================================================
function SOSL_prepareDisplayContext(&$output, &$message)
{
	global $context, $user_info, $txt;
	$prime_lang = $txt['lang_locale'];
	$alt_lang = $txt['lang_dictionary'];

	// Find all language bbcodes in the post's message:
	$output['SOSL_lang'] = array();
	$pattern = '#\<div class="SOSL_([\w\-]+?)"#i' . ($u = ($context['utf8'] ? 'u' : ''));
	if (preg_match_all($pattern, $output['body'], $matches, PREG_PATTERN_ORDER))
	{
		// Translate all natural language strings into RFC-1766 IDs:
		$matches = array_unique($matches[1]);
		foreach ($matches as $match)
			$output['body'] = str_replace('<div class="SOSL_' . $match . '"', '<div class="SOSL_' . SOSL_langName(false, $match) . '"', $output['body']);

		// Determine the unique language codes used:
		preg_match_all($pattern, $output['body'], $matches, PREG_PATTERN_ORDER);
		$matches = array_unique($matches[1]);
		$first_lang = $matches[0];
		foreach ($matches as $match)
			$first_lang = ($match == $prime_lang || substr($match, 0, 2) == $alt_lang ?  $match : $first_lang);
		$output['SOSL_lang'] = $matches;
		$output['SOSL_primary'] = $first_lang;

		// Replace the "SOSL" with "SOSL_<post_id>" and hide all non-selected languages:
		foreach ($matches as $lang)
			$output['body'] = str_replace('<div class="SOSL_' . $lang . '"', '<div class="SOSL_' . $output['id'] . '_' . $lang . '"' . ($first_lang == $lang ? '' : ' style="display: none;"'), $output['body']);
		$message['body'] = $output['body'];
	}
	return $output;
}

//=================================================================================
// Function that returns language names for RFC 1766 language codes given:
//=================================================================================
function SOSL_langName($lang = false, $trans = false)
{
	global $smcFunc, $modSettings, $sourcedir;
	static $codes = false, $natural = false, $cached = false;

	if (empty($codes))
	{
		// Our massive "RFC-1766 => Language string" array:
		$codes = array(
			'en' => 'English',
			'en-au' => 'English (Australia)',
			'en-bz' => 'English (Belize)',
			'en-ca' => 'English (Canada)',
			'en-gb' => 'English (United Kingdom)',
			'en-ie' => 'English (Ireland)',
			'en-jm' => 'English (Jamaica)',
			'en-nz' => 'English (New Zealand)',
			'en-ph' => 'English (Philippines)',
			'en-tt' => 'English (Trinidad)',
			'en-us' => 'English (United States)',
			'en-za' => 'English (South Africa)',
			'en-zw' => 'English (Zimbabwe)',
			'af' => 'Afrikaans',
			'ar' => 'Arabic',
			'ar-ae' => 'Arabic (U.A.E.)',
			'ar-bh' => 'Arabic (Kingdom of Bahrain)',
			'ar-dz' => 'Arabic (Algeria)',
			'ar-eg' => 'Arabic (Egypt)',
			'ar-iq' => 'Arabic (Iraq)',
			'ar-jo' => 'Arabic (Jordan)',
			'ar-kw' => 'Arabic (Kuwait)',
			'ar-lb' => 'Arabic (Lebanon)',
			'ar-ly' => 'Arabic (Libya)',
			'ar-ma' => 'Arabic (Morocco)',
			'ar-om' => 'Arabic (Oman)',
			'ar-qa' => 'Arabic (Qatar)',
			'ar-sa' => 'Arabic (Saudi Arabia)',
			'ar-sy' => 'Arabic (Syria)',
			'ar-tn' => 'Arabic (Tunisia)',
			'ar-ye' => 'Arabic (Yemen)',
			'as' => 'Assamese',
			'az' => 'Azerbaijani',
			'be' => 'Belarusian',
			'bg' => 'Bulgarian',
			'bn' => 'Bangla',
			'ca' => 'Catalan',
			'cs' => 'Czech',
			'da' => 'Danish',
			'de' => 'German (Germany)',
			'de-at' => 'German (Austria)',
			'de-ch' => 'German (Switzerland)',
			'de-li' => 'German (Liechtenstein)',
			'de-lu' => 'German (Luxembourg)',
			'div' => 'Divehi',
			'el' => 'Greek',
			'es' => 'Spanish',
			'es-ar' => 'Spanish (Argentina)',
			'es-bo' => 'Spanish (Bolivia)',
			'es-cl' => 'Spanish (Chile)',
			'es-co' => 'Spanish (Colombia)',
			'es-cr' => 'Spanish (Costa Rica)',
			'es-do' => 'Spanish (Dominican Republic)',
			'es-ec' => 'Spanish (Ecuador)',
			'es-gt' => 'Spanish (Guatemala)',
			'es-hn' => 'Spanish (Honduras)',
			'es-mx' => 'Spanish (Mexico)',
			'es-ni' => 'Spanish (Nicaragua)',
			'es-pa' => 'Spanish (Panama)',
			'es-pe' => 'Spanish (Peru)',
			'es-pr' => 'Spanish (Puerto Rico)',
			'es-py' => 'Spanish (Paraguay)',
			'es-sv' => 'Spanish (El Salvador)',
			'es-us' => 'Spanish (United States)',
			'es-uy' => 'Spanish (Uruguay)',
			'es-ve' => 'Spanish (Venezuela)',
			'et' => 'Estonian',
			'eu' => 'Basque (Basque)',
			'fa' => 'Persian',
			'fi' => 'Finnish',
			'fo' => 'Faeroese',
			'fr' => 'French (France)',
			'fr-be' => 'French (Belgium)',
			'fr-ca' => 'French (Canada)',
			'fr-ch' => 'French (Switzerland)',
			'fr-lu' => 'French (Luxembourg)',
			'fr-mc' => 'French (Monaco)',
			'gd' => 'Scottish Gaelic',
			'gl' => 'Galician',
			'gu' => 'Gujarati',
			'he' => 'Hebrew',
			'hi' => 'Hindi',
			'hr' => 'Croatian',
			'hu' => 'Hungarian',
			'hy' => 'Armenian',
			'id' => 'Indonesian',
			'is' => 'Icelandic',
			'it' => 'Italian (Italy)',
			'it-ch' => 'Italian (Switzerland)',
			'ja' => 'Japanese',
			'ka' => 'Georgian',
			'kk' => 'Kazakh',
			'kn' => 'Kannada',
			'ko' => 'Korean',
			'kok' => 'Konkani',
			'kz' => 'Kyrgyz',
			'lt' => 'Lithuanian',
			'lv' => 'Latvian',
			'mk' => 'Macedonian (FYROM)',
			'ml' => 'Malayalam',
			'mn' => 'Mongolian (Cyrillic)',
			'mr' => 'Marathi',
			'ms' => 'Malay',
			'mt' => 'Maltese',
			'nb-no' => 'Norwegian (Bokmal)',
			'ne' => 'Nepali (India)',
			'nl-be' => 'Dutch (Belgium)',
			'nl' => 'Dutch (Netherlands)',
			'nn-no' => 'Norwegian (Nynorsk)',
			'no' => 'Norwegian (Bokmal)',
			'or' => 'Odia',
			'pa' => 'Punjabi',
			'pl' => 'Polish',
			'pt' => 'Portuguese (Portugal)',
			'pt-br' => 'Portuguese (Brazil)',
			'rm' => 'Rhaeto-Romanic',
			'ro' => 'Romanian',
			'ro-md' => 'Romanian (Moldova)',
			'ru' => 'Russian',
			'ru-md' => 'Russian (Moldova)',
			'sa' => 'Sanskrit',
			'sb' => 'Sorbian',
			'sk' => 'Slovak',
			'sl' => 'Slovenian',
			'sq' => 'Albanian',
			'sr' => 'Serbian',
			'sv' => 'Swedish',
			'sv-fi' => 'Swedish (Finland)',
			'sw' => 'Swahili',
			'sx' => 'Sutu',
			'syr' => 'Syriac',
			'ta' => 'Tamil',
			'te' => 'Telugu',
			'th' => 'Thai',
			'tn' => 'Tswana',
			'tr' => 'Turkish',
			'ts' => 'Tsonga',
			'tt' => 'Tatar',
			'uk' => 'Ukrainian',
			'ur' => 'Urdu',
			'uz' => 'Uzbek',
			'vi' => 'Vietnamese',
			'xh' => 'Xhosa',
			'yi' => 'Yiddish',
			'zh' => 'Chinese',
			'zh-cn' => 'Chinese (China)',
			'zh-hk' => 'Chinese (Hong Kong SAR)',
			'zh-mo' => 'Chinese (Macao SAR)',
			'zh-sg' => 'Chinese (Singapore)',
			'zh-tw' => 'Chinese (Taiwan)',
			'zu' => 'Zulu',
		);
		$natural = array(
			'english' => 'en',
			'afrikaans' => 'af',
			'arabic' => 'ar',
			'assamese' => 'as',
			'azerbaijani' => 'az',
			'belarusian' => 'be',
			'bulgarian' => 'bg',
			'bangla' => 'bn',
			'catalan' => 'ca',
			'czech' => 'cs',
			'danish' => 'da',
			'german' => 'de',
			'divehi' => 'div',
			'greek' => 'el',
			'spanish' => 'es',
			'estonian' => 'et',
			'basque' => 'eu',
			'persian' => 'fa',
			'finnish' => 'fi',
			'faeroese' => 'fo',
			'french' => 'fr',
			'scottish gaelic' => 'gd',
			'galician' => 'gl',
			'gujarati' => 'gu',
			'hebrew' => 'he',
			'hindi' => 'hi',
			'croatian' => 'hr',
			'hungarian' => 'hu',
			'armenian' => 'hy',
			'indonesian' => 'id',
			'icelandic' => 'is',
			'italian' => 'it',
			'japanese' => 'ja',
			'georgian' => 'ka',
			'kazakh' => 'kk',
			'kannada' => 'kn',
			'korean' => 'ko',
			'konkani' => 'kok',
			'kyrgyz' => 'kz',
			'lithuanian' => 'lt',
			'latvian' => 'lv',
			'macedonian' => 'mk',
			'malayalam' => 'ml',
			'mongolian' => 'mn',
			'marathi' => 'mr',
			'malay' => 'ms',
			'maltese' => 'mt',
			'norwegian' => 'nb-no',
			'nepali' => 'ne',
			'dutch' => 'nl-be',
			'odia' => 'or',
			'punjabi' => 'pa',
			'polish' => 'pl',
			'portuguese' => 'pt',
			'rhaeto-romanic' => 'rm',
			'romanian' => 'ro',
			'russian' => 'ru',
			'sanskrit' => 'sa',
			'sorbian' => 'sb',
			'slovak' => 'sk',
			'slovenian' => 'sl',
			'albanian' => 'sq',
			'serbian' => 'sr',
			'swedish' => 'sv',
			'swahili' => 'sw',
			'sutu' => 'sx',
			'syriac' => 'syr',
			'tamil' => 'ta',
			'telugu' => 'te',
			'thai' => 'th',
			'tswana' => 'tn',
			'turkish' => 'tr',
			'tsonga' => 'ts',
			'tatar' => 'tt',
			'ukrainian' => 'uk',
			'urdu' => 'ur',
			'uzbek' => 'uz',
			'vietnamese' => 'vi',
			'xhosa' => 'xh',
			'yiddish' => 'yi',
			'chinese' => 'zh',
			'zulu' => 'zu',
		);
	}

	if (!$cached && !empty($modSettings['SOSL_installed']) || !empty($modSettings['SOSL_no_sublangs']))
	{
		if (($cached = cache_get_data('SOSL_languages', 3600)) == null)
		{
			// Are we showing only the installed languages?
			if (!empty($modSettings['SOSL_installed']))
			{
				require_once($sourcedir . '/ManageServer.php');
				$languages = list_getLanguages();
				$list = array();
				foreach ($languages as $locale)
				{
					$tmp = str_replace('_', '-', $smcFunc['strtolower']($locale['locale']));
					if (isset($codes[$tmp]))
						$list[$tmp] = $codes[$tmp];
					$tmp = substr($tmp, 0, 2);
					$list[$tmp] = $codes[$tmp];
				}
				$codes = array_intersect($codes, $list);
			}

			// Are we allowing sublanguages to be included in the list?
			if (!empty($modSettings['SOSL_no_sublangs']))
			{
				foreach ($codes as $id => $code)
				{
					if (strpos($id, '-'))
						unset($codes[$id]);
					elseif ($pos = strpos($code, '('))
						$codes[$id] = substr($code, 0, $pos - 1);
				}
			}

			// Cache the array results we just built:
			cache_put_data('SOSL_languages', $codes, 3600);
			$cached = true;
		}
		else
			$codes = $cached;
	}

	// Are we mapping the natural language ID to a RFC 1766 ID?
	if (!empty($trans))
	{
		$trans = $smcFunc['strtolower']($trans);
		return isset($natural[$trans]) ? $natural[$trans] : $trans;
	}
	// Are we returning the natural language ID for the specified RFC 1766 ID?
	if (!empty($lang))
	{
		$lang = $smcFunc['strtolower']($lang);
		$lang = str_replace('_', '-', $smcFunc['strtolower']($lang));
		return isset($codes[$lang]) ? $codes[$lang] : $lang;
	}
	// I guess not....  We must be returning the whole array, then.....
	return $codes;
}

?>