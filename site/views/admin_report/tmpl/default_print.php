<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>

<?php echo LIMETICKET_Helper::PageStyle(); ?>
<?php if (LIMETICKET_Input::getCmd('type') != "bare"): ?>
	<?php echo LIMETICKET_Helper::PageTitle("Reports", $this->report->title); ?>
<?php endif; ?>

<?php if (LIMETICKET_Input::getCmd('type') == ""): ?>
	<div class="well well-small form-horizontal form-condensed">
		<?php echo $this->report->listFilterValues(); ?>
	</div>
<?php endif; ?>

<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'admin_report'.DS.'snippet'.DS.'_report_table.php'); ?>

<?php echo LIMETICKET_Helper::PageStyleEnd(); ?>


<script>
jQuery(document).ready( function () {
	window.print();
});
</script>