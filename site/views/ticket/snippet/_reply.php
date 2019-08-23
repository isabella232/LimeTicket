<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>

<?php if ((!$st->is_closed || LIMETICKET_Settings::get('support_user_can_reopen')) && !$this->ticket->readonly && !LIMETICKET_Settings::get('support_user_show_reply_always')): ?>
	<p>
		<a class="btn btn-default ticketrefresh pull-right" href='<?php echo LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=ticket&task=view.refresh&ticketid=' . $this->ticket->id ); ?>'>
			<i class="icon-refresh"></i> <?php echo JText::_("REFRESH"); ?>
		</a>

		<a class="btn btn-primary post_reply" href='<?php echo LIMETICKETRoute::_( '&option=com_limeticket&view=ticket&layout=reply&ticketid=' . $this->ticket->id ); ?>'>
			<i class="icon-redo"></i> <?php echo JText::_("POST_REPLY_LINK"); ?>
		</a>

		<?php echo LIMETICKET_GUIPlugins::output("userTicketReplyBar2", array('ticket'=> $this->ticket)); ?>
	</p>
<?php endif; ?>

<div id="messagereply" style="<?php if (!LIMETICKET_Settings::get('support_user_show_reply_always')) echo ' display: none;'; ?>">

	<div class="clearfix"></div>

	<form id='inlinereply' target="form_results" action="<?php echo LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=ticket&task=reply.post', false ); ?>" method="post" enctype="multipart/form-data" class="form-horizontal form-condensed">
	
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
			<textarea name='body' id='body' class='sceditor_hidden' rows='<?php echo (int)LIMETICKET_Settings::get('support_user_reply_height'); ?>' cols='<?php echo (int)LIMETICKET_Settings::get('support_user_reply_width'); ?>' style='width:97%;height:<?php echo (int)((LIMETICKET_Settings::get('support_user_reply_height') * 15) + 80); ?>px'></textarea>
		</div>

		<?php if (LIMETICKET_Settings::get('support_user_attach')): ?>
		<div class="control-group">
			<label class="control-label"><?php echo JText::sprintf('UPLOAD_FILE',LIMETICKET_Helper::display_filesize(LIMETICKET_Helper::getMaximumFileUploadSize())); ?></label>
			<div class="controls">
						<?php include JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'tmpl'.DS.'attach.php'; ?>
			</div>
		</div>
		<?php endif; ?>
	
		<?php if (LIMETICKET_Settings::get('support_user_show_reply_always')) :?>
			<a class="btn btn-default ticketrefresh pull-right" href='<?php echo LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=ticket&task=view.refresh&ticketid=' . $this->ticket->id ); ?>'>
				<i class="icon-refresh"></i> <?php echo JText::_("REFRESH"); ?>
			</a>
		<?php endif; ?>
	
		<input type="hidden" name="ticketid" value="<?php echo $this->ticket->id; ?>" />
		<button class="btn btn-primary" id='addcomment'><i class="icon-redo"></i> <?php echo JText::_("POST_REPLY"); ?></button>
		<?php if (LIMETICKET_Settings::get('support_user_can_close') && LIMETICKET_Settings::get('support_user_show_close_reply')): ?>
			<input type="hidden" id="should_close" name="should_close" value="" />
			<button class="btn btn-default" id='replyclose'><i class="icon-ban-circle"></i> <?php echo JText::_("REPLY_AND_CLOSE"); ?></button>
		<?php endif; ?>
		<?php if (!LIMETICKET_Settings::get('support_user_show_reply_always')) :?>
			<button class="btn btn-default" id='replycancel'><i class="icon-cancel"></i> <?php echo JText::_("CANCEL"); ?></button>
		<?php endif; ?>
	</form>
	<iframe name="form_results" id="form_results" style="display: none;"></iframe>

</div>

