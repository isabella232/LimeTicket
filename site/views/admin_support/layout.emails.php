<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'pagination.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'multicol.php');

class LimeticketViewAdmin_Support_EMails extends LimeticketViewAdmin_Support
{
	function display($tpl = NULL)
	{
		$preview = LIMETICKET_Input::getCmd('preview');
		if ($preview)
			return $this->showPreview($preview);	
		
		LIMETICKET_Helper::IncludeModal();

		$this->state = LIMETICKET_Input::getCmd('state');
		$this->ticket_view = "";
		$this->getLimits();
		$this->pending = $this->loadPending();

		$this->_display();
	}
	
	function showPreview($ticketid)
	{
		$this->ticket = new SupportTicket();
		$this->ticket->load($ticketid, false);

		if (substr($this->ticket->source,0 ,5) != "email")
			return;

		$this->ticket->loadAll();
		
		$this->_display("preview");
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
	
	function loadPending()
	{
		$this->tickets = new SupportTickets();
		$this->tickets->limit = $this->limit;
		$this->tickets->limitstart = $this->limitstart;
		
		
		if ($this->state == "declined")
		{
			$this->tickets->loadTicketsByQuery(array("source = 'email_declined'"), "t.lastupdate DESC");
		} else {
			$this->tickets->loadTicketsByQuery(array("source = 'email'"), "t.lastupdate DESC");
		}
		
		$this->pagination = new JPaginationJS($this->tickets->ticket_count, $this->limitstart, $this->limit);
		
		return $this->tickets->tickets;
	}
	
	function canEditField()
	{
		return false;	
	}
}