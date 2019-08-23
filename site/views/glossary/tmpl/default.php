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
<?php echo LIMETICKET_Helper::PageTitle("GLOSSARY", $this->subtitle); ?>

<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'glossary'.DS.'snippet'.DS.'_search.php'); ?>
<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'glossary'.DS.'snippet'.DS.'_letter_bar.php'); ?>

<?php $letter = ""; $descid = 0; ?>

<dl class="limeticket_glossary_wordlist">
<?php foreach($this->rows as $glossary) : ?>
	<?php 
		$descid++;
		$thisletter = strtolower(substr($glossary->word,0,1)); 
		if ($thisletter != $letter)
		{
			$letter = $thisletter;
			echo "<a name='letter_$letter' ></a>";
		}
	?>
	<dt><a name='<?php echo LIMETICKET_Glossary::MakeAnchor($glossary->word); ?>'></a>
		<?php if ($this->long_desc == 1 && $glossary->longdesc != ""): ?>
			<a href="<?php echo str_replace("XXWWXX", urlencode($glossary->word), LIMETICKETRoute::_("index.php?option=com_limeticket&view=glossary&layout=word&word=XXWWXX", false)); ?>">
				<?php echo $glossary->word; ?>
			</a>
		<?php elseif ($this->long_desc == 3 && $glossary->longdesc != ""): ?>
			<a class="show_modal" href="<?php echo str_replace("XXWWXX", urlencode($glossary->word), LIMETICKETRoute::_("index.php?option=com_limeticket&view=glossary&layout=word&tmpl=component&word=XXWWXX", false)); ?>">
				<?php echo $glossary->word; ?>
			</a>
		<?php else: ?>
			<?php echo $glossary->word; ?>	
		<?php endif; ?>
</dt>
<dd>
		<?php echo $glossary->description; ?>
		<?php if ($this->long_desc < 1): ?>
			<?php echo $glossary->longdesc; ?>
		<?php elseif ($this->long_desc == 2 && $glossary->longdesc != ""): ?>
			<div style="text-align: right;">
				<a href='#' id='glo_toggle_<?php echo $descid; ?>'
					onclick='jQuery("#glo_more_<?php echo $descid; ?>").show();jQuery("#glo_toggle_<?php echo $descid; ?>").hide(); return false;'>
					<?php echo JText::_("GLOSSARY_READ_MORE"); ?>
				</a>
			</div>

			<div style="display: none" id='glo_more_<?php echo $descid; ?>'>
				<?php echo $glossary->longdesc; ?>
			</div>
		<?php elseif ($this->long_desc == 1 && $glossary->longdesc != ""): ?>
			<div style="text-align: right;">
				<a href="<?php echo LIMETICKETRoute::_("index.php?option=com_limeticket&view=glossary&layout=word&word=" . urlencode($glossary->id)); ?>">
					<?php echo JText::_("GLOSSARY_READ_MORE"); ?>
				</a>
			</div>
		<?php elseif ($this->long_desc == 3 && $glossary->longdesc != ""): ?>
			<div style="text-align: right;">
				<a class="show_modal" href="<?php echo LIMETICKETRoute::_("index.php?option=com_limeticket&view=glossary&layout=word&tmpl=component&word=" . urlencode($glossary->id)); ?>">
					<?php echo JText::_("GLOSSARY_READ_MORE"); ?>
				</a>
			</div>
		<?php endif; ?>
	</dd>

<?php endforeach; ?>
</dl>

<?php include JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'_powered.php'; ?>
<?php echo LIMETICKET_Helper::PageStyleEnd(); ?>