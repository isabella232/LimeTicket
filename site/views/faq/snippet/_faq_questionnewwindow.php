<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<div class="media faq_faq faq_<?php echo $cat['id'] . "_" . $faq['id']; ?>_cont faq_<?php echo $faq['id']; ?>_cont"">
	<div class="pull-right">
		<?php echo $this->content->EditPanel($faq); ?>
	</div>
	<div class="media-body">
		<h5 class="media-heading">
			<a href='<?php echo LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=faq&tmpl=component&window=1&faqid=' . $faq['id'] ); ?>' 
					onclick="window.open(jQuery(this).attr('href'),'','width=<?php echo LIMETICKET_Settings::get('faq_popup_width'); ?>,height=<?php echo LIMETICKET_Settings::get('faq_popup_height'); ?>');return false;"
					>
				<?php echo $faq['question']; ?>
			</a>
		</h5>		
	</div>
</div>	
