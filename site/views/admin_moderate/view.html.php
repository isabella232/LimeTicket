<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @deprecated fcaae976814e75bebd5b23274daa0c94
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'comments.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'admin_helper.php');

class FssViewAdmin_Moderate extends LIMETICKETView
{
	function display($tpl = NULL)
	{
		LIMETICKET_Helper::noBots();
		LIMETICKET_Helper::noCache();
		
		if (!LIMETICKET_Permission::CanModerate())
			return LIMETICKET_Admin_Helper::NoPerm();
		
		$this->comments = new LIMETICKET_Comments(null,null);
		if ($this->comments->Process())
			return;
			
		parent::display();	
	}
}

