<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class TableFuser extends JTable
{
	
	var $id = null;

	var $rules = '';
	var $settings = '';
	
	function TableFuser(& $db) {
		parent::__construct('#__limeticket_users', 'user_id', $db);
	}
}


