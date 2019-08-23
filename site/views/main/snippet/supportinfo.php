<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<?php 
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'translate.php'); 
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'support_tickets.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'support_source.php'); 
?>

<?php if (LIMETICKET_Permission::auth("limeticket.handler", "com_limeticket.support_admin") && LIMETICKET_Settings::Get('mainmenu_support')): ?>
	
	<div class="<?php if ($this->info_well): ?>well well-mini<?php else: ?>margin-medium<?php endif; ?> limeticket_mainmenu_support_panel" id="main_menu_support_box">
		<table class="table-borderless">
			<tr>
				<td valign="middle" width="52" style="vertical-align: middle;" class="hidden-phone">
					<img class="limeticket_menu_support_image" src='<?php echo JURI::root( true ); ?>/components/com_limeticket/assets/images/support/support_48.png'>
				</td>
				<td valign="middle" width="200" style="vertical-align: middle;" class="hidden-phone">
					<h3 class="margin-none">
						<?php echo  JText::_("SUPPORT_TICKETS"); ?>
					</h3>
				</td>
				<td valign="middle" style="vertical-align: middle;">
					<div class="visible-phone">
						<h3 class="margin-none">
							<img class="limeticket_menu_support_image" src='<?php echo JURI::root( true ); ?>/components/com_limeticket/assets/images/support/support_48.png' width='24' height='24'>
							<?php echo  JText::_("SUPPORT_TICKETS"); ?>
						</h3>
					</div>
					<p>
						<?php
						LIMETICKET_Ticket_Helper::GetStatusList();
						$counts = SupportTickets::getTicketCount();
		
						$displayed = 0;
					
						LIMETICKET_Translate_Helper::Tr(LIMETICKET_Ticket_Helper::$status_list);
						foreach (LIMETICKET_Ticket_Helper::$status_list as $status)
						{
							if ($status->def_archive) continue;
							if ($status->is_closed) continue;
							if (!array_key_exists($status->id, $counts)) continue;
							if ($counts[$status->id] < 1) continue;
						
							$displayed++;
						?>
							<h4 class="margin-mini">
								<a href="<?php echo LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=admin_support&tickets=' . $status->id ); ?>">
									<?php echo $status->title; ?> (<?php echo $counts[$status->id]?>)
								</a>
							</h4>
						<?php
						}
						
						foreach (SupportSource::getMainMenu_ListItems() as $item)
						{
						?>
							<h4 class="margin-mini">
								<a href="<?php echo LIMETICKETRoute::_( $item->link ); ?>">
									<?php echo $item->name; ?> (<?php echo $item->count;?>)
								</a>
							</h4>
						<?php
						}
						?>
						
						<?php if ($displayed == 0): ?>
							<h4 class="margin-mini">
								<?php echo JText::_('THERE_ARE_NO_OPEN_SUPPORT_TICKETS'); ?>
							</h4>
						<?php endif; ?>

					</p>
				</td>
			</tr>
		</table>
	</div>
<?php endif; ?>
	
<?php if (LIMETICKET_Permission::CanModerate() && LIMETICKET_Settings::Get('mainmenu_moderate')): ?>

<div class="<?php if ($this->info_well): ?>well well-mini<?php else: ?>margin-medium<?php endif; ?> limeticket_mainmenu_moderate_panel">
	<table class="table-borderless">
		<tr>
			<td valign="middle" width="52" style="vertical-align: middle;" class="hidden-phone">
				<img src='<?php echo JURI::root( true ); ?>/components/com_limeticket/assets/images/support/moderate_48.png'>
			</td>
			<td valign="middle" width="200" style="vertical-align: middle;" class="hidden-phone">
				<h3 class="margin-none">	
					<?php echo  JText::_("MODERATE"); ?>
				</h3>
			</td>
			<td valign="middle" style="vertical-align: middle;">
					<div class="visible-phone">
						<h3 class="margin-none">
							<img class="limeticket_menu_support_image" src='<?php echo JURI::root( true ); ?>/components/com_limeticket/assets/images/support/moderate_48.png' width='24' height='24'>
							<?php echo  JText::_("MODERATE"); ?>
						</h3>
					</div>
					<?php $this->comments->DisplayModStatus("modstatus_menu.php"); ?>
			</td>
		</tr>
	</table>
</div>

<?php endif; ?>
