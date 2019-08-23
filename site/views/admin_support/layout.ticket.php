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
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'support_helper.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'support_print.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'fields.php');

class FssViewAdmin_Support_ticket extends FssViewAdmin_Support
{
	function display($tpl = NULL)
	{
		// view a ticket!
		$this->ticketid = LIMETICKET_Input::getInt('ticketid');
		$document = JFactory::getDocument();
		$document->addScript( JURI::root().'components/com_limeticket/assets/js/bootstrap/bootstrap-timepicker.min.js' );
		
		$this->ticket = new SupportTicket();
		if (!$this->ticket->load($this->ticketid))
		{
			if ($this->ticket->checkExist($this->ticketid))
			{
				return $this->_display("noperm");
			} else {
				return JError::raiseWarning(404, JText::_('Ticket not found'));
			}
		}
		
		if ($this->ticket->merged > 0 && LIMETICKET_Input::getInt('no_redirect') != '1')
			JFactory::getApplication()->redirect(LIMETICKETRoute::_('index.php?option=com_limeticket&view=admin_support&layout=ticket&ticketid=' . $this->ticket->merged . "&Itemid=" . LIMETICKET_Input::getInt('Itemid'), false));
		
		$reverse = JRequest::getInt('sort', null);

		if ($reverse !== null)
		{
			if ($reverse)
			{
				// we want messages in opposite order to normal
				if (SupportUsers::getSetting("reverse_order"))
				{
					$reverse = true;
				} else {
					$reverse = false;
				}
			} else {
				// we want messages in normal order
				$reverse = null;
			}
		}

		$this->ticket->loadAll($reverse);
	
		$this->loadMerged();
	
		$pathway = JFactory::getApplication()->getPathway();
		$pathway->addItem(JText::_("SUPPORT"),LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=admin_support&tickets=' . $this->ticket_view ));
		$pathway->addItem(JText::_("VIEW_TICKET"). " : " . $this->ticket->reference . " - " . $this->ticket->title);

		$this->assignOnOpen();
		$this->tryLock();
		
		if ($this->ticket->admin_id > 0)
			$this->adminuser = SupportUsers::getUser($this->ticket->admin_id);
		
		$this->ticket_view = $this->ticket->ticket_status_id;

		$this->HandleRefresh();

		if (LIMETICKET_Settings::get('time_tracking') == "auto")
		{	
			$session = JFactory::getSession();
			$session->set( 'ticket_' . $this->ticket->id . "_opened", time() );
		}


		
		LIMETICKET_Helper::IncludeModal();
		LIMETICKET_Helper::AddSCEditor();
		

		$this->HandleRefresh();

		$this->print = LIMETICKET_Input::getCmd('print');
		if ($this->print)
			return $this->_display("print");
		
		$this->_display();
	}	
	
	function HandleRefresh()
	{
		$this->do_refresh = LIMETICKET_Settings::Get('support_admin_refresh');
		
		if (LIMETICKET_Input::getInt("refresh") > 0)
		{
			$output = array();
			$output['count'] = $this->count;
			
			header("Content-Type: application/json");
			echo json_encode($output);
			exit;
		}	
	}
	
	function loadMerged()
	{	
		$db = JFactory::getDBO();
		$qry = "SELECT * FROM #__limeticket_ticket_ticket WHERE merged = " . $db->escape($this->ticket->id);
		$db->setQuery($qry);
		$this->merged = $db->loadObjectList();
		
		$this->merge = false;
		
		$session = JFactory::getSession();
		
		if ($session->get('merge'))
		{
			$this->merge = $session->get('merge');
			$this->merge_ticket = new SupportTicket();
			$this->merge_ticket->load($session->get('merge_ticket_id'));	
		}
	}
	
	function assignOnOpen()
	{
		if (LIMETICKET_Settings::get( 'support_autoassign' ) == 2 && $this->ticket->admin_id == 0)
			$this->ticket->assignHandler(JFactory::getUser()->id, 1);
	}
	
	function tryLock()
	{
		if (!$this->ticket->isLocked() && LIMETICKET_Settings::get('support_lock_time') > 0)
			$this->ticket->updateLock();
	}
	
	function CanEditField($field)
	{
		if (is_array($field) && $field['type'] == "plugin")
		{
			$aparams = LIMETICKETCF::GetValues($field);
			$plugin = LIMETICKETCF::get_plugin($aparams['plugin']);
			if (!$plugin->CanEdit())
				return false;
		}

		return true;
	}

}
