<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );
require_once (JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_limeticket'.DS.'settings.php');
jimport('joomla.html.pane');


class LimeticketsViewLimetickets extends JViewLegacy
{
 
    function display($tpl = null)
	{
		LIMETICKET_CSSParse::OutputCSS('components/com_limeticket/assets/css/bootstrap/bootstrap_limeticketonly.less');
	
		if (JRequest::getVar('hide_template_warning'))
		{
			$db = JFactory::getDBO();
			$sql = "REPLACE INTO #__limeticket_settings (setting, value) VALUES ('bootstrap_template', '" . $db->escape(LIMETICKET_Helper::GetTemplate()) . "')";
			$db->setQuery($sql);
			$db->Query();
			
			JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_limeticket&view=limetickets', false));
		}

		JToolBarHelper::title( JText::_( 'LIMETICKET_PORTAL' ), 'limeticket.png' );
		LIMETICKETAdminHelper::DoSubToolbar();
	
		parent::display($tpl);
	}
	
	function Item($title, $link, $icon, $help)
	{
?>
		<div class="limeticket_main_item limeticketTip" data-placement="right" title="<?php echo JText::_($help); ?>">	
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

	function FSJItem($title, $link, $com, $icon, $help)
	{
		if (strtoupper($title) == $title) // If we are all uppercase, needs translating
			$title = JText::_($title);
		?>
		<div class="limeticket_main_item limeticketTip" data-placement="right" title="<?php echo JText::_($help); ?>">	
			<div class="limeticket_main_icon">
				<a href="<?php echo LIMETICKETRoute::_($link); // OK ?>">
					<img src="<?php echo JURI::root( true ); ?>/administrator/components/<?php echo $com; ?>/assets/images/<?php echo $icon;?>-48.png" width="48" height="48">
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


