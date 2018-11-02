<?php
/**********************************************************************************
* Subs-BBCode-Language.php
***********************************************************************************
* This mod is licensed under the 2-clause BSD License, which can be found here:
*	http://opensource.org/licenses/BSD-2-Clause
***********************************************************************************
* This program is distributed in the hope that it is and will be useful, but      *
* WITHOUT ANY WARRANTIES; without even any implied warranty of MERCHANTABILITY    *
* or FITNESS FOR A PARTICULAR PURPOSE.                                            *
**********************************************************************************/
if (!defined('SMF'))
	die('Hacking attempt...');

function BBCode_Language(&$bbc)
{
	global $modSettings;

	// Format: [language=x]{parsed text}[/language]
	$bbc[] = array(
		'tag' => 'language',
		'type' => 'unparsed_commas_content',
		'content' => '',	// < == Nope, not a mistake!!!
		'trim' => 'both',
	);
	// Format: [language]{parsed text}[/language]
	$bbc[] = array(
		'tag' => 'language',
		'before' => '',
		'after' => '',
	);
}

function BBCode_Language_Embed(&$message, &$smileys, &$cache_id, &$parse_tags)
{
	global $txt, $modSettings, $context;
	static $user_lang = null, $def_locale, $def_dictionary;

	// Make sure we've lowercased all possible proper responses from the language file!
	if ($user_lang == null)
	{
		$user_lang = strtolower($txt["lang_dictionary"]);
		$def_locale = !empty($modSettings['SLBBC_Default']) ? strtolower($modSettings['SLBBC_Default']) : '';
		$def_dictionary = substr($def_locale, 0, 2);
	}

	// Find all language bbcodes in the post's message:
	$pattern = '#\[language=(.+?)\]#i' . ($u = ($context['utf8'] ? 'u' : ''));
	if (preg_match_all($pattern, $message, $matches, PREG_PATTERN_ORDER))
	{
		// We've got matches!!!  Let's search them for the user's language:
		$found = 0;
		foreach ($matches[1] as $id => $match)
		{
			// Found a match for user language?
			$matches[1][$id] = $match = substr(strtolower($match), 0, 2);
			if ($match == $user_lang)
			{
				$message = preg_replace('#\[language=' . $match . '(|_[\w]{2})\]#i' . $u, '[language]', $message);
				return;
			}
			// Found default dictionary or locale match?
			elseif ($match == $def_locale)
				$found = $id;
		}

		// Transform specified "[language=?]" into "[language]":
		$message = preg_replace('#\[language=' . $matches[1][$id] . '(|_[\w]{2})\]#i' . $u, '[language]', $message);
	}
}

function BBCode_Language_Options(&$config_vars)
{
	$config_vars[] = array('text', 'SLBBC_Default', 5, 'javascript' => ' maxlength="5"');
}

?>