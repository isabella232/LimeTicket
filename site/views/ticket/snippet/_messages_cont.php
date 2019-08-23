<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

?>
<?php if (!LIMETICKET_Settings::get('user_hide_all_details')): ?>
	<?php echo LIMETICKET_Helper::PageSubTitle("MESSAGES"); ?>
<?php endif; ?>

<?php LIMETICKET_Helper::HelpText("support_user_view_mes_header"); ?>

<?php $st = LIMETICKET_Ticket_Helper::GetStatusByID($this->ticket->ticket_status_id); ?>

<?php if (!LIMETICKET_Settings::get('support_user_reply_under')): ?>
	<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_reply.php'); ?>
<?php endif; ?>

<div id="messagepleasewait" style="display: none;clear: both" class="alert alert-info">
	<?php echo JText::_('PLEASE_WAIT'); ?>
</div>

<?php LIMETICKET_Helper::HelpText("support_user_view_mes_buttons"); ?>

<?php
$session = JFactory::getSession();
$value = $session->get("ticket_message");
$session->clear("ticket_message");
if ($value): ?>
<div class="alert alert-success limeticket_ticket_reply_message">
	<a class="close" data-dismiss="alert">&times;</a>
	<?php echo $value; ?>
</div>
<?php endif; ?>

<div id="ticket_messages">
	<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_messages.php'); ?>
</div>

<?php if (LIMETICKET_Settings::get('support_user_reply_under')): ?>
	<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_reply.php'); ?>
<?php endif; ?>

<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_messages_key.php'); ?>

<?php LIMETICKET_Helper::HelpText("support_user_view_mes_footer"); ?>

