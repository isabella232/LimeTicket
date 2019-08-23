<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class TableCustom extends JTable
{
	
	var $id = null;

	var $title = null;

	var $body= null;


	function TableCustom(& $db) {
		parent::__construct('#__limeticket_custom_text', 'id', $db);
	}
}


