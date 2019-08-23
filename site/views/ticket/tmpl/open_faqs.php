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
<?php echo LIMETICKET_Helper::PageTitle("SUPPORT","NEW_SUPPORT_TICKET"); ?>

<div class="limeticket_spacer"></div>

<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'ticket'.DS.'snippet'.DS.'_openheader.php'); ?>

<?php echo LIMETICKET_Helper::PageSubTitle("PLEASE_ENTER_THE_SUBJECT_FOR_YOUR_ENQUIRY"); ?>

<form class="form form-horizontal form-condensed" id="ticketfindform">
	<div class="control-group">
		<label class="control-label"><?php echo JText::_("SUBJECT"); ?></label>
		<div class="controls">
			<input type="text" class="input-xlarge" name='subject' id='subject' size='<?php echo LIMETICKET_Settings::get('support_subject_size'); ?>' value="<?php echo LIMETICKET_Helper::escape($this->subject) ?>" required>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label"></label>
		<div class="controls">
			<a href="<?php echo LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=ticket&layout=open&task=open.find' ); ?>" id="ticketsearch" class="btn btn-default"><?php echo JText::_("SEARCH"); ?></a>
			<a href="<?php echo LIMETICKETRoute::_( $this->open_link ); ?>" id="openticket" class="btn btn-primary hide"><?php echo JText::_("OPEN_NEW_TICKET"); ?></a>
		</div>
	</div>
</form>

<div id="search_results">
</div>

<script>
var timeoutID = null;

jQuery(document).ready(function() {
	jQuery('#ticketfindform').submit(function (ev) {
		ev.preventDefault()
		
		var search = jQuery('#subject').val();
		if (search == "") return;
		
		updateSearch(search);
	});
	
	jQuery('#ticketsearch').click(function (ev) {
		ev.preventDefault();
		jQuery('#ticketfindform').submit();
	});
	
	jQuery('#openticket').click(function (ev) {
		var url = jQuery(this).attr('href');
		url = limeticket_url_append(url, "subject", jQuery('#subject').val());	
		jQuery(this).attr('href', url);
	});
	
<?php if (LIMETICKET_Settings::get('open_search_live')): ?>
	jQuery('#subject').keyup(function () {
		clearTimeout(timeoutID);
		var $target = jQuery(this);
		timeoutID = setTimeout(function() { updateSearch($target.val()); }, 500); 
	});
<?php endif; ?>
});

function updateSearch(search)
{
	if(search.length < 3) return;
	var url = jQuery('#ticketsearch').attr('href');
	url = limeticket_url_append(url, "search", search);	
	jQuery('#search_results').load(url, function () {
		init_elements();	
	});
		
	jQuery('#openticket').show();		
}

</script>

<?php include JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'_powered.php'; ?>
<?php echo LIMETICKET_Helper::PageStyleEnd(); ?>