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

class LimeticketModelAnnounce extends JModelLegacy
{
	function __construct()
	{
		parent::__construct();
		$mainframe = JFactory::getApplication(); global $option;

		$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', LIMETICKET_Settings::Get('announce_per_page'), 'int');
		$limitstart = LIMETICKET_Input::getInt('limitstart', 0);
		
		// In case limit has been changed, adjust it
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
	
	}
	
 	function &getAnnounces( )
    {
        if (empty($this->_data)) {
            $query = $this->_buildQuery();
			if (LIMETICKET_Input::getCmd('feed') == "rss")
			{
				$this->_db->setQuery( $query, 0, 20 );
			} else {
				$this->_db->setQuery( $query, $this->getState('limitstart'), $this->getState('limit') );
			}
			$this->_data = $this->_db->loadAssocList();
        }
        return $this->_data;
    }
    
    function &getAnnounce()
    {
        $db = JFactory::getDBO();
		$announceid = LIMETICKET_Input::getInt('announceid', 0);
        $query = "SELECT * FROM #__limeticket_announce";
		
		$where = array();
		$where[] = "id = '".LIMETICKETJ3Helper::getEscaped($db, $announceid)."'";
				
		if (LIMETICKET_Permission::auth("core.edit", "com_limeticket.announce")) // we have editor so can see all unpublished arts
		{
			
		} else if (LIMETICKET_Permission::auth("core.edit.own", "com_limeticket.announce")){
			$where[] = " ( published = 1 OR author = {$this->content->userid} ) ";	
		} else {
			$where[] = "published = 1";	
		}
		
		$db = JFactory::getDBO();
		$where[] = 'language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')';
		$user = JFactory::getUser();
		$where[] = 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				
		
		if (count($where) > 0)
			$query .= " WHERE " . implode(" AND ",$where);

        $db->setQuery( $query  );
        $rows = $db->loadAssoc();
        return $rows;      
    }
 
   	function _buildQuery()
	{
		$query = "SELECT * FROM #__limeticket_announce ";
		
		$where = array();
		
		if (LIMETICKET_Permission::auth("core.edit", "com_limeticket.announce")) // we have editor so can see all unpublished arts
		{
			
		} else if (LIMETICKET_Permission::auth("core.edit.own", "com_limeticket.announce")) {
			$where[] = " ( published = 1 OR author = {$this->content->userid} ) ";	
		} else {
			$where[] = "published = 1";	
		}
			
		// add language and access to query where
		$db = JFactory::getDBO();
		$where[] = 'language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')';
		$user = JFactory::getUser();
		$where[] = 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				
	
		if (count($where) > 0)
			$query .= " WHERE " . implode(" AND ",$where);

		$query .= " ORDER BY added DESC";
		return $query;
	}

	function getTotal()
	{
        // Load the content if it doesn't already exist
        if (empty($this->_total)) {
            $query = $this->_buildQuery();
            $this->_total = $this->_getListCount($query);
        }
        return $this->_total;
	}

	function &getPagination()
	{
        // Load the content if it doesn't already exist
        if (empty($this->_pagination)) {
            jimport('joomla.html.pagination');
            $this->_pagination = new JPaginationEx($this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
        }
        return $this->_pagination;
	}
	
}

