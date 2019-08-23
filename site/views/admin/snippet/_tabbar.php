<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

if (empty($this->view)) $this->view = LIMETICKET_Input::getCmd('view');
if (empty($this->layout)) $this->layout = LIMETICKET_Input::getCmd('layout');

?>

<ul class="nav nav-tabs limeticket_admin_tabbar">

	<?php if (LIMETICKET_Permission::auth("limeticket.handler", "com_limeticket.support_admin") || LIMETICKET_Permission::PermAnyContent()): ?>
		<li class="<?php if ($this->view == "admin") echo "active";?>">
			<a href='<?php echo LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=admin' );?>'>
				<img src='<?php echo JURI::root( true ); ?>/components/com_limeticket/assets/images/support/supportadmin_16.png'>
				<?php echo JText::_("OVERVIEW"); ?>
			</a> 
		</li>
	<?php endif; ?>

	<?php if (LIMETICKET_Permission::auth("limeticket.handler", "com_limeticket.support_admin")): ?>
		<li class="<?php if ($this->view == "admin_support") echo "active";?>">
			<a href='<?php echo LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=admin_support' );?>'>
				<img src='<?php echo JURI::root( true ); ?>/components/com_limeticket/assets/images/support/support_16.png'>
				<?php echo JText::_("SA_SUPPORT"); ?>
			</a> 
		</li>
	<?php endif; ?>

	<?php if (LIMETICKET_Permission::CanModerate()): ?>
		<li class="<?php if ($this->view == "admin_moderate") echo "active";?>">
			<a href='<?php echo LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=admin_moderate' );?>'>
				<img src='<?php echo JURI::root( true ); ?>/components/com_limeticket/assets/images/support/moderate_16.png'>
				<?php echo JText::_("SA_MODERATE"); ?>
			</a>
		</li>
	<?php endif; ?>

	<?php if (LIMETICKET_Permission::PermAnyContent()): ?>
		<li class="<?php if ($this->view == "admin_content") echo "active";?>">
			<a href='<?php echo LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=admin_content' );?>'>
				<img src='<?php echo JURI::root( true ); ?>/components/com_limeticket/assets/images/support/content_16.png'>
				<?php echo JText::sprintf("SA_CONTENT"); ?>
			</a>
		</li>
	<?php endif; ?>

	<?php if (LIMETICKET_Permission::AdminGroups()): ?>
		<li class="<?php if ($this->view == "admin_groups") echo "active";?>">
			<a href='<?php echo LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=admin_groups' );?>'>
				<img src='<?php echo JURI::root( true ); ?>/components/com_limeticket/assets/images/support/groups_16.png'>
				<?php echo JText::_("GROUPS"); ?>
			</a>
		</li>
	<?php endif; ?>

	<?php if (LIMETICKET_Permission::auth("limeticket.reports", "com_limeticket.reports")): ?>
		<li class="<?php if ($this->view == "admin_report") echo "active";?>">
			<a href='<?php echo LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=admin_report' );?>'>
				<img src='<?php echo JURI::root( true ); ?>/components/com_limeticket/assets/images/support/report_16.png'>
				<?php echo JText::_("REPORTS"); ?>
			</a>
		</li> 
	<?php endif; ?>

	<?php echo LIMETICKET_GUIPlugins::output("adminTabs"); ?>
</ul>
