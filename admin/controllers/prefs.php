<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class LimeticketsControllerPrefs extends LimeticketsController
{
	function __construct()
	{
		parent::__construct();
	}

	function reset()
	{
		$db = JFactory::getDBO();
		
		$sql = "UPDATE #__limeticket_users SET settings = ''";
		$db->setQuery($sql);
		$db->Query();
		$count = $db->getAffectedRows();
		
		$msg = JText::_("Preferences reset for $count users");
		$this->setRedirect( 'index.php?option=com_limeticket&view=plugins&layout=configure&type=gui&name=default_prefs', $msg );
	}

}
