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

<?php echo LIMETICKET_Helper::PageTitle("SUPPORT",$this->no_permission_title); ?>

<?php if (isset($this->no_permission_header)): ?>
	<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_openheader.php'); ?>
<?php endif; ?>

<p class="alert alert-warning">
	<?php echo JText::_($this->no_permission_message); ?>
</p>

<?php include JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'_powered.php'; ?>
<?php echo LIMETICKET_Helper::PageStyleEnd(); ?>