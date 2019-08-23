<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
	<?php echo LIMETICKET_Helper::PageSubTitle("<a href='".LIMETICKETRoute::x( '&layout=moderate&ident=' )."'><img src='". JURI::root( true ) ."/components/com_limeticket/assets/images/support/moderate_24.png'>&nbsp;" . JText::_("MODERATE"). "</a>",false); ?>

	<p>
		<?php echo JText::sprintf("MOD_STATUS",$this->comments->GetModerateTotal(),LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=admin_moderate' )); ?>
	</p>
	<?php $this->comments->DisplayModStatus(); ?>