<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>

<?php echo LIMETICKET_Helper::PageSubTitle("<a href='".LIMETICKETRoute::x( 'index.php?option=com_limeticket&view=admin_content' )."'><img src='". JURI::root( true ) ."/components/com_limeticket/assets/images/support/content_24.png'>&nbsp;" . JText::_("CONTENT"). "</a>",false); ?>

<?php if (LIMETICKET_Permission::PermAnyContent()): ?>
<?php echo LIMETICKET_Helper::PageSubTitle2("YOUR_ARTICLES"); ?>

<ul>
<?php foreach ($this->artcounts as $type): ?>
	<?php if (LIMETICKET_Permission::auth("core.edit.own", LIMETICKET_Admin_Helper::id_to_asset($type['id'])) || LIMETICKET_Permission::auth("core.edit", LIMETICKET_Admin_Helper::id_to_asset($type['id']))): ?>
		<li>
			<?php echo $type['desc']; ?>: <b><?php echo $type['counts']['user_total']; ?> </b> &nbsp;&nbsp;
			(<b><?php echo $type['counts']['user_pub']; ?></b> <?php echo JText::_('PUBLISHED'); ?>, 
			<b><?php echo $type['counts']['user_unpub']; ?></b> <?php echo JText::_('UNPUBLISHED'); ?>) - 
			<a href="<?php echo LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=admin_content&type=' . $type['id'] ); ?>"><?php echo JText::_('VIEW_NOW'); ?></a>
		</li>
	<?php endif; ?>
<?php endforeach; ?>
</ul>

<?php endif; ?>

<?php if (LIMETICKET_Permission::PermOthersContent()): ?>

	<?php echo LIMETICKET_Helper::PageSubTitle2("ALL_ARTICLES"); ?>
	<ul>
	<?php foreach ($this->artcounts as $type): ?>
		<?php if (LIMETICKET_Permission::auth("core.edit", LIMETICKET_Admin_Helper::id_to_asset($type['id']))): ?>
			<li>
				<?php echo $type['desc']; ?>: <b><?php echo $type['counts']['total']; ?></b> &nbsp;&nbsp;
				(<b><?php echo $type['counts']['pub']; ?></b> <?php echo JText::_('PUBLISHED'); ?>, 
				<b><?php echo $type['counts']['unpub']; ?></b> <?php echo JText::_('UNPUBLISHED'); ?>) - 
				<a href="<?php echo LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=admin_content&type=' . $type['id'] ); ?>"><?php echo JText::_('VIEW_NOW'); ?></a>
			</li>
		<?php endif; ?>
	<?php endforeach; ?>
	</ul>
<?php endif; ?>
	