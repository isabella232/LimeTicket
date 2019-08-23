<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<?php echo LIMETICKET_Helper::PageStylePopup(true); ?>
<?php echo LIMETICKET_Helper::PageTitlePopup('Signature Preview'); ?>

<?php echo LIMETICKET_Helper::ParseBBCode(trim($this->signature)); ?>

<?php echo LIMETICKET_Helper::PageStylePopupEnd(); ?>					 			   	 	 