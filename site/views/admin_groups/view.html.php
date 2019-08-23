<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @deprecated 823a802a7e35dd8a17c991422ae202bd
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');
jimport('joomla.filesystem.file');
jimport('joomla.utilities.date');

require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'admin_helper.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'support_actions.php');

class FssViewAdmin_Groups extends LIMETICKETView
{
    function display($tpl = null)
    {
		LIMETICKET_Helper::noBots();
		LIMETICKET_Helper::noCache();
		
		if (!LIMETICKET_Permission::AdminGroups())
			return LIMETICKET_Admin_Helper::NoPerm();
	
		$this->groupid = LIMETICKET_Input::getInt('groupid');
		
		$what = LIMETICKET_Input::getCmd('what');
		
		if (JRequest::getVar('messages') != "")
			return $this->redirectMessages();

		if ($what == "productlist")
			return $this->DisplayProducts();
			
		if ($what == "setperm")
			return $this->SetPerm();	
		
		if ($what == "toggleallemail")
			return $this->ToggleAllEMail();
		
		if ($what == "toggleadmin")
			return $this->ToggleIsAdmin();
			
		if ($what == "pickuser")
			return $this->PickUser();
	
		if ($what == "adduser")
			return $this->AddUser();
		
		if ($what == "removemembers")
			return $this->RemoveUsers();
			
		if ($what == "savegroup" || $what == "saveclose")
			return $this->SaveGroup($what);
			
		if ($what == "create")
			return $this->CreateGroup();
		
		if ($what == "deletegroup")
			return $this->DeleteGroup();
		
		if ($this->groupid > 0)
			return $this->DisplayGroup();
		
		return $this->DisplayGroupList();
		
		parent::display();
    }

	function RedirectMessages()
	{
		$messages = explode("|", JRequest::getVar("messages"));
		foreach ($messages as $message)
		{
			JFactory::getApplication()->enqueueMessage($message, 'warning');
		}
		JFactory::getApplication()->redirect(LIMETICKET_Helper::getCurrentURL(true, array("messages" => "*")));
	}
	
	function getGroupPerms()
	{
		$this->group_id_access = array();
		
		$model = $this->getModel();
		$model->group_id_access = $this->group_id_access;
	}

