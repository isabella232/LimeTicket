<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>

<?php echo LIMETICKET_Helper::PageSubTitle("<a href='".LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=report' )."'>
	<img class='limeticket_support_main_image' src='". JURI::root( true ) ."/components/com_limeticket/assets/images/support/report_24.png'>&nbsp;" . JText::_("REPORTS"). "</a>",false); ?>
<p>
	<a href="<?php echo LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=report' ); ?>"><?php echo JText::_('VIEW_NOW'); ?></a>
</p>
		