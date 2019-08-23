<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<?php $what = LIMETICKET_Input::getCmd('what', ''); ?>

	<?php if ($this->view_mode != 'popup'): ?>	
	<div class='media'>
		<div class="pull-right">
			<?php echo $this->content->EditPanel($art); ?>
		</div>
		<div class="media-body">
			<?php if ($what == "recent" && LIMETICKET_Settings::get( 'kb_show_recent_stats' ) && $art['modified'] != "0000-00-00 00:00:00"):?>
				<span class="pull-right">
					<?php echo LIMETICKET_Helper::Date($art['modified'], LIMETICKET_DATE_SHORT); ?>
				</span>
			<?php endif; ?>
			<?php if ($what == "viewed" && LIMETICKET_Settings::get( 'kb_show_viewed_stats' )):?>
				<span class="pull-right">
					<?php echo $art['views']; ?> <?php echo JText::_("VIEW_S") ?>
				</span>
			<?php endif; ?>
			<?php if ($what == "rated" && LIMETICKET_Settings::get( 'kb_show_rated_stats' )):?>
				<span class="pull-right">
					<?php echo $art['rating']; ?>
					<img src="<?php echo JURI::root( true ); ?>/components/com_limeticket/assets/images/highestrated_small.png" width="16" height="16">
				</span>
			<?php endif; ?>
			<h5 class="media-heading">
				<a href='<?php echo LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=kb&kbartid=' . $art['id'] ); ?>'>
					<?php echo $art['title']; ?>
				</a>
			</h5>
		</div>
	</div>
	<?php elseif ($this->view_mode == 'popup'): ?>	
	<div class='media'>
		<div class="pull-right">
			<?php echo $this->content->EditPanel($art); ?>
		</div>
		<div class="media-body">
			<h5 class="media-heading">
				<a class="show_modal_iframe" data_modal_width="<?php echo LIMETICKET_Settings::get("kb_popup_width"); ?>" href='<?php echo LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=kb&tmpl=component&kbartid=' . $art['id'] ); ?>'>
					<?php echo $art['title']; ?>
				</a>
			</h5>
		</div>		
	</div>	
	<?php endif; ?>

