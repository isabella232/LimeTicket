<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>

<?php echo LIMETICKET_Helper::PageStylePopup(true); ?>
<?php echo LIMETICKET_Helper::PageTitlePopup('SUPPORT_ADMIN',"EMAIL_TICKET_PREVIEW"); ?>

<?php 

$this->email_preview = true;
global $curcol;
global $cols;
$cols = 2;
$curcol = 1;

function limeticket_start_col()
{
	global $curcol;
	if ($curcol == 1)
	{
		echo "<tr>";		
	} else {
		echo "";
	}
	$curcol++;
}

function limeticket_end_col()
{
	global $curcol;
	global $cols;
	if ($curcol > $cols)
	{
		echo "</tr>";	
		$curcol = 1;
	} else {
		
	}
}

?>

<table class='table table-borderless table-condensed table-narrow' style='width:100% !important'>

<?php limeticket_start_col(); ?>
	<th width="100"><?php echo JText::_("SUBJECT"); ?></th>
	<td width="270">
		<?php echo $this->ticket->title; ?>
	</td>
<?php limeticket_end_col(); ?>

<?php if ($this->ticket->product): ?>
<?php limeticket_start_col(); ?>
	<th width="100"><?php echo JText::_("PRODUCT"); ?></th>
	<td width="270">
		<?php echo LIMETICKET_Translate_Helper::TrF('title', $this->ticket->product, $this->ticket->prtr); ?>
	</td>
<?php limeticket_end_col(); ?>
<?php endif; ?>

<?php if ($this->ticket->department): ?>
<?php limeticket_start_col(); ?>
	<th width="100"><?php echo JText::_("DEPARTMENT"); ?></th>
	<td width="270">
		<?php echo LIMETICKET_Translate_Helper::TrF('title', $this->ticket->department, $this->ticket->dtr); ?>
	</td>
<?php limeticket_end_col(); ?>
<?php endif; ?>


<?php if (LIMETICKET_Settings::get('support_hide_category') != 1): ?>
<?php limeticket_start_col(); ?>
	<th width="100"><?php echo JText::_("CATEGORY"); ?></th>
	<td width="270">
		<?php echo LIMETICKET_Translate_Helper::TrF('title', $this->ticket->category, $this->ticket->ctr); ?>
	</td>
<?php limeticket_end_col(); ?>
<?php endif; ?>


<?php limeticket_start_col(); ?>
	<th width="100"><?php echo JText::_("USER"); ?></th>
	<td width="270">
		<?php if ($this->ticket->user_id == 0): ?>
			<?php echo $this->ticket->unregname; ?> (<?php echo JText::_("UNREGISTERED"); ?>)
		<?php else: ?>
			<?php if (file_exists(JPATH_SITE.DS.'components'.DS.'com_community')): ?>
				<a href='<?php echo JRoute::_('index.php?option=com_community&view=profile&userid='. $this->ticket->user_id);?>'>
			<?php endif; ?>
			<?php echo $this->ticket->name; ?> (<?php echo $this->ticket->username; ?>)
			<?php if (file_exists(JPATH_SITE.DS.'components'.DS.'com_community')): ?></a><?php endif; ?>
		<?php endif; ?>
	</td>
<?php limeticket_end_col(); ?>

<?php if ($this->ticket->email): ?>
<?php limeticket_start_col(); ?>
	<th><?php echo JText::_("EMAIL"); ?></th>
	<td>
		<?php echo $this->ticket->email; ?>
	</td>

<?php limeticket_end_col(); ?>
<?php endif; ?>

<?php limeticket_start_col(); ?>
	<th><?php echo JText::_("LAST_UPDATE"); ?></th>
	<td>
		<?php echo LIMETICKET_Helper::Date($this->ticket->lastupdate, LIMETICKET_DATETIME_MID); ?>
	</td>
<?php limeticket_end_col(); ?>


<?php limeticket_start_col(); ?>
	<th style="vertical-align: middle"><?php echo JText::_("STATUS"); ?></th>
	<td>
		<span style='color: <?php echo $this->ticket->scolor; ?>'>
			<?php echo LIMETICKET_Translate_Helper::TrF('title', $this->ticket->status, $this->ticket->str); ?>
		</span>
	</td>
<?php limeticket_end_col(); ?>


<?php if (LIMETICKET_Settings::get('support_hide_priority') != 1) : ?>
<?php limeticket_start_col(); ?>
	<th style="vertical-align: middle"><?php echo JText::_("PRIORITY"); ?></th>
	<td>
		<span style='color:<?php echo $this->ticket->pcolor; ?>'>
			<?php echo LIMETICKET_Translate_Helper::TrF('title', $this->ticket->priority, $this->ticket->ptl); ?>
		</span>
	</td>
<?php limeticket_end_col(); ?>
<?php endif; ?>

</table>

<?php $this->print = true; ?>

<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_messages.php'); ?>
<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_attachments.php'); ?>
</div>

<div class="modal-footer">
	<a href="#" class="btn btn-success" onclick="parent.limeticket_modal_hide(); parent.email_accept(<?php echo $this->ticket->id; ?>); return false;"><?php echo JText::_('Accept'); ?></a>
	<a href="#" class="btn btn-danger" onclick="parent.limeticket_modal_hide(); parent.email_decline(<?php echo $this->ticket->id; ?>); return false;"><?php echo JText::_('Decline'); ?></a>
</div>
	