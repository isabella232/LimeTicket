<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );

class FsssViewTicketstatus extends JViewLegacy
{

	function display($tpl = null)
	{
		$ticketstatus		= $this->get('Data');
		$isNew		= ($ticketstatus->id < 1);

		$text = $isNew ? JText::_("NEW") : JText::_("EDIT");
		JToolBarHelper::title(   JText::_("TICKET_STATUS").': <small><small>[ ' . $text.' ]</small></small>', 'limeticket_ticketstatuss' );
		JToolBarHelper::custom('translate','translate', 'translate', 'Translate', false);
		JToolBarHelper::spacer();
		JToolBarHelper::apply();
		JToolBarHelper::save();
		JToolBarHelper::save2new();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}
		LIMETICKETAdminHelper::DoSubToolbar();
		$db	= JFactory::getDBO();

		$this->for_user = "";

		$this->ticketstatus = $ticketstatus;

		parent::display($tpl);
	}
}


