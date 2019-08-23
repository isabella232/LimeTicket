<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

/**
 * SupportActionsJomSocial
 * 
 * Adds PM using Jom social as ticket notification
 **/

/**
 * This plugin needs to be updated for the 2.5 email system
 **/

require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'email.php');

class SupportActionsJomSocial extends SupportActionsPlugin
{
	var $title = "JomSocial (NO LONGER AVAILABLE)";
	var $description = "The JomSocial plugin is no longer available, as it needs to be fully rewritten with the new email system.";
	
	/*function Admin_Reply($ticket, $params)
	{
		if (!$params['user_message'])
		return;
		
		$status = $ticket->getStatus();
		
		if ($status->is_closed)
		{
			LIMETICKET_EMail_JomSocial::Admin_Close($ticket, $params['subject'], $params['user_message'], $params['files']);
		} else {
			LIMETICKET_EMail_JomSocial::Admin_Reply($ticket, $params['subject'], $params['user_message'], $params['files']);
		}
	}
	
	function Admin_Private($ticket, $params)
	{
		if (!$params['handler_message'])
		return;
		
		if (JFactory::getUser()->id != $ticket->admin_id)
		{
			LIMETICKET_EMail_JomSocial::Admin_Forward($ticket, $params['subject'], $params['handler_message'], $params['files']);
		}
	}
	
	function Admin_ForwardUser($ticket, $params)
	{
		if (!$params['user_message'])
		return;
		
		LIMETICKET_EMail_JomSocial::User_Create($ticket, $params['subject'], $params['user_message'], $params['files']);
	}
	
	function Admin_ForwardProduct($ticket, $params)
	{
		if ($ticket->admin_id > 0)
		{
			if ($params['handler_message'])
			{
				LIMETICKET_EMail_JomSocial::Admin_Forward($ticket, $params['subject'], $params['handler_message'], $params['files']);
			} else if ($params['user_message']) {
				LIMETICKET_EMail_JomSocial::Admin_Forward($ticket, $params['subject'], $params['user_message'], $params['files']);
			}	
		}
		
		if ($params['user_message'])
		LIMETICKET_EMail_JomSocial::Admin_Reply($ticket, $params['subject'], $params['user_message'], $params['files']);
	}
	
	function Admin_ForwardHandler($ticket, $params)
	{
		if ($params['handler_message'])
		{
			LIMETICKET_EMail_JomSocial::Admin_Forward($ticket, $params['subject'], $params['handler_message'], $params['files']);
		} else if ($params['user_message']) {
			LIMETICKET_EMail_JomSocial::Admin_Forward($ticket, $params['subject'], $params['user_message'], $params['files']);
		}
		
		if ($params['user_message'])
		LIMETICKET_EMail_JomSocial::Admin_Reply($ticket, $params['subject'], $params['user_message'], $params['files']);
	}
	
	function User_Open($ticket, $params)
	{
		if ($ticket->email)
		{
			LIMETICKET_EMail_JomSocial::User_Create_Unreg($ticket, $params['subject'], $params['user_message'], $params['files']);
		} else {
			LIMETICKET_EMail_JomSocial::User_Create($ticket, $params['subject'], $params['user_message'], $params['files']);
		}
		LIMETICKET_EMail_JomSocial::Admin_Create($ticket, $params['subject'], $params['user_message'], $params['files']);
	}
	
	function User_Reply($ticket, $params)
	{
		if ($params['user_message'])
		LIMETICKET_EMail_JomSocial::User_Reply($ticket, $params['subject'], $params['user_message'], $params['files']);
	}
	
	function CanEnable()
	{
		@include (JPATH_SITE.DS.'components'.DS.'com_community'.DS.'models'.DS.'inbox.php');
		
		if (!class_exists("CommunityModelInbox"))
		return true;
		
		return "Jom Social not installed";
	}*/	
}

/**
 * Extend email class, and override the send function
 * 
 * This allows the email to be intercepted and instead of being sent as an email 
 * added into the database as a personal message
 **/
