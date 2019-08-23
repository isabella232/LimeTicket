<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>

<li id='prod_cont_<?php echo $product->id; ?>' class="media highlight product">
	<div class="pull-left <?php if ($hasprodimages) echo 'product-image'; ?>">
		<?php if ($product->image) : ?>
			<img class="media-object pointer" onclick="jQuery('#prodid').val('<?php echo $product->id; ?>'); jQuery('#prodselect').submit(); return false;" src="<?php echo JURI::root( true ); ?>/images/limeticket/products/<?php echo LIMETICKET_Helper::escape($product->image); ?>">
		<?php endif; ?>
	</div>
	<div class="media-body">
		<h4 class="media-heading"><a href='#' onclick="jQuery('#prodid').val('<?php echo $product->id; ?>'); jQuery('#prodselect').submit(); return false;"><?php echo $product->title ?></a></h4>
		<?php echo $product->description; ?>
	</div>
</li>
