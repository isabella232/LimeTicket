<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');

class LimeticketViewAdmin_Support_New extends LimeticketViewAdmin_Support
{
	function display($tpl = NULL)
	{
		$type = LIMETICKET_Input::getCmd('type');
		
		$session = JFactory::getSession();
		$session->clear('admin_create');
		$session->clear('admin_create_user_id');
		$session->clear('ticket_email');
		$session->clear('ticket_name');	
		$session->clear('ticket_reference');
		
		if ($type == "registered")
			return $this->displayRegistered();
		
		if ($type == "unregistered")
			return $this->displayUnRegistered();
		
		$this->_display();
	}
	
	function displayRegistered()
	{
		if (LIMETICKET_Settings::get('support_no_admin_for_user_open'))
			JFactory::getApplication()->redirect("index.php?option=com_limeticket&view=admin_support");

		LIMETICKET_Helper::IncludeModal();
		$this->_display("registered");	    
	}
	
	function displayUnRegistered()
	{
		if (LIMETICKET_Settings::get('support_no_admin_for_user_open'))
			JFactory::getApplication()->redirect("index.php?option=com_limeticket&view=admin_support");

		$this->_display("unregistered");	    
	}	
}		   	   			 	 	