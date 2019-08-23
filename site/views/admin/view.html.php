<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @deprecated 593b5976da58f2285cc697431670b67a
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');

require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'content.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'comments.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'admin_helper.php');

class FssViewAdmin extends LIMETICKETView
{
	var $parser = null;
	var $layoutpreview = 0;

	function display($tpl = null)
	{		
		LIMETICKET_Helper::noBots();
		LIMETICKET_Helper::noCache();
		
		$layout = LIMETICKET_Input::getCmd('layout');
		if ($layout == "support")
			return JFactory::getApplication()->redirect(LIMETICKETRoute::_('index.php?option=com_limeticket&view=admin_support',false));
		if ($layout == "content")
			return JFactory::getApplication()->redirect(LIMETICKETRoute::_('index.php?option=com_limeticket&view=admin_content',false));
		if ($layout == "moderate")
			return JFactory::getApplication()->redirect(LIMETICKETRoute::_('index.php?option=com_limeticket&view=admin_moderate',false));
		if ($layout == "shortcut")
			return JFactory::getApplication()->redirect(LIMETICKETRoute::_('index.php?option=com_limeticket&view=admin_shortcut',false));
		
		$can_view = false;
		$view = array();
		if (LIMETICKET_Permission::PermAnyContent())
		{
			$view[] = LIMETICKETRoute::_('index.php?option=com_limeticket&view=admin_content',false);
			$can_view = true;
		}
		if (LIMETICKET_Permission::AdminGroups())
		{
			$view[] = LIMETICKETRoute::_('index.php?option=com_limeticket&view=admin_groups',false);
			$can_view = true;
		}
		if (LIMETICKET_Permission::auth("limeticket.reports", "com_limeticket.reports"))
		{
			$view[] = LIMETICKETRoute::_('index.php?option=com_limeticket&view=admin_report',false);
			$can_view = true;
		}
		if (LIMETICKET_Permission::auth("limeticket.handler", "com_limeticket.support_admin"))
		{
			$view[] = LIMETICKETRoute::_('index.php?option=com_limeticket&view=admin_support',false);
			$can_view = true;
		}
		if (LIMETICKET_Permission::CanModerate())
		{
			$view[] = LIMETICKETRoute::_('index.php?option=com_limeticket&view=admin_moderate',false);
			$can_view = true;
		}
		
		if (!$can_view)
			return LIMETICKET_Admin_Helper::NoPerm();
		
		// if only 1 section visible, then view that section only
		if (count($view) == 1)
		{
			$mainframe = JFactory::getApplication();
			$link = reset($view);
			$mainframe->redirect($link);	
		}

		
		$this->comments = new LIMETICKET_Comments(null,null);

		$this->artcounts = LIMETICKET_ContentEdit::getArticleCounts();

		parent::display();
	}
}

