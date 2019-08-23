<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'support_ticket.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'support_tickets.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'parser.php');

class LimeticketViewTicket_List extends LimeticketViewTicket
{
	function display($tpl = NULL)
	{
		$session = JFactory::getSession();
		$session->clear('ticket_pass');
		$session->clear('ticket_email');
		$session->clear('ticket_reference');
		$session->clear('ticket_find');

		$this->setupView();
		$this->loadCounts();
		
		// if we are not logged in, then redirect to view
		if (!$this->validateUser()) return;
	
		$this->ticket_list = new SupportTickets();

		$this->sortTicketWhere();
		
		LIMETICKET_Helper::IncludeModal();

		$this->_display();
	}	
	
	function sortTicketWhere()
	{
		$user = JFactory::getUser();
		$userid = $user->get('id');
				
		$where = array();
		$where[] = SupportUsers::getUsersWhere($userid);
		
		$this->multiuser = SupportHelper::userIdMultiUser($userid);

		$tickets = LIMETICKET_Input::getCmd('tickets','open');
		
		if (LIMETICKET_Settings::get('support_simple_userlist_tabs')) $tickets = "all";
		if (LIMETICKET_Input::getCmd('search_all')) $tickets = "";
		
		if ($tickets == 'open') $where[] = " ticket_status_id IN ( " . implode(", ", LIMETICKET_Ticket_Helper::GetStatusIDs("is_closed", true)) . ") ";
		if ($tickets == 'closed') $where[] = " ticket_status_id IN ( " . implode(", ", LIMETICKET_Ticket_Helper::GetStatusIDs('is_closed', false, true)) . ") ";

		if ($tickets > 0) 
		{
			$statuss = SupportHelper::getStatuss(false);
			
			$status_list = array();
			$status_list[] = (int)$tickets;
			
			foreach ($statuss as $status)
			{
				if ($status->combine_with == (int)$tickets)
				{
					$status_list[] = $status->id;
				}
			}
			
			$where[] = " ticket_status_id IN (" . implode(", ", $status_list) . ")";
		}
		
		$db = JFactory::getDBO();
		
		$search = LIMETICKET_Input::getString('search');
		if ($search != "")
		{
			LIMETICKET_Helper::allowBack();

			// We have the nearly full query here, so use it to get a list of ticket ids
			
			$this->ticket_view = "search";
			
			$ids = $this->ticket_list->loadTicketsIDsByQuery($where);

			if (count($ids) < 1)
				$ids[] = 0;
			
			$mode = "";
			if (LIMETICKET_Helper::contains($search, array('*', '+', '-', '<', '>', '(', ')', '~', '"'))) $mode = "IN BOOLEAN MODE";

			$msgsrch = "SELECT ticket_ticket_id FROM #__limeticket_ticket_messages WHERE ticket_ticket_id IN (" . implode(", ", $ids) . ") AND admin < 3 AND ";
			$msgsrch .= " ( MATCH (body) AGAINST ('" . $db->escape($search) . "' $mode) ";
			if (LIMETICKET_Settings::get('search_extra_like')) $msgsrch .= " OR body LIKE '" . LIMETICKET_Helper::strForLike($search) . "'";
			$msgsrch .= " )";
			$db->setQuery($msgsrch);
			
			$ids = $db->loadColumn();
	
			// search custom fields that are set to be searched
			$fields = LIMETICKETCF::GetAllCustomFields(true);			
			foreach ($fields as $field)
			{
				if (!$field["basicsearch"]) continue;
				if ($field['permissions'] > 1 && $field['permissions'] < 5) continue;
				if ($field['peruser']) continue;
				
				$fieldid = $field['id'];
				
				if ($field['type'] == "checkbox")
				{
					if ($search == "1")
					{
						$search = "on";
					} else {
						$search = "";
					}
				}
				
				if ($field['type'] == "plugin")
				{
					// try to do a plugin based search
					$data = array();
					foreach ($field['values'] as $item)
					{
						list($key, $value) = explode("=", $item, 2);
						$data[$key] = $value;	
					}
					if (array_key_exists("plugin", $data))
					{
						$plugins = LIMETICKETCF::get_plugins();
						if (array_key_exists($data['plugin'], $plugins))
						{
							$po = $plugins[$data['plugin']];	

							if (method_exists($po, "Search"))
							{
								$res = $po->Search($data['plugindata'], $search, false, false);
								
								if ($res !== false)
								{
									foreach ($res as $item)
										$ids[] = (int)$item->ticket_id;
									continue;
								}
							}
						}
					}
				}
				
				$qry = "SELECT ticket_id FROM #__limeticket_ticket_field WHERE field_id = '" . LIMETICKETJ3Helper::getEscaped($db, $fieldid) . "' AND value LIKE '%" . LIMETICKETJ3Helper::getEscaped($db, $search) . "%'";
				$db->setQuery($qry);	
				$data = $db->loadObjectList();
				foreach ($data as $item)
				{
					$id = (int)$item->ticket_id;
					if ($id > 0) $ids[] = $id;
				}
				
			}
			
			$ors = array();
			
			$ors[] = "MATCH (t.title) AGAINST ('" . $db->escape($search) . "' $mode)";
			$ors[] = "t.reference LIKE '" . LIMETICKET_Helper::strForLike($search) . "'";
			if (count($ids) > 0) $ors[]= "t.id IN (" . implode(", ", $ids) . ")";
			if (LIMETICKET_Settings::get('search_extra_like') || strlen($search) < 4) $ors[] = "t.title LIKE '" . LIMETICKET_Helper::strForLike($search) . "'";
			
			$where[] = " ( " . implode(" OR ", $ors) . " ) ";
		}


		$order = LIMETICKET_Input::getString('ordering');
		$order_dir = 'asc';
		if (strpos($order, ".asc")) $order = str_replace(".asc","", $order);
		if (strpos($order, ".desc"))
		{
			$order = str_replace(".desc","", $order);
			$order_dir = "desc";
		}

			if ($order != "")
		{
			$order = " $order $order_dir";	
		} else {
			$order = " lastupdate DESC ";
		}

		$this->limit = JFactory::getApplication()->getUserStateFromRequest('global.list.limit_ticket', 'limit', LIMETICKET_Settings::Get('ticket_per_page'), 'int');
		$this->limitstart = LIMETICKET_Input::getInt('limitstart');
		$this->limitstart = ($this->limit != 0 ? (floor($this->limitstart / $this->limit) * $this->limit) : 0);
		$this->ticket_list->limitstart = $this->limitstart;
		$this->ticket_list->limit = $this->limit;
		$this->ticket_list->loadTicketsByQuery($where, $order, true);
		
		$this->ticket_list->loadTags();
		$this->ticket_list->loadAttachments();
		$this->ticket_list->loadGroups();
		$this->ticket_list->loadLockedUsers();
		$this->ticket_list->loadCustomFields();
		
		$this->ticket_count = $this->ticket_list->ticket_count;
		$this->tickets = $this->ticket_list->tickets;
		$this->pagination = new JPaginationEx($this->ticket_count, $this->limitstart, $this->limit);
	}
	
