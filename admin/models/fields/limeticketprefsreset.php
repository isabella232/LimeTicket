<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

JFormHelper::loadFieldClass('text');

class JFormFieldLIMETICKETPrefsReset extends JFormFieldText
{
	protected $type = 'LIMETICKETPrefsReset';

	protected function getInput()
	{
		$db = JFactory::getDBO();
		
		$sql = "SELECT count(*) as count FROM #__limeticket_users WHERE settings <> ''";
		$db->setQuery($sql);
		$data = $db->loadObject();
		
		$output = array();
		$output[] = "<p>There are {$data->count} users with handler prefernces set</p>";

		$output[] = "<a href='" . JRoute::_("index.php?option=com_limeticket&task=reset&controller=prefs") . "' class='btn btn-default'>Reset Prefs</a>";	
		
		return implode($output);
	}
}
