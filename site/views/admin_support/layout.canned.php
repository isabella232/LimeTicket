<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'support_canned.php');

class FssViewAdmin_Support_Canned extends FssViewAdmin_Support
{
	function display($tpl = NULL)
	{
		LIMETICKET_Helper::AddSCEditor();

		$editid = LIMETICKET_Input::getInt('cannedid', -2);
		if ($editid != -2)
		{
			if ($editid > 0)
			{
				$db = JFactory::getDBO();
				$qry = "SELECT * FROM #__limeticket_ticket_fragments WHERE id = " . LIMETICKETJ3Helper::getEscaped($db, $editid);
				$db->setQuery($qry);
				$this->canned_item = $db->loadObject();
			} else {
				$this->canned_item = new stdClass();
				$this->canned_item->id = 0;
				$this->canned_item->description = "";
				$this->canned_item->grouping = "";
				$this->canned_item->content = "";		
			}
			return $this->_display("edit");	
		}
		
		// if we are saving, then save
		$saveid = LIMETICKET_Input::getInt('saveid', -2);
		
		if ($saveid != -2)
		{
			$description = LIMETICKET_Input::getString('description');
			$grouping = LIMETICKET_Input::getString('grouping');
			$content = LIMETICKET_Input::getHTML('content');
			
			if ($saveid == 0)
			{
				$qry = "INSERT INTO #__limeticket_ticket_fragments (description, grouping, content, type) VALUES (";
				$qry .= "'" . LIMETICKETJ3Helper::getEscaped($db, $description) . "',";
				$qry .= "'" . LIMETICKETJ3Helper::getEscaped($db, $grouping) . "',";
				$qry .= "'" . LIMETICKETJ3Helper::getEscaped($db, $content) . "', 0)";
				
				$db = JFactory::getDBO();
				$db->setQuery($qry);
				$db->Query();
			} else {
				$qry = "UPDATE #__limeticket_ticket_fragments SET description = '" . LIMETICKETJ3Helper::getEscaped($db, $description) . "', ";
				$qry .= "grouping = '" . LIMETICKETJ3Helper::getEscaped($db, $grouping) . "', ";
				$qry .= "content = '" . LIMETICKETJ3Helper::getEscaped($db, $content) . "' WHERE id = " . LIMETICKETJ3Helper::getEscaped($db, $saveid);
				
				$db = JFactory::getDBO();
				$db->setQuery($qry);
				$db->Query();
			}
			
			$mainframe = JFactory::getApplication();
			$link = JRoute::_('index.php?option=com_limeticket&view=admin_support&layout=canned&tmpl=component', false);
			$mainframe->redirect($link);
		}
		// if we are editing then show edit
		
		// otherwise show list
		
		$deleteid = LIMETICKET_Input::getInt('deleteid');
		if ($deleteid > 0)
		{
			$qry = "DELETE FROM #__limeticket_ticket_fragments WHERE id = " . LIMETICKETJ3Helper::getEscaped($db, $deleteid);	
			$db = JFactory::getDBO();
			$db->setQuery($qry);
			$db->Query();
		}
		
		$search = LIMETICKET_Input::getString('search');

		if ($search)
		{
			$db = JFactory::getDBO();
			$qry = "SELECT * FROM #__limeticket_ticket_fragments WHERE type = 0 AND (description LIKE '%" . $db->escape($search) . "%' OR content LIKE '%" . $db->escape($search) . "%')";
			$db->setQuery($qry);
			$this->canned = $db->loadObjectList();
		} else {
			$this->canned = SupportCanned::GetCannedReplies();
		}
		
		$this->_display("list");
	}
}