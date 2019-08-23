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
<?php echo LIMETICKET_Helper::PageTitle("KNOWLEDGE_BASE",$this->product['title']); ?>
<div class="limeticket_spacer"></div>
<?php $product = $this->product; ?>

<?php //include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_prod.php'); ?>

<?php if ($this->prod_search) : ?>
	<form id="searchProd" action="<?php echo LIMETICKETRoute::_( $this->base_url ); ?>" method="post" name="limeticketForm">
		<input type="hidden" name='prodid' value='<?php echo (int)$product['id']; ?>'>
		<div class="input-append">
			<input id='kb_search' type="text" name='kbsearch' value="<?php echo LIMETICKET_Helper::escape($this->search); ?>" placeholder="<?php echo JText::_('SEARCH_KNOWLEDGE_BASE_ARTICLES'); ?>">
			<input id='kb_submit' class='btn btn-primary' type='submit' value='<?php echo JText::_("SEARCH"); ?>' />
			<input id='art_reset' class='btn btn-default' type='submit' value='<?php echo JText::_("RESET"); ?>' />
		</div>
	</form>
<?php endif; ?>
	
<?php if (LIMETICKET_Settings::get('kb_view_top')): ?>
<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_views.php'); ?>
<?php endif; ?>

<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_cat_list.php'); ?>	

<?php if (count($this->arts) > 0): ?>
	<div class="limeticket_clear"></div>
	<?php foreach ($this->arts as &$art) : ?>
		<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_art.php'); ?>
	<?php endforeach; ?>	
<?php endif; ?>

<?php if (!LIMETICKET_Settings::get('kb_view_top')): ?>
<?php include "components/com_limeticket/views/kb/snippet/_views.php" ?>
<?php endif; ?>

<?php include JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'_powered.php'; ?>
<?php echo LIMETICKET_Helper::PageStyleEnd(); ?>

<script>
<?php include JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'assets'.DS.'js'.DS.'content_edit.js'; ?>
</script>
