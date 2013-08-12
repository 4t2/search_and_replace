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
 * @copyright  Lingo4you 2012
 * @author     Mario Müller <http://www.lingo4u.de/>
 * @package    Search and Replace
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

$GLOBALS['TL_DCA']['tl_search_and_replace_rules'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'                      => 'tl_search_and_replace',
		'enableVersioning'            => true,
		'onload_callback' => array
		(
#			array('tl_search_and_replace', 'checkPermission')
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 4,
			'fields'                  => array('sorting', 'id'),
			'panelLayout'             => 'filter;sort,search,limit',
			'headerFields'            => array('title', 'tstamp', 'lastMessage'),
			'child_record_callback'   => array('tl_search_and_replace_rules', 'listRules')
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
				'label'               => &$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.gif'
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="var id=%s; Backend.getScrollOffset(); ($$(\'#rule\'+id).getStyle(\'color\')==\'black\' ? $$(\'#rule\'+id).setStyle(\'color\', \'silver\') : $$(\'#rule\'+id).setStyle(\'color\', \'black\')); return AjaxRequest.toggleVisibility(this, id);"',
				'button_callback'     => array('tl_search_and_replace_rules', 'toggleIcon')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('hasPattern', 'isRegex'),
		'default'                     => '{title_legend},title,isActive;{table_legend},search_table,search_table_fields;{rules_legend},hasPattern,replacement;{regex_legend},isRegex'
	),
	
	// Subpalettes
	'subpalettes' => array
	(
		'hasPattern'				=> 'ignoreCase,pattern',
		'isRegex'                   => 'modIgnoreCase,modMultiLine,modDotAll,modUngreedy,modUTF8'
	),

	// Fields
	'fields' => array
	(
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['title'],
			'search'                  => true,
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array
			(
				'mandatory'			=> true,
				'decodeEntities'	=> true,
				'maxlength'			=> 255,
				'tl_class'			=> 'w50'
			)
		),
		'search_table' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['search_table']['title'],
			'search'                  => true,
			'exclude'                 => true,
			'inputType'               => 'radio',
			'options'				  => array_keys($GLOBALS['SEARCH_AND_REPLACE']['TABLES']),
			'reference'				  => &$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['search_table']['reference'],
			'eval'                    => array
			(
				'includeBlankOption'	=> false,
				'maxlength'				=> 255,
				'tl_class'				=> 'w50',
				'submitOnChange'		=> true
			)
		),
		'search_table_fields' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['search_table_fields'],
			'search'                  => true,
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'options_callback'		  => array('tl_search_and_replace_rules', 'getTableFields'),
			'eval'                    => array
			(
				'includeBlankOption'	=> false,
				'mandatory' 			=> true,
				'multiple'				=> true,
				'tl_class'				=> 'w50 bigField'
			)
		),
		'hasPattern' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['hasPattern'],
			'exclude'				=> true,
			'inputType'				=> 'checkbox',
			'default'				=> 1,
			'eval'					=> array(
				'submitOnChange'	=>true,
				'tl_class'			=>'w50'
			)
		),
		'ignoreCase' => array
		(
			'label'			=> &$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['ignoreCase'],
			'exclude'		=> true,
			'default'		=> 1,
			'inputType'		=> 'checkbox',
			'eval'          => array('tl_class'=>'w50')
		),
		'pattern' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['pattern'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'explanation'             => 'insertTags',
			'eval'                    => array
			(
				'mandatory' 	=> false,
				'rows' 			=> 6,
				'allowHtml'		=> true,
				'preserveTags'	=> true,
				'decodeEntities'=> false,
				'style'			=> 'height:auto'
			)
		),
		'replacement' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['replacement'],
			'exclude'                 => true,
			'search'                  => false,
			'inputType'               => 'textarea',
			'explanation'             => 'insertTags',
			'eval'                    => array
			(
				'mandatory' 	=> false,
				'rows' 			=> 6,
				'allowHtml'		=> true,
				'preserveTags'	=> true,
				'decodeEntities'=> false,
				'style'			=> 'height:auto',
				'tl_class'		=> 'clr'
			)
		),
		'isRegex' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['isRegex'],
			'exclude'				=> true,
			'inputType'				=> 'checkbox',
			'eval'					=> array('submitOnChange'=>true)
		),
		'modIgnoreCase' => array
		(
			'label'			=> &$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['modIgnoreCase'],
			'exclude'		=> true,
			'default'		=> 1,
			'inputType'		=> 'checkbox',
			'eval'          => array('tl_class'=>'w50')
		),
		'modMultiLine' => array
		(
			'label'			=> &$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['modMultiLine'],
			'exclude'		=> true,
			'default'		=> 1,
			'inputType'		=> 'checkbox',
			'eval'          => array('tl_class'=>'w50')
		),
		'modDotAll' => array
		(
			'label'			=> &$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['modDotAll'],
			'exclude'		=> true,
			'default'		=> 1,
			'inputType'		=> 'checkbox',
			'eval'          => array('tl_class'=>'w50')
		),
		'modUngreedy' => array
		(
			'label'			=> &$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['modUngreedy'],
			'exclude'		=> true,
			'inputType'		=> 'checkbox',
			'eval'          => array('tl_class'=>'w50')
		),
		'modUTF8' => array
		(
			'label'			=> &$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['modUTF8'],
			'exclude'		=> true,
			'inputType'		=> 'checkbox',
			'eval'          => array('tl_class'=>'w50')
		),
		
		'isActive' => array
		(
			'label'			=> &$GLOBALS['TL_LANG']['tl_search_and_replace_rules']['isActive'],
			'exclude'		=> true,
			'inputType'		=> 'checkbox',
			'eval'          => array('tl_class'=>'w50 m12')
		)
	)

);


