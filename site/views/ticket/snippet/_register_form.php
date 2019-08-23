<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<?php if (JComponentHelper::getParams( 'com_users' )->get('allowUserRegistration')): ?>
	<?php echo LIMETICKET_Helper::PageSubTitle("REGISTER"); ?>
	<?php
		$return = LIMETICKET_Helper::getCurrentURLBase64();

		$register_url = LIMETICKETRoute::_("index.php?option=com_limeticket&view=login&layout=register&return=" . $return);
		
		if (property_exists($this, "return"))
			$register_url = LIMETICKETRoute::_("index.php?option=com_limeticket&view=login&layout=register&return=" . $this->return);
		
		if (JRequest::getVar('return'))
			$register_url = LIMETICKETRoute::_("index.php?option=com_limeticket&view=login&layout=register&return=" . JRequest::getVar('return'));

		if (LIMETICKET_Settings::get('support_custom_register'))
			$register_url = LIMETICKET_Settings::get('support_custom_register');
	?>	
	<p><?php echo JText::sprintf('IF_YOU_WOULD_LIKE_TO_CREATE_A_USER_ACCOUNT_PLEASE_REGISTER_HERE', $register_url); ?></p>
<?php endif; ?>