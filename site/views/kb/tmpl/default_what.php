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
<?php echo LIMETICKET_Helper::PageTitle("KNOWLEDGE_BASE",$this->pagetitle); ?>

<?php $product = $this->product; ?>
<?php $cat = $this->cat; ?>
<?php if ($product['id'] > 0): ?>
<?php //include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_prod.php'); ?>	
<?php endif; ?>
<?php if ($cat['id'] > 0): ?>
<?php //include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_cat.php'); ?>	
<?php endif; ?>

<?php if (LIMETICKET_Settings::get('kb_view_top')): ?>
<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_views.php'); ?>
<?php endif; ?>

<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_art_list.php'); ?>	

<?php if (!LIMETICKET_Settings::get('kb_view_top')): ?>
<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_views.php'); ?>
<?php endif; ?>

<?php include JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'_powered.php'; ?>
<?php echo LIMETICKET_Helper::PageStyleEnd(); ?>


<script>
<?php include JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'assets'.DS.'js'.DS.'content_edit.js'; ?>
</script>
