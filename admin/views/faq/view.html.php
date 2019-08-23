<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );



class LimeticketsViewFaq extends JViewLegacy
{

	function display($tpl = null)
	{
		$mainframe = JFactory::getApplication();
		$faq		= $this->get('Data');
		$isNew		= ($faq->id < 1);

		$text = $isNew ? JText::_("NEW") : JText::_("EDIT");
		JToolBarHelper::title(   JText::_("FAQ").': <small><small>[ ' . $text.' ]</small></small>', 'limeticket_faqs' );
		JToolBarHelper::apply();
		JToolBarHelper::save();
		JToolBarHelper::save2new();
		
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}
		LIMETICKETAdminHelper::DoSubToolbar();

		$this->faq = $faq;

		$query = 'SELECT id, title' .
				' FROM #__limeticket_faq_cat' .
				' ORDER BY ordering';
		$db	= JFactory::getDBO();
		$db->setQuery($query);

		$sections = $db->loadObjectList();

		if (count($sections) < 1)
		{
			$link = LIMETICKETRoute::_('index.php?option=com_limeticket&view=faqs',false);
			$mainframe->redirect($link,"You must create a FAQ category first");
			return;
					
		}
		
		if ($faq->id > 0)
		{
			$qry = "SELECT * FROM #__limeticket_faq_tags WHERE faq_id = ".LIMETICKETJ3Helper::getEscaped($db, $faq->id)." ORDER BY tag";
			$db->setQuery($qry);
			$this->tags = $db->loadObjectList();
		} else {
			$this->tags = array();	
		}
		
		$lists['catid'] = JHTML::_('select.genericlist',  $sections, 'faq_cat_id', 'class="inputbox" size="1" ', 'id', 'title', intval($faq->faq_cat_id));

		$this->lists = $lists;

		parent::display($tpl);
	}
}


