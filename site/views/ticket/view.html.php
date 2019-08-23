<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @deprecated b3f7241e5538fe45702a675bbd17f4f8
**/
defined('_JEXEC') or die;


jimport( 'joomla.application.component.view');
jimport('joomla.filesystem.file');
jimport('joomla.utilities.date');

require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'task.php');

class LimeticketViewTicket extends LIMETICKETView
{
	function display($tpl = NULL)
	{		
		LIMETICKET_Helper::noBots();
		LIMETICKET_Helper::noCache();

		if (!LIMETICKET_Permission::AllowSupport())
		{
			return $this->noPermission("NO_PERM", "YOU_DO_NOT_HAVE_PERMISSION_TO_DO_THIS");
		}

		$autologin = LIMETICKET_Input::getCmd('login');
		if ($autologin != "") LIMETICKET_Helper::AutoLogin($autologin);

		$this->claimTickets();

		$layout = LIMETICKET_Input::getCmd('layout', 'list');	
		$layout = preg_replace("/[^a-z0-9\_]/", '', $layout);
		
		$file = JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'ticket'.DS.'layout.' . $layout . '.php';
		if (!file_exists($file))
		{
			$file = JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'ticket'.DS.'layout.list.php';
			$layout = "list";
		}
		require_once($file);
		
		$class_name = "LimeticketViewTicket_" . $layout;
		
		$layout_handler = new $class_name();
		$layout_handler->setLayout($layout);
		$layout_handler->_models = $this->_models;
		$layout_handler->_defaultModel = $this->_defaultModel;
		if (!$layout_handler->init()) return false;

		$layout_handler->display();
	}
	
	public function getName()
	{
		$this->_name = "ticket";
		return $this->_name;
	}
	
	function _display($tpl = NULL)
	{
		parent::display($tpl);	
	}
	
	function init()
	{
		$user = JFactory::getUser();
		$this->userid = $user->get('id');

		$this->model = $this->getModel("ticket");
		
		if (!LIMETICKET_Permission::auth("limeticket.ticket.view", "com_limeticket.support_user") && 
			!LIMETICKET_Permission::auth("limeticket.ticket.open", "com_limeticket.support_user"))
		return LIMETICKET_Helper::NoPerm();	

		LIMETICKET_Helper::StylesAndJS(array('calendar', 'base64'));

		if (Task_Helper::HandleTasks($this)) return false;
		
		return true;
	}
	
	function setupView()
	{
		$this->def_open = LIMETICKET_Ticket_Helper::GetStatusID('def_open');
		$this->ticket_view = LIMETICKET_Input::getCmd('tickets', $this->def_open);
		
		if (!$this->ticket_view && LIMETICKET_Settings::get('support_simple_userlist_tabs')) $this->ticket_view = "all";	
	}
	
	function loadCounts()
	{
		$this->count = SupportHelper::getUserTicketCount();	
	}
	
	
	function CanEditField($field)
	{
		if ($this->ticket->readonly) return false;
		
		if (is_array($field) && $field['type'] == "plugin")
		{
			$aparams = LIMETICKETCF::GetValues($field);
			$plugin = LIMETICKETCF::get_plugin($aparams['plugin']);
			if (!$plugin->CanEdit()) return false;
		}
		
		$peruser = "";
		
		if (is_array($field))
		{
			$peruser = $field['peruser'];			
		} else {
			$peruser = $field->peruser;
		}
		
		if ($peruser == 1)
		{
			$owner = $this->ticket->user_id;
			
			$user = JFactory::getUser();
			$userid = $user->get('id');
			if ($owner == $userid) return true;
		}
		
		return true;
	}
	
	
	function needLogin($type = 0)
	{
		// type 0 = normal
		// type 1 = dupe email
		// type 2 = no ticket
		
		$session = JFactory::getSession();
		$session->clear('ticket_pass');
		$session->clear('ticket_email');
		$session->clear('ticket_reference');
		$session->clear('ticket_find');

		$layout = LIMETICKET_Input::getCmd('layout');

		$url = LIMETICKET_Helper::getCurrentURL();
		/*if (array_key_exists('REQUEST_URI',$_SERVER))
		{
			$url = $_SERVER['REQUEST_URI'];//JURI::current() . "?" . $_SERVER['QUERY_STRING'];
		} else {
			$option = LIMETICKET_Input::getCmd('option');
			$view = LIMETICKET_Input::getCmd('view');
			$Itemid = LIMETICKET_Input::getInt('Itemid');
			$url = "index.php?option=" . $option . "&view=" . $view . "&layout=" . $layout . "&Itemid=" . $Itemid; 
		}

		$url = str_replace("&what=find","",$url);*/
		$url = base64_encode($url);

		$this->assign('type',$type);		
		$this->return = $url;

		if ($layout == "") $this->setLayout("view");	
		$this->_display("login");
	}

