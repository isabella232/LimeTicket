<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'cron'.DS.'cron.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'email.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'tickethelper.php');

class LIMETICKETCronAutoClose extends LIMETICKETCron
{
	function Execute($aparams)
	{
		$debug = 0;
		
		$this->Log("Auto closing tickets");

		$db = JFactory::getDBO();

		$can_close = LIMETICKET_Ticket_Helper::GetStatusIDs('can_autoclose');
		$def_close = LIMETICKET_Ticket_Helper::GetStatusID('def_closed');
		
		if ($debug) $this->Log("Can Close : " . implode(", ", $can_close));
		if ($debug) $this->Log("Close To : " . $def_close);
		
		$now = LIMETICKET_Helper::CurDate();
		// if no audit log to be created, then can just close all tickets in a single query, this is quicker!
		if (!$aparams['addaudit'] && !$aparams['emailuser'])
		{
			if ($debug) echo "No audit required, just closing<br>";
		
			$qry = "UPDATE #__limeticket_ticket_ticket SET closed = '{$now}', ticket_status_id = $def_close WHERE DATE_ADD(`lastupdate` ,INTERVAL " . LIMETICKETJ3Helper::getEscaped($db, $aparams['closeinterval']) . " DAY) < '{$now}' AND ticket_status_id IN (" . implode(", ", $can_close) . ")";
			$db->setQuery($qry);
			$db->Query(); // UNCOMMENT

			$rows = $db->getAffectedRows();
			if ($debug) $this->Log($qry); // COMMENT
			$this->Log("Auto closed $rows tickets");
			return;
		}

		$qry = "SELECT * FROM #__limeticket_ticket_ticket WHERE DATE_ADD(`lastupdate` ,INTERVAL " . LIMETICKETJ3Helper::getEscaped($db, $aparams['closeinterval']) . " DAY) < '{$now}' AND ticket_status_id IN (" . implode(", ", $can_close) . ")";
		$db->setQuery($qry);
		if ($debug) $this->Log($qry);
		
		$rows = $db->loadAssocList();
		$this->Log("Found ".count($rows)." tickets to close");

		if (count($rows) == 0)
			return;

		$ids = array();

		$auditrows = array();

		foreach($rows as $row)
		{
			$ids[] = LIMETICKETJ3Helper::getEscaped($db, $row['id']);

			if ($aparams['addaudit'])
			{
				// add audit log to the ticket	
				$auditqry[] = "(".LIMETICKETJ3Helper::getEscaped($db, $row['id']).", 'Audit Message', 'Ticket auto-closed after ".LIMETICKETJ3Helper::getEscaped($db, $aparams['closeinterval'])." days of inactivity', 0, 3, '{$now}')";
			}
			
			if ($aparams['emailuser'])
			{
				$ticket = new SupportTicket();
				$ticket->load($row['id']);
				LIMETICKET_EMail::Admin_AutoClose($ticket);
			}
		}
			
		if ($aparams['addaudit'])
		{
			$qry = "INSERT INTO #__limeticket_ticket_messages (ticket_ticket_id, subject, body, user_id, admin, posted) VALUES \n";
			$qry .= implode(",\n ",$auditqry);
			if ($debug) $this->Log("Saving Audit Messages");
			if ($debug) $this->Log($qry);
			$db->setQuery($qry);
			$db->Query();
		}
		
		$qry = "UPDATE #__limeticket_ticket_ticket SET closed = '{$now}', ticket_status_id = $def_close WHERE id IN (" . implode(", ",$ids) . ")";
		if ($debug) $this->Log("Closing Tickets");
		if ($debug) $this->Log($qry);
		$db->setQuery($qry);
		$db->Query();
		
		$this->Log("Closed ".count($rows)." tickets");
		/*echo "<pre style='background-color:white;'>";
		echo $qry;
		echo "</pre>";*/
	}
}