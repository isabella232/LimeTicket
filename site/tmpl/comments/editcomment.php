<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<?php if ($this->can_add): ?>
<div class="limeticket_edit_comment"><?php echo JText::_('EDIT_COMMENT'); ?></div>

	<form id='editcommentform' action="<?php echo LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=comments&tmpl=component' );?>" method="post" class="form-horizontal form-condensed">
		<input type='hidden' name='comment' value='add' >
		<input type='hidden' name='ident' value='<?php echo  $this->ident ?>' >
		<input type='hidden' name='task' value='savecomment' >
		<input type='hidden' name='identifier' value='<?php echo $this->identifier ?>' >
		
		<?php if ($this->itemid): ?>
			<input type='hidden' name='itemid' value='<?php echo  $this->itemid ?>' >
		<?php endif; ?>
		
		<?php if ($this->commentid > 0): ?>
			<input type='hidden' name='commentid' value='<?php echo  $this->commentid ?>' >
		<?php endif; ?>
		
		<?php if ($this->show_item_select && $this->handler) : ?>
			<div class="control-group <?php echo $this->errors['itemid'] ? 'error' : ''; ?>">
				<label class="control-label"><?php echo $this->handler->email_article_type; ?></label>
				<div class="controls">
					<?php echo $this->GetItemSelect(); ?>
					<span class="help-inline"><?php echo $this->errors['itemid']; ?></span>
				</div>
			</div>
		<?php endif; ?>
		<div class="control-group <?php echo $this->errors['name'] ? 'error' : ''; ?>">
			<label class="control-label"><?php echo JText::_('Name'); ?></label>
			<div class="controls">
				<?php if (!LIMETICKET_Permission::CanModerate() && $this->loggedin) : ?>
					<input name='name' type='hidden' id='comment_name' value='<?php echo LIMETICKET_Helper::escape($this->comment['name']) ?>' placeholder="<?php echo JText::_('Name'); ?>" required>
				<?php else: ?>
					<input type="text" name='name' id='comment_name' value='<?php echo LIMETICKET_Helper::escape($this->comment['name']) ?>'  placeholder="<?php echo JText::_('Name'); ?>" required>
				<?php endif; ?>
				<span class="help-inline"><?php echo $this->errors['name']; ?></span>
			</div>
		</div>
	
		<?php if ($this->use_email): ?>
			<div class="control-group <?php echo $this->errors['email'] ? 'error' : ''; ?>">
				<label class="control-label"><?php echo JText::_('EMail'); ?></label>
				<div class="controls">
					<input type="text" name='email' value='<?php echo LIMETICKET_Helper::escape($this->comment['email']) ?>' placeholder="<?php echo JText::_('EMail'); ?>">
					<span class="help-inline"><?php echo $this->errors['email']; ?></span>
				</div>
			</div>
		<?php endif; ?>
	
		<?php if ($this->use_website): ?>
			<div class="control-group <?php echo $this->errors['website'] ? 'error' : ''; ?>">
				<label class="control-label"><?php echo JText::_('website'); ?></label>
				<div class="controls">
					<input type="text" name='website' value='<?php echo LIMETICKET_Helper::escape($this->comment['website']) ?>' placeholder="<?php echo JText::_('website'); ?>">
					<span class="help-inline"><?php echo $this->errors['website']; ?></span>
				</div>
			</div>
		<?php endif; ?>

		<?php foreach ($this->customfields as $custfield): ?>
			<div class="control-group <?php if (LIMETICKETCF::HasErrors($custfield, $this->errors)) echo "error"; ?>">
				<label class="control-label"><?php echo LIMETICKETCF::FieldHeader($custfield,true, false); ?></label>
				<div class="controls">
					<?php echo LIMETICKETCF::FieldInput($custfield,$this->errors,'comments'); ?>
				</div>
			</div>
		<?php endforeach; ?>

		<div class="control-group <?php echo $this->errors['body'] ? 'error' : ''; ?>">
			<label class="control-label"><?php echo JText::_('COMMENT_BODY'); ?></label>
			<div class="controls">
				<textarea name='body' rows='5' cols='60' id='comment_body' style="height:150px;width:400px" required><?php echo LIMETICKET_Helper::escape($this->comment['body']) ?></textarea>
				<span class="help-inline"><?php echo $this->errors['body']; ?></span>
			</div>
		</div>
				
		<div class="control-group <?php echo $this->errors['captcha'] ? 'error' : ''; ?>">
			<label class="control-label"></label>
			<div class="controls">
				<input class='btn btn-primary' type=submit value='<?php echo JText::_('SAVE_COMMENT'); ?>' id='addcomment'>
				<input class='btn btn-default' type=submit value='<?php echo JText::_('CANCEL'); ?>' id='canceledit' uid='<?php echo $this->uid; ?>' commentid='<?php echo $this->commentid; ?>'>
			</div>
		</div>		

	</form>
<?php endif; ?>