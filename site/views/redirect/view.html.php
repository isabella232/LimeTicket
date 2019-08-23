<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @deprecated 429cd01fb6045255547258af68b75934
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'models'.DS.'admin.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'comments.php');


class limeticketViewredirect extends LIMETICKETView
{
    function display($tpl = null)
    {
		JFactory::getApplication()->redirect(LIMETICKETRoute::_("index.php?option=com_limeticket&view=main", false));
		return;
    }
	
}

