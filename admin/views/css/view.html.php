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

class limeticketsViewcss extends JViewLegacy
{
	function display($tpl = null)
	{
		$file = JRequest::getVar('file');

		$file = str_replace("/", "", $file);
		$file = str_replace("\\", "", $file);
		
		header("Content-type: text/css");
		readfile(JPATH_ROOT . DS . "cache" . DS . "limeticket" . DS . "css" . DS . $file);
		exit;
	}
}
	 	  	   		  	  