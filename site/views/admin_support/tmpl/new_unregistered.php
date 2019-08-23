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

<?php echo LIMETICKET_Helper::PageSubTitle("CREATE_TICKET_FOR_UNREGISTERED_USER"); ?>

<form action="<?php echo LIMETICKETRoute::_('index.php?option=com_limeticket&view=ticket&layout=open&admincreate=2'); ?>" method="post" class="form-horizontal form-condensed">

	<div class="control-group">
		<label class="control-label"><?php echo JText::_("EMAIL_ADDRESS"); ?></label>
		<div class="controls">
			<input type="text" name="admin_create_email" class="inputbox" value="<?php echo LIMETICKET_Helper::escape(LIMETICKET_Input::getString('admin_create_email')); ?>">
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label"><?php echo JText::_("NAME"); ?></label>
		<div class="controls">
			<input type="text" name="admin_create_name" class="inputbox" value="<?php echo LIMETICKET_Helper::escape(LIMETICKET_Input::getString('admin_create_name')); ?>">
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label"></label>
		<div class="controls">
			<input class='btn btn-primary' type="submit" id="new_ticket" value="<?php echo JText::_("OPEN_TICKET_FOR_USER"); ?>">
			<a class='btn btn-default' href="<?php echo LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=admin_support' ); ?>"><?php echo JText::_("CANCEL"); ?></a>
		</div>
	</div>
	
</form>

<?php include JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'_powered.php'; ?>
<?php echo LIMETICKET_Helper::PageStyleEnd(); ?>
