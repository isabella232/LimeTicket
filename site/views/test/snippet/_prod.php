<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<?php LIMETICKET_Translate_Helper::TrSingle($product); ?>

<div class='media kb_prod_<?php echo $product['id']; ?> test_prod_accordion'>

	<?php 
		$link = "#";
		if ($this->test_show_prod_mode != "inline" && $this->test_show_prod_mode != "accordian")
			$link = LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=test&prodid=' . $product['id'] );// FIX LINK
	?>
	
	<?php if ($product['image']) : ?>
	<div class='pull-left'>
		<a href="<?php echo $link; ?>" <?php if ($link == "#") echo "onclick='return false;'"; ?>>
			<?php if (substr($product['image'],0,1) != "/"): ?>
				<img class='media-object' src='<?php echo JURI::root( true ); ?>/images/limeticket/products/<?php echo LIMETICKET_Helper::escape($product['image']); ?>' width='64' height='64'>
			<?php else: ?>	
				<img class='media-object' src='<?php echo JURI::root( true ); ?><?php echo LIMETICKET_Helper::escape($product['image']); ?>' width='64' height='64'>
			<?php endif; ?>
		</a>
	</div>
	<?php endif; ?>
	
	<div class="media-body accordion-group"  style="border:0;padding:0;margin:0">
		<div
			<?php if ($this->test_show_prod_mode == "accordian"): ?>
				style="cursor: pointer" data-toggle="collapse" data-target="#test_prod_<?php echo $product['id']; ?>" data-parent=".test_prod_accordion"
			<?php endif; ?>
			>
			<h4 class='media-heading'>
				<a href='<?php echo $link; ?>' <?php if ($link == "#") echo "onclick='return false;'"; ?>>
					<?php echo $product['title'] ?>
				</a>
			</h4>
			<?php echo $product['description']; ?>
		</div>
				
		<?php if ($this->test_show_prod_mode != "list"): ?>
			<div id="test_prod_<?php echo $product['id']; ?>" 
				<?php if ($this->test_show_prod_mode == "accordian"): ?>
					class="collapse"
				<?php endif; ?>	
				>
				<?php $testcount += $this->comments->DisplayCommentsOnly($product['id']); ?>
			</div>
		<?php endif; ?>
	</div>
</div>
