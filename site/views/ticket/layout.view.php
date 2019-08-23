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
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'support_print.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'multicol.php');

class LimeticketViewTicket_View extends LimeticketViewTicket
{
	function display($tpl = NULL)
	{
		$this->ticketid = LIMETICKET_Input::getInt("ticketid");

		if (!$this->validateUser()) return;
		
		$this->ticket = new SupportTicket();
		if (!$this->ticket->load($this->ticketid, $this->user_type))
		{
			return $this->noPermission();
		}
		
		$this->ticket->loadAll(!LIMETICKET_Settings::get('support_user_reverse_messages'));
		$this->ticket->setupUserPerimssions();
		
		SupportSource::doUser_View_Redirect($this->ticket);

		$this->redirectMergedTickets();
		$this->loadMergedTickets();
		$this->updateTicketLanguage();
		$this->pris = SupportHelper::getPrioritiesUser();
		$this->statuss = SupportHelper::getStatussUser();
		
		$errors['subject'] = '';
		$errors['body'] = '';
		$errors['cat'] = '';
		$this->errors = $errors;

		$this->ticket_view = "ticket";
		$this->loadCounts();
		
		if (LIMETICKET_Input::getCmd('print')) return $this->_display("print");
		
		LIMETICKET_Helper::IncludeModal();
		LIMETICKET_Helper::AddSCEditor(true);
		LIMETICKET_Helper::StylesAndJS(array('scrollsneak'));
		
		$this->_display();
	}	
	
	function redirectMergedTickets()
	{
		if ($this->ticket->merged > 0 && JFactory::getSession()->Get('ticket_email') == "")
		{
			$link = LIMETICKETRoute::_("index.php?option=com_limeticket&view=ticket&layout=view&ticketid=" . $this->ticket->merged);
			JFactory::getApplication()->redirect($link);
		}
	}
	
	function loadMergedTickets()
	{		
		$db = JFactory::getDBO();
		$this->merged = new SupportTickets();
		$this->merged->limitstart = 0;
		$this->merged->limit = 250;
		$this->merged->loadTicketsByQuery(array("merged = " . $db->escape($this->ticket->id)));
	}	
	
	function updateTicketLanguage()
	{
		$lang = JFactory::getLanguage()->getTag();
		$db = JFactory::getDBO();
		$qry = "UPDATE #__limeticket_ticket_ticket SET lang = '" . LIMETICKETJ3Helper::getEscaped($db, $lang) . "' WHERE id = " . $this->ticket->id;
		$db->setQuery($qry);
		$db->Query();	
	}
}
