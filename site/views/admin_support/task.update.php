<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'task.php');

class Task_Update extends Task_Helper
{
	/**
	 * Updates the category for a ticket
	 */	
	function ticket_cat_id()
	{
		if (!$this->view->can_EditMisc())
			exit;
		
		$ticketid = LIMETICKET_Input::getInt('ticketid');
		$ticket_cat_id = LIMETICKET_Input::getInt('ticket_cat_id');
		
		if ($ticketid < 1) exit;
		if ($ticket_cat_id < 1) exit;

		$ticket = new SupportTicket();
		if ($ticket->load($ticketid))
		{
			$ticket->updateCategory($ticket_cat_id);
			echo $ticket->getCategory()->title;
		}
		exit;
	}
	
	/**
	 * Updates priority for ticket
	 */
	function ticket_pri_id()
	{
		$ticketid = LIMETICKET_Input::getInt('ticketid');
		$ticket_pri_id = LIMETICKET_Input::getInt('ticket_pri_id'); 

		if ($ticketid < 1) exit;
		if ($ticket_pri_id < 1) exit;

		$ticket = new SupportTicket();
		if (!$ticket->Load($ticketid))
			exit;
		
		$ticket->updatePriority($ticket_pri_id);	
		exit;
	}
	
	function ticket_status_id()
	{
		// update status in ticket
		
		// redirect if the config is set to
		$ticketid = LIMETICKET_Input::getInt('ticketid');
		$ticket_status_id = LIMETICKET_Input::getInt('ticket_status_id'); 

		if ($ticketid < 1) exit;
		if ($ticket_status_id < 1) exit;

		$link = LIMETICKETRoute::_('index.php?option=com_limeticket&view=admin_support&layout=ticket&ticketid=' . $ticketid,false);
		
		// check permission for new status
		$statuss = SupportHelper::getStatuss();
		$new_status = $statuss[$ticket_status_id];
		if ($new_status->is_closed && !$this->view->can_Close())
		{
			JFactory::getApplication()->redirect($link);		
			exit;
		}

		$ticket = new SupportTicket();
		if ($ticket->Load($ticketid))
		{			
			$old_st = $ticket->getStatus();
			$ticket->updateStatus($ticket_status_id, true);	
			$new_st = $ticket->getStatus();

			// if we have closed the ticket, and return on close is set, then we should do a redirect dependant on the setting
			if ($new_st->is_closed && SupportUsers::getSetting("return_on_close"))
			{
				$link = SupportHelper::parseRedirectType($old_st->id, SupportUsers::getSetting("return_on_close"));	
			}
			
			if ($new_st->is_closed && !$old_st->is_closed)
			{
				// SEND CLOSED EMAIL HERE!
				if (!LIMETICKET_Settings::get('support_email_on_close_no_dropdown'))
					LIMETICKET_EMail::Admin_Close($ticket, $ticket->title, '', array());
			}
		}
		
		JFactory::getApplication()->redirect($link);		
		exit;
	}
	
	/**
	 * Updates email for ticket
	 */
	function email()
	{
		if (!$this->view->can_ChangeUser())
			exit;
		
		$ticketid = LIMETICKET_Input::getInt('ticketid');
		$email = trim(LIMETICKET_Input::getEMail('email'));

		if ($ticketid < 1) exit;
		if ($email == "") exit;

		$ticket = new SupportTicket();
		if (!$ticket->Load($ticketid))
			exit;
		
		if ($ticket->email == "")
			exit;
		
		$ticket->updateUnregEMail($email);	
		
		echo $email;
		exit;	
	}
	
	/**
	 * Update ticket title/subject
	 */	
	function subject()
	{
		if (!$this->view->can_EditTicket())
			exit;
		
		$ticketid = LIMETICKET_Input::getInt('ticketid');
		$subject = trim(LIMETICKET_Input::getString('subject'));

		if ($ticketid < 1) exit;
		if ($subject == "") exit;

		$ticket = new SupportTicket();
		if (!$ticket->Load($ticketid))
			exit;

		$ticket->updateSubject($subject);	
		
		echo $subject;
		exit;	
	}
	
		
	/**
	 * Update ticket lock for current user
	 */	
	function lock()
	{
		$ticketid = LIMETICKET_Input::getInt('ticketid');
		
		if ($ticketid < 1) exit;
		
		$ticket = new SupportTicket();
		if (!$ticket->Load($ticketid))
			exit;

		$ticket->updateLock();	
		
		exit;	
	}
	
	function add_tag()
	{
		if (!$this->view->can_EditMisc())
			exit;
		
		$ticketid = LIMETICKET_Input::getInt('ticketid');
		$tag = urldecode(trim(LIMETICKET_Input::getString('tag')));

		if ($ticketid < 1) exit;
		if ($tag == "") exit;

		$this->ticket = new SupportTicket();
		if (!$this->ticket->Load($ticketid))
			exit;

		$this->ticket->addTag($tag);	
		
		include $this->view->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_tags.php');
		
		exit;	
	}
	
