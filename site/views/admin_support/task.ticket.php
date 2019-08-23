<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'task.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'email.php');

/**
 * Stuff related to archiving and deleting tickets
 **/

class Task_Ticket extends Task_Helper
{
	function resend_password()
	{
		$this->loadTicket();
		LIMETICKET_Settings::set('support_email_on_create', 1);
		LIMETICKET_EMail::User_Create_Unreg($this->ticket, $this->ticket->title, JText::_('RESENDING_TICKET_PASSWORD'));
		
		$link = LIMETICKETRoute::_("index.php?option=com_limeticket&view=admin_support&layout=ticket&ticketid=" . $this->ticket->id, false);
			
		JFactory::getApplication()->redirect($link, JText::_("PASSWORD_FOR_TICKET_HAS_BEEN_RESENT_TO_THE_USER"));
		
		return false;
	}
	
	function resend_all_passwords()
	{
		$this->loadTicket();
		
		$email = $this->ticket->email;
		
		$tickets = new SupportTickets();
		$tickets->limitstart = 0;
		$tickets->limit = 500;
		$tickets->loadTicketsByQuery(array("t.email = '$email'"), "lastupdate DESC");
		
		LIMETICKET_EMail::User_Unreg_Passwords($tickets->tickets);
		
		$link = LIMETICKETRoute::_("index.php?option=com_limeticket&view=admin_support&layout=ticket&ticketid=" . $this->ticket->id, false);	
		JFactory::getApplication()->redirect($link, JText::_("PASSWORDS_FOR_ALL_THIS_USERS_TICKETS_HAVE_BEEN_SENT"));
		
		return false;
	}
	
	private function loadTicket()
	{
		$ticketid = LIMETICKET_Input::getInt('ticketid');
		$this->ticket = new SupportTicket();
		$this->ticket->load($ticketid);
	}
	
	function addcc()
	{
		$this->loadTicket();
		if ($this->ticket)
		{
			$ids = LIMETICKET_Input::getString('ids');
			$is_admin = LIMETICKET_Input::getInt('is_admin');
			$is_readonly = LIMETICKET_Input::getInt('is_readonly');
			$ids = explode(",", $ids);
			
			if (count($ids) > 0)
			{
				
				if ($is_admin && $this->ticket->admin_id < 1)
				{
					$new_handler_id = $ids[0];
					unset($ids[0]);
					
					$this->ticket->assignHandler($new_handler_id, TICKET_ASSIGN_FORWARD);
					$action_name = "Admin_ForwardHandler";
					$action_params = array('subject' => $subject, 'user_message' => '', 'handler_message' => '', 'handler_id' => $new_handler_id);
					SupportActions::DoAction($action_name, $this->ticket, $action_params);					
				}
						
				$this->ticket->addCC($ids, $is_admin, $is_readonly);
			}	
		}
		
		$link = LIMETICKETRoute::_("index.php?option=com_limeticket&view=admin_support&layout=ticket&ticketid=" . $this->ticket->id, false);	
		JFactory::getApplication()->redirect($link);
	}
	
	function addemailcc()
	{
		$this->loadTicket();
		if ($this->ticket)
		{
			$email = LIMETICKET_Input::getString('email');
			
			if ($email)
			{
				$this->ticket->addEMailCC($email);
			}
		}
		
		$link = LIMETICKETRoute::_("index.php?option=com_limeticket&view=admin_support&layout=ticket&ticketid=" . $this->ticket->id, false);	
		JFactory::getApplication()->redirect($link);
	}
	
	function removecc()
	{
		$this->loadTicket();
		if ($this->ticket)
		{
			$ids = LIMETICKET_Input::getString('ids');
			$is_admin = LIMETICKET_Input::getInt('is_admin');
			
			if ($ids != "")
			{
				$ids = explode(",", $ids);
			} else {
				$ids = array();
			}
			if (LIMETICKET_Input::getInt('urid') > 0) $ids[] = LIMETICKET_Input::getInt('urid');
			$this->ticket->removeCC($ids, $is_admin);	

		}
		
		if (LIMETICKET_Input::getInt('nr') != 1)
		{
			$link = LIMETICKETRoute::_("index.php?option=com_limeticket&view=admin_support&layout=ticket&ticketid=" . $this->ticket->id, false);	
			JFactory::getApplication()->redirect($link);
		} else {
			exit;	
		}
	}

	function delete()
	{
		$this->loadTicket();
		
		if ($this->view->can_EditTicket())
		{
			$this->ticket->delete();
		}

		$link = LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=admin_support&tickets=' . $this->ticket->ticket_status_id );
		JFactory::getApplication()->redirect($link);
	}
}