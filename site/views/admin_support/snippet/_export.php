<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<?php if (LIMETICKET_Permission::auth("core.create", "com_limeticket.kb")): ?>
	<div style="display: none;">
		<form id="ticket_to_kb" action="<?php echo JRoute::_("index.php?option=com_limeticket&view=admin_content&type=kb&what=new"); ?>" method="POST" target="_blank">
			<input name="option" value="com_limeticket" />
			<input name="view" value="admin_content" />
			<input name="type" value="kb" />
			<input name="what" value="new" />
			<input name="title" value="<?php echo LIMETICKET_Helper::escape($this->ticket->title); ?>" />
			<textarea name="body"><?php 
			foreach ($this->ticket->messages as $message)
			{
				if ($message->admin == 3) continue; 
				$msg = LIMETICKET_Helper::ParseBBCode($message->body, $message);
				echo LIMETICKET_Helper::escape($msg) . "\n";
				//echo "<hr />\n";
			}
			?></textarea>
		</form>
	</div>
<?php endif; ?>

<?php if (LIMETICKET_Permission::auth("core.create", "com_limeticket.faq")): ?>
	<div style="display: none;">
		<form id="ticket_to_faq" action="<?php echo JRoute::_("index.php?option=com_limeticket&view=admin_content&type=faqs&what=new"); ?>" method="POST" target="_blank">
			<input name="option" value="com_limeticket" />
			<input name="view" value="admin_content" />
			<input name="type" value="faqs" />
			<input name="what" value="new" />
			<input name="question" value="<?php echo LIMETICKET_Helper::escape($this->ticket->title); ?>" />
			<textarea name="answer"><?php 
			foreach ($this->ticket->messages as $message)
			{
				if ($message->admin == 3) continue; 
				$msg = LIMETICKET_Helper::ParseBBCode($message->body, $message);
				echo LIMETICKET_Helper::escape($msg) . "\n";
				//echo "<hr />\n";
			}
			?></textarea>
		</form>
	</div>
<?php endif; ?>
