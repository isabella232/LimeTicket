<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

/**
 * Functions relating to general admin stuff
 * 
 * NO TYPE SPECIFIC STUFF - Ie, no content / support / groups etc
 **/
class LIMETICKET_Admin_Helper
{
	static function NoPerm()
	{
		/*if (array_key_exists('REQUEST_URI',$_SERVER))
		{
			$url = $_SERVER['REQUEST_URI'];//JURI::current() . "?" . $_SERVER['QUERY_STRING'];
		} else {
			$option = LIMETICKET_Input::getCmd('option','');
			$view = LIMETICKET_Input::getCmd('view','');
			$layout = LIMETICKET_Input::getCmd('layout','');
			$Itemid = LIMETICKET_Input::getInt('Itemid',0);
			$url = LIMETICKETRoute::_("index.php?option=" . $option . "&view=" . $view . "&layout=" . $layout . "&Itemid=" . $Itemid); 	
		}
		$url = base64_encode($url);*/

		$return = LIMETICKET_Helper::getCurrentURLBase64();

		require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'admin'.DS.'tmpl'.DS.'noperm.php');		
		
		return false;
	}
	
	static function id_to_asset($id)
	{
		switch ($id)
		{
		case 'announce':	
			return "com_limeticket.announce";
		case 'faqs':	
			return "com_limeticket.faq";
		case 'kb':	
			return "com_limeticket.kb";
		case 'glossary':	
			return "com_limeticket.glossary";
		}	
		
		return "com_limeticket";
	}
}