<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @deprecated 3805b865eeb0b8b9fdac8e72f2fe16f6
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );
jimport('joomla.filesystem.file');
jimport('fsj_core.admin.update');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'comments.php');

class limeticketViewcomments extends LIMETICKETView
{
	function display($tpl = null)
	{
		LIMETICKET_Helper::noBots();
		LIMETICKET_Helper::noCache();
		
		$ident = JRequest::getVar('identifier');
		$item = JRequest::getVar('itemid');

		$this->comments = new LIMETICKET_Comments($ident,$item);
		if (JRequest::getVar('opt_show_posted_message_only'))
			$this->comments->opt_show_posted_message_only = JRequest::getVar('opt_show_posted_message_only');
		$this->comments->Process();
		exit;
	}
}
