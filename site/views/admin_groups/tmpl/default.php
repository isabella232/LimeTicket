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
<?php echo LIMETICKET_Helper::PageTitle("GROUP_ADMINISTRATION"); ?>

<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'admin'.DS.'snippet'.DS.'_tabbar.php'); ?>

<?php if (LIMETICKET_Permission::auth("limeticket.groups", "com_limeticket.groups")): ?>
<p>
	<a class="btn btn-success" href="<?php echo LIMETICKETRoute::_('index.php?option=com_limeticket&view=admin_groups&what=create'); ?>">
		<i class="icon-plus"></i>
		<?php echo JText::_('CREATE_NEW_GROUP'); ?>
	</a>
</p>
<?php endif; ?>

<?php if (count($this->groups) == 0): ?>
	<div class="alert alert-info">
		<?php echo JText::_("THERE_ARE_CURRENTLY_NO_GROUP"); ?>
	</div>	
<?php endif; ?>

<?php foreach ($this->groups as &$group): ?>
<?php
	$allemail = LIMETICKET_Helper::GetYesNoText($group->allemail);
	$ccexclude = LIMETICKET_Helper::GetYesNoText($group->ccexclude);
	if ($group->allsee == 0)
	{
		$allsee = JText::_('VIEW_NONE');//"None";	
	} elseif ($group->allsee == 1)
	{
		$allsee = JText::_('VIEW');//"See all tickets";	
	} elseif ($group->allsee == 2)
	{
		$allsee = JText::_('VIEW_REPLY');//"Reply to all tickets";	
	} elseif ($group->allsee == 3)
	{
		$allsee = JText::_('VIEW_REPLY_CLOSE');//"Reply to all tickets";	
	}
?>

	<div class='well well-small'>
		<div class="pull-right">
			<a class="btn btn-default" href='<?php echo LIMETICKETRoute::_("index.php?option=com_limeticket&view=admin_groups&groupid={$group->id}"); ?>'>
				<i class="icon-edit"></i>
				<?php echo JText::_('EDIT'); ?>
			</a>
		</div>

		<h4 style="margin-top: 0px"><?php echo $group->groupname; ?></h4>

		<?php if ($group->description): ?>
			<p><?php echo $group->description; ?></p>
		<?php endif; ?>
		
		<table class="table table-condensed table-borderless margin-none">
			<tr>
				<th style="width:150px"><?php echo JText::_('GMEMBERS');?></th>
				<td>
					<a class="limeticketTip" href="<?php echo LIMETICKETRoute::_("index.php?option=com_limeticket&view=admin_groups&groupid={$group->id}"); ?>" title="<?php echo JText::_('EDIT_MEMBERS');?>" >
						<img src="<?php echo JURI::root( true ); ?>/administrator/components/com_limeticket/assets/members.png" width="16" height="16">	
						<?php 
						if ($group->cnt == 0)
						{
							echo JText::_("NO_MEMBERS"); 
						} else if ($group->cnt == 1) {
							echo JText::sprintf("X_MEMBER",$group->cnt); 
						} else {
							echo JText::sprintf("X_MEMBERS",$group->cnt); 
						}				
						?>
					</a>
				</td>
				
				<th style="width:150px">
					<?php echo JText::_('PRODUCTS'); ?>:
				</th>
				<td>					
					<?php if ($group->allprods) { ?>
						<?php echo JText::_("ALL_PROD"); ?>
					<?php } else { ?>
					<?php $link = LIMETICKETRoute::_("index.php?option=com_limeticket&view=admin_groups&tmpl=component&groupid={$group->id}&what=productlist"); ?>
						<a class="show_modal limeticketTip" title="<?php echo JText::_("VIEW_PROD_INFO"); ?>"  href="<?php echo $link; ?>"><?php echo JText::_("VIEW_PROD"); ?></a>
					<?php } ?>
				</td>
			</tr>
			<tr>
				<th>
					<?php echo JText::_("CC_ALL_USERS"); ?>&nbsp;<?php echo $allemail; ?>
				</th>
				<th>
					<?php echo JText::_("CC_EXCLUDE"); ?>&nbsp;<?php echo $ccexclude; ?>
				</th>
				<th>
					<?php echo JText::_('DEFAULT_PERMISSIONS');?>:
				</th>
				<td>
					<?php echo $allsee; ?>
				</td>
			</tr>
		</table>
	</div>
<?php endforeach; ?>

<script>

jQuery(document).ready(function () {
	jQuery('.popup_link').click(function (ev) {
		ev.preventDefault();
	
		var url = jQuery(this).attr('href');
		TINY.box.show({iframe:url, width:500,height:300});
	});
});

</script>

<?php include JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'_powered.php'; ?>
<?php echo LIMETICKET_Helper::PageStyleEnd(); ?>
