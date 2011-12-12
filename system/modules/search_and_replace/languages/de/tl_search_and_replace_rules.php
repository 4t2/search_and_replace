<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['title_legend'] = 'Bezeichnung';
$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['table_legend'] = 'Tabellen und Felder';
$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['rules_legend'] = 'Ersetzungsregeln';
$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['regex_legend'] = 'Regulärer Ausdruck';
$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['apply_legend'] = 'Regel anwenden';

/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['title'] = array('Titel', 'Bitte geben Sie den Titel der Regel ein.');
$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['search_table']['title'] = array('Tabelle', 'In welcher Tabelle soll gesucht und ersetzt werden?');
$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['search_table']['reference'] = array
(
	'tl_content' => 'Inhaltselemente',
	'tl_article' => 'Artikel',
	'tl_page' => 'Seitenstruktur',
	'tl_news' => 'Nachrichten'
);
$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['search_table_fields'] = array('Tabellenfelder', 'In welchen Feldern soll gesucht und ersetzt werden?');

$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['hasPattern'] = array('Nach Ausdruck suchen', 'Es wird nach einem Ausdruck gesucht und dieser ersetzt.');
$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['ignoreCase'] = array('Groß- und Kleinschreibung ignorieren', 'Groß- und Kleinschreibung bei der Suche ignorieren.');
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