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
<?php echo LIMETICKET_Helper::PageTitle('SUPPORT_ADMIN',"CURRENT_SUPPORT_TICKETS"); ?>

<form id='limeticketForm' action="<?php echo LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=admin_support&tickets=' . $this->ticket_view ); ?>" name="limeticketForm" method="post">

	<?php if (JRequest::getVar('searchtype') != ""): ?>
		<div id="limeticket_showing_search" class="hide" style="display: none;"></div>
	<?php endif; ?>

	<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'admin'.DS.'snippet'.DS.'_tabbar.php'); ?>

	<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_supportbar.php'); ?>

	<?php if ($this->merge): ?>
		<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_merge_notice.php'); ?>
	<?php endif; ?>

	<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_search.php'); ?>

	<?php
	$def_archive = LIMETICKET_Ticket_Helper::GetStatusID('def_archive');
	$closed = LIMETICKET_Ticket_Helper::GetClosedStatus();
	if (array_key_exists($this->ticket_view, $closed) || $this->ticket_view == "closed"): ?>
		<p>
			<a class="btn btn-default btn-small" href="<?php echo LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=admin_support&task=archive.archive&tickets=' . $this->ticket_view ); ?>" onclick="return confirm('<?php echo JText::_('ARCHIVE_CONFIRM'); ?>');">
				<?php echo JText::_("ARCHIVE_ALL_CLOSED_TICKETS"); ?>
			</a>
			<?php if (LIMETICKET_Settings::get('support_delete')): ?>
				<a class="btn btn-default btn-small" href="<?php echo LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=admin_support&task=archive.delete&tickets=' . $this->ticket_view ); ?>" onclick="return confirm('<?php echo JText::_('DELETE_ALL_CONFIRM'); ?>');">
					<?php echo JText::_("DELETE_ALL_CLOSED_TICKETS"); ?>
				</a>
			<?php endif; ?>
		</p>
	<?php elseif ($this->ticket_view == $def_archive): ?>
		<?php if (LIMETICKET_Settings::get('support_delete')): ?>
			<p>
				<a class="btn btn-default btn-small" href="<?php echo LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=admin_support&task=archive.delete&tickets=' . $this->ticket_view ); ?>" onclick="return confirm('<?php echo JText::_('DELETE_ALL_ARCHIVED_CONFIRM'); ?>');">
					<?php echo JText::_("DELETE_ALL_ARCHIVED_TICKETS"); ?>
				</a>
			</p>
		<?php endif; ?>
	<?php endif; ?>

	<div id="limeticket_ticket_list">
		<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_ticket_list.php'); ?>
	</div>

	<?php if (count($this->tickets)) : ?>
		<?php echo $this->pagination->getListFooter(); ?>
	<?php endif; ?>

</form>

<script>

<?php if (LIMETICKET_Input::getInt('batch') == '1'): ?>
toggleBatch();
<?php endif; ?>


<?php if ($this->do_refresh): ?>
jQuery(document).ready( function () {
	setInterval("limeticket_refresh_tickets()", <?php echo $this->do_refresh * 1000; ?> );
});
<?php endif; ?>

// DO NOT DELETE THESE!
function cannedRefresh()
{
		
}
// DO NOT DELETE THESE!
function sigsRefresh()
{
		
}
// DO NOT DELETE THESE!

</script>

<?php include JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'_powered.php'; ?>
<?php echo LIMETICKET_Helper::PageStyleEnd(); ?>
