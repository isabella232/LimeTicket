<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );
jimport('joomla.filesystem.file');
jimport('fsj_core.admin.update');

class limeticketsViewimport extends JViewLegacy
{
	function display($tpl = null)
	{
		$source = JRequest::getVar('source');
		
		if ($source == "huru") 
			return $this->importHuru();
		
		parent::Display($tpl);
	}
	
	function importHuru()
	{
		require_once JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'import'.DS.'import.huru.php';
		
		$hi = new LIMETICKET_Huru_Import();
		
		$this->title = "Huru Helpdesk Import";
		$this->log = $hi->Run();
		
		parent::Display("log");
	}
}
