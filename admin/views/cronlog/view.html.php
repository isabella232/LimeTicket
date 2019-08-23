<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');

class LimeticketsViewCronLog extends JViewLegacy
{
	function display($tpl = null)
	{
		$document = JFactory::getDocument();
		JHtml::_('behavior.framework');
		JHTML::_('behavior.tooltip');

		$task = JRequest::getVar('task');
		JToolBarHelper::title( JText::_("Cron_Log"), 'limeticket_cronlog' );
		JToolBarHelper::cancel('cancellist');
		//LIMETICKETAdminHelper::DoSubToolbar();
		
		if ($task == "cancellist")
			return $this->BackToEmails();

		if ($task == "clear")
			return $this->ClearCronLog();
		
		LIMETICKET_Helper::cleanLogs();
		
		$this->DisplayList();
	}

	function BackToEmails()
	{
		$mainframe = JFactory::getApplication();
		$link = LIMETICKETRoute::_('index.php?option=com_limeticket&view=limetickets',false);
		$mainframe->redirect($link);
	}

	function ClearCronLog()
	{
		$db = JFactory::getDBO();
		$qry = "TRUNCATE #__limeticket_cron_log";
		$db->SetQuery($qry);
		$db->Query($qry);
		$mainframe = JFactory::getApplication();
		$link = LIMETICKETRoute::_('index.php?option=com_limeticket&view=cronlog',false);
		$mainframe->redirect($link);
	}

	function DisplayList()
	{
		JHTML::_('behavior.modal', 'a.modal');

		$page = JRequest::getVar('page',0);
		$perpage = 20;

		$date = JRequest::getVar('date');
		$qry = "SELECT DATE(`when`) as `date`, DATE(`when`) as `label` FROM #__limeticket_cron_log GROUP BY `date` ORDER BY `date` DESC";
		$db = JFactory::getDBO();
		$db->setQuery($qry);
		$dates = array();
		$dates[] = JHTML::_('select.option', '', JText::_("SELECT_DATE"), 'date', 'label');
		$dates = array_merge($dates, $db->loadObjectList());
		$datelist = JHTML::_('select.genericlist',  $dates, 'date', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'date', 'label', $date);
		$this->dates = $datelist;
		
		
		$taskname = JRequest::getVar('taskname');
		$qry = "SELECT cron, cron as label FROM #__limeticket_cron_log GROUP BY cron ORDER BY cron";
		$db = JFactory::getDBO();
		$db->setQuery($qry);
		$tasks = array();
		$tasks[] = JHTML::_('select.option', '', JText::_("SELECT_TASK"), 'cron', 'label');
		$tasks = array_merge($tasks, $db->loadObjectList());
		$takslist = JHTML::_('select.genericlist',  $tasks, 'taskname', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'cron', 'label', $taskname);
		$this->tasks = $takslist;
		

		$qry = "SELECT * FROM #__limeticket_cron_log ";
		$wheres = array();
		if ($date)
			$wheres[] = " DATE(`when`) = '".LIMETICKETJ3Helper::getEscaped($db, $date)."' ";
		if ($taskname)
			$wheres[] = " cron = '".LIMETICKETJ3Helper::getEscaped($db, $taskname)."' ";

		if (count($wheres) > 0)
			$qry .= "WHERE " . implode(" AND " , $wheres);
		$qry .= " ORDER BY `when` desc";
		$db = JFactory::getDBO();
		$db->setQuery($qry);
		$db->query();
		$rowcount = $db->getNumRows();

		if ($rowcount > $perpage)
		{
			$db->setQuery($qry, $page * $perpage, $perpage);
		}
		$rows = $db->loadObjectList();
		
		$this->rows = $rows;
		$pagecount = ceil($rowcount / $perpage);

		$this->pagecount = $pagecount;
		$this->page = $page;
		parent::display();	
	}
	
	function outputData($row)
	{
		if ($row->type == "emailsend") return $this->outputEmailSend($row);	
		
		return "";
	}
	
	function outputEmailSend($row)
	{
		$data = json_decode($row->data);
		if (!$data) return;
		
		if (empty($data->vars)) return "";
		
		$output = array();
		
		$output[] = "<table class='table table-bordered table-condensed'><tr><th>Variable</th><th>Values</th></tr>";
		
		foreach ($data->vars as $key => $value)
		{
			$output[] = "<tr><td>{" . htmlentities($key) . "}</td><td>" .htmlentities($value) . "</td></tr>";
		}
		
		$output[] = "</table>";
		return implode($output);
	}
}
