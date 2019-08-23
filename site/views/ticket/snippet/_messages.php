<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die;

if (LIMETICKET_Settings::get('glossary_support')) require_once(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'glossary.php');

?>

<?php if (empty($this->print)) $this->print = false; ?>

<table class='table table-bordered table-ticketborders table-condensed' style="table-layout: fixed">

<?php $first = true; $user = JFactory::getUser(); ?>

	<?php foreach ($this->ticket->messages as $message) : ?>
		<?php if ($message->admin == 2 || $message->admin == 3 || $message->admin == 4) continue; ?>
		<?php if ($message->admin == 5 || $message->admin == 6) continue; ?>

		<?php 
			$rowclass = "user";
			if ($message->admin == 1)
				$rowclass = "admin";
			if ($message->user_id != $user->id)
				$rowclass = "otheruser";
		?>

		<tr id="message<?php echo $message->id; ?>" class="<?php if (!$first) echo 'first'; ?> limeticket_ticket_row_<?php echo $rowclass; ?> limeticket_ticket_row_<?php echo $rowclass; ?>_head">
			<td>
				<div class="pull-right">
					<?php if ($message->admin == 1) : ?>
						<?php LIMETICKET_Helper::$message_labels[$message->id] = "success"; ?>
						<a class="label label-success">
					<?php else: ?>
						<?php if ($message->user_id == $user->id): ?>
							<?php LIMETICKET_Helper::$message_labels[$message->id] = "warning"; ?>
							<a class="label label-warning">
						<?php else: ?>
							<?php LIMETICKET_Helper::$message_labels[$message->id] = "info"; ?>
							<a class="label label-info">
						<?php endif; ?>
					<?php endif; ?>	
					
						<?php if ($message->poster): ?>
							<?php echo $message->poster; ?>
						<?php elseif ($message->email): ?>
							<?php echo $message->email; ?>
						<?php elseif ($message->name): ?>
							<?php echo $message->name; ?>
						<?php else: ?>
							<?php echo $this->ticket->unregname; ?>
						<?php endif; ?>


					</a>
				</div>

				<!--<img src='<?php echo JURI::root( true ); ?>/components/com_limeticket/assets/images/message.png'>-->
				<strong>
					<?php echo $message->subject; ?>
				</strong>
			</td>
		</tr>

		<tr class="limeticket_ticket_row_<?php echo $rowclass; ?> limeticket_ticket_row_<?php echo $rowclass; ?>_body">
			<td style="overflow-x: auto">
				<div class="pull-right" style="margin-bottom: 8px;margin-left: 8px;">
					<?php if ($message->admin == 1 && LIMETICKET_Settings::get('ratings_per_message')) echo SupportHelper::messageRating($message, false, LIMETICKET_Settings::get('ratings_per_message_change')); ?>
					<i>
						<?php echo LIMETICKET_Helper::TicketTime($message->posted, LIMETICKET_DATETIME_MID); ?>
					</i>
				</div>
				
				<?php 

					if (strpos($message->body, "[cid:") !== false)
					{
						require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'cron'.DS.'emailcheck.php');
						$ec = new LIMETICKETCronEMailCheck();
						$message->body = $ec->processInlineImages($message->id);
					}
				
					$msg = $message->body;
					$msg = LIMETICKET_Helper::ParseBBCode($msg, $message, false, false, true);

					if (LIMETICKET_Settings::get('glossary_support'))
					{
						echo LIMETICKET_Glossary::ReplaceGlossary($msg);
					} else {
						echo $msg;
					}
				?>
				
				<?php if (array_key_exists("attach", $message)) : ?>
					<?php foreach ($message->attach as &$attach): ?>
						<?php if ($attach->inline) continue; ?>
						<?php $image = in_array(strtolower(pathinfo($attach->filename, PATHINFO_EXTENSION)), array('jpg','jpeg','png','gif')); ?>
							
							
							<div class="padding-mini">
							
							<?php if ($image): ?>
								<div class="pull-left">
									<a class="show_modal_image" href="<?php echo LIMETICKETRoute::_('index.php?option=com_limeticket&view=ticket&task=attach.view&fileid=' . $attach->id . "&ticketid=" . $this->ticket->id); ?>">
										<img class="media-object" src="<?php echo LIMETICKETRoute::_('index.php?option=com_limeticket&view=ticket&task=attach.thumbnail&fileid=' . $attach->id . "&ticketid=" . $this->ticket->id); ?>" width="16" height="16">
									</a>
								</div>
							<?php else: ?>
								<div class="pull-left">
									<a href="<?php echo LIMETICKETRoute::_('index.php?option=com_limeticket&view=ticket&task=attach.download&fileid=' . $attach->id . "&ticketid=" . $this->ticket->id); ?>">
										<i class="icon-download"></i>
									</a>
								</div>
							<?php endif; ?>
							
							&nbsp;
							<a href='<?php echo LIMETICKETRoute::_('index.php?option=com_limeticket&view=ticket&task=attach.download&fileid=' . $attach->id . "&ticketid=" . $this->ticket->id); ?>'>
								<?php echo $attach->filename; ?>
							</a>
						</div>

					<?php endforeach; ?>
				<?php endif; ?>

			</td>
		</tr>

		<?php $first = false; ?>
	<?php endforeach; ?>
</table>

<?php if (LIMETICKET_Settings::get('glossary_support')) echo LIMETICKET_Glossary::Footer();
