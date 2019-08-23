<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

if (empty($this->print)) $this->print = "";
if (LIMETICKET_Settings::get('glossary_support')) require_once(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'glossary.php');

?>
<table class='limeticket_ticket_messages table table-bordered table-ticketborders table-condensed' style="table-layout: fixed">

<?php $first = true; ?>

<?php foreach ($this->ticket->messages as $message) : ?>
	<?php if ($message->admin == TICKET_MESSAGE_OPENEDBY): ?>
		<tr class="limeticket_support_msg_audit ticket_message_opened info first">
			<td style="overflow-x: auto">
				<div class="ticket_message_message" id="message_<?php echo (int)$message->id; ?>">
					<?php echo JText::_('TICKET_OPENED'); ?>
					<b>
						<?php $muser = JFactory::getUser($message->user_id); ?>
						<?php echo $message->name; ?> (<?php echo $muser->username; ?>)
					</b>
				</div>
			</td>
		</tr>
	<?php elseif ($message->admin == TICKET_MESSAGE_AUDIT) : ?>
		<tr class="limeticket_support_msg_audit ticket_message_audit success first" style="display:none;">
			<td style="overflow-x: auto">
				<div class="pull-left" style="margin-right: 8px;">
					<b>
						<?php if ($message->admin == 0 && $this->ticket->unregname != "") : ?>
							<?php echo $this->ticket->unregname; ?>
						<?php else: ?>
							<?php echo $message->name; ?>
						<?php endif; ?>
					</b>
				</div>
				
				<div class="pull-right" style="margin-left: 8px;">
					<i>
						<?php echo LIMETICKET_Helper::TicketTime($message->posted, LIMETICKET_DATETIME_MID); ?>
					</i>
				</div>
				
				<div class="ticket_message_message" id="message_<?php echo (int)$message->id; ?>">
					<?php 
						$msg = $message->body;
						$msg = LIMETICKET_Helper::ParseBBCode($msg, $message);
						echo $msg;
					?>
				</div>
			</td>
		</tr>
	<?php elseif ($message->admin == TICKET_MESSAGE_DRAFT) : ?>
		<tr class="info ticket_message_draft first">
			<td style="overflow-x: auto">
				<div class="pull-right" style="margin-left: 8px;">
					<a href="<?php echo LIMETICKETRoute::_('index.php?option=com_limeticket&view=admin_support&layout=reply&ticketid=' . LIMETICKET_Input::getInt('ticketid') . "&draft=" . $message->id); ?>" 
						class="btn btn-info btn-mini limeticketTip" title="<?php echo JText::_('POST_REPLY_WITH_DRAFT'); ?>"><i class="icon-redo"></i></a>
					<a href="<?php echo LIMETICKETRoute::_('index.php?option=com_limeticket&view=admin_support&layout=reply&ticketid=' . LIMETICKET_Input::getInt('ticketid') . "&what=removedraft&draft=" . $message->id); ?>"
						class="btn btn-default btn-mini limeticketTip" title="<?php echo JText::_('REMOVE_DRAFT'); ?>"><i class="icon-delete"></i></a>
				</div>
								
				<div class="pull-right" style="margin-left: 8px;">
					<span class='label label-inverted'><?php echo JText::_('DRAFT_REPLY'); ?></span>
				</div>
				
				<strong id='subject_<?php echo $message->id; ?>'><?php echo $message->subject; ?></strong>
				
				<div class="ticket_message_message" id="message_<?php echo (int)$message->id; ?>">
					<?php 
						$msg = $message->body;
						$msg = LIMETICKET_Helper::ParseBBCode($msg, $message);
						echo $msg;
					?>
				</div>
			</td>
		</tr>
	<?php elseif ($message->admin == TICKET_MESSAGE_TIME) : ?>
		<?php if ($message->time == 0) continue; ?>		
		<tr class="<?php if (!$first) echo 'first'; ?> warning ticket_message_time ticket_message_id_<?php echo (int)$message->id; ?>">
			<td style="overflow-x: auto">
				<?php if ($this->print == "" && $this->can_EditTicket() && $this->can_ChangeTicket()): ?>
					<a class="pull-right editmessage timeonly" id="edit_<?php echo (int)$message->id; ?>" href="#" style="margin-left:4px;">
						<i class="icon-edit limeticketTip" title="<?php echo JText::_('EDIT_MESSAGE'); ?>"></i>
					</a>
				<?php endif; ?>
				<div class="pull-right ticket_message_poster">
					<?php SupportHelper::TimeTaken($message); ?>
					<span class="label label-info">
						<?php echo $message->name ? $message->name : $this->ticket->unregname; ?>
					</span>
				</div>

				<div class="ticket_message_message" id="message_<?php echo (int)$message->id; ?>">
					<?php 
						$msg = $message->body;
						$msg = LIMETICKET_Helper::ParseBBCode($msg, $message);
						echo $msg;
					?>
				</div>
			</td>
		</tr>
		<?php $first = false; ?>
	<?php else: ?>		
		<?php if ($this->print == "clean" && $message->admin == TICKET_MESSAGE_PRIVATE) continue; ?>
		<tr class="<?php if (!$first) echo 'first'; ?> <?php if ($message->admin == TICKET_MESSAGE_PRIVATE) echo "warning"; ?> ticket_message_<?php echo (int)$message->admin; ?> ticket_message_id_<?php echo (int)$message->id; ?> ticket_message_header">
			<td style="overflow-x: auto">
				<?php if ($this->print == "" && $this->can_EditTicket() && $this->can_ChangeTicket()): ?>
					<a class="pull-right editmessage" id="edit_<?php echo (int)$message->id; ?>" href="#" style="margin-left:4px;">
						<i class="icon-edit limeticketTip" title="<?php echo JText::_('EDIT_MESSAGE'); ?>"></i>
						<!--<img src="<?php echo JURI::root( true ); ?>/components/com_limeticket/assets/images/edit.png" alt="Edit"/>-->
					</a>
				<?php endif; ?>
				<div class="pull-right ticket_message_poster">

					<?php if ($message->source == "email"): ?>
						<i class="icon-mail"></i>
					<?php endif; ?>

					<?php SupportHelper::TimeTaken($message); ?>

					<?php if ($message->admin == TICKET_MESSAGE_ADMIN) : ?>
						<?php LIMETICKET_Helper::$message_labels[$message->id] = "success"; ?>
						<span class="label label-success">
					<?php elseif ($message->admin == TICKET_MESSAGE_PRIVATE) : ?>
						<?php LIMETICKET_Helper::$message_labels[$message->id] = "info"; ?>
						<span class="label label-info">
					<?php else: ?>
						<span class="label label-warning">
						<?php LIMETICKET_Helper::$message_labels[$message->id] = "warning"; ?>
					<?php endif; ?>	
			
					<?php if ($message->name): ?>
						<?php echo $message->name; ?>
					<?php elseif ($message->poster): ?>
						<?php echo $message->poster; ?>
					<?php elseif ($message->email): ?>
						<?php echo $message->email; ?>
					<?php else: ?>
						<?php echo $this->ticket->unregname; ?>
					<?php endif; ?>
					
					</span>
				</div>
				<!--<img src='<?php echo JURI::root( true ); ?>/components/com_limeticket/assets/images/message.png'>-->
				<strong class="ticket_message_subject" id='subject_<?php echo (int)$message->id; ?>'><?php echo $message->subject; ?></strong>
			</td>
		</tr>

		<tr class="ticket_message_body <?php if ($message->admin == TICKET_MESSAGE_PRIVATE) echo "warning"; ?> ticket_message_id_<?php echo (int)$message->id; ?>">
			<td style="overflow-x: auto">

				<div class="ticket_message_date pull-right" style="margin-bottom: 8px;margin-left: 8px;">
					<?php if ($message->admin == 1 && LIMETICKET_Settings::get('ratings_per_message') && $message->rating > 0) echo SupportHelper::messageRating($message, false, false); ?>
					<i>
						<?php echo LIMETICKET_Helper::TicketTime($message->posted, LIMETICKET_DATETIME_MID); ?>
					</i>
				</div>

				<div class="ticket_message_message" id="message_<?php echo (int)$message->id; ?>">
					<?php 

						if (strpos($message->body, "[cid:") !== false)
						{
							require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'cron'.DS.'emailcheck.php');
							$ec = new LIMETICKETCronEMailCheck();
							$message->body = $ec->processInlineImages($message->id);
						}

						$msg = $message->body;
						$msg = LIMETICKET_Helper::ParseBBCode($msg, $message);

						if (LIMETICKET_Settings::get('glossary_support'))
						{
							echo LIMETICKET_Glossary::ReplaceGlossary($msg);
						} else {
							echo $msg;
						}
					?>
				</div>

				<?php if (isset($message->attach)) : ?>
					<?php foreach ($message->attach as &$attach): ?>
						<?php if ($attach->inline) continue; ?>
						<div class="padding-mini">
							<?php if ($this->print && empty($this->replying)): ?>
								<i class="icon-download"></i>
								<?php echo $attach->filename; ?>
							<?php else: ?>
								<a href='<?php echo LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=admin_support&task=attach.download&ticketid=' . $this->ticket->id . '&fileid=' . $attach->id ); ?>'>
									<i class="icon-download"></i>
									<?php echo $attach->filename; ?>
								</a>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>

				<div id="message_raw_<?php echo (int)$message->id; ?>" style='display:none'><?php echo LIMETICKET_Helper::escape($message->body); ?></div>
				
			</td>
		</tr>

		<?php $first = false; ?>		
	<?php endif; ?>
<?php endforeach; ?>
</table>

<?php if (LIMETICKET_Settings::get('glossary_support')) echo LIMETICKET_Glossary::Footer(); ?>
