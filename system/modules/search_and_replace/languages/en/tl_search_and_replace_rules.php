<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['title_legend'] = 'Title';
$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['table_legend'] = 'Tables and columns';
$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['rules_legend'] = 'Replace rules';
$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['regex_legend'] = 'Regular expression';
$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['apply_legend'] = 'Apply rule';

/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['title'] = array('Title', 'Please enter the rule title.');
$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['search_table']['title'] = array('Table', 'Choose the table for the search and replace rule.');
$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['search_table']['reference'] = array
(
	'tl_content' => 'Content elements',
	'tl_article' => 'Article',
	'tl_page' => 'Site structure',
	'tl_news' => 'News'
);
$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['search_table_fields'] = array('Table fields', 'Choose the fields for the search and replace rule.');

/* to do */

$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['hasPattern'] = array('Nach Ausdruck suchen', 'Es wird nach einem Ausdruck gesucht und dieser ersetzt.');
$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['pattern'] = array('Suchmuster', 'Zu suchender Ausdruck. Bei regulären Ausdrücken ohne Begrenzer und Modifikator.');
$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['replacement'] = array('Ersetzungsausdruck', 'Zu ersetzender Ausdruck.');

$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['isRegex'] = array('Regel ist ein Regulärer Ausdruck', 'Die Regel ist ein Regulärer Ausdruck.');
$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['modIgnoreCase'] = array('Modifikator i', 'Groß- und Kleinschreibung ignorieren.');
$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['modMultiLine'] = array('Modifikator m', 'Zeilenumbrüche ignorieren.');
$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['modDotAll'] = array('Modifikator s', 'Punkt umfasst alle Zeichen.');
$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['modUngreedy'] = array('Modifikator U', 'Gier von Quantifikatoren umkehren.');
$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['modUTF8'] = array('Modifikator u', 'Suchmuster werden als UTF-8 behandelt.');

$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['isActive'] = array('Regel anwenden', 'Die Regel anwenden.');

/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['new']        = array('Neue Regel', 'Eine neue Regel erstellen');