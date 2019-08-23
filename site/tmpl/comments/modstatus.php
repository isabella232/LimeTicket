<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<?php if (is_array($this->_moderatecounts)): ?>
	<div class="limeticket_moderate_status">
		<ul>
			<?php  foreach ($this->_moderatecounts as $ident => $count) : ?>
				<li>
					<?php echo $this->handlers[$ident]->GetDesc(); ?>: 
					<b><?php echo $count['count']; ?></b>
- 
					<a href="<?php echo LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=admin_moderate&ident=' . $ident ); ?>">
						<?php echo JText::_('VIEW_NOW'); ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
<?php endif; ?>
