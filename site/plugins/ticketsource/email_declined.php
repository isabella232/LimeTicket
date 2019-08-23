<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class Ticket_Source_EMail_Declined extends Ticket_Source
{
	var $name = "EMail Declined";
	
	var $user_show = false;
	var $admin_show = false;	
}
