<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

?>
<h1>Import Data from existing system:</h1>

<div class="alert alert-danger alert-error">
<h4>WARNING: CLICKING ANY OF THE BELOW LINKS WILL REMOVE ALL EXISTING SUPPORT PORTAL DATA</h4>
</div>

<a class='btn btn-danger' href='<?php echo JRoute::_("index.php?option=com_limeticket&view=import&source=huru"); ?>'>Huru Help Desk</a>