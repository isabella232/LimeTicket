<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport('joomla.application.component.model');



class FsssModelKbcat extends JModelLegacy
{

	function __construct()
	{
		parent::__construct();

		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId((int)$array[0]);
	}

	function setId($id)
	{
		$this->_id		= $id;
		$this->_data	= null;
	}

	function &getData()
	{
		if (empty( $this->_data )) {
			$query = ' SELECT * FROM #__limeticket_kb_cat '.
					'  WHERE id = '.LIMETICKETJ3Helper::getEscaped($this->_db,$this->_id);
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}
		if (!$this->_data) {
			$this->_data = new stdClass();
			$this->_data->id = 0;
			$this->_data->parentid = 0;
			$this->_data->title = null;
			$this->_data->description = null;
			$this->_data->ordering = 0;
			$this->_data->published = 1;
			$this->_data->image = null;
			$this->_data->parcatid = 0;
			$this->published = 1;
			$this->_data->access = 1;
			$this->_data->language = "*";
			$this->_data->prodids = "";
		}
		return $this->_data;
	}

	function store($data)
	{
		$row = $this->getTable();

		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		
		if (JRequest::getVar('allprods'))
		{
			$row->prodids = "";	
		} else {
			$db	= JFactory::getDBO();
			$query = "SELECT * FROM #__limeticket_prod ORDER BY title";
			$db->setQuery($query);
			$products = $db->loadObjectList();
			
			$prodids = array();
			
			foreach ($products as $product)
			{
				$id = $product->id;
				$value = JRequest::getVar( "prod_" . $product->id );
				if ($value != "")
				{
					$prodids[] = $product->id;
					//$query = "INSERT INTO #__limeticket_kb_art_prod (kb_art_id, prod_id) VALUES (" . LIMETICKETJ3Helper::getEscaped($db, $row->id) . "," . LIMETICKETJ3Helper::getEscaped($db, $id) . ")";
					//$db->setQuery($query);$db->Query();					
				}
			}
			$row->prodids = implode(";", $prodids);	
		}

		/*print_p($_POST);
		print_p($row);
		exit;*/

		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		$this->_id = $row->id;
		$this->_data = $row;

		return true;
	}

	function delete()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$row = $this->getTable();

		if (count( $cids )) {
			foreach($cids as $cid) {
				if (!$row->delete( $cid )) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
			}
		}
		return true;
	}

	function unpublish()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$table = $this->getTable();

		return $table->publish($cids, 0);
	}

	function publish()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$table = $this->getTable();

		return $table->publish($cids, 1);
	}

	function changeorder($direction)
	{
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		if (isset( $cid[0] ))
		{
			$row = $this->getTable();
			$row->load( (int) $cid[0] );
			$row->move($direction);

			return true;
		}
		return false;
	}

	function saveorder()
	{
		$cid		= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$order		= JRequest::getVar( 'order', array (0), 'post', 'array' );
		$total		= count($cid);

		JArrayHelper::toInteger($cid, array(0));
		JArrayHelper::toInteger($order, array(0));

		// Instantiate an article table object
		$row = $this->getTable();

		// Update the ordering for items in the cid array
		for ($i = 0; $i < $total; $i ++)
		{
			$row->load( (int) $cid[$i] );
			if ($row->ordering != $order[$i]) {
				$row->ordering = $order[$i];

				if (!$row->store()) {
					JError::raiseError( 500, $db->getErrorMsg() );
					return false;
				}
			}
		}

		$row->reorder();
		$row->reset();

		return true;
	}
}


