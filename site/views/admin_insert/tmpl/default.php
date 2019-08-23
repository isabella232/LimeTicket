<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<form id="limeticketForm" name="limeticketForm" action="<?php echo JRoute::_('index.php?option=com_limeticket&view=admin_insert&tmpl=component&type=' . LIMETICKET_Input::getCmd('type') . "&editor=" . LIMETICKET_Input::getCmd('editor')); ?>" method='post'>
<?php echo LIMETICKET_Helper::PageStylePopup(true); ?>
<?php echo LIMETICKET_Helper::PageTitlePopup(JText::_($this->addbtntext)); ?>

<?php $this->OutputTable(); ?>

</div>

<div class="modal-footer">
	<a href='#' class="btn btn-default" onclick='parent.limeticket_modal_hide(); return false;'><?php echo JText::_('CANCEL'); ?></a>
</div>
</form>