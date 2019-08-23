<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.model' );

class LimeticketModelTest extends JModelLegacy
{
	function &getProduct()
	{
		$db = JFactory::getDBO();
		$prodid = LIMETICKET_Input::getInt('prodid');
		$query = "SELECT * FROM #__limeticket_prod WHERE id = '".LIMETICKETJ3Helper::getEscaped($db, $prodid)."'";

		$db->setQuery($query);
		$rows = $db->loadAssoc();
		return $rows;        
	} 
	
	function &getProducts()
	{
		$db = JFactory::getDBO();
		$query = "SELECT * FROM #__limeticket_prod";
		
		$where = array();
		$where[] = "published = 1";
		$where[] = "intest = 1";
		
		$user = JFactory::getUser();
		$where[] = 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				
		
		if (count($where) > 0)
			$query .= " WHERE " . implode(" AND ",$where);

		$query .= " ORDER BY ordering ";

		$db->setQuery($query);
		$rows = $db->loadAssocList('id');
		if (!is_array($rows))
			return array();
		return $rows;        
	} 
}

