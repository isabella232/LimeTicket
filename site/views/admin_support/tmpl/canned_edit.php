<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<div class="limeticket_main">
	<form id="mainform" action="<?php echo JRoute::_("index.php"); ?>" method="post" class="form-horizontal form-condensed">

		<?php echo LIMETICKET_Helper::PageStylePopup(true); ?>
		<?php echo LIMETICKET_Helper::PageTitlePopup('SUPPORT_ADMIN',$this->canned_item->id > 0 ? "EDIT_CANNED_REPLY" : "NEW_CANNED_REPLY"); ?>

			<input type="hidden" name="option" value="com_limeticket" />
			<input type="hidden" name="view" value="admin_support" />
			<input type="hidden" name="layout" value="canned" />
			<input type="hidden" name="tmpl" value="component" />
			<input type="hidden" name="saveid" value="<?php echo LIMETICKET_Helper::escape($this->canned_item->id); ?>" />
		
			<div class="control-group">
				<label class="control-label"><?php echo JText::_('DESCRIPTION'); ?></label>
				<div class="controls">
					<input type="text" name="description" value="<?php echo LIMETICKET_Helper::escape($this->canned_item->description); ?>" />
				</div>
			</div>
			<div class="control-group">
				<label class="control-label"><?php echo JText::_('GROUPING'); ?></label>
				<div class="controls">
					<input type="text" name="grouping" value="<?php echo LIMETICKET_Helper::escape($this->canned_item->grouping); ?>" />
				</div>
			</div>
			<p>
				<textarea style='width:97%; height: 270px;' name='content' id='content' class="sceditor" rows='7' cols='40'><?php echo LIMETICKET_Helper::escape($this->canned_item->content); ?></textarea>
			</p>

		</div>

		<div class="modal-footer">
			<button class="btn btn-primary" onclick="jQuery('#mainform').submit(); return false;"><?php echo JText::_('SAVE'); ?></button>
			<a class="btn btn-default" href="<?php echo LIMETICKETRoute::_("index.php?option=com_limeticket&view=admin_support&tmpl=component&layout=canned" ); ?>"><?php echo JText::_('CANCEL'); ?></a>
		</div>
	</form>
</div>