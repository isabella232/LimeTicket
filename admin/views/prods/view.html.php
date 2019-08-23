<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );


class FsssViewProds extends JViewLegacy
{
    function display($tpl = null)
    {
        JToolBarHelper::title( JText::_("PRODUCTS"), 'limeticket_prods' );
        JToolBarHelper::deleteList();
        JToolBarHelper::editList();
        JToolBarHelper::addNew();
		JToolBarHelper::divider();
		if (LIMETICKET_Helper::TableExists("#__virtuemart_products_en_gb") || LIMETICKET_Helper::TableExists("#__vm_product"))
			JToolBarHelper::custom('import_vm','copy','copy','IMPORT_FROM_VIRTUEMART',false);

		if (LIMETICKET_Helper::TableExists("#__hikashop_product"))
			JToolBarHelper::custom('import_hs','copy','copy','Import From Hika Shop',false);

        JToolBarHelper::cancel('cancellist');
		LIMETICKETAdminHelper::DoSubToolbar();

        $this->lists = $this->get('Lists');
        $this->data = $this->get('Data');
        $this->pagination = $this->get('Pagination');

		$categories = array();
		$categories[] = JHTML::_('select.option', '-1', JText::_("IS_PUBLISHED"), 'id', 'title');
		$categories[] = JHTML::_('select.option', '1', JText::_("PUBLISHED"), 'id', 'title');
		$categories[] = JHTML::_('select.option', '0', JText::_("UNPUBLISHED"), 'id', 'title');
		$this->lists['published'] = JHTML::_('select.genericlist',  $categories, 'ispublished', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'id', 'title', $this->lists['ispublished']);

		$what = JRequest::getVar('what');
		if ($what == "togglefield")
			return $this->toggleField();

        parent::display($tpl);
    }

	function toggleField()
	{
		$id = JRequest::getVar('id');
		$field = JRequest::getVar('field');			
		$val = JRequest::getVar('val');	

		if ($field == "")
			return;
		if ($id < 1)
			return;
		if ($field != "inkb" && $field != "insupport" && $field != "intest")
			return;

		$db = JFactory::getDBO();

		$qry = "UPDATE #__limeticket_prod SET ".LIMETICKETJ3Helper::getEscaped($db, $field)." = ".LIMETICKETJ3Helper::getEscaped($db, $val)." WHERE id = ".LIMETICKETJ3Helper::getEscaped($db, $id);
		$db->setQuery($qry);
		$db->Query();

		echo LIMETICKET_GetYesNoText($val);
		exit;
	}
}



