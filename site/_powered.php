<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<div style="clear:both;"></div>

<?php if (!LIMETICKET_Settings::get('hide_powered')) : ?>
<div align="center" style="text-align:center;padding-top:20px;">
	<a href="http://www.limesurvey.org/">
		Powered by LimeTicket Support Portal
		<br>
		<img style="padding-top:2px;" border="0" src="<?php echo JURI::root( true ); ?>/components/com_limeticket/assets/images/logo_small.png"><br>
	</a>
</div>
<?php endif; ?>
