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
<?php echo LIMETICKET_Helper::PageTitle('SUPPORT_ADMIN',"NEW_SUPPORT_TICKET"); ?>
<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'admin'.DS.'snippet'.DS.'_tabbar.php'); ?>

<?php echo LIMETICKET_Helper::PageSubTitle("CREATE_TICKET_FOR_REGISTERED_USER"); ?>

<script>
function PickUser(userid, username, name)
{
	jQuery('#user_id').val(userid);
	jQuery('#username_display').html(name + " (" + username + ")");
	limeticket_modal_hide();
}

jQuery(document).ready(function () {
	jQuery('#new_ticket').click( function (ev) {
		if (jQuery('#user_id').val() == 0)
		{
			ev.preventDefault();
			alert("Please select a user");
		}
	});
});

</script>

<?php 

$extra = "";
if (LIMETICKET_Input::getInt("prodid") > 0)
	$extra .= "&prodid=" . LIMETICKET_Input::getInt("prodid");
if (LIMETICKET_Input::getInt("deptid") > 0)
	$extra .= "&deptid=" . LIMETICKET_Input::getInt("deptid");
if (LIMETICKET_Input::getInt("catid") > 0)
	$extra .= "&catid=" . LIMETICKET_Input::getInt("catid");
?>

<form action="<?php echo LIMETICKETRoute::_('index.php?option=com_limeticket&view=ticket&layout=open&admincreate=1' . $extra); ?>" method="post" class="form-horizontal form-condensed">

	<div class="control-group">
		<label class="control-label"><?php echo JText::_("SELECT_USER"); ?></label>
		<div class="controls">
			<a id="pick_user" class="btn btn-default show_modal_iframe" href="<?php echo LIMETICKETRoute::_("index.php?option=com_limeticket&view=admin_support&layout=users&tmpl=component"); ?>" data_modal_width="1400">
				<?php echo JText::_("CHANGE"); ?>
			</a>
			<span class="help-inline" id="username_display"><?php echo LIMETICKET_Input::getString('regname', JText::_("NONE_")); ?></span>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label"></label>
		<div class="controls">
			<input class='btn btn-primary' type="submit" id="new_ticket" value="<?php echo JText::_("OPEN_TICKET_FOR_USER"); ?>">
			<a class='btn btn-default' href="<?php echo LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=admin_support' ); ?>"><?php echo JText::_("CANCEL"); ?></a>
		</div>
	</div>
	
	<input name="user_id" id="user_id" type="hidden" value="<?php echo LIMETICKET_Input::getInt('user_id'); ?>">
	
</form>
<?php include JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'_powered.php'; ?>
<?php echo LIMETICKET_Helper::PageStyleEnd(); ?>
