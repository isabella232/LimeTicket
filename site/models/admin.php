<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.model' );
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'pagination.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'tickethelper.php');


class FssModelAdmin extends JModelLegacy
{
	function getTests()
	{
		$query = "SELECT t.id, t.prod_id, t.title, t.body, t.email, t.name, t.website, t.added, p.title as ptitle FROM #__limeticket_test as t LEFT JOIN #__limeticket_prod as p ON t.prod_id = p.id WHERE t.published = 0 ORDER BY added LIMIT 10";
		$db = JFactory::getDBO();
		$db->setQuery($query);
		$rows = $db->loadAssocList();
		return $rows;
	}	
	
	function getTestCount()
	{
		$query = "SELECT count(*) as cnt FROM #__limeticket_test WHERE published = 0";
		$db = JFactory::getDBO();
		$db->setQuery($query);
		$rows = $db->loadAssoc();
		return $rows['cnt'];
	}
	
	function getKbcomms()
	{
		$query = "SELECT c.id, c.name, c.email, c.website, c.body, c.created, a.title FROM #__limeticket_kb_comment as c LEFT JOIN #__limeticket_kb_art as a ON c.kb_art_id = a.id WHERE c.published = 0 ORDER BY created LIMIT 10";
		$db = JFactory::getDBO();
		$db->setQuery($query);
		$rows = $db->loadAssocList();
		return $rows;
	}
	
	function getKbcommcount()
	{
		/*$query = "SELECT count(*) as cnt FROM #__limeticket_kb_comment WHERE published = 0";
		$db = JFactory::getDBO();
		$db->setQuery($query);
		$rows = $db->loadAssoc();
		return $rows['cnt'];*/
		return 0;
	}

	function &getMessage($messageid)
	{
		$db = JFactory::getDBO();
		
		$query = "SELECT m.* FROM #__limeticket_ticket_messages as m WHERE m.id = '".LIMETICKETJ3Helper::getEscaped($db, $messageid)."' ORDER BY posted DESC";

		$db->setQuery($query);
		$rows = $db->loadAssoc();
		return $rows;   		
	}

	function getUser($user_id)
	{
		$db = JFactory::getDBO();

		$query = " SELECT * FROM #__users ";
		$query .= " WHERE id = '".LIMETICKETJ3Helper::getEscaped($db, $user_id)."'";
		
		$db->setQuery($query);
		$rows = $db->loadAssoc();
		return $rows;   		
	}

	function GetDepartment($dept_id)
	{
		$db = JFactory::getDBO();
		$qry = "SELECT * FROM #__limeticket_ticket_dept WHERE id = '".LIMETICKETJ3Helper::getEscaped($db, $dept_id)."'";
        $db->setQuery($qry);
		$rec = $db->loadObject();
		return $rec->title;	
	}
		
	function GetProduct($prod_id)
	{
		$db = JFactory::getDBO();
		$qry = "SELECT * FROM #__limeticket_prod WHERE id = '".LIMETICKETJ3Helper::getEscaped($db, $prod_id)."'";
        $db->setQuery($qry);
		$rec = $db->loadObject();
		return $rec->title;	
	}

	function getProducts()
	{
		if (empty( $this->_prods )) 
		{
			$query = "SELECT * FROM #__limeticket_prod ORDER BY title";
			$db = JFactory::getDBO();
			$db->setQuery($query);
			$this->_prods = $db->loadAssocList();
		}
		return $this->_prods;
	}

	function getGroups()
	{
		if (empty( $this->_groups )) 
		{
			$query = "SELECT * FROM #__limeticket_ticket_group ORDER BY groupname";
			$db = JFactory::getDBO();
			$db->setQuery($query);
			$this->_groups = $db->loadAssocList();
		}
		return $this->_groups;
	}

	function getTags($ticketid)
	{
		if (empty( $this->_tags )) 
		{
			$db = JFactory::getDBO();
			$query = "SELECT tag FROM #__limeticket_ticket_tags WHERE ticket_id = '".LIMETICKETJ3Helper::getEscaped($db, $ticketid)."'";
			$db->setQuery($query);
			$tags = $db->loadObjectList();
			$this->_tags = array();
			foreach ($tags as $tag)
				$this->_tags[] = $tag->tag;
		}
		return $this->_tags;	
	}

	function getAnnouncements()
	{
		// get a list of announcements, including pagination and filter
			
		$db = JFactory::getDBO();
		$qry = "SELECT a.id, a.title, a.subtitle, a.published, a.added, u.name, u.username FROM #__limeticket_announce as a LEFT JOIN #__users as u ON a.author = u.id ";
		
		$qry .= " ORDER BY added DESC";
		$db->setQuery($qry);
		return $db->loadObjectList();
	}

	function getAnnouncement()
	{
		// get a list of announcements, including pagination and filter
		$id = LIMETICKET_Input::getInt('id',0);
		
		$db = JFactory::getDBO();
		$qry = "SELECT a.*, u.name, u.username FROM #__limeticket_announce as a LEFT JOIN #__users as u ON a.author = u.id ";
		
		$qry .= "WHERE a.id = '".LIMETICKETJ3Helper::getEscaped($db, $id)."'";
		
		$db->setQuery($qry);
		return $db->loadObject();
	}

}

