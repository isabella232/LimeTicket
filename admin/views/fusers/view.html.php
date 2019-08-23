<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );

require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'permission.php');

class LimeticketsViewFusers extends JViewLegacy
{
 
    function display($tpl = null)
    {
		LIMETICKET_CSSParse::OutputCSS('components/com_limeticket/assets/css/bootstrap/bootstrap_limeticketonly.less');
		
		LIMETICKET_Helper::IncludeModal();
		
		JToolBarHelper::title( JText::_("Permissions"), 'limeticket_users' );
        JToolBarHelper::deleteList();
        JToolBarHelper::editList();
        //JToolBarHelper::addNew("OK");
		$bar = JToolbar::getInstance('toolbar');
		$bar->appendButton('Standard', 'new', "LIMETICKET_Add_User", "add", false);

        JToolBarHelper::cancel('cancellist');
		LIMETICKETAdminHelper::DoSubToolbar();

        $this->lists = $this->get('Lists');
        $this->data = $this->get('Data');
        $this->pagination = $this->get('Pagination');

        parent::display($tpl);
    }
	
	function Item($title, $link, $icon, $help)
	{
?>
		<div class="limeticket_main_item limeticketTip" title="<?php echo JText::_($help); ?>">	
			<div class="limeticket_main_icon">
				<a href="<?php echo LIMETICKETRoute::_($link); // OK ?>">
					<img src="<?php echo JURI::root( true ); ?>/administrator/components/com_limeticket/assets/images/<?php echo $icon;?>-48x48.png" width="48" height="48">
				</a>
			</div>
			<div class="limeticket_main_text">
				<a href="<?php echo LIMETICKETRoute::_($link); // OK ?>">
					<?php echo JText::_($title); ?>
				</a>
			</div>
		</div>	
<?php
	}
}



