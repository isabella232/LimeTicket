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

class Task_Reply extends Task_Helper
{
	/**
	 * Updates priority for ticket
	 */
	function post()
	{
		if (!$this->_post()) return false;

		$this->setupReplyMessage();

		echo "<script>\n
		window.parent.refreshPage();
		</script>";
		//location = window.parent.location + '#message" . $this->messageid . "';\n </script>";
		exit;
	}
	
	function fullpost()
	{
		if (!$this->_post()) return false;

		$this->setupReplyMessage();

		$this->redirect($this->link);
	}

	private function setupReplyMessage()
	{
		$message = LIMETICKET_Helper::HelpText("support_message_user_reply", true);

		if ($message)
		{
			$session = JFactory::getSession();
			$session->set('ticket_message', $message);
		}
	}
	
	private function _post()
	{
		$user = JFactory::getUser();
		$userid = $user->get('id');
		$db = JFactory::getDBO();

		$ticketid = LIMETICKET_Input::getInt('ticketid');
		

		$this->link = $link = LIMETICKETRoute::_('index.php?option=com_limeticket&view=ticket&layout=view&ticketid=' . $ticketid,false);
		
		if (!$this->view->validateUser())
		{
			echo "Redirect 0";
			exit;
			$this->redirect($link);
		}

		$ticket = new SupportTicket();
		if (!$ticket->Load($ticketid, $this->view->user_type))
		{
			echo "Redirect 1";
			exit;
			return $this->redirect($link);
		}
		$ticket->setupUserPerimssions();
		
		// dont change read only tickets
		if ($ticket->readonly)
		{
			echo "Redirect 2";
			exit;
			return $this->redirect($link);
		}

		$subject = LIMETICKET_Input::getString('subject');
		$body = LIMETICKET_Input::getBBCode('body');
		$source = LIMETICKET_Input::getCmd('source');

		$messageid = -1;
		if ($body) $messageid = $ticket->addMessage($body, $subject, $userid, TICKET_MESSAGE_USER, 0, 0, 0, $source);

		$files = $ticket->addFilesFromPost($messageid, $userid);		
		$ticket->stripImagesFromMessage($messageid);		
		
		$def_user = LIMETICKET_Ticket_Helper::GetStatusID('def_user');

		// if we have requested a close of the ticket, set the status to the default closed instead of default reply
		if (LIMETICKET_Input::getInt('should_close') && LIMETICKET_Settings::get('support_user_show_close_reply') && $ticket->canclose) $def_user = LIMETICKET_Ticket_Helper::GetStatusID('def_closed');			
		if (LIMETICKET_Input::GetInt('reply_status') > 0) $def_user = LIMETICKET_Input::GetInt('reply_status');	
			
		$ticket->updateStatus($def_user);
			
		$action_params = array('subject' => $subject, 'user_message' => $body, 'files' => $files, 'status' => $def_user, 'sender' => $userid);
		SupportActions::DoAction("User_Reply", $ticket, $action_params);

		$this->messageid = $messageid;

		return true;
	}
}