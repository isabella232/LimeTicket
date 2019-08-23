<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport('joomla.application.component.model');



class FsssModelFaq extends JModelLegacy
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
			$query = ' SELECT * FROM #__limeticket_faq_faq '.
					'  WHERE id = '.LIMETICKETJ3Helper::getEscaped($this->_db,$this->_id);
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}
		if (!$this->_data) {
			$user = JFactory::getUser();
			$this->_data = new stdClass();
			$this->_data->id = 0;
			$this->_data->question = null;
			$this->_data->answer = null;
			$this->_data->longanswer = null;
			$this->_data->ordering = 0;
			$this->_data->published = 1;
			$this->_data->featured = 0;
			$this->_data->faq_cat_id = 0;
			$this->_data->fullanswer = null;
			$this->_data->author = $user->id;
			$this->_data->access = 1;
			$this->_data->language = "*";
		}
		return $this->_data;
	}

	function store($data)
	{
		$row = $this->getTable();
		
		$user = JFactory::getUser();
		if (!array_key_exists('author', $data) || $data['author'] == 0)
		{
			$data['author'] = $user->id;
		}

		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
	
		// sort code for all products
		$db	= JFactory::getDBO();		
		$query = "DELETE FROM #__limeticket_faq_tags WHERE faq_id = " . LIMETICKETJ3Helper::getEscaped($db, $row->id);		
		$db->setQuery($query);$db->Query();

		// store new product ids
		$tags = JRequest::getVar('tags');
		$lines = explode("\n",$tags);
			
		foreach ($lines as $tag)
		{
			$tag = trim($tag);
			if ($tag == "") continue;
			
			$query = "REPLACE INTO #__limeticket_faq_tags (faq_id, tag, language) VALUES (" . LIMETICKETJ3Helper::getEscaped($db, $row->id) . ",'" . LIMETICKETJ3Helper::getEscaped($db, $tag) . "','" . LIMETICKETJ3Helper::getEscaped($db, $row->language) . "')";
			$db->setQuery($query);$db->Query();					
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
				
				$db	= JFactory::getDBO();		
				$query = "DELETE FROM #__limeticket_faq_tags WHERE faq_id = " . LIMETICKETJ3Helper::getEscaped($db, $row->id);		
				$db->setQuery($query);$db->Query();
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

	function unfeature()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$db = JFactory::getDBO();		
		$ids = array();
		foreach ($cids as $id)
			$ids[] = (int)LIMETICKETJ3Helper::getEscaped($db, $id);

		$qry = "UPDATE #__limeticket_faq_faq SET featured = 0 WHERE id IN (" . implode(", ", $ids) . ")";
		
		$db->setQuery($qry);
		
		return $db->query();
	}

	function feature()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$db = JFactory::getDBO();		
		$ids = array();
		foreach ($cids as $id)
			$ids[] = (int)LIMETICKETJ3Helper::getEscaped($db, $id);

		$qry = "UPDATE #__limeticket_faq_faq SET featured = 1 WHERE id IN (" . implode(", ", $ids) . ")";
		
		$db->setQuery($qry);
		
		return $db->query();
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


