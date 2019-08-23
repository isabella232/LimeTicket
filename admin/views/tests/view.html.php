<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );
jimport('joomla.utilities.date');
require_once( JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'comments.php' );

class LimeticketsViewTests extends JViewLegacy
{
 	function DoPublishComment($published)
	{
		$commentid = JRequest::getVar('commentid',0,'','int');   
		
		if (!$commentid)
			return;
			
		$db = JFactory::getDBO();
		$qry = "UPDATE #__limeticket_comments SET published = $published WHERE id = '".LIMETICKETJ3Helper::getEscaped($db, $commentid)."'";
		$db->SetQuery($qry);
		$db->Query();
		
		echo $qry;	
		exit;
		return true;	
	}
 
    function display($tpl = null)
    {
		$task = JRequest::getVar('task');
		if ($task == "removecomment")
			return $this->DoPublishComment(2);

		if ($task == "approvecomment")
			return $this->DoPublishComment(1);
				
        JToolBarHelper::title( JText::_("MODERATION"), 'limeticket_moderate' );
        JToolBarHelper::deleteList();
        JToolBarHelper::editList();
        JToolBarHelper::addNew();
		LIMETICKETAdminHelper::DoSubToolbar();

        $this->lists = $this->get('Lists');
        $this->data = $this->get('Data');
        $this->pagination = $this->get('Pagination');

		/*$query = 'SELECT id, title FROM #__limeticket_prod ORDER BY title';

		$db	= JFactory::getDBO();
		$categories[] = JHTML::_('select.option', '0', JText::_("SELECT_PRODUCT"), 'id', 'title');
		$db->setQuery($query);
		$categories = array_merge($categories, $db->loadObjectList());

		$this->lists['prods'] = JHTML::_('select.genericlist',  $categories, 'prod_id', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'id', 'title', $this->lists['prod_id']);*/
	
		$this->ident_to_name = array();
// ##NOT_TEST_START##
		$this->ident_to_name[1] = "kb";
		$this->ident_to_name[4] = "announce";
// ##NOT_TEST_END##
		$this->ident_to_name[5] = "test";
		$this->comment_objs = array();
		$comment_itemids = array();
	
		$this->ident = JRequest::getVar('ident','');		

		$sections = array();
		$sections[] = JHTML::_('select.option', '0', "-- ".JText::_("ALL_SECTIONS") . " --", 'id', 'title');
		foreach($this->ident_to_name as $ident => $name)
		{
			$this->comment_objs[$ident] = new LIMETICKET_Comments($name);		
			$sections[] = JHTML::_('select.option', $ident, $this->comment_objs[$ident]->handler->descriptions, 'id', 'title');
		}
		$this->lists['sections'] = JHTML::_('select.genericlist',  $sections, 'ident', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'id', 'title', $this->ident);


		$categories = array();
		$categories[] = JHTML::_('select.option', '-1', "-- ".JText::_("MOD_STATUS") . " --", 'id', 'title');
		$categories[] = JHTML::_('select.option', '0', JText::_("AWAITING_MODERATION"), 'id', 'title');
		$categories[] = JHTML::_('select.option', '1', JText::_("ACCEPTED"), 'id', 'title');
		$categories[] = JHTML::_('select.option', '2', JText::_("DECLINED"), 'id', 'title');
		$this->lists['published'] = JHTML::_('select.genericlist',  $categories, 'ispublished', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'id', 'title', $this->lists['ispublished']);


		foreach ($this->data as &$data)
		{
			$ident = $data->ident;
			$comment_itemids[$ident][$data->itemid] = $data->itemid;
		}
	
		$db	= JFactory::getDBO();
		
		foreach ($this->comment_objs as $ident => &$obj)
		{
			if (array_key_exists($ident, $comment_itemids))
			{
				$idlist = $comment_itemids[$ident];
				$obj->handler->GetItemData($idlist);
			}
		}
		
        parent::display($tpl);
    }
}



