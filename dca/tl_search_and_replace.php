<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Lingo4you 2013
 * @author     Mario Müller <http://www.lingolia.com/>
 * @package    Search and Replace
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

$GLOBALS['TL_DCA']['tl_search_and_replace'] = array
(
	// Allgemein Konfiguration
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ctable'                      => array('tl_search_and_replace_rules'),
		'switchToEdit'                => true,
		'enableVersioning'            => true
	),
	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 1,
			'fields'                  => array('title'),
			'flag'                    => 1,
			'panelLayout'             => 'search,limit'
		),
		'label' => array
		(
			'fields'                  => array('title'),
			'format'                  => '%s'
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_search_and_replace']['edit'],
				'href'                => 'table=tl_search_and_replace_rules',
				'icon'                => 'edit.gif',
				'attributes'          => 'class="contextmenu"'
			),
			'editheader' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_search_and_replace']['editheader'],
				'href'                => 'act=edit',
				'icon'                => 'header.gif',
				'button_callback'     => array('tl_search_and_replace', 'editHeader'),
				'attributes'          => 'class="edit-header"'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_search_and_replace']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif',
				'button_callback'     => array('tl_search_and_replace', 'copyTopic') /* copyChannel */
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_search_and_replace']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"',
				'button_callback'     => array('tl_search_and_replace', 'deleteTopic') /* deleteChannel*/
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_search_and_replace']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			),
			'replace' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_search_and_replace']['replace'],
				'href'                => 'key=replace&amp;step=preview',
				'icon'                => 'system/modules/search_and_replace/assets/images/cog_go.png'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array(),
		'default'                     => '{title_legend},title;{page_legend},pages,recursive'
	),

	// Subpalettes
	'subpalettes' => array
	(
	),
	
	// Fields
	'fields' => array
	(
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_search_and_replace']['title'],
			'search'                  => true,
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'decodeEntities'=>true, 'maxlength'=>255)
		),
		'pages' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_search_and_replace']['pages'],
			'exclude'                 => true,
			'inputType'               => 'pageTree',
			'eval'                    => array(
				'mandatory' => false,
				'multiple' => true,
				'fieldType'=>'checkbox'
			)
		),
		'recursive' => array
		(
			'label'			=> &$GLOBALS['TL_LANG']['tl_search_and_replace']['recursive'],
			'exclude'		=> true,
			'inputType'		=> 'checkbox'
			#'eval'          => array('tl_class'=>'w50')
		),
	)
);




/**
 * Class tl_search_and_replace
 */
class tl_search_and_replace extends \Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}


	/**
	 * Check permissions to edit table tl_lingo_wordlist_topic
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin)
		{
			return;
		}
	}


	/**
	 * Return the edit header button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function editHeader($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || count(preg_grep('/^tl_search_and_replace::/', $this->User->alexf)) > 0) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : '';
	}


	/**
	 * Return the copy channel button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function copyTopic($row, $href, $label, $title, $icon, $attributes)
	{
		return $this->User->isAdmin ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Return the delete channel button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function deleteTopic($row, $href, $label, $title, $icon, $attributes)
	{
		return $this->User->isAdmin ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}
}
