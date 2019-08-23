<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

$template = LIMETICKET_Input::GetString("print");
$custom_print = Support_Print::loadPrint($template);
?>

<?php if (!$custom_print || (int)($custom_print->noheader) != 1): ?>
	<?php echo LIMETICKET_Helper::PageStyle(); ?>
	<?php echo LIMETICKET_Helper::PageTitle('SUPPORT_TICKET',$this->ticket->title); ?>
<?php endif; ?>

<?php if ($custom_print): ?>

	<?php $file = Support_Print::outputPrint($template, $custom_print); ?>
	<?php if ($file) include $file; ?>

<?php else: ?>
	
	<?php if (LIMETICKET_Settings::get("messages_at_top") == 2 || LIMETICKET_Settings::get("messages_at_top") == 3)
		include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_messages_cont.php'); ?>

	<?php echo LIMETICKET_Helper::PageSubTitle("TICKET_DETAILS"); ?>

	<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_ticket_info.php'); ?>

	<?php if (LIMETICKET_Settings::get("messages_at_top") == 0 || LIMETICKET_Settings::get("messages_at_top") == 1)
		include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_messages_cont.php'); ?>
	
<?php endif; ?>

<?php if (!$custom_print || (int)($custom_print->noheader) != 1): ?>
	<?php include JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'_powered.php'; ?>
	<?php echo LIMETICKET_Helper::PageStyleEnd(); ?>
<?php endif; ?>

<script>
jQuery(document).ready( function () {
	window.print();
});
</script>