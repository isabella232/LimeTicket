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
<?php echo LIMETICKET_Helper::PageTitle("NO_PERM"); ?>

<p class="alert alert-warning">
	<?php echo JText::_("YOU_DO_NOT_HAVE_PERMISSION_TO_DO_THIS"); ?>
</p>

<?php $user = JFactory::getUser(); if ($user->id == 0): ?>
	<?php /*SNIP*/ include(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_login_form.php'); ?>
<?php endif; ?>

<?php include JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'_powered.php'; ?>
<?php echo LIMETICKET_Helper::PageStyleEnd(); ?>
