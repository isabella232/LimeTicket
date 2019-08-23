<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'task.php');

/**
 * Stuff related to archiving and deleting tickets
 **/

class Task_Attach extends Task_Helper
{
	function thumbnail()
	{
		// load in tickets to do	
		$ticketid = LIMETICKET_Input::getInt('ticketid'); 
		$fileid = LIMETICKET_Input::getInt('fileid'); 
		
		SupportHelper::attachThumbnail($ticketid, $fileid);
	}
	
	function view()
	{
		// load in tickets to do	
		$ticketid = LIMETICKET_Input::getInt('ticketid'); 
		$fileid = LIMETICKET_Input::getInt('fileid'); 
		
		SupportHelper::attachView($ticketid, $fileid);
	}
	
	function download()
	{
		// load in tickets to do	
		$ticketid = LIMETICKET_Input::getInt('ticketid'); 
		$fileid = LIMETICKET_Input::getInt('fileid'); 
		
		SupportHelper::attachDownload($ticketid, $fileid);
	}
	
	function delete()
	{
		if (!$this->view->can_EditTicket())
		return;
		
		// load in tickets to do	
		$ticketid = LIMETICKET_Input::getInt('ticketid'); 
		$fileid = LIMETICKET_Input::getInt('fileid'); 
		
		$ticket = new SupportTicket();
		if ($ticket->load($ticketid))
		{
			$ticket->deleteAttach($fileid);
		}
		
		JFactory::getApplication()->redirect(LIMETICKETRoute::_('index.php?option=com_limeticket&view=admin_support&layout=ticket&ticketid=' . $ticketid, false));
		exit;
	}
}