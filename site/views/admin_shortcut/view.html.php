<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @deprecated 572add9fd1733ee7e43718c4165ed0a4
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');

class LimeticketViewAdmin_Shortcut extends LIMETICKETView
{

	function display($tpl = null)
	{
		LIMETICKET_Helper::noBots();
		LIMETICKET_Helper::noCache();
		
		$sc = LIMETICKET_Input::getCmd('shortcut');
		$status = LIMETICKET_Input::getCmd('status');
		
		$link = "index.php?option=com_limeticket&view=admin";
		
		switch ($sc)
		{
			case "create.registered":
				$link = "index.php?option=com_limeticket&view=admin_support&layout=new&type=registered";
				break;
			case "create.unregistered":
				$link = "index.php?option=com_limeticket&view=admin_support&layout=new&type=unregistered";
				break;
				
			// Lookup status from advanced tab for these!
			case "tickets.mine":
				$link = "index.php?option=com_limeticket&view=admin_support&tickets=-1&what=search&searchtype=advanced&showbasic=1&handler=-1&status=$status";
				break;
				
			case "tickets.other":
				$link = "index.php?option=com_limeticket&view=admin_support&tickets=-1&what=search&searchtype=advanced&showbasic=1&handler=-2&status=$status";
				break;
				
			case "tickets.unassigned":
				$link = "index.php?option=com_limeticket&view=admin_support&tickets=-1&what=search&searchtype=advanced&showbasic=1&handler=-3&status=$status";
				break;
				
			case "tickets.status":
				$link = "index.php?option=com_limeticket&view=admin_support&tickets=$status";
				break;
				
			case "myadminsettings":
				$link = "index.php?option=com_limeticket&view=admin_support&layout=settings";
				break;
			
			case "content.announcements":
				$link = "index.php?option=com_limeticket&view=admin_content&type=announce";
				break;
			
			case "content.faqs":
				$link = "index.php?option=com_limeticket&view=admin_content&type=faqs";
				break;
			
			case "content.kb":
				$link = "index.php?option=com_limeticket&view=admin_content&type=kb";
				break;
		
			case "content.glossary":
				$link = "index.php?option=com_limeticket&view=admin_content&type=glossary";
				break;		
		}

		$link = LIMETICKETRoute::_($link, false);
		//$link = JRoute::_($link, false);
		
		$mainframe = JFactory::getApplication();
		$mainframe->redirect($link);

	}

}