<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'support_canned.php');

class LimeticketViewAdmin_Support_Signature extends LimeticketViewAdmin_Support
{
	function display($tpl = NULL)
	{
		LIMETICKET_Helper::AddSCEditor();
		
		if (LIMETICKET_Input::getInt('sigid'))
			return $this->display_edit();
			
		$this->sigs = SupportCanned::GetAllSigs(null);
		
		$this->_display("list");
	}
	
	function display_edit()
	{
		$editid = LIMETICKET_Input::getInt('sigid');
		
		if ($editid > 0)
		{
			$db = JFactory::getDBO();
			$qry = "SELECT * FROM #__limeticket_ticket_fragments WHERE id = " . LIMETICKETJ3Helper::getEscaped($db, $editid);
			$db->setQuery($qry);
			$this->sig_item = $db->loadObject();
				
			$this->sig_item->params = json_decode($this->sig_item->params, true);
			if (is_string($this->sig_item->params))
				$this->sig_item->params = array();	
				
			$this->sig_item->personal = 0;
				
			$userid = JFactory::getUser()->id;
				
			if (isset($this->sig_item->params['userid']))
			{
				if ($this->sig_item->params['userid'] > 0 && $userid != $this->sig_item->params['userid'])
				{
					$mainframe = JFactory::getApplication();
					$link = JRoute::_('index.php?option=com_limeticket&view=admin_support&layout=signature&tmpl=component');
					$mainframe->redirect($link);
				}
					
				$this->sig_item->personal = 1;
			}			
		} else {
			$this->sig_item = new stdClass();
			$this->sig_item->id = 0;
			$this->sig_item->description = "";
			$this->sig_item->content = "";		
			$this->sig_item->personal = 1;		
		}
		return $this->_display("edit");	
	}
}