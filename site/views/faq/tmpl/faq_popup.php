<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>

<?php echo LIMETICKET_Helper::PageStylePopup(LIMETICKET_Input::getInt('window') == '1' ? '2' : true); ?>

<?php echo LIMETICKET_Helper::PageTitlePopup($this->faq['question']); ?>
<?php //echo JRequest::getVar('window'); ?>
<div class="media">
	
	<?php echo $this->content->EditPanel($this->faq); ?>
	
	<div class="media-body">
		

		<?php if ($this->faq['featured']): ?>
			<div class="pull-right well well-xsmall">
				<img src="<?php echo JURI::root( true ); ?>/components/com_limeticket/assets/images/featured.png" width="16" height="16" />
				<?php echo JText::_('Featured_faq'); ?>
			</div>
		<?php endif; ?>

		<?php 
		if (LIMETICKET_Settings::get( 'glossary_faqs' )) {
			echo LIMETICKET_Glossary::ReplaceGlossary($this->faq['answer']); 
			if ($this->faq['fullanswer'])
			{
				echo LIMETICKET_Glossary::ReplaceGlossary($this->faq['fullanswer']); 
			}
		} else {
			echo $this->faq['answer']; 
			if ($this->faq['fullanswer'])
			{
				echo $this->faq['fullanswer']; 
			}
		}		
		?>
		<?php if (count($this->tags) > 0): ?>
		<div class='limeticket_faq_tags'>
	
			<span><?php echo JText::_('TAGS'); ?>:</span>
			<?php echo implode(", ", $this->tags); ?>
		</div>
		<?php endif; ?>
	</div>	

</div>

<?php include JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'_powered.php'; ?>
<?php if (LIMETICKET_Settings::get( 'glossary_faqs' )) echo LIMETICKET_Glossary::Footer(); ?>

<?php echo LIMETICKET_Helper::PageStylePopupEnd(); ?>

<script>
jQuery(document).ready( function ()
{
	jQuery('a').click( function (ev) {
		ev.preventDefault();
		var href = jQuery(this).attr('href');
		if (href != '#')
			window.parent.location.href = href;
	});		
});

/*##NOT_EXT_START##*/
<?php include JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'assets'.DS.'js'.DS.'content_edit.js'; ?>
/*##NOT_EXT_END##*/
</script>

