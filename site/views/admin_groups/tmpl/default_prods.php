<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<?php echo LIMETICKET_Helper::PageStylePopup(); ?>
<?php echo LIMETICKET_Helper::PageTitlePopup(JText::_("PRODUCTS_SELECTED_FOR_GROUP")); ?>

<ul>
	<?php foreach ($this->products as $product): ?>
		<li><?php echo $product->title; ?></li>
	<?php endforeach; ?>

	<?php if (count($this->products) == 0): ?>
		<li>None Selected</li>
	<?php endif; ?>
</ul>

<?php echo LIMETICKET_Helper::PageStylePopupEnd(); ?>