	function listHeader()
	{
		if (empty($this->parser)) $this->parser = new LIMETICKETParser();

		$this->parser->loadTemplate(LIMETICKET_Settings::get('support_user_template'),1);
		$this->parser->processSortTags();
		$this->parser->SetVar("multiuser",$this->multiuser);
		$this->parser->SetVar('hidehandler', LIMETICKET_Settings::get('support_hide_handler') == 1);
		$this->parser->SetVar("candelete", LIMETICKET_Settings::get('support_delete'));
		
		$this->cst = LIMETICKET_Ticket_Helper::GetStatusByID($this->ticket_view);
		if ($this->cst)
		{		
			if ($this->cst->is_closed) $this->parser->SetVar('view', 'closed');
			if ($this->cst->def_archive) $this->parser->SetVar('view', 'archived');
		}
		echo $this->parser->getTemplate();
	}

	function listRow($ticket)
	{
		if (empty($this->parser)) $this->parser = new LIMETICKETParser();
		
		$this->parser->loadTemplate(LIMETICKET_Settings::get('support_user_template'),0);
		$ticket->forParser($this->parser->vars, true, true, $this->parser->template);

		if ($this->cst)
		{
			if ($this->cst->is_closed) $this->parser->SetVar('view', 'closed');
			if ($this->cst->def_archive) $this->parser->SetVar('view', 'archived');
		}

		echo $this->parser->getTemplate();
	}

