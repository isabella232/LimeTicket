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

<?php echo LIMETICKET_Helper::PageTitle('SUPPORT_ADMIN',"LIST_HANDLERS"); ?>

<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'admin'.DS.'snippet'.DS.'_tabbar.php'); ?>

<form id="mainform" name="mainform" action="<?php echo LIMETICKETRoute::_("index.php?option=com_limeticket&view=admin_support&layout=listhandlers"); ?>" method="post" class="form-inline form-condensed">
	<?php echo $this->mode_select; ?>
	<?php echo $this->products_select; ?>
	<?php echo $this->departments_select; ?>
	<?php echo $this->categories_select; ?>
	<?php echo $this->status_select; ?>
</form>

<table class="table table-bordered table-striped table-striped">
	<thead>
		<tr>
			<th><?php echo JText::_('USERNAME'); ?></th>
			<th><?php echo JText::_('NAME'); ?></th>
			<th><?php echo JText::_('EMAIL'); ?></th>
			<th><?php echo JText::_('ALL_OPEN'); ?></th>
			<th>
				<div class="pull-right">
					<div class="btn-group">
						<a href="#" class="limeticketTip btn btn-default btn-micro dropdown-toggle" data-toggle="dropdown" title="<?php echo JText::_('CHANGE_STATUS'); ?>"><i class="icon-arrow-down"></i></a>
						<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
							<?php foreach ($this->statuss as $status): ?>
								<li><a tabindex="-1" href="#" onclick='jQuery("#status").val("<?php echo $status->id; ?>");document.mainform.submit( );return false'><?php echo $status->title; ?></a></li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
				<?php echo $this->status_obj->title; ?>
			</th>
			<th><?php echo JText::_('STATUS'); ?></th>
		</tr>
	</thead>
	
	<tbody>
		
		<?php foreach ($this->handler_details as $handler): ?>
	
			<tr>
				<td><?php echo $handler->username; ?></td>
				<td><?php echo $handler->name; ?></td>
				<td><?php echo $handler->email; ?></td>
				<td>
					<?php if (LIMETICKET_Permission::auth("limeticket.handler.seeothers", "com_limeticket.support_admin", JFactory::getUser()->id) || $handler->id == JFactory::getUser()->id): ?>
						<div class="pull-right">
							<a href="<?php echo LIMETICKETRoute::_("index.php?option=com_limeticket&view=admin_support&layout=list&searchtype=advanced&what=search&status=allopen&handler=" . $handler->id, false); ?>" class="limeticketTip" title="<?php echo JText::_('VIEW_TICKETS'); ?>"><i class="icon-new-tab"></i></a>
						</div>
					<?php endif; ?>
					<?php echo $handler->open_tickets; ?> 
				</td>
				<td>
					<?php if (LIMETICKET_Permission::auth("limeticket.handler.seeothers", "com_limeticket.support_admin", JFactory::getUser()->id) || $handler->id == JFactory::getUser()->id): ?>
						<div class="pull-right">
							<a href="<?php echo LIMETICKETRoute::_("index.php?option=com_limeticket&view=admin_support&layout=list&searchtype=advanced&what=search&status=" . $this->status . "&handler=" . $handler->id, false); ?>" class="limeticketTip" title="<?php echo JText::_('VIEW_TICKETS'); ?>"><i class="icon-new-tab"></i></a>
						</div>
					<?php endif; ?>
					<?php echo $handler->status_count; ?>
				</td>
				<td>
					<?php if (LIMETICKET_Permission::auth("limeticket.ticket_admin.ooo", "com_limeticket.support_admin", JFactory::getUser()->id) || $handler->id == JFactory::getUser()->id): ?>
						<div class="pull-right">
							<a href="<?php echo LIMETICKETRoute::_("index.php?option=com_limeticket&view=admin_support&layout=outofoffice&user_id=" . $handler->id, false); ?>" class="limeticketTip" title="<?php echo JText::_('CHANGE_AVAILABILITY'); ?>">
								<i class="icon-edit"></i>
							</a>
						</div>
					<?php endif; ?>
						
					<?php if (!empty($handler->settings) && !empty($handler->settings->out_of_office) && $handler->settings->out_of_office): ?>
						<span class="label label-important"><?php echo JText::_('UNAVAILABLE'); ?></span>
					<?php else: ?>
						<span class="label label-success"><?php echo JText::_('AVAILABLE'); ?></span>
					<?php endif; ?>
					
					
				</td>
			</tr>
		
		<?php endforeach; ?>
	
	</tbody>
</table>

<div class="pull-right">
	<a href="<?php echo LIMETICKETRoute::_("index.php?option=com_limeticket&view=admin_support&layout=list", false); ?>" class='btn btn-default'><?php echo JText::_('CANCEL'); ?></a>
</div>
<?php include JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'_powered.php'; ?>
<?php echo LIMETICKET_Helper::PageStyleEnd(); ?>
