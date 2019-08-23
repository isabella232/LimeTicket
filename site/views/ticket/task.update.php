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

class Task_Update extends Task_Helper
{
	/**
	 * Updates priority for ticket
	 */
	function ticket_pri_id()
	{	
		if (!$this->view->validateUser()) return;

		$ticketid = LIMETICKET_Input::getInt('ticketid');
		$ticket_pri_id = LIMETICKET_Input::getInt('ticket_pri_id'); 
		
		$link = LIMETICKETRoute::_('index.php?option=com_limeticket&view=ticket&layout=view&ticketid=' . $ticketid,false);

		if ($ticketid < 1 || $ticket_pri_id < 1) return $this->redirect($link);

		$ticket = new SupportTicket();
		if (!$ticket->Load($ticketid, $this->view->user_type))  return $this->redirect($link);
		
		$ticket->setupUserPerimssions();
		if (!$ticket->readonly) $ticket->updatePriority($ticket_pri_id);
				
		return $this->redirect($link);
	}
	
		
	function ticket_status_id()
	{
		if (!$this->view->validateUser()) return;

		// update status in ticket
		$ticketid = LIMETICKET_Input::getInt('ticketid');
		$ticket_status_id = LIMETICKET_Input::getInt('ticket_status_id'); 

		$link = LIMETICKETRoute::_('index.php?option=com_limeticket&view=ticket&layout=view&ticketid=' . $ticketid,false);
		
		if ($ticketid < 1 || $ticket_status_id < 1) return $this->redirect($link);


		$ticket = new SupportTicket();
		if (!$ticket->Load($ticketid, $this->view->user_type)) return $this->redirect($link);
		$ticket->setupUserPerimssions();

		// dont change read only tickets
		if ($ticket->readonly) return $this->redirect($link);

		// check permission for new status
		$statuss = SupportHelper::getStatuss();
		$new_status = $statuss[$ticket_status_id];
		
		// check we are not closing the ticket and the user is allowed etc
		if ($new_status->is_closed && !$ticket->canclose) return $this->redirect($link);

		$ticket->updateStatus($ticket_status_id);	

		return $this->redirect($link);
	}
	
	function addccuser()
	{
		if (!$this->view->validateUser()) return;

		$db	= JFactory::getDBO();
		$ticketid = LIMETICKET_Input::getInt('ticketid');
		$userid = LIMETICKET_Input::getInt('userid');
		$readonly = LIMETICKET_Input::getInt('readonly');
		
		$this->ticket = new SupportTicket();
		if (!$this->ticket->load($ticketid, $this->view->user_type)) return true;
		$this->ticket->setupUserPerimssions();
		
		if (!$this->ticket->readonly) $this->ticket->addCC($userid, false, $readonly);
		
		$this->ticket = new SupportTicket();
		$this->ticket->load($ticketid, $this->view->user_type);
		$this->ticket->loadCC();
		
		include $this->view->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_ccusers.php');
		exit;
	}
	
	function removeccuser()
	{
		if (!$this->view->validateUser()) return;

		$db	= JFactory::getDBO();
		$ticketid = LIMETICKET_Input::getInt('ticketid');
		$userid = LIMETICKET_Input::getInt('userid');
		
		$this->ticket = new SupportTicket();
		if (!$this->ticket->load($ticketid, $this->view->user_type)) return true;
		$this->ticket->setupUserPerimssions();

		if (!$this->ticket->readonly) $this->ticket->removeCC($userid, false);
		
		$this->ticket = new SupportTicket();
		$this->ticket->load($ticketid, $this->view->user_type);
		$this->ticket->loadCC();
		
		include $this->view->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_ccusers.php');
		exit;
	}
	
	function rating()
	{
		if (!$this->view->validateUser()) return;

		$messageid = LIMETICKET_Input::getInt('id');
		if ($messageid < 1) exit;
		
		$ticketid = SupportTicket::idFromMessage($messageid);
		
		$ticket = new SupportTicket();
		if (!$ticket->load($ticketid, $this->view->user_type)) exit;
		
		$rating = LIMETICKET_Input::getInt('rating');
		if ($rating < 1) exit;
		
		$ticket->rateMessage($messageid, $rating);
		
		$message = $ticket->getMessage($messageid);
		echo SupportHelper::messageRating($message, true);
		exit;
	}
	
	function ticketrating()
	{
		if (!$this->view->validateUser()) return; 

		$ticketid = LIMETICKET_Input::getInt('id');		
		$ticket = new SupportTicket();
		if (!$ticket->load($ticketid, $this->view->user_type)) exit;
		
		$rating = LIMETICKET_Input::getInt('rating');
		if ($rating < 1) exit;
		
		$ticket->rate($rating);
		echo SupportHelper::ticketRating($ticket, true);
		if (LIMETICKET_Input::getInt('nothanks') != 1) echo "<div class='thanks'>" . JText::_("TICKET_RATE_THANKS") . "</div>";
		exit;
	}
	
	function customrating()
	{
		if (!$this->view->validateUser()) return;

		list($ticketid, $customid) = explode("-", LIMETICKET_Input::getString("id"));
		$ticket = new SupportTicket();
		if (!$ticket->load($ticketid, $this->view->user_type)) exit;
		
		$ticket->setupUserPerimssions();
		
		if ($ticket->readonly) exit;
		
		$rating = LIMETICKET_Input::getInt('rating');

		if ($rating < 1) exit;
		
		$url = 'index.php?option=com_limeticket&view=ticket&task=update.customrating';
		
		$ticket->updateCustomField($customid, $rating);
		
		echo SupportHelper::ratingChoose($rating, $ticketid . "-" . $customid, JRoute::_($url), true, true, "CLICK_TO_RATE");
		exit;
	}
}