	function DisplayGroup()
	{
		$this->creating = false;
		
		$groupid = LIMETICKET_Input::getInt('groupid');
		if (!$this->canAdminGroup($groupid))
			return LIMETICKET_Admin_Helper::NoPerm();
		
		$this->group = $this->get('Group');
		$this->groupmembers = $this->get('GroupMembers');
		//print_p($this->groupmembers);
		
		$this->pagination = $this->get('GroupMembersPagination');
	
		$mainframe = JFactory::getApplication();
		$pathway = $mainframe->getPathway();
		if (LIMETICKET_Helper::NeedBaseBreadcrumb($pathway, array( 'view' => 'groups' )))	
			$pathway->addItem(JText::_('TICKET_GROUPS'), LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=admin_groups' ) );
		$pathway->addItem($this->group->groupname );

		$this->buildGroupEditForm();

		$this->order = LIMETICKET_Input::getCmd('filter_order');
		$this->order_Dir = LIMETICKET_Input::getCmd('filter_order_Dir');
		$this->limit_start = LIMETICKET_Input::getInt("limit_start");

		LIMETICKET_Helper::IncludeModal();

		parent::display('group');
	}
	
	function buildGroupEditForm()
	{
		$db = JFactory::getDBO();
		
		$idents = array();
		$idents[] = JHTML::_('select.option', '0', JText::_("VIEW_NONE"), 'id', 'title');
		$idents[] = JHTML::_('select.option', '1', JText::_("VIEW"), 'id', 'title');
		$idents[] = JHTML::_('select.option', '2', JText::_("VIEW_REPLY"), 'id', 'title');			
		$idents[] = JHTML::_('select.option', '3', JText::_("VIEW_REPLY_CLOSE"), 'id', 'title');			
		$this->allsee = JHTML::_('select.genericlist',  $idents, 'allsee', ' class="inputbox" size="1"', 'id', 'title', $this->group->allsee);

		$this->allprod = JHTML::_('select.booleanlist', 'allprods', 
			array('class' => "inputbox inline",
				'size' => "1", 
				'onclick' => "DoAllProdChange();"),
			 intval($this->group->allprods));

		$query = "SELECT * FROM #__limeticket_prod WHERE insupport = 1 AND published = 1 ORDER BY title";
		$db->setQuery($query);
		$products = $db->loadObjectList();

		$query = "SELECT * FROM #__limeticket_ticket_group_prod WHERE group_id = " . LIMETICKETJ3Helper::getEscaped($db, $this->group->id);
		$db->setQuery($query);
		$selprod = $db->loadAssocList('prod_id');
		
		$this->assign('allprods',$this->group->allprods);
		
		$prodcheck = "";
		foreach($products as $product)
		{
			$checked = false;
			if (array_key_exists($product->id,$selprod))
			{
				$prodcheck .= '<label class="checkbox">';
				$prodcheck .= '<input type="checkbox" name="prod_' . $product->id . '" checked>' . $product->title;
				$prodcheck .= '</label>';
				
				//$prodcheck .= "<input type='checkbox' name='prod_" . $product->id . "' checked />" . $product->title . "<br>";
			} else {
				$prodcheck .= '<label class="checkbox">';
				$prodcheck .= '<input type="checkbox" name="prod_' . $product->id . '">' . $product->title;
				$prodcheck .= '</label>';
				//$prodcheck .= "<input type='checkbox' name='prod_" . $product->id . "' />" . $product->title . "<br>";
			}
		}
		$this->products = $prodcheck;	
		
		$this->order = "";
		$this->order_Dir = "";
	}
		
	function DisplayGroupList()
	{
		$this->groups = $this->get('Groups');
		
		$mainframe = JFactory::getApplication();
		$pathway = $mainframe->getPathway();
		if (LIMETICKET_Helper::NeedBaseBreadcrumb($pathway, array( 'view' => 'groups' )))	
			$pathway->addItem(JText::_('TICKET_GROUPS'), LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=admin_groups' ) );
			
		LIMETICKET_Helper::IncludeModal();
			
		parent::display();
	}
	
	function DisplayProducts()
	{
		$this->products = $this->get('GroupProds');
		$this->group = $this->get('Group');
		
		parent::display('prods');
		
		exit;	
	}
	
	function SetPerm()
	{
		$db	= JFactory::getDBO();
		
		$userid = LIMETICKET_Input::getInt('userid');
		$groupid = LIMETICKET_Input::getInt('groupid');
		$perm = LIMETICKET_Input::getString('perm');
		
		if (!$this->canAdminGroup($groupid))
			return;
		
		$qry = "UPDATE #__limeticket_ticket_group_members SET allsee = '".LIMETICKETJ3Helper::getEscaped($db, $perm)."' WHERE user_id = '".LIMETICKETJ3Helper::getEscaped($db, $userid)."' AND group_id = '".LIMETICKETJ3Helper::getEscaped($db, $groupid)."'";
		
		$db->setQuery($qry);
		$db->Query();
		
		echo "1";
		
		exit;		
	}
	
	function ToggleIsAdmin()
	{
		$db	= JFactory::getDBO();
		
		$userid = LIMETICKET_Input::getInt('userid');
		$groupid = LIMETICKET_Input::getInt('groupid');
		
		if (!$this->canAdminGroup($groupid))
			return;
			
		$qry = "SELECT isadmin FROM #__limeticket_ticket_group_members WHERE user_id = '".LIMETICKETJ3Helper::getEscaped($db, $userid)."' AND group_id = '".LIMETICKETJ3Helper::getEscaped($db, $groupid)."'";
		$db->setQuery($qry);
		
		$current = $db->loadObject();
		$isadmin = $current->isadmin;
		$isadmin = 1 - $isadmin;
		
		$qry = "UPDATE #__limeticket_ticket_group_members SET isadmin = '".LIMETICKETJ3Helper::getEscaped($db, $isadmin)."' WHERE user_id = '".LIMETICKETJ3Helper::getEscaped($db, $userid)."' AND group_id = '".LIMETICKETJ3Helper::getEscaped($db, $groupid)."'";
		
		$db->setQuery($qry);
		$db->Query();
		
		echo LIMETICKET_Helper::GetYesNoText($isadmin);
		
		exit;		
		
	}
	
	function ToggleAllEMail()
	{
		$db	= JFactory::getDBO();
		
		$userid = LIMETICKET_Input::getInt('userid');
		$groupid = LIMETICKET_Input::getInt('groupid');
		
		if (!$this->canAdminGroup($groupid))
			return;
		
		$qry = "SELECT allemail FROM #__limeticket_ticket_group_members WHERE user_id = '".LIMETICKETJ3Helper::getEscaped($db, $userid)."' AND group_id = '".LIMETICKETJ3Helper::getEscaped($db, $groupid)."'";
		$db->setQuery($qry);
		
		$current = $db->loadObject();
		$allemail = $current->allemail;
		$allemail = 1 - $allemail;
		
		$qry = "UPDATE #__limeticket_ticket_group_members SET allemail = '".LIMETICKETJ3Helper::getEscaped($db, $allemail)."' WHERE user_id = '".LIMETICKETJ3Helper::getEscaped($db, $userid)."' AND group_id = '".LIMETICKETJ3Helper::getEscaped($db, $groupid)."'";
		
		$db->setQuery($qry);
		$db->Query();
		
		echo LIMETICKET_Helper::GetYesNoText($allemail);
		
		exit;		
		
	}
	
	function canAdminGroup($groupid)
	{
		if (LIMETICKET_Permission::auth("limeticket.groups", "com_limeticket.groups"))
			return true;
		if (!array_key_exists($groupid, LIMETICKET_Permission::$group_id_access))
			return false;
		return true;	
	}
	
	function PickUser()
	{
		$filter = array();
		$filter[] = JHTML::_('select.option', '', JText::_('JOOMLA_GROUP'), 'id', 'name');
		$query = 'SELECT id, title as name FROM #__usergroups ORDER BY title';
		$db	= JFactory::getDBO();
		$db->setQuery($query);
		$filter = array_merge($filter, $db->loadObjectList());
		$this->gid = JHTML::_('select.genericlist',  $filter, 'gid', 'class="inputbox" size="1" onchange="document.limeticketForm.submit( );"', 'id', 'name', LIMETICKET_Input::getInt('gid'));

        $this->users = $this->get('Users');

		$this->search = LIMETICKET_Input::getString('search');
		$this->username = LIMETICKET_Input::getString('username');
		$this->email = LIMETICKET_Input::getString('email');
		$this->order = LIMETICKET_Input::getCmd('filter_order');
		$this->order_Dir = LIMETICKET_Input::getCmd('filter_order_Dir');

		$this->pagination = $this->get('UsersPagination');
		$this->limit_start = LIMETICKET_Input::getInt("limit_start");
		parent::display("users");
	}
	
	function AddUser()
	{
		$userids = LIMETICKET_Input::getArrayInt('cid');
		$groupid = LIMETICKET_Input::getInt('groupid');
		
		if (!$this->canAdminGroup($groupid))
			return;

		$messages = array();

		$db	= JFactory::getDBO();
		if (count($userids) > 0)
		{
			foreach ($userids as $userid)
			{
				if ($userid > 0)
				{
					$result = SupportActions::ActionResult("groupAdd", array('group_id' => $groupid, 'user_id' => $userid), true);	
					if ($result === true)
					{
						$qry = "REPLACE INTO #__limeticket_ticket_group_members (group_id, user_id) VALUES ('".LIMETICKETJ3Helper::getEscaped($db, $groupid)."', '".LIMETICKETJ3Helper::getEscaped($db, $userid)."')";
						$db->setQuery($qry);
						$db->query($qry);
					} else {
						$messages[] = $result;
					}
				}
			}
		}
	
		$link = LIMETICKETRoute::_('index.php?option=com_limeticket&view=admin_groups&groupid=' . $groupid);
		if (count($messages) > 0)
			$link .= "&messages=" . implode("|", $messages);
		echo "<script>\n";
		echo "parent.location.href=\"$link\";\n";
		echo "</script>";	
		exit;
	}
	
	function RemoveUsers()
	{
		$userids = LIMETICKET_Input::getArrayInt('cid');
		$groupid = LIMETICKET_Input::getInt('groupid');
		
		if (!$this->canAdminGroup($groupid))
			return;

		$db	= JFactory::getDBO();
		if (count($userids) > 0)
		{
			foreach ($userids as $userid)
			{
				$qry = "DELETE FROM #__limeticket_ticket_group_members WHERE group_id ='".LIMETICKETJ3Helper::getEscaped($db, $groupid)."' AND user_id = '".LIMETICKETJ3Helper::getEscaped($db, $userid)."'";
				$db->setQuery($qry);
				$db->query($qry);
			}
		}
		
		$mainframe = JFactory::getApplication();
		$link = LIMETICKETRoute::_('index.php?option=com_limeticket&view=admin_groups&groupid=' . $groupid,false);
		$mainframe->redirect($link,JText::_('SEL_REMOVED'));
	}
	
	function SaveGroup($what)
	{
		$db	= JFactory::getDBO();

		//echo "Saving Group<br>";
		//print_p($_POST);
		//exit;
		
		$groupid = LIMETICKET_Input::getInt('groupid');
		$groupname = LIMETICKET_Input::getString('groupname');
		$description = LIMETICKET_Input::getString('description');
		$allemail = LIMETICKET_Input::getInt('allemail');
		$allsee = LIMETICKET_Input::getInt('allsee');
		$allprods = LIMETICKET_Input::getInt('allprods');
		$ccexclude = LIMETICKET_Input::getInt('ccexclude');
		
		if (!$this->canAdminGroup($groupid))
			return;
		$msg = "";		
		if ($groupid > 0)
		{	
			$msg = JText::_("GROUP_SAVED");			
			
			// saving existing group	
			$qry = "UPDATE #__limeticket_ticket_group SET ";
			$qry .= " groupname = '".LIMETICKETJ3Helper::getEscaped($db, $groupname)."', ";
			$qry .= " description = '".LIMETICKETJ3Helper::getEscaped($db, $description)."', ";
			$qry .= " allsee = '".LIMETICKETJ3Helper::getEscaped($db, $allsee)."', ";
			$qry .= " allprods = '".LIMETICKETJ3Helper::getEscaped($db, $allprods)."', ";
			$qry .= " allemail = '".LIMETICKETJ3Helper::getEscaped($db, $allemail)."', ";
			$qry .= " ccexclude = '".LIMETICKETJ3Helper::getEscaped($db, $ccexclude)."' ";
			$qry .= " WHERE id = '".LIMETICKETJ3Helper::getEscaped($db, $groupid)."'";
			$db->setQuery($qry);
			//echo $qry."<br>";
			$db->Query();
			
			// save products
		} else {
			$msg = JText::_("GROUP_CREATED");			
			// creating new group	
			$qry = "INSERT INTO #__limeticket_ticket_group (groupname, description, allsee, allprods, allemail, ccexclude) VALUES (";
			$qry .= " '".LIMETICKETJ3Helper::getEscaped($db, $groupname)."', ";
			$qry .= " '".LIMETICKETJ3Helper::getEscaped($db, $description)."', ";
			$qry .= " '".LIMETICKETJ3Helper::getEscaped($db, $allsee)."', ";
			$qry .= " '".LIMETICKETJ3Helper::getEscaped($db, $allprods)."', ";
			$qry .= " '".LIMETICKETJ3Helper::getEscaped($db, $allemail)."', ";
			$qry .= " '".LIMETICKETJ3Helper::getEscaped($db, $ccexclude)."') ";
			
			$db->setQuery($qry);
			$db->Query();
			//echo $qry."<br>";
			$groupid = $db->insertid();
			//echo "New ID : $groupid<br>";
		}
		
		// save products
		if ($groupid > 0)
		{
			$qry = "DELETE FROM #__limeticket_ticket_group_prod WHERE group_id = '".LIMETICKETJ3Helper::getEscaped($db, $groupid)."'"; 
			//echo $qry."<br>";
			$db->setQuery($qry);
			$db->Query();
					
			if (!$allprods)
			{
				// get a product list
				$products = $this->get('Products');	
				foreach($products as &$product)
				{
					$id = $product->id;
					$field = "prod_" . $id;
					$value = LIMETICKET_Input::getString($field,'');
					if ($value == "on")
					{
						$qry = "REPLACE INTO #__limeticket_ticket_group_prod (group_id, prod_id) VALUES ('".LIMETICKETJ3Helper::getEscaped($db, $groupid)."', '".LIMETICKETJ3Helper::getEscaped($db, $id)."')";
						//echo $qry."<br>";
						$db->setQuery($qry);
						$db->Query();
				}	
				}
			}
		}
		//exit;
		
		$mainframe = JFactory::getApplication();
		
		if ($what == "saveclose")
		{
			$link = LIMETICKETRoute::_('index.php?option=com_limeticket&view=admin_groups',false);
			
		} else {
			$link = LIMETICKETRoute::_('index.php?option=com_limeticket&view=admin_groups&groupid=' . $groupid,false);
		}
		$mainframe->redirect($link,$msg);
	}
	
	function CreateGroup()
	{
		if (!LIMETICKET_Permission::auth("limeticket.groups", "com_limeticket.groups"))
			return LIMETICKET_Admin_Helper::NoPerm();
			
		$this->creating = true;
		$this->group = new stdclass();
		$this->group->id = 0;
		$this->group->groupname = null;
		$this->group->description = null;
		$this->group->allsee = 0;
		$this->group->allemail = 0;
		$this->group->allprods = 1;
		$this->group->ccexclude = 0;
		
		$this->buildGroupEditForm();
		
		parent::display('group');	
	}
	
	function DeleteGroup()
	{
		$db	= JFactory::getDBO();

		//echo "Deleting Group";
		$groupid = LIMETICKET_Input::getInt('groupid');
		if (!$this->canAdminGroup($groupid))
			return;
		
		$qry = "DELETE FROM #__limeticket_ticket_group WHERE id = '".LIMETICKETJ3Helper::getEscaped($db, $groupid)."'";
		$db->setQuery($qry);
		$db->Query();
		
		$qry = "DELETE FROM #__limeticket_ticket_group_members WHERE group_id = '".LIMETICKETJ3Helper::getEscaped($db, $groupid)."'";
		$db->setQuery($qry);
		$db->Query();
		
		$qry = "DELETE FROM #__limeticket_ticket_group_prod WHERE group_id = '".LIMETICKETJ3Helper::getEscaped($db, $groupid)."'";
		$db->setQuery($qry);
		$db->Query();
		
		//exit;
		
		$mainframe = JFactory::getApplication();
		$link = LIMETICKETRoute::_('index.php?option=com_limeticket&view=admin_groups',false);
		$mainframe->redirect($link,JText::_('GROUP_DELETED'));
	}
}

