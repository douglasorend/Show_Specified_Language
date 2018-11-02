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

//=================================================================================
// Admin hook functions to add the options and bbcode to the forum:
//=================================================================================
function SLBBC(&$bbc)
{
	global $txt;

	// Format: [language=x]{parsed text}[/language]
	$bbc[] = array(
		'tag' => 'language',
		'type' => 'unparsed_commas',
		'before' => '<div class="slbbc_$1">',
		'after' => '</div>',
		'trim' => 'outside',
		'block_level' => true,
	);
	// Format: [language]{parsed text}[/language]
	$bbc[] = array(
		'tag' => 'language',
		'before' => '<div class="slbbc_' . strtolower($txt["lang_dictionary"]) . '">',
		'after' => '',
		'trim' => 'outside',
		'block_level' => true,
	);
}

//=================================================================================
// Function to determine which language DIV to show to the user:
//=================================================================================
function SLBBC_prepareDisplayContext(&$output, &$message)
{
	global $context, $txt;

	// Find all language bbcodes in the post's message:
	$pattern = '#\<div class="slbbc_([\w\-]+?)"#i' . ($u = ($context['utf8'] ? 'u' : ''));
	if (preg_match_all($pattern, $output['body'], $matches, PREG_PATTERN_ORDER))
	{
		// Determine the unique language codes used:
		$matches = array_unique($matches[1]);
		$user_lang = strtolower($txt["lang_dictionary"]);
		$list = array();
		$first_lang = false;
		foreach ($matches as $match)
		{
			$first_lang = (empty($first_lang) || $match == $user_lang ? $match : $first_lang);
			$list[$match] = $match;
		}
		$context['SLBBC_lang'] = $list;
		
		// Replace the "slbbc" with "slbbc<post_id>" and hide all non-selected languages:
		foreach ($list as $src => $dst)
			$output['body'] = str_replace('<div class="slbbc_' . $src . '"', '<div class="slbbc' . $output['id'] . '_' . $dst . '"' . ($first_lang == $dst ? '' : ' style="display: none;"'), $output['body']);
	}
	return $output;
}

?>