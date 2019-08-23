<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @deprecated 4f87c1b1921c3b0b89db50e80e53ac26
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'attach'.DS.'attach_handler.php');

class LimeticketViewAttach extends LIMETICKETView
{
	function display($tpl = null)
    {
		$task = JRequest::getVar('task');
		if ($task == "process")
			return $this->process();
    }
	
	function process()
	{
		$upload_handler = new LIMETICKET_Attach_Handler();
		exit;
	}
}

