<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'task.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'support_canned.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'support_ticket.php');

/**
 * Stuff related to archiving and deleting tickets
 **/

class Task_Signature extends Task_Helper
{
	function delete()
	{
		$deleteid = LIMETICKET_Input::getInt('deleteid');
		if ($deleteid > 0)
		{
			$qry = "DELETE FROM #__limeticket_ticket_fragments WHERE id = " . LIMETICKETJ3Helper::getEscaped($db, $deleteid);	
			$db = JFactory::getDBO();
			$db->setQuery($qry);
			$db->Query();
		}
		
		$mainframe = JFactory::getApplication();
		$link = JRoute::_('index.php?option=com_limeticket&view=admin_support&layout=signature&tmpl=component');
		$mainframe->redirect($link);	
	}
	
	function save()
	{	
		// if we are saving, then save
		$saveid = LIMETICKET_Input::getInt('saveid', -1);
		
		if ($saveid != -1)
		{
			$description = LIMETICKET_Input::getString('description');
			$is_personal = LIMETICKET_Input::getInt('personal');
			$content = LIMETICKET_Input::getHTML('content');
			
			$params = array();
			
			if ($is_personal)
				$params['userid'] = JFactory::getUser()->id;
			
			$params = json_encode($params);
			
			if ($saveid == 0)
			{
				$qry = "INSERT INTO #__limeticket_ticket_fragments (description, params, content, type) VALUES (";
				$qry .= "'" . LIMETICKETJ3Helper::getEscaped($db, $description) . "',";
				$qry .= "'" . LIMETICKETJ3Helper::getEscaped($db, $params) . "',";
				$qry .= "'" . LIMETICKETJ3Helper::getEscaped($db, $content) . "', 1)";
				
				$db = JFactory::getDBO();
				$db->setQuery($qry);
				$db->Query();
			} else {
				$qry = "UPDATE #__limeticket_ticket_fragments SET description = '" . LIMETICKETJ3Helper::getEscaped($db, $description) . "', ";
				$qry .= "params = '" . LIMETICKETJ3Helper::getEscaped($db, $params) . "', ";
				$qry .= "content = '" . LIMETICKETJ3Helper::getEscaped($db, $content) . "' WHERE id = " . LIMETICKETJ3Helper::getEscaped($db, $saveid);
				
				$db = JFactory::getDBO();
				$db->setQuery($qry);
				$db->Query();
			}
		}
		
		$mainframe = JFactory::getApplication();
		$link = JRoute::_('index.php?option=com_limeticket&view=admin_support&layout=signature&tmpl=component', false);
		$mainframe->redirect($link);
	}
	
	function setdefault()
	{
		$sigid = LIMETICKET_Input::getInt('sigid');
		
		SupportUsers::updateSingleSetting("default_sig", $sigid);
		
		$mainframe = JFactory::getApplication();
		$link = JRoute::_('index.php?option=com_limeticket&view=admin_support&layout=signature&tmpl=component', false);
		$mainframe->redirect($link);
	}
	
	function dropdown()
	{
		include $this->view->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_signature_dropdown.php');
		exit;
	}
	
	function preview()
	{
		$this->ticketid = LIMETICKET_Input::getInt('ticketid');
		$this->sigid = LIMETICKET_Input::getInt('sigid');
		
		$ticket = new SupportTicket();
		$ticket->load($this->ticketid);
		$ticket->loadAll();
		
		$this->ticket = $ticket;

		$this->signature = SupportCanned::AppendSig($this->sigid, $this->ticket);
	
		include $this->view->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_signature_preview.php');
		return true;
	}
}