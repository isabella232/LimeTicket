<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class TableEmail extends JTable
{

	var $id = null;

	var $tmpl = '';

	var $body = '';
	
	var $description = '';
	
	var $subject = '';
	
	var $ishtml = 0;
	
	function TableEmail(& $db) {
		parent::__construct('#__limeticket_emails', 'id', $db);
	}

	function check()
	{
		return true;
	}
}


