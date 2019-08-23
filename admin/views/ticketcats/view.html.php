<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;


jimport( 'joomla.application.component.view' );


class LimeticketsViewTicketcats extends JViewLegacy
{
    
    function display($tpl = null)
    {
        JToolBarHelper::title( JText::_("TICKET_CATEGORIES"), 'limeticket_categories' );
        JToolBarHelper::deleteList();
        JToolBarHelper::editList();
        JToolBarHelper::addNew();
        JToolBarHelper::cancel('cancellist');
		LIMETICKETAdminHelper::DoSubToolbar();

        $this->lists = $this->get('Lists');
        $this->data = $this->get('Data');
        $this->pagination = $this->get('Pagination');

        parent::display($tpl);
    }
}