	function remove_tag()
	{
		if (!$this->view->can_EditMisc())
			exit;
		
		$ticketid = LIMETICKET_Input::getInt('ticketid');
		$tag = urldecode(trim(LIMETICKET_Input::getString('tag')));

		if ($ticketid < 1) exit;
		if ($tag == "") exit;

		$this->ticket = new SupportTicket();
		if (!$this->ticket->Load($ticketid))
			exit;

		$this->ticket->removeTag($tag);	

		include $this->view->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_tags.php');
		
		exit;	
	}
		
	function field()
	{
		if (!$this->view->can_EditFields())
			exit;
		
		// load in tickets to do		
		$ticketid = LIMETICKET_Input::getInt('ticketid');
		$fieldid = LIMETICKET_Input::getInt('fieldid');
		$value = LIMETICKET_Input::getString("custom_" . $fieldid,"");
		$ticket = new SupportTicket();
		if ($ticket->load($ticketid))
		{
			$ticket->updateCustomField($fieldid, $value, 3);	
		}
		
		echo "<script>parent.window.location.reload();</script>";
		exit;
	}
	
	function time()
	{
		if (!$this->view->can_EditMisc())
			exit;
		
		$ticketid = LIMETICKET_Input::getInt('ticketid');
		$time = LIMETICKET_Input::getInt('time');
		$notes = LIMETICKET_Input::getString('notes');
		
		$ticket = new SupportTicket();
		if ($ticket->load($ticketid))
		{
			$ticket->addTime($time, $notes, true);	
		}
		
		echo "OK";
		exit;
	}
	
	function comment()
	{
		if (!$this->view->can_EditTicket())
			exit;
		
		$messageid = LIMETICKET_Input::getInt('messageid'); 
		$_subject = LIMETICKET_Input::getString('subject', '-'); 
		$_body = LIMETICKET_Input::getBBCode('body', '-');
	
		$body = urldecode($_body);
		$subject = urldecode($_subject);
	
		$db = JFactory::getDBO();

		$qry = "SELECT * FROM #__limeticket_ticket_messages WHERE id = " . LIMETICKETJ3Helper::getEscaped($db, $messageid);
		$db->setQuery($qry);
		$row = $db->LoadAssoc();

		$ticket = new SupportTicket();
		if ($ticket->load($row['ticket_ticket_id']))
		{		
			if (JRequest::getVar('noaudit') == 1 && LIMETICKET_Settings::get('allow_edit_no_audit'))
				$ticket->audit_changes = false;
			
			if ($subject == '-')
				$subject = $row['subject'];
			
			if ($body == '-')
				$body = $row['body'];
			
			if (LIMETICKET_Input::GetString('datefrom'))
			{
				$timestart = strtotime(LIMETICKET_Input::getString('datefrom'));
				$timeend = strtotime(LIMETICKET_Input::getString('dateto'));
				$time = (int)(($timeend - $timestart) / 60);				// procecss and update new time
				
				$ticket->updateMessage($messageid, $subject, $body, $time, $timestart, $timeend);
				echo "{reload}";
			} elseif (LIMETICKET_Input::GetString('timefrom'))
			{
				$timestart = strtotime("1970-01-01 " . LIMETICKET_Input::getString('timefrom'));
				$timeend = strtotime("1970-01-01 " . LIMETICKET_Input::getString('timeto'));
				$time = (int)(($timeend - $timestart) / 60);				// procecss and update new time
				$ticket->updateMessage($messageid, $subject, $body, $time, $timestart, $timeend);
				echo "{reload}";
			} elseif (JRequest::getVar('timehours', '-') != '-')
			{
				$time = (int)(LIMETICKET_Input::getInt('timehours') * 60 + LIMETICKET_Input::getInt('timemins'));
				$ticket->updateMessage($messageid, $subject, $body, $time);
				echo "{reload}";
			} else {
				$ticket->updateMessage($messageid, $subject, $body);				
				echo "<h1>" . $_subject . "</h1>";
				echo $_body;
			}
		}

		exit;		
	}
	
	function delete_message()
	{
		if (!$this->view->can_EditTicket())
			exit;
		
		$messageid = LIMETICKET_Input::getInt('messageid'); 
		$subject = LIMETICKET_Input::getString('subject'); 

		$body = LIMETICKET_Input::getBBCode('body');
		
		$body = urldecode($body);
		$subject = urldecode($subject);
		
		$db = JFactory::getDBO();

		$qry = "SELECT * FROM #__limeticket_ticket_messages WHERE id = " . LIMETICKETJ3Helper::getEscaped($db, $messageid);
		$db->setQuery($qry);
		$row = $db->LoadAssoc();

		$ticket = new SupportTicket();
		if ($ticket->load($row['ticket_ticket_id']))
		{
			$ticket->deleteMessage($messageid, $subject, $body);
			
			if ($row['time'] > 0)
				$ticket->addTime(- $row['time']);	
		}

		exit;		
	}
}