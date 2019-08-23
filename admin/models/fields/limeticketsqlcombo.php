<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

class JFormFieldLIMETICKETSQLCombo extends JFormFieldList
{
	protected $type = 'LIMETICKETSQLCombo';
	
	function __construct()
	{
		parent::__construct();
	}
	
	public function __get($name)
	{
		$res = parent::__get($name);
		
		if ($res)
		return $res;
		
		return $this->$name;		
	}
	
	function getOptions()
	{
		$sql = $this->element->sql;
		if (!$sql)
		return array();
		
		$db = JFactory::getDBO();
		$db->setQuery($sql);
		
		return array_merge(parent::getOptions(), $db->loadObjectList());
	}
	
	function AdminDisplay($value, $name, $item)
	{
		return $value;
	}
}
		     		 					 