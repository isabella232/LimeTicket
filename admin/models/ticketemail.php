<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport('joomla.application.component.model');

class limeticketsModelticketemail extends JModelLegacy
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
			$query = ' SELECT * FROM #__limeticket_ticket_email '.
					'  WHERE id = '.LIMETICKETJ3Helper::getEscaped($this->_db,$this->_id);
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}
		if (!$this->_data) {
			$this->_data = new stdClass();
			$this->_data->id = 0;
			$this->_data->name = '';
			$this->_data->server = '';
			$this->_data->type = '';
			$this->_data->port = '110';
			$this->_data->username = '';
			$this->_data->password = '';
			$this->_data->checkinterval = '5';
			$this->_data->newticketsfrom = '';
			$this->_data->allowunknown = 0;
			$this->_data->allowrepliesonly = 0;
			$this->_data->prod_id = '';
			$this->_data->dept_id = '';
			$this->_data->cat_id = '';
			$this->_data->pri_id = '';
			$this->_data->handler = '';
			$this->_data->usessl = '';
			$this->_data->usetls = '';
			$this->_data->validatecert = '';
			$this->_data->allow_joomla = '';
			$this->_data->published = '1';
			$this->_data->toaddress = '';
			$this->_data->ignoreaddress = '';
			$this->_data->ignoresubject  = '';
			$this->_data->import_html  = 0;
			$this->_data->connectstring  = '';
			$this->_data->confirmnew  = 0;
			$this->_data->onimport = 'markread';
            $this->_data->cronid = 0;
            $this->_data->closedticket = 0;
		}
		return $this->_data;
	}

	function store($data)
	{
		$row = $this->getTable();

		// put entry into cron table
		$cronid = $data['cronid'];
		//print_r($data);

		$aparams = serialize($data);
		
		if ($cronid > 0)
		{
			$qry = "REPLACE INTO #__limeticket_cron (id, cronevent, class, `interval`, published, params) VALUES (";
			$qry .= "\"".$data['cronid'] . "\", ";
		} else {
			$qry = "INSERT INTO #__limeticket_cron (cronevent, class, `interval`, published, params) VALUES (";
		}
		$qry .= "\"EMail Check - " . LIMETICKETJ3Helper::getEscaped($this->_db,$data['name']) . "\", ";
		$qry .= "\"EMailCheck\", ";
		$qry .= "\"" . LIMETICKETJ3Helper::getEscaped($this->_db,$data['checkinterval']) . "\", ";
		$qry .= "\"" . LIMETICKETJ3Helper::getEscaped($this->_db,$data['published']) . "\", ";
		$qry .= "\"" . LIMETICKETJ3Helper::getEscaped($this->_db,$aparams) . "\")";

		//echo $qry."<br>";

		$this->_db->SetQuery($qry);
		$this->_db->query();

		if (!$cronid)
		{
			$data['cronid'] = $this->_db->insertid();	
		}

		/*print_p($data);
		exit;*/
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

				$qry = "SELECT cronid FROM #__limeticket_ticket_email WHERE id = '".LIMETICKETJ3Helper::getEscaped($this->_db,$cid)."'";
				$this->_db->setQuery($qry);
				//echo $qry."<br>";
				$temp = $this->_db->loadObject();

				$cronid = $temp->cronid;
				if ($cronid)
				{
					$qry = "DELETE FROM #__limeticket_cron WHERE id = '".LIMETICKETJ3Helper::getEscaped($this->_db,$cronid)."'";	
					$this->_db->setQuery($qry);
					//echo $qry."<br>";
					$this->_db->query();
				}
				
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

		$result = $table->publish($cids, 0);
		if ($result)
		{
			if (count( $cids )) {
				foreach($cids as $cid) {

					$qry = "SELECT cronid FROM #__limeticket_ticket_email WHERE id = '".LIMETICKETJ3Helper::getEscaped($this->_db,$cid)."'";
					$this->_db->setQuery($qry);
					//echo $qry."<br>";
					$temp = $this->_db->loadObject();

					$cronid = $temp->cronid;
					if ($cronid)
					{
						$qry = "UPDATE #__limeticket_cron SET published = 0 WHERE id = '".LIMETICKETJ3Helper::getEscaped($this->_db,$cronid)."'";	
						$this->_db->setQuery($qry);
						//echo $qry."<br>";
						$this->_db->query();
					}				
				}	
			}
		}
		return $result;
	}

	function publish()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$table = $this->getTable();

		$result = $table->publish($cids, 1);
		if ($result)
		{
			if (count( $cids )) {
				foreach($cids as $cid) {

					$qry = "SELECT cronid FROM #__limeticket_ticket_email WHERE id = '".LIMETICKETJ3Helper::getEscaped($this->_db,$cid)."'";
					$this->_db->setQuery($qry);
					//echo $qry."<br>";
					$temp = $this->_db->loadObject();

					$cronid = $temp->cronid;
					if ($cronid)
					{
						$qry = "UPDATE #__limeticket_cron SET published = 1 WHERE id = '".LIMETICKETJ3Helper::getEscaped($this->_db,$cronid)."'";	
						$this->_db->setQuery($qry);
						//echo $qry."<br>";
						$this->_db->query();
					}				
				}	
			}
		}
		return $result;
	}
}

