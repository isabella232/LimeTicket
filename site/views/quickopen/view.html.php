a<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @deprecated 68c1d5adf340ea5090ecdcbe1ebd377b
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');

require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'pagination.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'tickethelper.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'fields.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'email.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'helper.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'parser.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'support_actions.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'support_ticket.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'support_tickets.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'third'.DS.'simpleimage.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'multicol.php');

class FssViewQuickOpen extends LIMETICKETView
{
	function display($tpl = null)
	{
		if (LIMETICKET_Settings::Get('support_only_admin_open'))
			return $this->noPermission("Access Denied", "CREATING_NEW_TICKETS_BY_USERS_IS_CURRENTLY_DISABLED");	
		
		if (!LIMETICKET_Permission::auth("limeticket.ticket.open", "com_limeticket.support_user"))
			return LIMETICKET_Helper::NoPerm();	

		$mainframe = JFactory::getApplication();
		$user = JFactory::getUser();
		$userid = $user->get('id');
		
		$this->assign('userid',$userid);
		$this->assign('email','');
		
		// defaults for blank ticket
		
		$this->ticket = new stdClass();
		$this->ticket->prodid = LIMETICKET_Input::getInt('prodid');
		$this->ticket->deptid = LIMETICKET_Input::getInt('deptid');
		$this->ticket->catid = LIMETICKET_Input::getInt('catid');
		$this->ticket->priid = LIMETICKET_Input::getInt('priid');
		$this->ticket->subject = LIMETICKET_Input::getString('subject');
		$this->ticket->body = LIMETICKET_Input::getBBCode('body');
		
		$this->errors['subject'] = '';
		$this->errors['body'] = '';
		$this->errors['cat'] = '';

		$what = LIMETICKET_Input::getCmd('what');
		
		// done with ticket, try and save, if not, display any errors
		if ($what == "add")
		{
			if ($this->saveTicket())
			{
				echo "Saved OK!";
				exit;
				$link = 'index.php?option=com_limeticket&view=ticket&layout=view&Itemid=' . LIMETICKET_Input::getInt('Itemid') . '&ticketid=' . $this->ticketid;
				$mainframe->redirect($link);
				return;
			}
		}

		$this->product = $this->get('Product');
		$this->dept = $this->get('Department');
		$this->cats = SupportHelper::getCategories();
		$this->pris = SupportHelper::getPriorities();
		
		$this->support_user_attach = LIMETICKET_Settings::get('support_user_attach');
		
		$this->fields = LIMETICKETCF::GetCustomFields(0,$prodid,$deptid);

		parent::display();
	}
	
	
	function saveTicket()
	{
		$name = "";
		
		$db = JFactory::getDBO();

		$ok = true;
		$this->errors['subject'] = '';
		$this->errors['body'] = '';
		$this->errors['cat'] = '';
		
		if (LIMETICKET_Settings::get('support_subject_message_hide') == "subject")
		{
			$ticket->subject = substr(strip_tags($ticket->body), 0, 40);
		} else if ($ticket->subject == "")
		{
			$this->errors['subject'] = JText::_("YOU_MUST_ENTER_A_SUBJECT_FOR_YOUR_SUPPORT_TICKET");	
			$ok = false;
		}
		
		if (LIMETICKET_Settings::get('support_altcat'))
		{
			$cats = $this->get('Cats');
			
			if (count($cats) > 0 && $catid == 0)
			{
				$this->errors['cat'] = JText::_("YOU_MUST_SELECT_A_CATEGORY");	
				$ok = false;
			}
		}
		
		if ($body == "" && LIMETICKET_Settings::get('support_subject_message_hide') != "message")
		{
			$this->errors['body'] = JText::_("YOU_MUST_ENTER_A_MESSAGE_FOR_YOUR_SUPPORT_TICKET");	
			$ok = false;
		}
		
		$fields = LIMETICKETCF::GetCustomFields(0,$prodid,$deptid);
		if (!LIMETICKETCF::ValidateFields($fields,$this->errors))
		{
			$ok = false;	
		}
		
		$email = "";
		$password = "";
		$now = LIMETICKET_Helper::CurDate();
		
		if ($ok)
		{		
			/*$admin_id = LIMETICKET_Ticket_Helper::AssignHandler($prodid, $deptid, $catid);
			
			$now = LIMETICKET_Helper::CurDate();
			
			$def_open = LIMETICKET_Ticket_Helper::GetStatusID('def_open');
			
			$qry = "INSERT INTO #__limeticket_ticket_ticket (reference, ticket_status_id, ticket_pri_id, ticket_cat_id, ticket_dept_id, prod_id, title, opened, lastupdate, user_id, admin_id, email, password, unregname, lang) VALUES ";
			$qry .= "('', $def_open, '".LIMETICKETJ3Helper::getEscaped($db, $priid)."', '".LIMETICKETJ3Helper::getEscaped($db, $catid)."', '".LIMETICKETJ3Helper::getEscaped($db, $deptid)."', '".LIMETICKETJ3Helper::getEscaped($db, $prodid)."', '".LIMETICKETJ3Helper::getEscaped($db, $subject)."', '{$now}', '{$now}', '".LIMETICKETJ3Helper::getEscaped($db, $userid)."', '".LIMETICKETJ3Helper::getEscaped($db, $admin_id)."', '{$email}', '".LIMETICKETJ3Helper::getEscaped($db, $password)."', '{$name}', '".JFactory::getLanguage()->getTag()."')";
			

			$db->setQuery($qry);$db->Query();
			$this->ticketid = $db->insertid();
			
			$ref = LIMETICKET_Ticket_Helper::createRef($this->ticketid);

			$qry = "UPDATE #__limeticket_ticket_ticket SET reference = '".LIMETICKETJ3Helper::getEscaped($db, $ref)."' WHERE id = '" . LIMETICKETJ3Helper::getEscaped($db, $this->ticketid) . "'";  
			$db->setQuery($qry);$db->Query();


			$qry = "INSERT INTO #__limeticket_ticket_messages (ticket_ticket_id, subject, body, user_id, posted) VALUES ('";
			$qry .= LIMETICKETJ3Helper::getEscaped($db, $this->ticketid) . "','".LIMETICKETJ3Helper::getEscaped($db, $subject)."','".LIMETICKETJ3Helper::getEscaped($db, $body)."','".LIMETICKETJ3Helper::getEscaped($db, $userid)."','{$now}')";
			
			$db->setQuery($qry);$db->Query();
			$messageid = $db->insertid();
			
			LIMETICKETCF::StoreFields($fields,$this->ticketid);
			
			
			$files = array();
			// save any uploaded file
			
			for ($i = 1; $i < 10; $i++)
			{
				$file = JRequest::getVar('filedata_' . $i, '', 'FILES', 'array');
				if (array_key_exists('error',$file) && $file['error'] == 0 && $file['name'] != '')
				{
					$destpath = JPATH_COMPONENT_SITE.DS.'files'.DS.'support'.DS;					
					$destname = md5(mt_rand(0,999999).'-'.$file['name']); 
					
					while (JFile::exists($destpath . $destname))
					{
						$destname = md5(mt_rand(0,999999).'-'.$file['name']);               
					}
					
					if (JFile::upload($file['tmp_name'], $destpath . $destname))
					{
						$qry = "INSERT INTO #__limeticket_ticket_attach (ticket_ticket_id, filename, diskfile, size, user_id, added, message_id) VALUES ('";
						$qry .= LIMETICKETJ3Helper::getEscaped($db, $this->ticketid) . "',";
						$qry .= "'" . LIMETICKETJ3Helper::getEscaped($db, $file['name']) . "',";
						$qry .= "'" . LIMETICKETJ3Helper::getEscaped($db, $destname) . "',";
						$qry .= "'" . $file['size'] . "',";
						$qry .= "'" . LIMETICKETJ3Helper::getEscaped($db, $userid) . "',";
						$qry .= "'{$now}', $messageid )";
						
						
						$file_obj = new stdClass();
						$file_obj->filename = $file['name'];
						$file_obj->diskfile = $destname;
						$file_obj->size = $file['size'];
						$files[] = $file_obj;
						
						
						$db->setQuery($qry);$db->Query();     
					} else {
						// ERROR : File cannot be uploaded! try permissions	
					}
				}
			}
			
			$t = new SupportTicket();
			$t->load($this->ticketid, true);
			
			$subject = JRequest::getVar('subject','','','string');
			$body = JRequest::getVar('body','','','string', JREQUEST_ALLOWRAW);
			
			$action_name = "User_Open";
			$action_params = array('subject' => $subject, 'user_message' => $body, 'files' => $files);
			SupportActions::DoAction($action_name, $t, $action_params);*/
		}
		
		$this->errors = $errors;
		$this->ticket = $ticket;

		return $ok;
	}
	
	function noPermission($pagetitle = "INVALID_TICKET", $message = "YOU_ARE_TYING_TO_EITHER_ACCESS_AN_INVALID_TICKET_OR_DO_NOT_HAVE_PERMISSION_TO_VIEW_THIS_TICKET")
	{
		//echo dumpStack();
		
		$this->no_permission_title = $pagetitle;
		$this->no_permission_message = $message;
		
		$this->setLayout("nopermission");
		//print_r($this->ticket);
		parent::display();	    
	}

}
