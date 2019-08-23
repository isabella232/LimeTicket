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

class Task_Canned extends Task_Helper
{
	function dolist()
	{
		$ticketid = LIMETICKET_Input::getInt('ticketid');
		$ticket = new SupportTicket();
		$ticket->load($ticketid);
		echo SupportCanned::CannedList($ticket);
		exit;
	}
	
	function dropdown()
	{
		$ticketid = LIMETICKET_Input::getInt('ticketid');
		$ticket = new SupportTicket();
		$ticket->load($ticketid);
		echo SupportCanned::CannedDropdown(LIMETICKET_Input::getCmd('elem'), false, $ticket);
		exit;
	}
}