	function orderSelect()
	{
		$this->products = SupportHelper::getProducts();
		$this->departments = SupportHelper::getDepartments();
		$this->categories = SupportHelper::getCategories();
		
		$categories = array();
		$categories[] = JHTML::_('select.option', '', JText::_("ORDERING_HEADER"), 'id', 'title');
		$categories[] = JHTML::_('select.option', 'lastupdate.desc', JText::_("LAST_UPDATE"), 'id', 'title');
		$categories[] = JHTML::_('select.option', 't.title.asc', JText::_("SUBJECT"), 'id', 'title');
		$categories[] = JHTML::_('select.option', 'reference.asc', JText::_("TICKET_REF"), 'id', 'title');
		$categories[] = JHTML::_('select.option', 'username.asc', JText::_("USER_NAME"), 'id', 'title');
		$categories[] = JHTML::_('select.option', 'useremail.asc', JText::_("EMAIL"), 'id', 'title');
		$categories[] = JHTML::_('select.option', 'u.name.asc', JText::_("NAME"), 'id', 'title');
		$categories[] = JHTML::_('select.option', 'opened.asc', JText::_("CREATED"), 'id', 'title');
		if (LIMETICKET_Settings::get('support_hide_handler') != 1)	$categories[] = JHTML::_('select.option', 'handlerusername.asc', JText::_("HANDLER"), 'id', 'title');
		$categories[] = JHTML::_('select.option', 'status.asc', JText::_("STATUS"), 'id', 'title');
		if (count($this->products) > 0)	$categories[] = JHTML::_('select.option', 'product.asc', JText::_("PRODUCT"), 'id', 'title');
		if (count($this->departments) > 0) $categories[] = JHTML::_('select.option', 'department.asc', JText::_("DEPARTMENT"), 'id', 'title');
		if (count($this->categories) > 0 && LIMETICKET_Settings::get('support_hide_category') != 1) $categories[] = JHTML::_('select.option', 'category.asc', JText::_("CATEGORY"), 'id', 'title');
		if (LIMETICKET_Settings::get('support_hide_priority') != 1) $categories[] = JHTML::_('select.option', 'priority.asc', JText::_("PRIORITY"), 'id', 'title');


		foreach (LIMETICKETCF::GetAllCustomFields() as $field)
		{
			if (!$field['inlist']) continue;
			$categories[] = JHTML::_('select.option', "cf".$field['id'].".value.asc", $field['description'], 'id', 'title');
		}

		$categories[] = JHTML::_('select.option', 'lastupdate.asc', JText::_("LAST_UPDATE") . JText::_("LIMETICKET_ORDER_ASC"), 'id', 'title');
		$categories[] = JHTML::_('select.option', 't.title.desc', JText::_("SUBJECT") . JText::_("LIMETICKET_ORDER_DESC"), 'id', 'title');
		$categories[] = JHTML::_('select.option', 'reference.desc', JText::_("TICKET_REF") . JText::_("LIMETICKET_ORDER_DESC"), 'id', 'title');
		$categories[] = JHTML::_('select.option', 'username.desc', JText::_("USER_NAME") . JText::_("LIMETICKET_ORDER_DESC"), 'id', 'title');
		$categories[] = JHTML::_('select.option', 'useremail.desc', JText::_("EMAIL") . JText::_("LIMETICKET_ORDER_DESC"), 'id', 'title');
		$categories[] = JHTML::_('select.option', 'u.name.desc', JText::_("NAME") . JText::_("LIMETICKET_ORDER_DESC"), 'id', 'title');
		$categories[] = JHTML::_('select.option', 'opened.desc', JText::_("CREATED") . JText::_("LIMETICKET_ORDER_DESC"), 'id', 'title');
		if (LIMETICKET_Settings::get('support_hide_handler') != 1) $categories[] = JHTML::_('select.option', 'handlerusername.desc', JText::_("HANDLER") . JText::_("LIMETICKET_ORDER_DESC"), 'id', 'title');
		$categories[] = JHTML::_('select.option', 'status.desc', JText::_("STATUS") . JText::_("LIMETICKET_ORDER_DESC"), 'id', 'title');
		if (count($this->products) > 0)	$categories[] = JHTML::_('select.option', 'product.desc', JText::_("PRODUCT") . JText::_("LIMETICKET_ORDER_DESC"), 'id', 'title');
		if (count($this->departments) > 0) $categories[] = JHTML::_('select.option', 'department.desc', JText::_("DEPARTMENT") . JText::_("LIMETICKET_ORDER_DESC"), 'id', 'title');
		if (count($this->categories) > 0 && LIMETICKET_Settings::get('support_hide_category') != 1) $categories[] = JHTML::_('select.option', 'category.desc', JText::_("CATEGORY") . JText::_("LIMETICKET_ORDER_DESC"), 'id', 'title');
		if (LIMETICKET_Settings::get('support_hide_priority') != 1) $categories[] = JHTML::_('select.option', 'priority.desc', JText::_("PRIORITY") . JText::_("LIMETICKET_ORDER_DESC"), 'id', 'title');

		/* Add custom fields in here */

		foreach (LIMETICKETCF::GetAllCustomFields() as $field)
		{
			if (!$field['inlist']) continue;
			$categories[] = JHTML::_('select.option', "cf".$field['id'].".value.desc", $field['description'] . JText::_("LIMETICKET_ORDER_DESC"), 'id', 'title');
		}

		return JHTML::_('select.genericlist',  $categories, 'ordering', 'id="adminOrdering" class="inputbox input-medium" size="1" onchange="jQuery(\'#limeticketFormTS\').submit();"', 'id', 'title', JFactory::getApplication()->getUserStateFromRequest("limeticket_admin.ordering","ordering",""));
	}	
}
