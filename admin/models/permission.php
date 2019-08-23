<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport('joomla.application.component.model');
jimport('joomla.application.component.modeladmin');

class FsssModelPermission extends JModelAdmin
{
	public function getForm($form = "content", $data = array(), $loadData = true)
	{
		$form = $this->loadForm("com_limeticket." . $form, $form, array('control' => 'jform', 'load_data' => $loadData));
		
		return $form;
	}
	
	public function getItem($pk = null)
	{
		$type = $this->getSection();	
		$db = JFactory::getDBO();
		$qry = "SELECT id as asset_id, rules, name, title FROM #__assets WHERE name = '{$type}'";
		//echo $qry . "<br>";
		$db->setQuery($qry);
		
		$item = $db->loadObject();
	
		//print_p($item);
	
		return $item;
	}
	
	function getSection()
	{
		$type = JRequest::getVar("section", "com_limeticket");
		if ($type != "com_limeticket" && substr($type, 0, 7) != "com_limeticket")
			$type = "com_limeticket." . $type;
		
		return $type;
	}
	
	function getFormID()
	{
		$section = $this->getSection();
		$bits = explode(".", $section);
		array_shift($bits);
		if (count($bits) < 1)
			return "content";
		
		$forms = array("support_user","support_admin", 'comments', 'groups', 'reports', 'moderation');
		
		$section_parsed = implode(".", $bits);
		
		if (in_array($section_parsed, $forms))
			return $section_parsed;
		
		return "content";
	}
	
	protected function loadFormData()
	{
		$item = $this->getItem();
		
		return $item;
	}
}



