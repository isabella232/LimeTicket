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
<?php echo LIMETICKET_Helper::PageTitle("SUPPORT","VIEW_SUPPORT_TICKET"); ?>

<?php 

$table_cols = LIMETICKET_Settings::get('support_info_cols_user');

$table_classes = "table table-borderless table-valign table-condensed table-narrow";
if ($table_cols > 1)
	$table_classes = "table table-borderless table-valign table-condensed";
?>

<div class="hide" style="display: none;" id="limeticket_ticket_base_url"><?php echo LIMETICKETRoute::_('index.php?option=com_limeticket&view=ticket&layout=view&ticketid=' . $this->ticket->id); ?></div>

<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_tabbar.php'); ?>

<?php LIMETICKET_Helper::HelpText("support_user_view_header"); ?>

<?php
$session = JFactory::getSession();
$value = $session->get("ticket_open_message");
$session->clear("ticket_open_message");
if ($value): ?>
<div class="alert alert-success limeticket_ticket_reply_message">
	<a class="close" data-dismiss="alert">&times;</a>
	<?php echo $value; ?>
</div>
<?php endif; ?>

<?php if (LIMETICKET_Permission::auth("limeticket.handler", "com_limeticket.support_admin")): ?>
	<?php 
		$adminticket = new SupportTicket();
	if ($adminticket->canLoad($this->ticket->id)): ?>
		<div class="alert alert-info">
			<?php echo JText::sprintf("LIMETICKET_YOU_HAVE_PERMISSION_TO_VIEW_THIS_TICKET_AS_A_HANDLER_WITH_MORE_FUNCTIONALITY", LIMETICKETRoute::_('index.php?option=com_limeticket&view=admin_support&layout=ticket&ticketid=' . $this->ticket->id, false)); ?>
		</div>
	<?php endif; ?>
<?php endif; ?>

<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_ticket_rate.php'); ?>

<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_ticket_print.php'); ?>

<?php if (LIMETICKET_Settings::get("messages_at_top") == 1 || LIMETICKET_Settings::get("messages_at_top") == 3)
	include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_messages_cont.php'); ?>

<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_ticket_merged.php'); ?>

<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_ticket_info.php'); ?>

<?php if (LIMETICKET_Settings::get("messages_at_top") == 0 || LIMETICKET_Settings::get("messages_at_top") == 2)
	include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_messages_cont.php'); ?>

<?php if (count($this->ticket->attach) > 0) : ?>
	<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_ticket_attach.php'); ?>
<?php endif; ?>

<script>

function doPrint(link)
{
	printWindow = window.open(jQuery(link).attr('href')); 
	return false;
}

function CreateEvents()
{
	jQuery('#addcomment').click(function(ev) {
		ev.stopPropagation();
		ev.preventDefault();
		jQuery('#messagereply').hide();
		jQuery('#messagepleasewait').show();

		jQuery('#inlinereply').submit();
		
		jQuery('#new_status').removeAttr('disabled');
		jQuery('#new_pri').removeAttr('disabled');

	});	
	
	jQuery('#replyclose').click(function(ev) {
		ev.stopPropagation();
		ev.preventDefault();
		
		jQuery('#should_close').val("1");
		jQuery('#messagereply').hide();
		jQuery('#messagepleasewait').show();
		
		jQuery('#inlinereply').submit();
		
		jQuery('#new_status').removeAttr('disabled');
		jQuery('#new_pri').removeAttr('disabled');
	});	
	
	jQuery('#replycancel').click(function(ev) {
		ev.stopPropagation();
		ev.preventDefault();
		jQuery('#messagereply').hide();
		jQuery('.post_reply').show();
		jQuery('#body').val("");

		jQuery('#new_status').removeAttr('disabled');
		jQuery('#new_pri').removeAttr('disabled');
	});
}

jQuery(document).ready(function () {
	jQuery('.post_reply').click(function(ev) {
		try {
			jQuery('#messagereply').show();
			jQuery('.post_reply').hide();
			jQuery('.limeticket_ticket_reply_message').hide();
			jQuery('#new_status').attr('disabled', 'disabled');
			jQuery('#new_pri').attr('disabled', 'disabled');
		
<?php if (LIMETICKET_Settings::Get('support_sceditor')): ?>		
			if (typeof sceditor_emoticons_root != 'undefined')
			{
				var rows = parseInt(jQuery("textarea.sceditor_hidden").attr('rows'));
				jQuery("textarea.sceditor_hidden").attr('rows', rows + 8);
				jQuery("textarea.sceditor_hidden").addClass('sceditor');
				jQuery("textarea.sceditor_hidden").removeClass('sceditor_hidden');
			
				init_sceditor();
			}
<?php endif; ?>
			ev.stopPropagation();
			ev.preventDefault();
		} catch (e) {
		}
	});

	jQuery('.ticketrefresh').click(function(ev) {
		ev.preventDefault();
		
		jQuery('#messagepleasewait').show();
		
		// fake height on please wat to stop page flickering so much
		try {
			var height = jQuery('#ticket_messages').height() - jQuery('#messagepleasewait').height() - 6;
			jQuery('#messagepleasewait').css('margin-bottom', height + 'px');
		} catch (e) {
		}
		
		jQuery('#ticket_messages').html("");
		//alert("Load");
		
		jQuery('#ticket_messages').load(jQuery(this).attr('href') + "&rand=" + Date.now(), function () {
			jQuery('#messagepleasewait').hide();
			//alert("Done");
		});
	});	

	CreateEvents();	
});

function AddCCUser(userid, readonly)
{
	limeticket_modal_hide();
	
	jQuery('#ccusers').html('<?php echo JText::_('PLEASE_WAIT'); ?>');
	
	var url = jQuery('#limeticket_ticket_base_url').text();
	url = limeticket_url_append(url, 'task', 'update.addccuser');
	url = limeticket_url_append(url, 'userid', userid);
	url = limeticket_url_append(url, 'readonly', readonly);
	
	jQuery.ajax({
		url: url,
		context: document.body,
		success: function(result){
			jQuery('#ccusers').html(result);
		}
	});
}

function removecc(userid)
{
	jQuery('#ccusers').html('<?php echo JText::_('PLEASE_WAIT'); ?>');
	
	var url = jQuery('#limeticket_ticket_base_url').text();
	url = limeticket_url_append(url, 'task', 'update.removeccuser');
	url = limeticket_url_append(url, 'userid', userid);

	jQuery.ajax({
		url: url,
		context: document.body,
		success: function(result){
			jQuery('#ccusers').html(result);
		}
	});
}

var sneaky = new ScrollSneak('freestyle-support');
function refreshPage()
{
	sneaky.sneak();
	window.location = window.location;
}

</script>


<?php include JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'_powered.php'; ?>
<?php echo LIMETICKET_Helper::PageStyleEnd(); ?>
