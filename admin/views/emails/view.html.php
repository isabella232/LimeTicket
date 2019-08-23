<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );


class LimeticketsViewEmails extends JViewLegacy
{
 
    function display($tpl = null)
    {
        JToolBarHelper::title( JText::_("EMAIL_TEMPLATE_MANAGER"), 'limeticket_emails' );
        //JToolBarHelper::deleteList();
        JToolBarHelper::editList();
        //JToolBarHelper::addNew();
        JToolBarHelper::cancel('cancellist');
		LIMETICKETAdminHelper::DoSubToolbar();

        $this->data = $this->get('Data');

        parent::display($tpl);
    }
}


