<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'support_actions.php');

class LimeticketsControllerlistuser extends JControllerLegacy
{
	var $messages = array();

	function __construct()
	{
		parent::__construct();
	}

	function cancellist()
	{
		$link = 'index.php?option=com_limeticket&view=limetickets';
		$this->setRedirect($link, $msg);
	}

	function adduser()
	{
		$cid = JRequest::getVar('cid',  0, '', 'array');
		$groupid = JRequest::getVar('groupid');

		$this->AddMembership($cid,$groupid);

		$link = "index.php?option=com_limeticket&view=members&groupid=$groupid";
		if (count($this->messages) > 0)
			$link .= "&messages=" . implode("|", $this->messages);
		echo "<script>\n";
		echo "parent.location.href=\"$link\";\n";
		echo "</script>";
		//$this->setRedirect($link, $msg);
	}

	function AddMembership($userids, $groupid)
	{
		$db	= JFactory::getDBO();
		foreach ($userids as $userid)
		{
			$result = SupportActions::ActionResult("groupAdd", array('group_id' => $groupid, 'user_id' => $userid), true);	
			if ($result === true)
			{
				$qry = "REPLACE INTO #__limeticket_ticket_group_members (group_id, user_id) VALUES ('" . LIMETICKETJ3Helper::getEscaped($db, $groupid) . "', '" . LIMETICKETJ3Helper::getEscaped($db, $userid)."')";
				$db->setQuery($qry);
				$db->query($qry);
			} else {
				$this->messages[] = $result;
			}
		}
	}
}


