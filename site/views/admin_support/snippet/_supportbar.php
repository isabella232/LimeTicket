<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'support_source.php');

$cst = LIMETICKET_Ticket_Helper::GetStatusByID($this->ticket_view); 
LIMETICKET_Translate_Helper::TrSingle($cst);
?>

<ul class="nav nav-tabs limeticket_support_tabbar <?php if (LIMETICKET_Input::getInt('ticketid') > 0) echo "nav-always"; ?>">

	<?php echo LIMETICKET_GUIPlugins::output("adminSupportTabs_Start"); ?>

	<?php if (LIMETICKET_Input::getCmd('what') == "search") : ?>
		<?php $this->ticket_view = "search"; ?>
		<?php $cst = null; ?>
		<li class="<?php if ($this->ticket_view == "search") echo "active";?>">
			<a href='#'>
				<?php echo JText::sprintf("SA_RESULTS",$this->ticket_count); ?>
			</a>
		</li>
	<?php endif; ?>
	
	<?php if (LIMETICKET_Input::getCmd('layout') == "settings") : ?>
		<?php $this->ticket_view = "settings"; ?>
		<?php $cst = null; ?>
		<li class="active">
			<a href='#'>
				<?php echo JText::_("MY_SETTINGS"); ?>
			</a>
		</li>
	<?php else: ?>

		<?php if ($cst && !$cst->own_tab) :?>
			<li class="active">
				<a href='<?php echo LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=admin_support&tickets=' . $cst->id ); ?>'>
					<?php echo $cst->title; ?> (<span class='ticket_count_<?php echo $cst->id; ?>'><?php echo $this->count[$cst->id]; ?></span>)
				</a>
			</li>
		<?php endif; ?>

	<?php endif; ?>

<?php 
$tabs = LIMETICKET_Ticket_Helper::GetStatuss("own_tab"); 
LIMETICKET_Translate_Helper::Tr($tabs);
?>

<?php foreach ($tabs as $tab): ?>
	
	<li class="<?php if (isset($cst) && $cst->id == $tab->id) echo "active";?>">
		<a href='<?php echo LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=admin_support&tickets=' . $tab->id ); ?>'>
			<?php echo $tab->title; ?> (<span class='ticket_count_<?php echo $tab->id; ?>'><?php echo $this->count[$tab->id]; ?></span>)
		</a>
	</li>
	
<?php endforeach; ?>	
	
<?php if (LIMETICKET_Settings::get('support_tabs_allopen') || $this->ticket_view == "allopen"): ?>
	<li class="<?php if ($this->ticket_view == "allopen") echo "active";?>">
		<a href='<?php echo LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=admin_support&tickets=allopen' ); ?>'>
			<?php echo LIMETICKET_Helper::TicketCountSpan($this->count['allopen'], "allopen", JText::sprintf("SA_ALLOPEN",$this->count['allopen'])); ?>
		</a>
	</li>
<?php endif; ?>

<?php if (LIMETICKET_Settings::get('support_tabs_allclosed') || $this->ticket_view == "closed"): ?>
	<li class="<?php if ($this->ticket_view == "closed") echo "active";?>">
		<a href='<?php echo LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=admin_support&tickets=closed' ); ?>'>
			<?php echo LIMETICKET_Helper::TicketCountSpan($this->count['allclosed'], "allclosed", JText::sprintf("SA_CLOSED",$this->count['allclosed'])); ?>
		</a>
	</li>
<?php endif; ?>

<?php if (LIMETICKET_Settings::get('support_tabs_all') || $this->ticket_view == "all"): ?>
	<li class="<?php if ($this->ticket_view == "all") echo "active";?>">
		<a href='<?php echo LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=admin_support&tickets=all' ); ?>'>
			<?php echo LIMETICKET_Helper::TicketCountSpan($this->count['all'], "all", JText::sprintf("SA_ALL",$this->count['all'])); ?>
		</a>
	</li>
<?php endif; ?>

