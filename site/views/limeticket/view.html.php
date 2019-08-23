<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @deprecated 57e86eed5109923b70957b737e75d017
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');
jimport('joomla.utilities.date');

class FssViewFss extends LIMETICKETView
{
    function display($tpl = null)
    {
        $mainframe = JFactory::getApplication();
		$option = LIMETICKET_Input::getCmd('option');
		if ($option == "com_fst")
		{
			$link = LIMETICKETRoute::_('index.php?option=com_limeticket&view=test',false);
		} else if ($option == "com_fsf")
		{
			$link = LIMETICKETRoute::_('index.php?option=com_limeticket&view=faq',false);
		} else {
			$link = LIMETICKETRoute::_('index.php?option=com_limeticket&view=main',false);
		}
		$mainframe->redirect($link);
    }
}

