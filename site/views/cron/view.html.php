<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @deprecated 97ed680195d104a3b47f288602b618ad
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');
require_once( JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'cron.php' );

class LimeticketViewCron extends LIMETICKETView
{
	function display($tpl = null)
    {
		LIMETICKET_Helper::noBots();
		LIMETICKET_Helper::noCache();
		
		LIMETICKET_Cron_Helper::runCron(LIMETICKET_Input::getInt('test'));
		
		exit;
	}
}
