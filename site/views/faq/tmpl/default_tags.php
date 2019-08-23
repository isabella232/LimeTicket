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
<?php echo LIMETICKET_Helper::PageTitle("FREQUENTLY_ASKED_QUESTIONS","TAGS"); ?>

	<div class="limeticket_spacer"></div>

	<div class="media">
		<a class="pull-left" href="#" onclick="return false;">
			<img class="media-object" src='<?php echo JURI::root( true ); ?>/components/com_limeticket/assets/images/tags-64x64.png' width='64' height='64'>
		</a>
		
		<div class="media-body">
			<div style="min-height: 64px">
						
				<h4 class="media-heading">
					<?php if (LIMETICKET_Settings::Get('faq_cat_prefix')): ?>
						<?php echo JText::_("FAQS"); ?> 
					<?php endif; ?>
					<?php echo JText::_('TAGS'); ?>
				</h4>
			
			</div>
			
			<div>
			
				<?php if (count($this->tags)) foreach ($this->tags as $tag) : ?>
					<div class='media'>
						<div class="media-body">
							<h4 class="media-heading">
								<a href='<?php echo LIMETICKETRoute::_('index.php?option=com_limeticket&view=faq&tag=' . urlencode($tag->tag)); ?>'>
									<?php echo $tag->tag; ?>
								</a>
							</h4>
						</div>
					</div>	
				<?php endforeach; ?>
				<?php if (count($this->tags) == 0): ?>
					<p><?php echo JText::_("NO_TAGS_FOUND");?></p>
				<?php endif; ?>

			</div>
		</div>
	</div>	

	
<?php include JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'_powered.php'; ?>

<?php echo LIMETICKET_Helper::PageStyleEnd(); ?>
