<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @deprecated eea903a944f9772f67422f273ff63e6b
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');

class FssViewPlugin extends LIMETICKETView
{
	function display($tpl = null)
	{
		$type = LIMETICKET_Input::getCmd('type');
		$name = LIMETICKET_Input::getCmd('name');
		
		$plugin_file = JPATH_SITE.DS."components".DS."com_limeticket".DS."plugins".DS.$type.DS.$name.".php";
		
		if (!file_exists($plugin_file))
			return;
		
		require_once($plugin_file);
		
		switch ($type)
		{
			case 'cron':
				$class = "LIMETICKETCronPlugin" . $name;
				break;
			case 'custfield':
				$class = $name."Plugin";
				break;
			case 'gui':
				$class = "LIMETICKET_GUIPlugin_" . $name;
				break;
			case 'tickets':
				$class = "SupportActions" . $name;
				break;
			case 'ticketsource':
				$class = "Ticket_Source_" . $name;
				break;
			case 'userlist':
				$class = "User_List_" . $name;
				break;
		}
		
		if (!class_exists($class))
			return;
		
		$plugin = new $class();
		$plugin->name = $name;
		$plugin->type = $type;
		$plugin->process();
		
		parent::display();	
	}
}
