<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
	<form action="<?php echo LIMETICKETRoute::_("index.php?option=com_limeticket&view=ticket&layout=field&tmpl=component&ticketid=" . LIMETICKET_Input::getInt('ticketid')); ?>" method="post" class="form-horizontal form-condensed" id="limeticketForm">

	<?php echo LIMETICKET_Helper::PageStylePopup(true); ?>
	<?php echo LIMETICKET_Helper::PageTitlePopup("SUPPORT","EDIT_TICKET"); ?>

	<?php $input = LIMETICKETCF::FieldInput($this->field, $this->errors, "ticket", array('ticketid' => LIMETICKET_Input::getInt('ticketid',0), 'userid' => $this->ticket->user_id, 'ticket' => $this->ticket)); ?>

	<div class="control-group" style="min-height:<?php echo $this->field['min_popup_height']; ?>px;">
		<label class="control-label">
		
			<?php echo LIMETICKETCF::FieldHeader($this->field, false, false); ?>
		</label>
		<div class="controls">
			<?php echo $input ?>
		</div>
	</div>

		<input type="hidden" name="savefield" value="<?php echo $this->field['id']; ?>">
		<input type="hidden" name="fieldid" value="<?php echo $this->field['id']; ?>">

	</div>

	<div class="modal-footer">
		<a class="btn btn-primary" href='#' onclick="jQuery('#limeticketForm').submit();return false;"><?php echo JText::_("SAVE"); ?></a>
		<a class='btn btn-default' onclick='parent.limeticket_modal_hide(); return false;'><?php echo JText::_("CANCEL"); ?></a>
	</div>
	
</form>
