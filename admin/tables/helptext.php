<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class TableHelpText extends JTable
{

	var $dentifier = null;

	function TableHelpText(& $db) {
		parent::__construct('#__limeticket_help_text', 'identifier', $db);
	}

	function publish($ident, $state)
	{
		$db = JFactory::getDBO();
		$qry = "UPDATE #__limeticket_help_text SET published = " . $db->escape($state) . " WHERE identifier = '" . $db->escape($ident) . "'";
		$db->setQuery($qry);
		$db->Query();
		
		return true;
	}
}


