<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Class SearchAndReplace
 *
 * @copyright  Lingo4you 2011
 * @author     Mario Müller <http://www.lingo4u.de/>
 * @package    SiteExport
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
class SearchAndReplace extends Backend
{
	protected $arrPages = array();
	protected $pageList = array();
	protected $arrParentId = array();
	protected $replaceRules = array();
	protected $replaceTables = array();

	/**
	 * Export a theme
	 * @param object
	 */
	public function replace(DataContainer $dc)
	{
		global $objPage;
		
		$this->replaceTables = $GLOBALS['SEARCH_AND_REPLACE']['TABLES'];

		// Get the site export data
		$objSearchAndReplace = $this->Database->prepare("SELECT * FROM tl_search_and_replace WHERE id=?")
			->limit(1)
			->execute($dc->id);		

		$this->pageList = deserialize($objSearchAndReplace->pages);

		$html = '<div id="tl_buttons" style="margin-bottom:10px"><a href="'.$this->getReferer(true).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBT']).'" accesskey="b" onclick="Backend.getScrollOffset();">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a></div>';
		$html .= '<div class="tl_panel">';

		if ($this->Input->get('step') == 'preview')
		{
			$html .= '<div style="float:left; padding-left: 10px;"><div style="padding-top: 6px;">'.$GLOBALS['TL_LANG']['tl_search_and_replace']['fe']['site_preview'].'</div></div>';
			$html .= '<div style="float:right; padding-right: 4px;"><form method="get" class="popup info" id="search_and_replace" action="'.$this->Environment->script.'"><div class="tl_formbody"><input type="submit" value="'.$GLOBALS['TL_LANG']['tl_search_and_replace']['fe']['run'][0].'" title="'.$GLOBALS['TL_LANG']['tl_search_and_replace']['fe']['run'][1].'" class="tl_submit"><input type="hidden" name="do" value="search_and_replace"><input type="hidden" name="key" value="replace"><input type="hidden" name="step" value="go"><input type="hidden" name="id" value="'.$dc->id.'"></div></form></div>';
			$html .= '<div class="clear"></div>';
		}
		elseif ($this->Input->get('step') == 'go')
		{
			$html .= '<div style="float:left; padding-left: 10px;"><div style="padding-top: 6px;">'.$GLOBALS['TL_LANG']['tl_search_and_replace']['fe']['replace_done'].'</div></div>';
			$html .= '<div class="clear"></div>';
		}

		$html .= '</div>';
		$html .= '<div class="tl_listing_container parent_view">';

		if ($objSearchAndReplace->recursive && is_array($this->pageList) && count($this->pageList) > 0)
		{
			for ($i=count($this->pageList)-1; $i>=0; $i--)
			{
				array_splice($this->pageList, $i+1, 0, $this->getChildPages($this->pageList[$i]));
			}
			
			$this->log('SearchAndReplace ID '.$dc->id.': '.count($this->pageList).' recursive', 'SearchAndReplace', TL_GENERAL);
		}

		if (count($this->pageList) > 0)
		{
			foreach ($this->pageList as $pageId)
			{
				$objPage = $this->Database->prepare("
					SELECT *
					FROM
						tl_page
					WHERE
						`id`=?
					ORDER BY `sorting`
				")
				->limit(1)
				->execute($pageId);

				if ($objPage->numRows > 0)
				{
					$this->arrPages[] = array(
						'title' => $objPage->title,
						'id' => $objPage->id,
#						'obj' => $objPage,
						'level' => $this->getPageLevel($objPage->pid),
						'sort' => (array_search($pageId, $this->pageList) !== FALSE ? array_search($pageId, $this->pageList) + 9000000 : $objPage->sorting)
					);
				}
				usort($this->arrPages, array($this, 'pageSort'));
			}
		}

		$this->normalizePageLevels();

		if (count($this->arrPages))
		{
			if ($this->Input->get('step') == 'go')
			{
				// Get the site export data
				$objReplaceRules = $this->Database->prepare("SELECT * FROM tl_search_and_replace_rules WHERE pid=? AND isActive='1' ORDER BY `sorting`")
					->execute($dc->id);

				if ($objReplaceRules->numRows > 0)
				{
					$this->replaceRules = $objReplaceRules->fetchAllAssoc();
				}
			}

			$lastLevel = 0;
			$html .= '<ul>';

			foreach ($this->arrPages as $index => $page)
			{
				if ($page['level'] > $lastLevel)
				{
					$html .= str_pad('<ul>', 4*($page['level']-$lastLevel), '<ul>');
				}
				elseif ($page['level'] < $lastLevel)
				{
					$html .= str_pad('</li></ul>', 10*($lastLevel-$page['level']), '</li></ul>').'</li>';
				}
				elseif ($index > 0)
				{
					$html .= '</li>';
				}
				
				$lastLevel = $page['level'];

				if ($this->Input->get('step') == 'go')
				{
					$msg = $this->processRules($page['id']);
#					$html .= '<li>' . $page['title'] . ' (' . var_export($msg, true) . ')';
					$html .= '<li>' . $page['title'] . ' :: ' . $msg;
				}
				else
				{
					$html .= '<li>' . $page['title'] .'';
				}
			}

			$html .= str_pad('</li></ul>', 10*$lastLevel, '</li></ul>');
			
			if ($this->Input->get('step') == 'go')
			{
				$this->log('SearchAndReplace ID '.$dc->id.': '.count($this->arrPages).' pages saved', 'SearchAndReplace', TL_GENERAL);
			}
			
		}
		else
		{
			$this->log('SearchAndReplace ID '.$dc->id.': No pages found!', 'SearchAndReplace', TL_ERROR);
			return 'SearchAndReplace ID '.$dc->id.': No pages found!';
		}
		
		$html .= '</li></ul></div>';

		return $html;
	}


	protected function processRules($pageId)
	{
		$arrCache = array();
		$arrCacheOrg = array();

		$arrCache['tl_page'] = $this->getTablePages($pageId);
		$arrCacheOrg['tl_page'] = $arrCache['tl_page'];

		foreach ($this->replaceRules as $rule)
		{
			if (!isset($arrCache[$rule['search_table']]))
			{
				if ($rule['search_table'] == 'tl_article')
				{
					$arrCache['tl_article'] = $this->getTableArticles($arrCache['tl_page'][0]['id']);
					$arrCacheOrg['tl_article'] = $arrCache['tl_article'];
				}
				elseif ($rule['search_table'] == 'tl_content')
				{
					if (!isset($arrCache['tl_article']))
					{
						$arrCache['tl_article'] = $this->getTableArticles($arrCache['tl_page'][0]['id']);
						$arrCacheOrg['tl_article'] = $arrCache['tl_article'];
					}

					$arrCache['tl_content'] = array();

					foreach ($arrCache['tl_article'] as $article)
					{
						$arrCache['tl_content'] = array_merge(
							$arrCache['tl_content'],
							$this->getTableContent($article['id'])
						);
						$arrCacheOrg['tl_content'] = $arrCache['tl_content'];
					}
				}
			}

			$arrFields = unserialize($rule['search_table_fields']);

			for ($r=0; $r<count($arrCache[$rule['search_table']]); $r++)
			{
				#foreach ($this->replaceTables[$rule['search_table']] as $field)
				foreach ($arrFields as $field)
				{
					$fieldSplit = explode(':', $field);
					
					if (count($fieldSplit) > 1)
					{
						$arrCache[$rule['search_table']][$r][$fieldSplit[0]] = $this->applyRule($arrCache[$rule['search_table']][$r][$fieldSplit[0]], $rule, $fieldSplit[1]);
					}
					else
					{
						$arrCache[$rule['search_table']][$r][$field] = $this->applyRule($arrCache[$rule['search_table']][$r][$field], $rule, false);
					}
				}
			}			
		}

		$msg = '';

		foreach ($arrCache as $strTable => $arrTable)
		{
			$count = 0;
			
			$this->loadDataContainer($strTable);

			for ($r=0; $r<count($arrTable); $r++)
			{
				$arrParams = array();

				foreach ($this->replaceTables[$strTable] as $field)
				{
					if ($arrTable[$r][$field] != $arrCacheOrg[$strTable][$r][$field])
					{
						$arrParams[$field] = $arrTable[$r][$field];
					}
				}
				
				if (count($arrParams))
				{
					$count++;

					$this->createInitialVersion($strTable, $arrTable[$r]['id']);

					$this->Database->prepare("UPDATE `".$strTable."` %s WHERE `id`=?")->set($arrParams)->execute($arrTable[$r]['id']);

					$this->createNewVersion($strTable, $arrTable[$r]['id']);
				}
			}
			
			if ($count > 0)
			{
				$msg .= $strTable . ' (' . $count . ') ';
			}
		}
#die(var_export($arrCache, true));
		return $msg;
		return count($arrCache['tl_page']).','.count($arrCache['tl_article']).','.count($arrCache['tl_content']);
	}

	protected function getTablePages($pageId)
	{
		return $this->Database->prepare("SELECT `id`,`".implode('`,`', $this->replaceTables['tl_page'])."` FROM `tl_page` WHERE `id`=?")->execute($pageId)->fetchAllAssoc();
	}

	protected function getTableArticles($pageId)
	{
		return $this->Database->prepare("SELECT `id`,`".implode('`,`', $this->replaceTables['tl_article'])."` FROM `tl_article` WHERE `pid`=?")->execute($pageId)->fetchAllAssoc();
	}

	protected function getTableContent($articleId)
	{
		return $this->Database->prepare("SELECT `id`,`".implode('`,`', $this->replaceTables['tl_content'])."` FROM `tl_content` WHERE `pid`=?")->execute($articleId)->fetchAllAssoc();
	}


	protected function applyRule($value, $rule, $fieldIndex)
	{
		#if (array_key_exists($field, $GLOBALS['SEARCH_AND_REPLACE']['SERIALIZED']))
		if ($fieldIndex !== false)
		{
			$value = deserialize($value);
			$str = $value[$fieldIndex];
			#$str = $value[$GLOBALS['SEARCH_AND_REPLACE']['SERIALIZED'][$field]];
			
		}
		else
		{
			$str = $value;
		}

		if ($rule['hasPattern'] != '1')
		{
			$str = $rule['replacement'];
		}
		elseif ($rule['isRegex'] == '1')
		{
			$pattern = '~' . str_replace('~', '\~', $rule['pattern']) . '~';
			$pattern .= ($rule['modIgnoreCase'] == '1' ? 'i' : '');
			$pattern .= ($rule['modMultiLine']  == '1' ? 'm' : '');
			$pattern .= ($rule['modDotAll']     == '1' ? 's' : '');
			$pattern .= ($rule['modUngreedy']   == '1' ? 'U' : '');
			$pattern .= ($rule['modUTF8']       == '1' ? 'u' : '');

			$str = preg_replace($pattern, $rule['replacement'], $str);
#				$this->log($pattern . ' → ' . $rule['replacement'], 'SiteExport', TL_GENERAL);
		}
		elseif (!strlen($rule['pattern']) && !strlen($str))
		{
			$str = $rule['replacement'];
		}
		else
		{
			$str = str_replace($rule['pattern'], $rule['replacement'], $str);
		}

		#if (array_key_exists($field, $GLOBALS['SEARCH_AND_REPLACE']['SERIALIZED']))
		if ($fieldIndex !== false)
		{
			#$value[$GLOBALS['SEARCH_AND_REPLACE']['SERIALIZED'][$field]] = $str;
			$value[$fieldIndex] = $str;
			$value = serialize($value);
		}
		else
		{
			$value = $str;
		}

		return $value;
	}


	/**
	 * shrink page levels to avoid gaps
	 */
	protected function normalizePageLevels()
	{
		$level = -1;

		for ($i=0; $i<count($this->arrPages); $i++)
		{
			if (($this->arrPages[$i]['level'] - $level) > 1)
			{
				$stopLevel = $this->arrPages[$i]['level'];
				$diffLevel = $this->arrPages[$i]['level'] - $level - 1;

				for ($t=$i; $t<count($this->arrPages) && $this->arrPages[$t]['level']>=$stopLevel; $t++)
				{
					$this->arrPages[$t]['level'] -= $diffLevel;
				}
			}
			$level = $this->arrPages[$i]['level'];			
		}
	}


	protected function getPageLevel($pid)
	{
		$level = 1;
		
		while ($pid > 0)
		{
			if (isset($this->arrParentId[$pid]))
			{
				$pid = $this->arrParentId[$pid];
			}
			else
			{
				$objPage = $this->Database->prepare("SELECT `pid` FROM tl_page WHERE `id`=?")->execute($pid);
				$this->arrParentId[$pid] = $objPage->pid;
				$pid = $objPage->pid;
			}
			
			$level++;
		}
		
		return $level;
	}


	/**
	 * Gets all child pages if article_list_recursive is set
	 */
	protected function getChildPages($pageId, $recursive = true, $level=0)
	{
		$pageArray = array();

		$objPages = $this->Database->prepare("SELECT `id` FROM `tl_page` WHERE `pid`=? ORDER BY `sorting`")->execute($pageId);

		while ($objPages->next())
		{
			$pageArray[] = $objPages->id;
#			$this->idLevels[$objPages->id] = $level;
			
			if ($recursive)
			{
				$pageArray = array_merge($pageArray, $this->getChildPages($objPages->id, $recursive, $level+1));
			}
		}

		return $pageArray;
	}
	
	
	/**
	 * Helper function for usort
	 */
	protected function pageSort($a, $b)
	{
		if ($a['sort'] == $b['sort']) {
		    return 0;
		}
		return ($a['sort'] < $b['sort']) ? -1 : 1;
	}

}