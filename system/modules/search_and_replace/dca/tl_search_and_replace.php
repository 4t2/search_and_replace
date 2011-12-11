<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');


/**
 * Table tl_search_and_replace
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
				'icon'                => 'system/modules/search_and_replace/html/images/cog_go.png'
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
 * Class tl_lingo_wordlist_topic
 */
class tl_search_and_replace extends Backend
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
		return ($this->User->isAdmin || $this->User->hasAccess('create', 'newsletterp')) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
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
		return ($this->User->isAdmin || $this->User->hasAccess('delete', 'newsletterp')) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ' : $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}
}


?>