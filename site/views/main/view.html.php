<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @deprecated 0ea5b655c0d39cec1c674c3f0768a0cd
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'models'.DS.'admin.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'comments.php');


class LimeticketViewMain extends LIMETICKETView
{
    function display($tpl = null)
    {
        $mainframe = JFactory::getApplication();
		$aparams = $mainframe->getPageParameters('com_limeticket');

		$this->template = $aparams->get('template');
		if ($this->template == "")
			$this->template = "grid";
		
		$this->show_desc = $aparams->get('show_desc');
		$this->mainwidth = $aparams->get('mainwidth');
		$this->maincolums = $aparams->get('maincolums');
		if ($this->maincolums == 0 || $this->maincolums == "")
			$this->maincolums = 3;
		$this->hideicons = $aparams->get('hideicons');
		
		$this->imagewidth = $aparams->get('imagewidth');
		if ($this->imagewidth == 0 || $this->imagewidth == "")
			$this->imagewidth = 128;
			
		$this->imageheight = $aparams->get('imageheight');
		if ($this->imageheight == 0 || $this->imageheight == "")
			$this->imageheight = 128;
			
		$this->border = $aparams->get('border');
		
		$this->info_top = $aparams->get('info_top');
		$this->info_well = $aparams->get('info_well');
		
		if ($this->info_top === null)
			$this->info_top = 1;
		
		if ($this->info_well === null)
			$this->info_well = 1;
		
		$db = JFactory::getDBO();
		$query = 'SELECT * FROM #__menu';
		$db->setQuery($query);
		$this->joomlamenus = $db->loadAssocList('id');

		// work out permissions, and if to show admin or not
		$showadmin = false;
		$showgroups = false;
		$showsupport = false;
		if (LIMETICKET_Permission::AnyAdmin())
			$showadmin = true;
		if (LIMETICKET_Permission::AllowSupport())
			$showsupport = true;
		if (LIMETICKET_Permission::AdminGroups())
			$showgroups = true;

		$this->showadmin = $showadmin;
		
		if ($showadmin)
			$this->getSupportOverView();
		
		$query = 'SELECT * FROM #__limeticket_main_menu ';
		$where = array();
		
		if (!$showadmin)
			$where[] = 'itemtype != 9';
		if (!$showgroups)
			$where[] = 'itemtype != 10';
		
		if (!$showsupport)
		{
			$where[] = 'itemtype != 4';
			$where[] = 'itemtype != 5';
		}

		if (!LIMETICKET_Permission::AllowSupportOpen())
			$where[] = 'itemtype != 4';

		// add language and access to query where
		$where[] = 'language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')';
		$user = JFactory::getUser();
		$where[] = 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				
		
		$where[] = "published = 1";
		
		if (count($where) > 0)
			$query .= " WHERE " . implode(" AND ",$where);
			
		$query .= " ORDER BY ordering";
			
		$db->setQuery($query);
		$this->menus = $db->loadAssocList('id');
		
		LIMETICKET_Translate_Helper::Tr($this->menus);

		$this->ValidateMenuLinks();
		
		$this->ProcessContentPlugins();

