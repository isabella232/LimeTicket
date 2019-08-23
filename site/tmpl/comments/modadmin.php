<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<div class="limeticket_spacer"></div>
<?php echo LIMETICKET_Helper::PageSubTitle('MODERATE'); ?>

<div class="form-inline">
<?php echo JText::_('MOD_COMMENTS'); ?> <?php echo $this->whatcomm; ?>
<?php echo JText::_('MOD_SECTION'); ?> <?php echo $this->identselect; ?>
<button class="btn btn-default" onclick='limeticket_moderate_refresh(); return false;'><?php echo JText::_('REFRESH'); ?></button>
</div>

<div id="limeticket_moderate">
	<?php include $this->tmplpath . DS .'modadmin_inner.php' ?>	
</div>
 
<?php $this->IncludeJS() ?>
