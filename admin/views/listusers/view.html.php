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


class LimeticketsViewlistusers extends JViewLegacy
{
  
    function display($tpl = null)
    {
		$tpl = JRequest::getVar('tpl', $tpl);

		JToolBarHelper::title( JText::_( 'List Users' ), 'limeticket_groups' );
        JToolBarHelper::editList();
		JToolBarHelper::cancel('cancellist');
		LIMETICKETAdminHelper::DoSubToolbar();

        $lists = $this->get('Lists');

		$document = JFactory::getDocument();
	
		$filter = array();
		$filter[] = JHTML::_('select.option', '', JText::_('JOOMLA_GROUP'), 'id', 'name');
		$query = 'SELECT id, title as name FROM #__usergroups ORDER BY title';
		$db	= JFactory::getDBO();
		$db->setQuery($query);
		$filter = array_merge($filter, $db->loadObjectList());
		$lists['gid'] = JHTML::_('select.genericlist',  $filter, 'gid', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'id', 'name', $lists['gid']);

		$this->lists = $lists;

        $this->data = $this->get('Data');
        $this->pagination = $this->get('Pagination');

        parent::display($tpl);
    }
}


