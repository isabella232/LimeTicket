<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<div class="limeticket_main" id="limeticket_modal_container" style="display: none;">
	<div class="modal limeticket_modal <?php /*if (!LIMETICKET_Settings::get('bootstrap_v3')) echo 'hide';*/ ?>" id="limeticket_modal" style='display: none'>
	</div>

	<div class="modal limeticket_modal <?php /*if (!LIMETICKET_Settings::get('bootstrap_v3')) echo 'hide';*/ ?>" id="limeticket_modal_base" style='display: none'>
	  <div class="modal-header">
		<button class="close simplemodal-close" data-dismiss="modal">&times;</button>
		<h3><?php echo JText::_('PLEASE_WAIT'); ?></h3>
	  </div>
	  <div class="modal-body">
		<p class="center">
			<img src="<?php echo JURI::root( true ); ?>/components/com_limeticket/assets/images/ajax-loader.gif">	 
		</p>
	  </div>
	  <div class="modal-footer">
		<a href="#" class="btn btn-default" onclick="limeticket_modal_hide(); return false;"><?php echo JText::_('JCANCEL'); ?></a>
	  </div>
	</div>
</div>
	    	 	    			