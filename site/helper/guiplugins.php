<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
require_once( JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'plugin.php' );

class LIMETICKET_GUIPlugins 
{
	static $loaded = false;
	static $plugins = array();
	
	static function load()
	{
		if (self::$loaded)
		return;
		
		$path = JPATH_SITE.DS."components".DS."com_limeticket".DS."plugins".DS."gui".DS;
		$files = JFolder::files($path, ".php$");
		
		foreach ($files as $file)
		{
			$id = pathinfo($file, PATHINFO_FILENAME);

			if (!LIMETICKET_Helper::IsPluignEnabled("gui", $id))
				continue;

			$class = "LIMETICKET_GUIPlugin_" . $id;
			require_once($path . DS . $file);
			if (class_exists($class))
			{
				self::$plugins[$id] = new $class();	
				self::$plugins[$id]->type = 'gui';
				self::$plugins[$id]->name = $id;
			}
		}
		
		self::$loaded = true;
	}

	static function output($function, $params = null)
	{
		self::load();

		if (!is_array($params))
			$params = array();

		foreach (self::$plugins as $plugin)
		{
			if (method_exists($plugin, $function))
			{
				return call_user_func_array(array($plugin, $function), $params);
			}
		}

		return "";
	}
}

class LIMETICKET_Plugin_GUI extends LIMETICKET_Plugin
{

}