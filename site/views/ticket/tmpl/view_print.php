<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

$template = LIMETICKET_Input::GetString("print");
$custom_print = Support_Print::loadPrint($template);

defined('_JEXEC') or die;
?>
<?php if (!$custom_print || (int)($custom_print->noheader) != 1): ?>
	<?php echo LIMETICKET_Helper::PageStyle(); ?>
	<?php echo LIMETICKET_Helper::PageSubTitle("TICKET_DETAILS"); ?>
<?php endif; ?>

<?php $this->print = true; ?>

<?php if ($custom_print): ?>

	<?php $file = Support_Print::outputPrint($template, $custom_print); ?>
	<?php include $file; ?>

<?php else: ?>
	
	<div class="limeticket_main">
	<table class='table table-borderless table-condensed table-narrow' style="min-width:300px" >

	<?php if (!LIMETICKET_Settings::get('user_hide_title')): ?>
		<tr>
			<th><?php echo JText::_("TITLE"); ?></th>
			<td><?php echo $this->ticket->title; ?></td>
		</tr>
	<?php endif; ?>

	<?php if (!LIMETICKET_Settings::get('user_hide_id')): ?>
		<tr>
			<th><?php echo JText::_("TICKET_ID"); ?></th>
			<td><?php echo $this->ticket->reference; ?></td>
		</tr>
	<?php endif; ?>

	<?php if (JFactory::getUser()->id != $this->ticket->user_id && !LIMETICKET_Settings::get('user_hide_user')) : ?>
		<tr>
			<th><?php echo JText::_("USER"); ?></th>
			<td><?php echo $this->ticket->name; ?></td>
		</tr>
	<?php endif; ?>

	<?php if (SupportHelper::userIdMultiUser($this->ticket->user_id) && !LIMETICKET_Settings::get('user_hide_cc')) : ?>
		<tr>
			<th><?php echo JText::_("CC_USERS"); ?></th>
			<td>
				<div id="ccusers">
					<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_ccusers.php'); ?>
				</div>
			</td>
		</tr>
	<?php endif; ?>

	<?php if ($this->ticket->password): ?>
		<tr>
			<th><?php echo JText::_("PASSWORD"); ?></th>
			<td><?php echo $this->ticket->password; ?></td>
		</tr>
	<?php endif; ?>

	<?php if ($this->ticket->product && !LIMETICKET_Settings::get('user_hide_product')): ?>
		<tr>
			<th><?php echo JText::_("PRODUCT"); ?></th>
			<td><?php echo $this->ticket->product; ?></td>
		</tr>
	<?php endif; ?>

	<?php if ($this->ticket->department && !LIMETICKET_Settings::get('user_hide_department')): ?>
		<tr>
			<th><?php echo JText::_("DEPARTMENT"); ?></th>
			<td><?php echo $this->ticket->department; ?></td>
		</tr>
	<?php endif; ?>

	<?php if ($this->ticket->category && !LIMETICKET_Settings::get('support_hide_category') && !LIMETICKET_Settings::get('user_hide_category')): ?>
		<tr>
			<th><?php echo JText::_("CATEGORY"); ?></th>
			<td><?php echo $this->ticket->category; ?></td>
		</tr>
	<?php endif; ?>

	<?php if (!LIMETICKET_Settings::get('user_hide_updated')): ?>
		<tr>
			<th><?php echo JText::_("LAST_UPDATE"); ?></th>
			<td><?php echo LIMETICKET_Helper::Date($this->ticket->lastupdate, LIMETICKET_DATETIME_MID); ?></td>
		</tr>
	<?php endif; ?>

	<?php $st = LIMETICKET_Ticket_Helper::GetStatusByID($this->ticket->ticket_status_id); ?>

	<?php if ($st->is_closed && strtotime($this->ticket->closed) > 0) : ?>
		<tr>
			<th><?php echo JText::_("CLOSED"); ?></th>
			<td><?php echo LIMETICKET_Helper::Date($this->ticket->closed, LIMETICKET_DATETIME_MID); ?></td>
		</tr>
	<?php endif; ?>

	<?php if (!LIMETICKET_Settings::get('support_hide_handler') && !LIMETICKET_Settings::get('user_hide_handler')) : ?>
		<tr>
			<th><?php echo JText::_("HANDLER"); ?></th>
			<td><?php if ($this->ticket->assigned) {echo $this->ticket->assigned;} else {echo JText::_("UNASSIGNED");} ?></td>
		</tr>
	<?php endif; ?>

	<?php if (!LIMETICKET_Settings::get('user_hide_custom')): ?>
		<?php foreach ($this->ticket->customfields as $field): ?>
			<?php if ($field['grouping'] != "") continue; ?>
			<?php if ($field['permissions'] > 1 && $field['permissions'] != 5) continue; ?>

			<tr>
				<th width='<?php echo LIMETICKET_Settings::get('ticket_label_width'); ?>'><?php echo LIMETICKETCF::FieldHeader($field, false, false); ?></th>
				<td>
					<?php echo LIMETICKETCF::FieldOutput($field, $this->ticket->custom, array('ticketid' => $this->ticket->id, 'userid' => $this->ticket->user_id, 'ticket' => $this->ticket)); ?>
				</td>
			</tr>	
		<?php endforeach; ?>
	<?php endif; ?>

	<?php if (!LIMETICKET_Settings::get('user_hide_status')): ?>
		<tr>
			<th style="vertical-align: middle"><?php echo JText::_("STATUS"); ?></th>
	
			<td>
				<span style='color: <?php echo $this->ticket->scolor; ?>'><?php echo $this->ticket->status; ?></span>
			</td>
		</tr>
	<?php endif; ?>

	<?php if (!LIMETICKET_Settings::get('support_hide_priority') && !LIMETICKET_Settings::get('user_hide_priority')) : ?>
		<tr>
			<th style="vertical-align: middle"><?php echo JText::_("PRIORITY"); ?></th>
			<td>
				<span style='color:<?php echo $this->ticket->pricolor; ?>'><?php echo $this->ticket->priority; ?></span>
			</td>
		</tr>
	<?php endif; ?>

	</table>

	<?php if (!LIMETICKET_Settings::get('user_hide_custom')) : ?>
		<?php $grouping = ""; $open = false; ?>

		<?php foreach ($this->ticket->customfields as $field) : ?>

			<?php if ($field['grouping'] == "")	continue; ?>
			<?php if ($field['permissions'] > 1 && $field['permissions'] != 5) continue; ?>
		
			<?php if ($field['grouping'] != $grouping): ?>
				<?php if ($open) echo "</table>";	?>
				<?php echo LIMETICKET_Helper::PageSubTitle($field['grouping']); ?>
				<table class='table table-borderless table-condensed table-narrow' style="min-width:300px">
				<?php $open = true;	$grouping = $field['grouping']; ?>
			<?php endif; ?>
	
			<tr>
				<th width='<?php echo LIMETICKET_Settings::get('ticket_label_width'); ?>'><?php echo LIMETICKETCF::FieldHeader($field, false, false); ?></th>
				<td>
					<?php echo LIMETICKETCF::FieldOutput($field,$this->ticket->custom, array('ticketid' => $this->ticket->id, 'userid' => $this->ticket->user_id, 'ticket' => $this->ticket)); ?>
				</td>
			</tr>	
	
		<?php endforeach; ?>

		<?php if ($open) echo "</table>"; ?>
	<?php endif; ?>

	<?php echo LIMETICKET_Helper::PageSubTitle("MESSAGES"); ?>

	<div id="ticket_messages">
		<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_messages.php'); ?>
	</div>
	
	<?php if (count($this->ticket->attach) > 0) : ?>
		<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_ticket_attach.php'); ?>
	<?php endif; ?>

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

</div>