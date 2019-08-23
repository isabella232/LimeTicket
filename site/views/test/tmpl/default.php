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
<?php echo LIMETICKET_Helper::PageTitle("TESTIMONIALS");?>
<?php $this->comments->DisplayAdd(); ?>

<?php if (!empty($this->showresult)): ?>
	<div class='limeticket_comments_result_<?php echo $this->comments->uid; ?>'></div>
<?php endif; ?>

<div id="comments"></div>

<?php $testcount = 0; ?>

<?php if(count($this->products) > 0) : ?>
	<?php foreach($this->products as &$product): ?>
		
		<?php if (!array_key_exists("id", $product)) continue; ?>
		
		<?php if ($this->comments->GetCountOnly($product['id']) == 0 && LIMETICKET_Settings::get('test_hide_empty_prod')) continue; ?>
		
		<?php include "components/com_limeticket/views/test/snippet/_prod.php" ?>

	<?php endforeach; ?>
<?php endif; ?>

<?php if ($testcount == 0): ?>
	<?php if ($this->test_show_prod_mode != "list"): ?>
		<div class=""><?php echo JText::_('THERE_ARE_NO_TESTIMONIALS_TO_DISPLAY'); ?></div>
	<?php endif; ?>
<?php endif; ?>

<?php $this->comments->IncludeJS() ?>

<?php include JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'_powered.php'; ?>

<?php echo LIMETICKET_Helper::PageStyleEnd(); ?>
