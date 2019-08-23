<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

require_once( JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'helper.php' );
require_once( JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'settings.php' );

class LIMETICKETCron
{
	var $_log;

	function Log($message)
	{
		$this->_log .= $message."<br>";
	}

	function SaveLog()
	{
		$db = JFactory::getDBO();
		$class = get_class($this);
		$class = str_ireplace("LIMETICKETCron","",$class);
		$now = LIMETICKET_Helper::CurDate();
		
		$qry = "INSERT INTO #__limeticket_cron_log (cron, `when`, log) VALUES ('".LIMETICKETJ3Helper::getEscaped($db, $class)."', '{$now}', '" . LIMETICKETJ3Helper::getEscaped($db, $this->_log) . "')";
		$db->SetQuery($qry);
		$db->Query();
		//echo $qry."<br>";
		
		LIMETICKET_Helper::cleanLogs();
	}

	function updateData($data)
	{
		$data = json_encode($data);
		$db = JFactory::getDBO();
		$qry = "UPDATE #__limeticket_cron SET params = '" . $db->escape($data) . "' WHERE id = " . $this->id;

		$db->setQuery($qry);
		$db->Query();
	}
}