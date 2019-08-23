<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );



class FsssViewHelpText extends JViewLegacy
{

	function display($tpl = null)
	{
		$mainframe = JFactory::getApplication();
		$item		= $this->get('Data');
		$text = JText::_("EDIT");
		JToolBarHelper::title(   JText::_("Help Text").': <small><small>[ ' . $text.' ]</small></small>', 'limeticket_helptexts' );
		JToolBarHelper::custom('translate','translate', 'translate', 'Translate', false);
		JToolBarHelper::spacer();
		JToolBarHelper::apply();
		JToolBarHelper::save();
		
		JToolBarHelper::cancel( 'cancel', 'Close' );

		LIMETICKETAdminHelper::DoSubToolbar();

		$this->item = $item;

		parent::display($tpl);
	}
}


