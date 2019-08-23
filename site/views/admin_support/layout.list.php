<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'support_helper.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'support_tickets.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'fields.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'pagination.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'parser.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'support_source.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'support_print.php');

class FssViewAdmin_Support_List extends FssViewAdmin_Support
{
	var $layoutpreview = 0;
	var $show_key = true;
	
	function display($tpl = NULL)
	{
		SupportSource::load();
		LIMETICKET_Helper::StylesAndJS(array('ticket_list'));
		LIMETICKET_Helper::IncludeChosen();
		
		// check sorting, and if sorted disable groupings
		if (JFactory::getApplication()->getUserStateFromRequest("limeticket_admin.ordering","ordering","") != "")
		{
			SupportUsers::setSetting('group_products', 0);
			SupportUsers::setSetting('group_departments', 0);
			SupportUsers::setSetting('group_cats', 0);
			SupportUsers::setSetting('group_group', 0);
			SupportUsers::setSetting('group_pri', 0);
		}

		$this->checkCreateCancel();
	
		$this->processBatch();
			
		$this->setupMerge();
			
		$this->getLimits();
		
		if (LIMETICKET_Input::getCmd("what") == "search")
			return $this->searchTickets();
		
		return $this->listTickets();
	}	
	
	function checkCreateCancel()
	{
		if (LIMETICKET_Input::getInt('cancel_create') > 0)
		{
					
			$session = JFactory::getSession();
			$session->clear('admin_create');
			$session->clear('admin_create_user_id');
			$session->clear('ticket_email');
			$session->clear('ticket_reference');
			$session->clear('ticket_name');	
		
			JFactory::getApplication()->redirect(LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=admin_support', false ));
			
			return;		
		}		
	}
	
	function listTickets()
	{
		// load list of tickets to display and then do the generic ticket list page
		
		$pathway = JFactory::getApplication()->getPathway();
		$pathway->addItem(JText::_("SUPPORT"));
				
		$def_open = LIMETICKET_Ticket_Helper::GetStatusID('def_open');
		$tickets = LIMETICKET_Input::getCmd('tickets',$def_open);
		
		$this->ticket_list = new SupportTickets();
		$this->ticket_list->limitstart = $this->limitstart;
		$this->ticket_list->limit = $this->limit;
		$this->ticket_list->loadTicketsByStatus($tickets);
		$this->ticket_count = $this->ticket_list->ticket_count;
		
		// get refresh settings
		$this->refresh = LIMETICKET_Input::getInt("refresh");
		$this->do_refresh = LIMETICKET_Settings::Get('support_admin_refresh');
				
		$this->pagination = new JPaginationEx($this->ticket_count, $this->limitstart, $this->limit);

		if (!$this->refresh)
			LIMETICKET_Helper::IncludeModal();

		$this->displayTicketList();
		
		if ($this->refresh)
			exit;
	}
	
	function searchTickets()
	{
		LIMETICKET_Helper::IncludeModal();
		LIMETICKET_Helper::allowBack();
		
		$this->ticket_count = 0;
		
		$this->refresh = 0;
		$this->do_refresh = 0;

		$pathway = JFactory::getApplication()->getPathway();
		$pathway->addItem(JText::_("SUPPORT"),LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=admin_support&tickets=' . $this->ticket_view, false ));
		$pathway->addItem(JText::_("SEARCH_RESULTS"));
		
		$tags = LIMETICKET_Input::getString('tags');	
		$tags = trim($tags,';');
		if ($tags)
		{
			$tags = explode(";",$tags);
			$this->tags = $tags;
		}
		
		$this->ticket_list = new SupportTickets();
		$this->ticket_list->limitstart = $this->limitstart;
		$this->ticket_list->limit = $this->limit;
		$this->ticket_list->loadTicketsBySearch();
		$this->ticket_count = $this->ticket_list->ticket_count;

		$this->pagination = new JPaginationJS($this->ticket_count, $this->limitstart, $this->limit);

		$this->displayTicketList();
	}
	
	function displayTicketList()
	{
		// load in data for search forms
		$this->handlers = SupportUsers::getHandlers(false, false);
		$this->products = SupportHelper::getProducts();
		$this->statuss = SupportHelper::getStatuss();
		$this->departments = SupportHelper::getDepartments();
		$this->categories = SupportHelper::getCategories();
		$this->priorities = SupportHelper::getPriorities();
		$this->ticketgroups = SupportHelper::getTicketGroups();
		$this->taglist = SupportHelper::getTags();
		
		// load extra data for the list of tickets we have
		$this->ticket_list->loadTags();
		$this->ticket_list->loadAttachments();
		$this->ticket_list->loadGroups();
		$this->ticket_list->loadLockedUsers();
		$this->ticket_list->loadCustomFields();
		
		if (LIMETICKET_Settings::get('support_show_msg_counts'))
			$this->ticket_list->loadMessageCounts();

		// load in custom fields
		$this->customfields = LIMETICKETCF::GetAllCustomFields(true);
		
		// set the list of tickets to this->tickets
		$this->tickets = $this->ticket_list->tickets;

		$session = JFactory::getSession();
		$preview = LIMETICKET_Input::getInt('preview');
		if ($preview == -1)
		{
			$preview = "";
			$session->clear('preview');
		}
		
		if ($preview == 1 || $session->Get('preview') == 1)
			$this->enablePreview();

		if ($this->refresh == 2)
		{
			include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_ticket_list.php');
			return;
		} elseif ($this->refresh)
		{
			ob_start();
			include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_ticket_list.php');
			$contents = ob_get_clean();
			
			$output = array();
			$output['count'] = $this->count;
			$output['tickets'] = $contents;
			
			header("Content-Type: application/json");
			echo json_encode($output);
			return;
		}
		
		return $this->_display();
	}

	function getLimits()
	{
		$mainframe = JFactory::getApplication();
		$limit = $mainframe->getUserStateFromRequest('global.list.limit_ticket', 'limit', SupportUsers::getSetting('per_page'), 'int');

		$limitstart = LIMETICKET_Input::getInt('limitstart');
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
	
		$this->limit = $limit;
		$this->limitstart = $limitstart;
	}
	
	function processBatch()
	{
		$enabled = LIMETICKET_Input::getInt('batch');
		if (!$enabled)
			return;
	}	

	function listHeader()
	{
		if (empty($this->parser))
			$this->parser = new LIMETICKETParser();

		if ($this->layoutpreview)
		{
			$this->parser->loadTemplate('preview',1);
		} else {
			$this->parser->loadTemplate(LIMETICKET_Settings::get('support_list_template'),1);
		}

		$this->parser->setVar('hidehandler', (LIMETICKET_Settings::get('support_hide_handler') == 1));
		$this->parser->setVar("candelete", LIMETICKET_Settings::get('support_delete'));

		$this->parser->processSortTags();

		$this->setupParserView();


		echo $this->parser->getTemplate();
	}

	function listRow(&$ticket)
	{
		if (empty($this->parser))
		{
			$this->parser = new LIMETICKETParser();
		}
		
		if (!property_exists($this->parser, "priorities"))
		{
			$this->parser->priorities = $this->get('priorities');
		}
	
		if ($this->layoutpreview)
		{
			$this->parser->loadTemplate('preview',0);
		} else {
			$this->parser->loadTemplate(LIMETICKET_Settings::get('support_list_template'),0);
		}

		$this->parser->Clear();
		$ticket->forParser($this->parser->vars, false, true, $this->parser->template);
		
		$this->setupParserView();
		
		$session = JFactory::getSession();
		
		if ($this->merge)
		{
			if ($this->merge == "related")
			{
				$link = LIMETICKETRoute::_('index.php?option=com_limeticket&view=admin_support&task=merge.related&source_id=' . $session->get('merge_ticket_id') . '&dest_id=' . $ticket->id, false);
				if ($ticket->id != $session->get('merge_ticket_id'))
					$this->parser->SetVar('mergebutton', "<a href='$link' class='btn btn-default btn-small'>" . JText::_('ADD_RELATED') . "</a>");
			} else {
				if ($this->merge == "into")
				{
					$link = LIMETICKETRoute::_('index.php?option=com_limeticket&view=admin_support&task=merge.merge&source_id=' . $session->get('merge_ticket_id') . '&dest_id=' . $ticket->id, false);
				} else {
					$link = LIMETICKETRoute::_('index.php?option=com_limeticket&view=admin_support&task=merge.merge&source_id=' . $ticket->id . '&dest_id=' . $session->get('merge_ticket_id'), false);
				}
			
				if ($ticket->id != $session->get('merge_ticket_id') && $ticket->merged == 0)
				{
					$this->parser->SetVar('mergebutton', "<a href='$link' class='btn btn-default btn-small'>" . JText::_('TICKET_MERGE') . "</a>");
				}
			}
		}

		echo $this->parser->getTemplate();
	}
	
	function setupParserView()
	{
		$this->parser->SetVar('view', $this->ticket_view);
		$this->cst = LIMETICKET_Ticket_Helper::GetStatusByID($this->ticket_view);
		if ($this->cst)
		{		
			if ($this->cst->is_closed)
				$this->parser->SetVar('view', 'closed');
			if ($this->cst->def_archive)
				$this->parser->SetVar('view', 'archived');
		}
		
		// set up merge
		
		// if we are merging, then need to display a message at top of list about whats going on, with a cancel button
		if ($this->merge)
		{
			$this->parser->SetVar('merge', 1);
		}
	}
	
	function grouping($type,$name,$ticket)
	{
		if (empty($this->group_nest))
		{
			$this->group_nest = array();
			$this->group_nest['prod'] = 0;
			$this->group_nest['dept'] = 0;
			$this->group_nest['cat'] = 0;
			$this->group_nest['group'] = 0;
			$this->group_nest['pri'] = 0;
			
			$base = 0;
			if (SupportUsers::getSetting("group_products"))
			{
				$this->group_nest['prod'] = $base;
				$base++;
			}	
			if (SupportUsers::getSetting("group_departments"))
			{
				$this->group_nest['dept'] = $base;
				$base++;
			}	
			if (SupportUsers::getSetting("group_cats"))
			{
				$this->group_nest['cat'] = $base;
				$base++;
			}	
			if (SupportUsers::getSetting("group_group"))
			{
				$this->group_nest['group'] = $base;
				$base++;
			}	
			if (SupportUsers::getSetting("group_pri"))
			{
				$this->group_nest['pri'] = $base;
				$base++;
			}	
		}
		
		if ($name == "")
		{
			if ($type == "prod")
				$name = JText::_('NO_PRODUCT');
			if ($type == "dept")
				$name = JText::_('NO_DEPARTMENT');	
			if ($type == "cat")
				$name = JText::_('NO_CATEGORY');	
			if ($type == "group")
				$name = JText::_('NO_GROUP');	
		}
		$style = "style='padding-left: " . (16 * $this->group_nest[$type]) . "px;'";
?>

	<div class="limeticket_ticket_grouping" <?php echo $style;?>>
		<img src='<?php echo JURI::root( true ); ?>/components/com_limeticket/assets/images/support/<?php echo $type; ?>.png' width="16" height="16">
		<?php echo $name; ?>
	</div>

<?php	
	}

	function enablePreview()
	{
		$session = JFactory::getSession();
		$session->Set('preview', 1);
		
		$this->layoutpreview = 1;

		echo "<div class='limeticket_layout_preview'><a href='" . LIMETICKETRoute::_('&preview=-1', false) . "'>List Preview - Click to close</a></div>";	// FIX LINK

		$list_template = LIMETICKET_Input::getString('list_template');
		$list_head = LIMETICKET_Input::getHTML('list_head');
		$list_row = LIMETICKET_Input::getHTML('list_row');
		$db = JFactory::getDBO();

		if ($list_template)
		{
			if ($list_template == "custom")
			{
				$qry = "REPLACE INTO #__limeticket_templates (template, tpltype, value) VALUES ('preview',0,'" . LIMETICKETJ3Helper::getEscaped($db, $list_row) . "')";
				$db->setQuery($qry);
				$db->Query();
				$qry = "REPLACE INTO #__limeticket_templates (template, tpltype, value) VALUES ('preview',1,'" . LIMETICKETJ3Helper::getEscaped($db, $list_head) . "')";
				$db->setQuery($qry);
				$db->Query();
			} else {
				$qry = "SELECT tpltype, value FROM #__limeticket_templates WHERE template = '".LIMETICKETJ3Helper::getEscaped($db, $list_template)."'";
				$db->setQuery($qry);
				$rows = $db->loadAssocList();
				foreach($rows as $row)
				{
					$qry = "REPLACE INTO #__limeticket_templates (template, tpltype, value) VALUES ('preview',".LIMETICKETJ3Helper::getEscaped($db, $row['tpltype']).",'" . LIMETICKETJ3Helper::getEscaped($db, $row['value']) . "')";
					$db->setQuery($qry);
					$db->Query();	
				}
			}
		}
	}

	function setupMerge()
	{
		$merge = LIMETICKET_Input::getCmd('merge');
		$merge_ticket_id = LIMETICKET_Input::getInt('ticketid');
		
		$this->merge = false;
		
		$session = JFactory::getSession();
		
		if ($merge == "cancel")
		{
			$ticket_id = $session->get('merge_ticket_id');
			
			$session->clear('merge');
			$session->clear('merge_ticket_id');
			
			return JFactory::getApplication()->redirect(LIMETICKETRoute::_("index.php?option=com_limeticket&view=admin_support&layout=ticket&ticketid=" . $ticket_id, false));
		}
		
		if ( ($merge == "from" || $merge == "into" || $merge == "related") && $merge_ticket_id > 0)
		{
			$session->set('merge', $merge);
			$session->set('merge_ticket_id', $merge_ticket_id);
		}
		
		if ($session->get('merge') != "")
		{
			$this->merge = $session->get('merge');
			$this->merge_ticket = new SupportTicket();
			$this->merge_ticket->load($session->get('merge_ticket_id'));
		} 		
	}

	function orderSelect()
	{
		$categories = array();
		$categories[] = JHTML::_('select.option', '', JText::_("ORDERING_HEADER"), 'id', 'title');
		$categories[] = JHTML::_('select.option', 'lastupdate.desc', JText::_("LAST_UPDATE"), 'id', 'title');
		$categories[] = JHTML::_('select.option', 't.title.asc', JText::_("SUBJECT"), 'id', 'title');
		$categories[] = JHTML::_('select.option', 'reference.asc', JText::_("TICKET_REF"), 'id', 'title');
		$categories[] = JHTML::_('select.option', 'username.asc', JText::_("USER_NAME"), 'id', 'title');
		$categories[] = JHTML::_('select.option', 'useremail.asc', JText::_("EMAIL"), 'id', 'title');
		$categories[] = JHTML::_('select.option', 'u.name.asc', JText::_("NAME"), 'id', 'title');
		$categories[] = JHTML::_('select.option', 'opened.asc', JText::_("CREATED"), 'id', 'title');
		if (LIMETICKET_Settings::get('support_hide_handler') != 1)
			$categories[] = JHTML::_('select.option', 'handlerusername.asc', JText::_("HANDLER"), 'id', 'title');
		$categories[] = JHTML::_('select.option', 'status.asc', JText::_("STATUS"), 'id', 'title');
		if (count($this->products) > 0)
			$categories[] = JHTML::_('select.option', 'product.asc', JText::_("PRODUCT"), 'id', 'title');
		if (count($this->departments) > 0)
			$categories[] = JHTML::_('select.option', 'department.asc', JText::_("DEPARTMENT"), 'id', 'title');
		if (count($this->categories) > 0 && LIMETICKET_Settings::get('support_hide_category') != 1)
			$categories[] = JHTML::_('select.option', 'category.asc', JText::_("CATEGORY"), 'id', 'title');
		if (LIMETICKET_Settings::get('support_hide_priority') != 1)
			$categories[] = JHTML::_('select.option', 'priority.asc', JText::_("PRIORITY"), 'id', 'title');


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
		if (LIMETICKET_Settings::get('support_hide_handler') != 1)
			$categories[] = JHTML::_('select.option', 'handlerusername.desc', JText::_("HANDLER") . JText::_("LIMETICKET_ORDER_DESC"), 'id', 'title');
		$categories[] = JHTML::_('select.option', 'status.desc', JText::_("STATUS") . JText::_("LIMETICKET_ORDER_DESC"), 'id', 'title');
		if (count($this->products) > 0)
			$categories[] = JHTML::_('select.option', 'product.desc', JText::_("PRODUCT") . JText::_("LIMETICKET_ORDER_DESC"), 'id', 'title');
		if (count($this->departments) > 0)
			$categories[] = JHTML::_('select.option', 'department.desc', JText::_("DEPARTMENT") . JText::_("LIMETICKET_ORDER_DESC"), 'id', 'title');
		if (count($this->categories) > 0 && LIMETICKET_Settings::get('support_hide_category') != 1)
			$categories[] = JHTML::_('select.option', 'category.desc', JText::_("CATEGORY") . JText::_("LIMETICKET_ORDER_DESC"), 'id', 'title');
		if (LIMETICKET_Settings::get('support_hide_priority') != 1)
			$categories[] = JHTML::_('select.option', 'priority.desc', JText::_("PRIORITY") . JText::_("LIMETICKET_ORDER_DESC"), 'id', 'title');

		/* Add custom fields in here */

		foreach (LIMETICKETCF::GetAllCustomFields() as $field)
		{
			if (!$field['inlist']) continue;
			$categories[] = JHTML::_('select.option', "cf".$field['id'].".value.desc", $field['description'] . JText::_("LIMETICKET_ORDER_DESC"), 'id', 'title');
		}

		return JHTML::_('select.genericlist',  $categories, 'ordering', 'id="adminOrdering" class="inputbox input-medium" size="1" onchange="limeticket_refresh_tickets();"', 'id', 'title', JFactory::getApplication()->getUserStateFromRequest("limeticket_admin.ordering","ordering",""));
	}
}