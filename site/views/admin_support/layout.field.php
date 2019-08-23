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
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'fields.php');

class FssViewAdmin_Support_Field extends FssViewAdmin_Support
{
	function display($tpl = NULL)
	{
		$ticketid = LIMETICKET_Input::getInt('ticketid');
		
		$this->ticket = new SupportTicket();
		if (!$this->ticket->load($ticketid))
			return;
	
		$this->fields = LIMETICKETCF::GetCustomFields($ticketid,$this->ticket->prod_id,$this->ticket->ticket_dept_id,3);
		$this->fieldvalues = LIMETICKETCF::GetTicketValues($ticketid, $this->ticket);

		$fieldid = LIMETICKET_Input::getInt('editfield');
		
		$this->assign('field','');
		$this->assign('fieldvalue','');
		$errors = array();
		$this->errors = $errors;
		
		foreach($this->fields as &$field)
		{
			if ($field['id'] == $fieldid)
				$this->field = $field;
		}

		foreach($this->fieldvalues as &$fieldvalue)
		{
			if ($fieldvalue['field_id'] == $fieldid)
			{
				JRequest::setVar('custom_' . $fieldid,$fieldvalue['value']);
			}
		}

		$this->fieldid = $fieldid;

		$this->_display();
	}
}