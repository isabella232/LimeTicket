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

class LimeticketViewTicket_Field extends LimeticketViewTicket
{
	function display($tpl = NULL)
	{
		$this->validateUser();
		$this->ticket_id = LIMETICKET_Input::getInt("ticketid");
		$this->fieldid = LIMETICKET_Input::getInt('fieldid');
		
		$this->errors = array();

		$this->ticket = new SupportTicket();
		if (!$this->ticket->load($this->ticket_id, $this->user_type)) return $this->noPermission();
		
		//$this->ticket->loadAll();
		$this->ticket->setupUserPerimssions();
		
		if ($this->ticket->readonly) return;
		
		$this->ticket->loadCustomFields();
		$this->field = $this->ticket->getField($this->fieldid);
		
		if (LIMETICKET_Input::GetInt('savefield') > 0) return $this->saveField();
		
		JRequest::setVar('custom_' . $this->fieldid, $this->ticket->getFieldValue($this->fieldid));

		$this->_display();
	}	
	
	function saveField()
	{
		// load in tickets to do		
		$ticketid = LIMETICKET_Input::getInt('ticketid');
		$fieldid = LIMETICKET_Input::getInt('fieldid');
		$value = LIMETICKET_Input::getString("custom_" . $fieldid,"");
		
		$this->ticket->updateCustomField($fieldid, $value);	

		echo "<script>parent.window.location.reload();</script>";
		exit;				
	}
}
