<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'cron'.DS.'cron.php');

class LIMETICKETCronPluginSample extends LIMETICKETCron
{
	var $title = "Sample Plugin";
	var $description = "Cron sample plugin";
	var $interval = 5; // run every 60 minutes

	function Execute($data)
	{
		if (!is_object($data)) $data = new stdClass();

		$this->Log("Sample cron plugin running");
		
		// store some information in persistant data object.
		// this can be used to store anything, but it limited is stored json encoded in a 64kb max size. 
		// please dont store lots of data in the field
		$data->my_data = "Some Value";
		$this->updateData($data);
	}
}