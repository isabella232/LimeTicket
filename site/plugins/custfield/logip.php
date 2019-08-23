<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

// the class name (SamplePlugin) MUST match the name of the php file (ie, this class must be in sample.php)
class LogIPPlugin extends LIMETICKETCustFieldPlugin
{
	var $name = "Log IP Address Plugin";

	function GetGroupClass()
	{
		return "limeticket_ip_log";	
	}

	function Input($current, $params, $context, $id) // output the field for editing
	{
		return "<style>.limeticket_ip_log { display: none; }</style>";
	}
	
	function Save($id, $params, $value = "")
	{
		return LIMETICKET_Helper::GetClientIP();
	}
	
	function Display($value, $params, $context, $id) // output the field for display
	{
		return $value;
	}
	
	function CanEdit()
	{
		return false;	
	}
}