<?php foreach (SupportSource::get_tabs() as $tab): ?>
	<li class="<?php if ($tab->active) echo "active";?>">
		<a href='<?php echo LIMETICKETRoute::_($tab->link); ?>'>
			<?php echo $tab->tabname; ?>
		</a>
	</li>
<?php endforeach; ?>

	<?php echo LIMETICKET_GUIPlugins::output("adminSupportTabs_Mid"); ?>

<?php 
	$nottabs = LIMETICKET_Ticket_Helper::GetStatuss("own_tab", true); 
	LIMETICKET_Translate_Helper::Tr($nottabs);
		
	$showother = (count($nottabs) > 0);
	
	if ($showother || !LIMETICKET_Settings::get('support_tabs_allopen') || !LIMETICKET_Settings::get('support_tabs_allclosed') || !LIMETICKET_Settings::get('support_tabs_all')) :
	?>
	
	<li class="dropdown">
		<a class="dropdown-toggle" data-toggle="dropdown" href="#" onclick="return false;">
			<?php echo JText::_('OTHER'); ?><b class="caret bottom-up"></b>
		</a>
				
		<ul class="dropdown-menu bottom-up pull-left">  
			
			<?php echo LIMETICKET_GUIPlugins::output("adminSupportTabs_Other_Start"); ?>

			<?php foreach ($nottabs as $tab): ?>
				<li>
					<a href='<?php echo LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=admin_support&tickets=' . $tab->id ); ?>'><?php echo $tab->title; ?> (<span class='ticket_count_<?php echo $tab->id; ?>'><?php echo $this->count[$tab->id]; ?></span>)</a>
				</li>
			<?php endforeach; ?>	
		
			<?php if (count($nottabs) > 0 && (!LIMETICKET_Settings::get('support_tabs_allopen') || !LIMETICKET_Settings::get('support_tabs_allclosed') || !LIMETICKET_Settings::get('support_tabs_all'))): ?>
				<li class="divider"></li>  
			<?php endif; ?>
				
			<?php if (!LIMETICKET_Settings::get('support_tabs_allopen')): ?>
				<li>
					<a href='<?php echo LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=admin_support&tickets=allopen' ); ?>'>
						<?php echo LIMETICKET_Helper::TicketCountSpan($this->count['allopen'], "allopen", JText::sprintf("SA_ALLOPEN",$this->count['allopen'])); ?>
					</a>
				</li>
			<?php endif; ?>

			<?php if (!LIMETICKET_Settings::get('support_tabs_allclosed')): ?>
				<li>
					<a href='<?php echo LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=admin_support&tickets=closed' ); ?>'>
						<?php echo LIMETICKET_Helper::TicketCountSpan($this->count['allclosed'], "allclosed", JText::sprintf("SA_CLOSED",$this->count['allclosed'])); ?>
					</a>
				</li>
			<?php endif; ?>

			<?php if (!LIMETICKET_Settings::get('support_tabs_all')): ?>
				<li>
					<a href='<?php echo LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=admin_support&tickets=all' ); ?>'>
						<?php echo LIMETICKET_Helper::TicketCountSpan($this->count['all'], "all", JText::sprintf("SA_ALL",$this->count['all'])); ?>
					</a>
				</li>
			<?php endif; ?>

			<?php echo LIMETICKET_GUIPlugins::output("adminSupportTabs_Other_End"); ?>
		</ul>
	</li>
	<?php endif; ?>

	<?php echo LIMETICKET_GUIPlugins::output("adminSupportTabs_End"); ?>

</ul>

<?php
$values = SupportUsers::getAllSettings();
if (!empty($values->out_of_office) && $values->out_of_office) : ?>
<div class="alert alert-warning">
	<div class="pull-right">
		<a class="btn btn-default" href="<?php echo LIMETICKETRoute::_("index.php?option=com_limeticket&view=admin_support&layout=outofoffice", false); ?>"><?php echo JText::_('SET_AS_AVAILABLE'); ?></a>
	</div>
	<h4>
		<?php echo JText::_('YOU_ARE_CURRENT_UNAVAILABLE'); ?>
	</h4>
	
	<div style="clear: both;"></div>
</div>
<?php endif; ?>