/*class LIMETICKET_EMail_JomSocial extends LIMETICKET_EMail
{
	static function Admin_Reply(&$ticket, $subject, $body, $files = array())
	{
		if (self::ShouldSend('email_on_reply') == 1)
		self::EMail_To_Ticket_User('email_on_reply', $ticket, $subject, $body, $files);
	}

	static function EMail_To_Ticket_Handler($template, $ticket, $subject, $body, $files, $sender)
	{
		$ticket = LIMETICKET_Helper::ObjectToArray($ticket);

		$mailer = new LIMETICKETMailer();
		self::Ticket_To_Admins($mailer, $ticket);
		
		// parse template etc
		$template = self::Get_Template($template);
		$email = self::ParseTemplate($template,$ticket,$subject,$body,$template['ishtml']);

		$mailer->isHTML($template['ishtml']);
		$mailer->setSubject($email['subject']);
		$mailer->setBody($email['body']);

		if (LIMETICKET_Settings::get('support_email_file_handler') == 1){
			$mailer->addFiles($files);
		}
		
		$mailer->addDebug('Ticket', $ticket);

		self::Send($mailer);
	}

	static function EMail_To_Ticket_User($template_name, $ticket, $subject = "", $body = "", $files = array())
	{
		$cc_admins = $template_name == "email_on_create" || 
			$template_name == "email_on_create_unreg" || 
			$template_name == "email_unreg_passwords" 
			? false : true;

		$is_create = $template_name == "email_on_create" || 
			$template_name == "email_on_create_unreg" 
			? true : false;

		$ticket = LIMETICKET_Helper::ObjectToArray($ticket);
		
		$db = JFactory::getDBO();

		$mailer = new LIMETICKETMailer();
		self::Ticket_To_Users($mailer, $ticket);

		// Add bcc to admins if set up. Dont send when creating a ticket as they get notifications from
		// Admin_Create function
		if ($cc_admins && LIMETICKET_Settings::get('support_email_bcc_handler')) 
		self::Ticket_To_Admins($mailer, $ticket);

		// build template and parse it
		$template = self::Get_Template($template_name, $ticket['lang']);	
		$email = self::ParseTemplate($template,$ticket,$subject,$body,$template['ishtml'], true);
		
		// set result to mailer
		$mailer->isHTML($template['ishtml']);
		$mailer->setSubject($email['subject']);
		$mailer->setBody($email['body']);

		// Only send attachments to users when creating if the ticket is created by an admin

		$session = JFactory::getSession();
		if (LIMETICKET_Settings::get('support_email_file_user') && (!$is_create || $session->Get('admin_create') > 0)) 
		$mailer->addFiles($files);

		// add debug info
		$mailer->addDebug('Ticket', $ticket);

		// send actual mail
		self::Send($mailer);
	}

	static function Send($mailer)
	{
		@include (JPATH_SITE.DS.'components'.DS.'com_community'.DS.'models'.DS.'inbox.php');
		
		if (!class_exists("CommunityModelInbox"))
		return;
		
		$from_uid = 0;
		
		$db = JFactory::getDBO();
		
		$msg = $mailer->Subject . "\n" . $mailer->getBody();

		$all_to = $mailer->getAllTo();

		foreach ($all_to as $email => $name)
		{
			$qry = "SELECT * FROM #__users WHERE email = '" . $db->escape($email) . "'";
			$db->SetQuery($qry);
			$user = $db->loadObject();
			
			if ($user && $user->id > 0)
			{
				$date = date("Y-m-d H:i:s");
				$body = $mailer->getBody();
				
				$qry = "INSERT INTO #__community_msg (`from`, from_name, posted_on, subject, body) VALUES (";
				$qry .= "2478, 'Support Portal', '" . $date . "', '" . $db->escape($mailer->getSubject()) . "', '" . $db->escape($body) . "')";
				$db->setQuery($qry);
				$db->Query();
				
				$insert = $db->insertid();
				
				$qry = "INSERT INTO #__community_msg_recepient (msg_id, `to`) VALUES (" . $insert . ", " . $user->id . ")"; 
				$db->setQuery($qry);
				$db->Query();
			}
		}
	}
	
	static function ShouldSend($tag)
	{
		return true;
	}
} */