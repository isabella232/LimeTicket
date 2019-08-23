<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<div class='media kb_prod_<?php echo $product['id']; ?>' >
	
<?php LIMETICKET_Translate_Helper::TrSingle($product); ?>

	<?php if ($product['image']) : ?>
	<div class='pull-left'>
		<a href="<?php echo LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=kb&prodid=' . $product['id'] );?>">
			<img class='media-object' src='<?php echo JURI::root( true ); ?>/images/limeticket/products/<?php echo LIMETICKET_Helper::escape($product['image']); ?>' width='64' height='64'>
		</a>
	</div>
	<?php endif; ?>
	
	<div class="media-body">
		<h4 class='media-heading'>
			<?php if (LIMETICKET_Input::getInt('prodid') != 0) : ?>
				<a href='<?php echo LIMETICKETRoute::_( $this->base_url . '&prodid=' . $product['id'] );?>'>
					<?php echo $product['title'] ?>
				</a>
					<?php else : ?>
				<a href='<?php echo LIMETICKETRoute::_( $this->base_url . '&prodid=' . $product['id'] );?>'>
					<?php echo $product['title'] ?>
				</a>
			<?php endif; ?>
		</h4>
		<?php echo $product['description']; ?>
	</div>
</div>
