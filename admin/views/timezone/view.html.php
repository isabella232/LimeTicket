<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );

class FsssViewTimezone extends JViewLegacy
{
	
	function display($tpl = null)
	{
		$mainframe = JFactory::getApplication();
		JToolBarHelper::title( JText::_("FREESTYLE_SUPPORT_PORTAL") .' - '. JText::_("Timezone Helper") , 'limeticket_settings' );
		JToolBarHelper::apply();
		JToolBarHelper::save();
		JToolBarHelper::cancel('cancellist');
		
		parent::display($tpl);
	}

}