/**
 * Class tl_search_and_replace_rules
 */
class tl_search_and_replace_rules extends Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}


	public function getTableFields(DataContainer $dc)
	{
		$strTable = $dc->activeRecord->search_table;
		$arrTable = $GLOBALS['SEARCH_AND_REPLACE']['TABLES'][$strTable];
		$arrSerialized = $GLOBALS['SEARCH_AND_REPLACE']['SERIALIZED'];

		$fields = array_intersect(
			$this->Database->getFieldNames($strTable),
			$arrTable
		);

		if (file_exists(TL_ROOT . '/system/modules/backend/languages/'.$GLOBALS['TL_LANGUAGE'].'/'.$strTable.'.php'))
		{
			include_once(TL_ROOT . '/system/modules/backend/languages/'.$GLOBALS['TL_LANGUAGE'].'/'.$strTable.'.php');
		}

		$arrFields = array();
		
		foreach ($fields as $field)
		{
			$fieldTitle = isset($GLOBALS['TL_LANG'][$strTable][$field][0]) ? $GLOBALS['TL_LANG'][$strTable][$field][0] : $field;

			if (isset($arrSerialized[$field]))
			{
				$arrFieldTitle = explode('/', $fieldTitle);

				foreach ($arrSerialized[$field] as $fieldIndex)
				{
					$arrFields[$field.':'.$fieldIndex] = isset($arrFieldTitle[$fieldIndex]) ? $arrFieldTitle[$fieldIndex] : $fieldTitle . ' [' . $fieldIndex . ']';
				}
			}
			else
			{
				$arrFields[$field] = $fieldTitle;
			}
		}
		
		return $arrFields;
/*
		$allowed_fields = array_values($GLOBALS['SEARCH_AND_REPLACE']['TABLES'][$dc->activeRecord->search_table]);
		
		foreach($all_fields as $field)
		{
			if (isset($allowed_fields[$field]))
			{
				$fields[] = $field;
			}
		}
		
		return $fields;
*/
	}

	/**
	 * Check permissions to edit table tl_newsletter
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin)
		{
			return;
		}

		// Set root IDs
		if (!is_array($this->User->newsletters) || count($this->User->newsletters) < 1)
		{
			$root = array(0);
		}
		else
		{
			$root = $this->User->newsletters;
		}

		$id = strlen($this->Input->get('id')) ? $this->Input->get('id') : CURRENT_ID;

		// Check current action
		switch ($this->Input->get('act'))
		{
			case 'paste':
			case 'select':
				// Allow
				break;

			case 'create':
				if (!strlen($this->Input->get('pid')) || !in_array($this->Input->get('pid'), $root))
				{
					$this->log('Not enough permissions to create newsletters in channel ID "'.$this->Input->get('pid').'"', 'tl_search_and_replace_rules checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'cut':
			case 'copy':
				if (!in_array($this->Input->get('pid'), $root))
				{
					$this->log('Not enough permissions to '.$this->Input->get('act').' wordlist ID "'.$id.'" to channel ID "'.$this->Input->get('pid').'"', 'tl_search_and_replace_rules checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				// NO BREAK STATEMENT HERE

			case 'edit':
			case 'show':
			case 'delete':
				if (!in_array($this->Input->get('pid'), $root))
				{
					$this->log('Not enough permissions to '.$this->Input->get('act').' wordlist ID "'.$id.'" to channel ID "'.$this->Input->get('pid').'"', 'tl_search_and_replace_rules checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				
				break;

			case 'editAll':
			case 'deleteAll':
			case 'overrideAll':
			case 'cutAll':
			case 'copyAll':

			default:
				if (strlen($this->Input->get('act')))
				{
					$this->log('Invalid command "'.$this->Input->get('act').'"', 'tl_search_and_replace_rules checkPermission', TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;
		}
	}


	/**
	 * Return the "toggle active" button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		if (strlen($this->Input->get('tid')))
		{
			$this->toggleVisibility($this->Input->get('tid'), ($this->Input->get('state') == 1));
			$this->redirect($this->getReferer());
		}

		// Check permissions AFTER checking the tid, so hacking attempts are logged
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_search_and_replace_rules::isActive', 'alexf'))
		{
			return '';
		}

		$href .= '&amp;tid='.$row['id'].'&amp;state='.($row['isActive'] ? '' : 1);

		if (!$row['isActive'])
		{
			$icon = 'invisible.gif';
		}		

		$objPage = $this->Database->prepare("SELECT * FROM tl_page WHERE id=?")
								  ->limit(1)
								  ->execute($row['pid']);

		if (!$this->User->isAdmin && !$this->User->isAllowed(4, $objPage->row()))
		{
			return $this->generateImage($icon) . ' ';
		}

		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}


	/**
	 * Disable/enable a user group
	 * @param integer
	 * @param boolean
	 */
	public function toggleVisibility($intId, $blnActive)
	{
		// Check permissions to edit
		$this->Input->setGet('id', $intId);
		$this->Input->setGet('act', 'toggle');
		$this->checkPermission();

		// Check permissions to publish
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_search_and_replace_rules::isActive', 'alexf'))
		{
			$this->log('Not enough permissions to change export_rule ID "'.$intId.'"', 'tl_search_and_replace_rules toggleVisibility', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$this->createInitialVersion('tl_search_and_replace_rules', $intId);
	
		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_search_and_replace_rules']['fields']['isActive']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_search_and_replace_rules']['fields']['isActive']['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$blnVisible = $this->$callback[0]->$callback[1]($blnActive, $this);
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_search_and_replace_rules SET tstamp=". time() .", isActive='" . ($blnActive ? 1 : '') . "' WHERE id=?")
					   ->execute($intId);

		$this->createNewVersion('tl_search_and_replace_rules', $intId);
	}


	/**
	 * Add an image to each page in the tree
	 * @param array
	 * @param string
	 * @return string
	 */
	public function addIcon($row, $label)
	{
		$time = time();
		$isActive = ($row['isActive'] && ($row['start'] == '' || $row['start'] < $time) && ($row['stop'] == '' || $row['stop'] > $time));

		return $this->generateImage('articles'.($isActive ? '' : '_').'.gif') .' '. $label;
	}

	/**
	 * List records
	 * @param array
	 * @return string
	 */
	public function listRules($arrRow)
	{
		$arrSearchFields = unserialize($arrRow['search_table_fields']);
#		$return = $arrSearchFields;
		$return = $arrRow['title'] . ($arrRow['isRegex'] ? ' [REGEX]' : '') . ' <span style="color:silver">[' . $arrRow['search_table'] . ' (' . implode(',', $arrSearchFields) . ')]</span>';

		if ($arrRow['isActive'] == '1')
		{
			return '<span id="rule'.$arrRow['id'].'" style="color:black">'.$return.'</span>';
		}
		else
		{
			return '<span id="rule'.$arrRow['id'].'" style="color:silver">'.$return.'</span>';
		}
	}

}