        parent::display();
    }
	
	function ValidateMenuLinks()
	{
		$allmenus = LIMETICKET_GetAllMenus();
		$basemenus = array();
	
		if (count($allmenus) > 0)
		{
			foreach($allmenus as $allmenu)
			{
				$basemenus[$allmenu->id] = $allmenu;	
			}
		}
		
		//print_r($basemenus);
		//print_r($this->menus);
		
		if (count($this->menus) > 0)
		{
			foreach($this->menus as &$menu)
			{
				if ($menu['itemtype'] == LIMETICKET_IT_LINK)
					continue;
				
				$itemid = $menu['itemid'];

				if ($menu['link'] != "")
				{
					if (array_key_exists($itemid,$basemenus))
					{
						if ($basemenus[$itemid]->link != $menu['link'])
						{
							//echo "Not using $itemid, link different<br>";	
							$menu['link'] = "";	
							$menu['itemid'] = 0;	
						}
					} else {
						//echo "Not using $itemid, link not found<br>";	
						$menu['link'] = "";	
						$menu['itemid'] = 0;	
					}
				}
			
				// menu link as null, find out itemid and link from database and store it
				$menus = LIMETICKET_GetMenus($menu['itemtype']);
				
				
				if ($menu['link'] == "")
				{
				
					if (count($menus) > 0)
					{
						/*print_r($menus[0]);*/
						$menu['link'] = $menus[0]->link;	
						$menu['itemid'] = $menus[0]->id;
					
						$db = JFactory::getDBO();
						$id = $menu['id'];

						$qry = "UPDATE #__limeticket_main_menu SET link = '".LIMETICKETJ3Helper::getEscaped($db, $menu['link'])."', itemid = '".LIMETICKETJ3Helper::getEscaped($db, $menu['itemid'])."' WHERE id = '".LIMETICKETJ3Helper::getEscaped($db, $id)."'";	
						$db->setQuery($qry);$db->Query();
						//echo $qry."<br>";
					} else {
						$id = $menu['id'];
						$qry = "UPDATE #__limeticket_main_menu SET link = '', itemid = 0 WHERE id = '$id'";	
						$db = JFactory::getDBO();
						$db->setQuery($qry);$db->Query();
					
						switch($menu['itemtype'])
						{
						case LIMETICKET_IT_KB:
							$menu['link'] = JRoute::_( 'index.php?option=com_limeticket&view=kb' );
							break;		
						case LIMETICKET_IT_FAQ:
							$menu['link'] = JRoute::_( 'index.php?option=com_limeticket&view=faq' );
							break;		
						case LIMETICKET_IT_TEST:
							$menu['link'] = JRoute::_( 'index.php?option=com_limeticket&view=test' );
							break;		
						case LIMETICKET_IT_NEWTICKET:
							$menu['link'] = JRoute::_( 'index.php?option=com_limeticket&view=ticket&layout=open' );
							break;		
						case LIMETICKET_IT_VIEWTICKETS:
							$menu['link'] = JRoute::_( 'index.php?option=com_limeticket&view=ticket' );
							break;		
						case LIMETICKET_IT_ANNOUNCE:
							$menu['link'] = JRoute::_( 'index.php?option=com_limeticket&view=announce' );
							break;		
						case LIMETICKET_IT_GLOSSARY:
							$menu['link'] = JRoute::_( 'index.php?option=com_limeticket&view=glossary' );
							break;		
						case LIMETICKET_IT_ADMIN:
							$menu['link'] = JRoute::_( 'index.php?option=com_limeticket&view=admin' );
							break;		
						case LIMETICKET_IT_GROUPS:
							$menu['link'] = JRoute::_( 'index.php?option=com_limeticket&view=admin_groups' );
							break;		
						}
					}
				}
			}
		}
	}

	function getSupportOverView()
	{
		$model = new LimeticketModelAdmin();
		
		$this->comments = new LIMETICKET_Comments(null,null);
		$this->moderatecount = $this->comments->GetModerateTotal();
	}
	
	function centerTable($data, $options)
	{
		$base_opts = array(
			'cols' => 3,
			'table-class' => '',
			'table-width' => '100%',
			'td-attrs' => '',
			'td-style' => '',
			'td-class' => '',
			'tmpl' => ''
			);

		$options = array_merge($base_opts, $options);
		
		$count = 0;
		$offset = 0;
		$col = 0;
		
		$display = array();

		foreach ($data as $item)
		{
			$offset++;	
			if ($offset > $options['cols'])
			{
				$col++;
				$offset = 1;
			}
	
			$display[$col][] = $item;
		}	
		
		$first = count($display[0]);
		$last = count($display[$col]);
		$lcm = $this->lcm($first, $last);
		$last_offset = $col;
		echo "<table class='{$options['table-class']}' width='{$options['table-width']}'>";
		
		foreach ($display as $offset => $row)
		{
			echo "<tr>";
			
			$in_row = count($row);
			$colspan = $lcm / $in_row;	
			$width = floor(100 / $in_row);		
			foreach ($row as $item)
			{
				echo "<td width='{$width}%' colspan='$colspan' {$options['td-attrs']} style='{$options['td-style']}' class='{$options['td-class']}'>";
				include $this->snippet($options['tmpl']);
				echo "</td>";	
			}
			echo "</tr>";	
		}
		
		echo "</table>";
	}
	
	/**
	 * Least common multiple of numbers "a" and "b"
	 * @param $a number "a"
	 * @param $b number "b"
	 * @return lcm(a, b)
	 * @autor Thomas (www.adamjak.net)
	 */
	function lcm($a, $b) {
		if ($a == 0 || $b == 0) {
			return 0;
		}
		return ($a * $b) / $this->gcd($a, $b);
	}

	/**
	 * Least common multiple of numbers "a" and "b"
	 * @param $a number "a"
	 * @param $b number "b"
	 * @return gcd(a, b)
	 * @autor Thomas (www.adamjak.net)
	 */
	function gcd($a, $b) {
		if ($a < 1 || $b < 1) {
			die("a or b is less than 1");
		}
		$r = 0;
		do {
			$r = $a % $b;
			$a = $b;
			$b = $r;
		} while ($b != 0);
		return $a;
	}
	
	function getLink($item)
	{
		
		if (!LIMETICKET_Helper::langEnabled()) // old style, non multi language link generation
		{
			$link = $item['link'];
			if ($item['itemid'] > 0)
				$link .= '&Itemid=' . $item['itemid'];
			$link = JRoute::_( $link );
			return $link;
		}
		
		// if we are a multi language site, then we need to ignore the itemid paramter, and let LIMETICKETRoute 
		// track down the correct link.
		// for manually added items, just use the link we have stored.
		switch($item['itemtype'])
		{
			case LIMETICKET_IT_KB:
				$item['link'] = LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=kb', false );
				break;		
			case LIMETICKET_IT_FAQ:
				$item['link'] = LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=faq', false );
				break;		
			case LIMETICKET_IT_TEST:
				$item['link'] = LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=test', false );
				break;		
			case LIMETICKET_IT_NEWTICKET:
				$item['link'] = LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=ticket&layout=open', false );
				break;		
			case LIMETICKET_IT_VIEWTICKETS:
				$item['link'] = LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=ticket', false );
				break;		
			case LIMETICKET_IT_ANNOUNCE:
				$item['link'] = LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=announce', false );
				break;		
			case LIMETICKET_IT_GLOSSARY:
				$item['link'] = LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=glossary', false );
				break;		
			case LIMETICKET_IT_ADMIN:
				$item['link'] = LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=admin', false );
				break;		
			case LIMETICKET_IT_GROUPS:
				$item['link'] = LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=admin_groups', false );
				break;		
		}	
		
		return $item['link'];
	}

	function ProcessContentPlugins()
	{
		foreach ($this->menus as &$menu)
		{
			$dispatcher	= JDispatcher::getInstance();
			JPluginHelper::importPlugin('content');
			$art = new stdClass;
			$art->text = $menu['description'];
			$art->noglossary = 1;
				
			$this->params = JFactory::getApplication()->getParams('com_limeticket');
				
			$results = $dispatcher->trigger('onContentPrepare', array('com_limeticket.menu', &$art, &$this->params, 0));
			$results = $dispatcher->trigger('onContentBeforeDisplay', array('com_limeticket.menu', &$art, &$this->params, 0));				
			$menu['description'] = $art->text;
		}
	}
}
