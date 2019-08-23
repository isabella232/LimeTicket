<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @deprecated c1e5d448f15e3c43605f9e690af17505
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');

class LimeticketViewCsstest extends LIMETICKETView
{
	function display($tpl = null)
	{
		$type = LIMETICKET_Input::getCmd('type');
		
		if ($type)
			return parent::display($type);
		
		parent::display();	
	}
}
