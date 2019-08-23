<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

require_once( JPATH_COMPONENT.DS.'helper'.DS.'glossary.php' );

?>
<?php echo LIMETICKET_Helper::PageStyle(); ?>
<?php echo LIMETICKET_Helper::PageTitle("GLOSSARY", $this->glossary->word); ?>

<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'glossary'.DS.'snippet'.DS.'_letter_bar.php'); ?>

<dl class="limeticket_glossary_wordlist">
	<dt>
		<?php echo $this->glossary->word; ?>	
	</dt>
	<dd>
		<?php echo $this->glossary->description; ?>
		<?php echo $this->glossary->longdesc; ?>
	</dd>
</dl>

<?php include JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'_powered.php'; ?>
<?php echo LIMETICKET_Helper::PageStyleEnd(); ?>