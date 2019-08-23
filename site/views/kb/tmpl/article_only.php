<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>

	<div id="kb_art_body">
		<?php 
		if (LIMETICKET_Settings::get( 'glossary_kb' )) {
			echo LIMETICKET_Glossary::ReplaceGlossary($this->art['body']); 
		} else {
			echo $this->art['body']; 
		}		
		?>
	</div>