	function denied($message)
	{
		if (is_array($message))
		{
			$this->no_permission_title = $message['title'];	
			$this->no_permission_message = $message['body'];	
		} else {
			$this->no_permission_title = JText::_("UNABLE_TO_OPEN_TICKET");	
			$this->no_permission_message = $message;	
		}	
		
		$this->no_permission_header = true;
		
		$this->setLayout("nopermission");
		$this->_display();
	}
	
	function noPermission($pagetitle = "INVALID_TICKET", $message = "YOU_ARE_TYING_TO_EITHER_ACCESS_AN_INVALID_TICKET_OR_DO_NOT_HAVE_PERMISSION_TO_VIEW_THIS_TICKET")
	{
		if (LIMETICKET_Permission::auth("limeticket.handler", "com_limeticket.support_admin"))
		{
			// we are a ticket handler, redirect to admin link
			$ticket_id = JRequest::getVar('ticketid');
			
			JFactory::getApplication()->redirect(LIMETICKETRoute::_('index.php?option=com_limeticket&view=admin_support&layout=ticket&ticketid=' . $ticket_id, false));	
		}
		
		$this->no_permission_title = $pagetitle;
		$this->no_permission_message = $message;
		
		$this->setLayout("nopermission");
		//print_r($this->ticket);
		parent::display();	    
	}
	
	function validateUser()
	{
		$user = JFactory::getUser();
		$userid = $user->get('id');
		$this->user_type = true;
		
		if ($userid > 0) return true;
		
		$this->user_type = "unreg";
		// use email for non registered ticket
		$session = JFactory::getSession();
		$sessionemail = "";
		$reference = "";

		if ($session->Get('ticket_email')) $sessionemail = $session->Get('ticket_email');	
		if ($session->Get('ticket_reference')) $reference = $session->Get('ticket_reference');
		
		$email = LIMETICKET_Input::getEMail('email',$sessionemail);
		$reference = LIMETICKET_Input::getEMail('reference',$reference);
		$session->Set('ticket_email', $email);
		$session->Set('ticket_reference', $reference);

		if ($email == "" && $reference == "")
		{
			$this->needLogin();
			return false;
		}
		
		$this->email = $email;
		
		if (in_array(LIMETICKET_Settings::get('support_unreg_type'), array(1, 2)))
		{
			$need_pass = (LIMETICKET_Settings::get('support_unreg_type') == 1);
			
			if ($need_pass)
			{
				$sessionpass = "";
				if ($session->Get('ticket_pass')) $sessionpass = $session->Get('ticket_pass');

				$password = LIMETICKET_Input::getString('password',$sessionpass);
				$session->Set('ticket_pass', $password);
			}

			$db = JFactory::getDBO();
			
			$qry = "SELECT id FROM #__limeticket_ticket_ticket WHERE reference = '" . $db->escape($reference) . "'";
			if ($need_pass)
			$qry .= " AND password = '" . $db->escape($password) . "'";

			$db->setQuery($qry);
			$row = $db->loadAssoc();
			
			if ($row)
			{
				$this->ticketid = $row['id'];
			} else {
				$this->needLogin(2);
				return false;
			}

		} else {
			if ($email == "")
			{
				$this->needLogin(2);
				return false;
			}

			// validate ticket password and find ticket id!
			$sessionpass = "";
			if ($session->Get('ticket_pass')) $sessionpass = $session->Get('ticket_pass');

			$password = LIMETICKET_Input::getString('password',$sessionpass);
			$session->Set('ticket_pass', $password);
			
			$db = JFactory::getDBO();
			
			$qry = "SELECT id FROM #__limeticket_ticket_ticket WHERE email = '".LIMETICKETJ3Helper::getEscaped($db, $email)."' AND password = '".LIMETICKETJ3Helper::getEscaped($db, $password)."'";
			//echo $qry."<br>";
			$db->setQuery($qry);
			$row = $db->loadAssoc();
			
			if ($row)
			{
				$this->ticketid = $row['id'];
			} else {
				$this->needLogin(2);
				return false;
			}
		}
		
		//echo "New Ticket ID : " . $this->ticketid . "<Br />";
		
		return true;	
	}
	
	
	function claimTickets()
	{
		$user = JFactory::getUser();
		
		if ($user->email != "" && $user->get('id') > 0)
		{
			$db = JFactory::getDBO();
			$qry = "UPDATE #__limeticket_ticket_ticket SET user_id = " . $user->get('id') . " WHERE email = '" . LIMETICKETJ3Helper::getEscaped($db, $user->email) . "'";
			$db->setQuery($qry);
			$db->Query();
		}
	}	

}
