<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>

<ul class="nav nav-tabs">
	<?php if (LIMETICKET_Permission::auth("core.edit.own", "com_limeticket.announce") || LIMETICKET_Permission::auth("core.edit", "com_limeticket.announce")): ?>
		<li class="<?php if ($this->type == "announce") echo "active";?>">
			<a href='<?php echo LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=admin_content&type=announce' ); ?>'>
				<?php echo JText::_("ANNOUNCEMENTS"); ?>
			</a>
		</li>
	<?php endif; ?>
	
	<?php if (LIMETICKET_Permission::auth("core.edit.own", "com_limeticket.kb") || LIMETICKET_Permission::auth("core.edit", "com_limeticket.kb")): ?>
		<li class="<?php if ($this->type == "kb") echo "active";?>">
			<a href='<?php echo LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=admin_content&type=kb' ); ?>'>
				<?php echo JText::_("KB_ARTICLES"); ?>
			</a> 
		</li>
	<?php endif; ?>
	
	<?php if (LIMETICKET_Permission::auth("core.edit.own", "com_limeticket.faq") || LIMETICKET_Permission::auth("core.edit", "com_limeticket.faq")): ?>
		<li class="<?php if ($this->type == "faqs") echo "active";?>">
			<a href='<?php echo LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=admin_content&type=faqs' ); ?>'>
				<?php echo JText::_("FAQS"); ?>
			</a> 
		</li>	
	<?php endif; ?>
	
	<?php if (LIMETICKET_Permission::auth("core.edit.own", "com_limeticket.glossary") || LIMETICKET_Permission::auth("core.edit", "com_limeticket.glossary")): ?>
		<li class="<?php if ($this->type == "glossary") echo "active";?>">
			<a href='<?php echo LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=admin_content&type=glossary' ); ?>'>
				<?php echo JText::_("GLOSSARY"); ?>
			</a> 
		</li>	
	<?php endif; ?>
</ul>
