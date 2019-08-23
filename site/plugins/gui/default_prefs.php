<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'guiplugins.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'support_ticket.php');

class LIMETICKET_GUIPlugin_Default_Prefs extends LIMETICKET_Plugin_GUI
{
	var $title = "Default Handler Preferences";
	var $description = "Change the default handler preferences. Includes tool to reset the handler preferences for all users.";
}