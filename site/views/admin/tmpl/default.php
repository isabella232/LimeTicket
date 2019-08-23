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
<?php echo LIMETICKET_Helper::PageTitle("ADMIN_OVERVIEW"); ?>

<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'admin'.DS.'snippet'.DS.'_tabbar.php'); ?>

<?php echo LIMETICKET_GUIPlugins::output("adminOverviewTop"); ?>

<?php if (LIMETICKET_Permission::auth("limeticket.handler", "com_limeticket.support_admin")): ?>
<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_overview.php'); ?>
<?php endif; ?>

<?php if (LIMETICKET_Permission::CanModerate()): ?>
<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'admin_moderate'.DS.'snippet'.DS.'_overview.php'); ?>
<?php endif; ?>

<?php if (LIMETICKET_Permission::PermAnyContent()): ?>
<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'admin_content'.DS.'snippet'.DS.'_overview.php'); ?>
<?php endif; ?>

<?php echo LIMETICKET_GUIPlugins::output("adminOverviewBottom"); ?>

<?php include JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'_powered.php'; ?>
<?php echo LIMETICKET_Helper::PageStyleEnd(); ?>