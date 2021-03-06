<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Back end modules
 */
$GLOBALS['BE_MOD']['batch']['search_and_replace'] = array
(
		'tables'     => array('tl_search_and_replace', 'tl_search_and_replace_rules'),
		'icon'       => 'system/modules/search_and_replace/assets/images/magnifier.png',
		'stylesheet' => 'system/modules/search_and_replace/assets/styles/search_and_replace.css',
		'replace' 	 => array('SearchAndReplace', 'replace')
);

$GLOBALS['SEARCH_AND_REPLACE']['TABLES'] = array
(
	'tl_content' => array
	(
		'headline',
		'text',
		'alt',
		'title',
		'caption',
		'singleSRC',
		'cssID',
		'invisible'
	),
	'tl_article' => array
	(
		'alias',
		'title',
		'teaser',
		'author',
		'cssID',
		'published'
	),
	'tl_page' => array
	(
		'alias',
		'title',
		'pageTitle',
		'language',
		'description',
		'published'
	),
	'tl_comments' => array
	(
		'comment',
		'name',
		'email',
		'website'
	),
	'tl_glossary_term' => array
	(
		'term',
		'definition',
		'example'
	)
);

$GLOBALS['SEARCH_AND_REPLACE']['SERIALIZED'] = array
(
	'headline' => array('unit', 'value'),
	'teaserCssID' => array(0, 1),
	'cssID' => array(0, 1),
	'space' => array(0, 1)
);
