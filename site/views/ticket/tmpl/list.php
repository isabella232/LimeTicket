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
<?php echo LIMETICKET_Helper::PageTitle("SUPPORT","CURRENT_SUPPORT_TICKETS"); ?>

<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_tabbar.php'); ?>

<?php LIMETICKET_Helper::HelpText("support_user_list_header"); ?>

<form id="limeticketFormTS" action="<?php echo LIMETICKETRoute::_('index.php?option=com_limeticket&view=ticket&layout=list&tickets=' . LIMETICKET_Input::getCmd('tickets'));?>" method="post" name="limeticketForm" class="form-inline form-condensed">

	<?php if (!LIMETICKET_Settings::get('support_simple_userlist_search')): ?>

		<div style="margin-bottom: 12px;">

			<div class="pull-right">
				<?php echo $this->orderSelect(); ?>
			</div>
		
			<div class="input-append">
				<input type="text" name="search" class='input-medium' id="basic_search" value="<?php echo LIMETICKET_Input::getString('search','') ?>" placeholder="<?php echo JText::_("SEARCH_TICKETS"); ?>">
				<a class='btn btn-primary' onclick='limeticket_submit_search();return false;'>
					<i class="icon-search"></i>
					<?php echo JText::_("SEARCH") ?>
				</a>
					<a class='btn btn-default' type="submit" onclick="jQuery('#basic_search').val('');jQuery('#search_all').removeAttr('checked');jQuery('#limeticketFormTS').submit();return false;">
					<i class="icon-remove"></i>
					<?php echo JText::_("RESET") ?>
				</a>					
			</div>
			<label class="checkbox">
				<input type="checkbox" name="search_all" id="search_all" value="1" <?php if (LIMETICKET_Input::getString('search_all')) echo "checked"; ?>> <?php echo JText::_('SEARCH_ALL_MY_TICKETS'); ?>
			</label>
		</div>
	
	<?php endif; ?>
	<?php LIMETICKET_Helper::HelpText("support_user_list_after_search"); ?>

<?php if (count($this->tickets) < 1) { ?>

<?php echo JText::_("YOU_CURRENTLY_HAVE_NO_SUPPORT_TICKETS"); ?>

<?php } else { ?>

<table class='table table-bordered table-ticketborders table-condensed'>

<?php $this->listHeader(); ?>

<?php foreach ($this->tickets as $ticket): ?>
	
<?php $this->listRow($ticket); ?>
	
<?php endforeach; ?>

</table>

	<?php echo $this->pagination->getListFooter(); ?>
<?php } ?>
</form>

<?php LIMETICKET_Helper::HelpText("support_user_list_after_footer"); ?>

<?php include JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'_powered.php'; ?>
<?php echo LIMETICKET_Helper::PageStyleEnd(); ?>

<script>

/*function highlightticket(ticketid)
{
	jQuery('.ticket_' + ticketid).each(function(){
		jQuery(this).attr('data-old_back',jQuery(this).css('background-color'));
		jQuery(this).css('background-color','<?php echo LIMETICKET_Settings::get('css_hl'); ?>');
	});
}

function unhighlightticket(ticketid)
{
	jQuery('.ticket_' + ticketid).each(function(){
		jQuery(this).css('background-color',jQuery(this).attr('data-old_back'));
	});
}*/

function toggleOrder()
{
	var order_dir = jQuery('#order_dir').val();
	if (order_dir == "asc")
	{
		jQuery('#order_dir').val('desc');	
	} else {
		jQuery('#order_dir').val('asc');	
	}	
	jQuery('#limeticketForm').submit();	
}

function limeticket_submit_search()
{
	jQuery('input[name="limitstart"]').val(0);
	jQuery("#limeticketFormTS").submit();
	return false;							
}


function limeticketAdminOrder(ordering)
{
    var deforder = "asc";
    if (ordering.indexOf(".asc") > 0)
    {
        ordering = ordering.replace(".asc", "");
    } else if (ordering.indexOf(".desc") > 0)
    {
        ordering = ordering.replace(".desc", "");
        deforder = "desc";
    }

    var current = jQuery('#ordering').val();
    var curorder = "asc";
    if (current.indexOf(".asc") > 0) {
        current = current.replace(".asc", "");
    } else if (current.indexOf(".desc") > 0) {
        current = current.replace(".desc", "");
        curorder = "desc";
    }

    if (current != ordering)
    {
        // different field
        jQuery('#ordering').val(ordering + "." + deforder);
    } else {
        // change direction
        if (curorder == "asc")
        {
            jQuery('#ordering').val(ordering + ".desc");
        } else {
            jQuery('#ordering').val(ordering + ".asc");
        }
    }

    limeticket_submit_search();
}

</script>