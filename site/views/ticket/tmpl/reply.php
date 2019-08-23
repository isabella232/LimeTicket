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

<?php echo LIMETICKET_Helper::PageTitle("SUPPORT","POST_REPLY"); ?>

<form id='post_reply' action="<?php echo LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=ticket&task=reply.fullpost' ); ?>" method="post"  enctype="multipart/form-data" class="form-horizontal form-condensed">
	
	<input name="ticketid" type="hidden" value="<?php echo $this->ticket->id; ?>" />	
	<div class="control-group <?php echo $this->errors['subject'] ? 'error' : ''; ?>">
		<label class="control-label"><?php echo JText::_("SUBJECT"); ?></label>
		<div class="controls">
			<input type="text" class="input-xlarge" name="subject" id="subject" size="35" value="Re: <?php echo LIMETICKET_Helper::encode($this->ticket->title) ?>" required="">
			<span class="help-inline"><?php echo $this->errors['subject']; ?></span>
		</div>
	</div>
	
	<?php if ($this->errors['body']): ?>
		<div class="control-group error">
			<span class='help-inline' id='error_subject'><?php echo $this->errors['body']; ?></span>
		</div>
	<?php endif; ?>

	<div class="control-group <?php echo $this->errors['body'] ? 'error' : ''; ?>">
		<textarea name='body' id='body' class='sceditor' rows='<?php echo LIMETICKET_Settings::get('support_user_reply_height'); ?>' cols='<?php echo LIMETICKET_Settings::get('support_user_reply_width'); ?>' style='width:97%;height:<?php echo (LIMETICKET_Settings::get('support_user_reply_height') * 15) + 80; ?>px'></textarea>
	</div>

		<?php if (LIMETICKET_Settings::get('support_user_attach')): ?>
	<div class="control-group">
		<label class="control-label"><?php echo JText::sprintf('UPLOAD_FILE',LIMETICKET_Helper::display_filesize(LIMETICKET_Helper::getMaximumFileUploadSize())); ?></label>
		<div class="controls">
			<?php include JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'tmpl'.DS.'attach.php'; ?>
		</div>
	</div>
	<?php endif; ?>
	
<div class='limeticket_ticket_foot'></div>

<a class='btn btn-primary' type='submit' href="#" onclick="jQuery('#post_reply').submit(); return false;" id='addcomment'><?php echo JText::_("POST_MESSAGE"); ?></a>
<?php if (LIMETICKET_Settings::get('support_user_can_close') && LIMETICKET_Settings::get('support_user_show_close_reply')): ?>
	<input type="hidden" id="should_close" name="should_close" value="" />
	<a class="btn btn-default" id='replyclose' href="#" onclick='jQuery("#should_close").val("1");jQuery("#post_reply").submit(); return false;'><i class="icon-ban-circle"></i> <?php echo JText::_("REPLY_AND_CLOSE"); ?></a>
<?php endif; ?>
<a class="btn btn-default" href="<?php echo LIMETICKETRoute::_("index.php?option=com_limeticket&view=ticket&layout=view&ticketid=" . $this->ticket->id, false ); ?>"><?php echo JText::_('JCANCEL'); ?></a>
</form>
<div style="height:10px;">&nbsp;</div>

<div id="ticket_messages">
<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_messages.php'); ?>
</div>

<?php if (count($this->attach) > 0) : ?>

<?php echo LIMETICKET_Helper::PageSubTitle("ATTACHEMNTS"); ?>

<table class='limeticket_ticket_attach' width='100%' cellspacing=0 cellpadding=4>

<?php foreach ($this->attach as $attach) : ?>

	<tr>
		<td class='limeticket_ticket_attach_file' valign="middle" width=26>
			<a href='<?php echo LIMETICKETRoute::_( '&fileid=' . $attach['id'] );// FIX LINK ?>'><img src='<?php echo JURI::root( true ); ?>/components/com_limeticket/assets/images/download-24x24.png'></a>
		</td>
		<td class='limeticket_ticket_attach_filename' valign="middle" width=60%>
			<a href='<?php echo LIMETICKETRoute::_( '&fileid=' . $attach['id'] );// FIX LINK ?>'><?php echo $attach['filename']; ?></a>
		</td>
		<td class='limeticket_ticket_attach_size' align=right valign="middle">	
			<?php echo LIMETICKET_Helper::display_filesize($attach['size']); ?>
		</td>
	</tr>
	<tr>
		<td colspan=2 class='limeticket_ticket_attach_user' width='60%'>
			<?php echo JText::_('UPLOADED_BY'); ?> <?php echo $attach['name']; ?>
		</td>
		<td class='limeticket_ticket_attach_date' width='40%' align=right>	
			<?php echo LIMETICKET_Helper::Date($attach['added'], LIMETICKET_DATETIME_MID); ?>
		</td>
	</tr>

<?php endforeach; ?>
</table>
<?php endif; ?>

<?php include "components/com_limeticket/_powered.php" ?>

<?php echo LIMETICKET_Helper::PageStyleEnd(); ?>