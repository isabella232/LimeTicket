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
<?php echo LIMETICKET_Helper::PageTitle("Reports"); ?>

<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'admin'.DS.'snippet'.DS.'_tabbar.php'); ?>

<?php $count = 0; ?>

<?php foreach ($this->reports as $report): ?>
	<?php if (!LIMETICKET_Permission::auth("limeticket.reports.report." . $report->name, "com_limeticket.reports") && 
		!LIMETICKET_Permission::auth("limeticket.reports.all", "com_limeticket.reports") ) continue; ?>
	<?php $count++; ?>
	<div class="well well-mini">
	<div class="pull-right margin-mini vert-center">
			<a class="btn btn-default" href='<?php echo JRoute::_('index.php?option=com_limeticket&view=admin_report&report=' . $report->name); ?>'>
				<?php echo JText::_('VIEW_REPORT'); ?>
			</a>
		</div>
		
		<h4 class="margin-mini"><?php echo JText::_($report->title); ?></h4>
		<p class="margin-mini"><?php echo JText::_($report->description); ?></p>
	</div>
<?php endforeach; ?>

<?php if ($count == 0): ?>
	<div class="alert">You do not have permission to view any reports</div>
<?php endif; ?>

<?php echo LIMETICKET_Helper::PageStyleEnd(); ?>