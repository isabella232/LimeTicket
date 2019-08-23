<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<?php if (empty($this->_moderatecounts)) $this->_moderatecounts = array(); 
	if (count($this->_moderatecounts) > 0)
		foreach ($this->_moderatecounts as $ident => $count) : ?>
			<li>
				<a href="<?php echo LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=admin_moderate&ident=' . $ident ); ?>">
					<?php echo $this->handlers[$ident]->GetDesc(); ?> (<?php echo $count['count']; ?>)
				</a>
			</li>
<?php endforeach; ?>

<?php if (count($this->_moderatecounts) == 0): ?>
	<li>
		<?php echo JText::_("NO_COMMENTS_FOR_MOD"); ?>
	</li>
<?php endif; ?>
