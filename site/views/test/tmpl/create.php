<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>

<?php echo $this->tmpl ? LIMETICKET_Helper::PageStylePopup(true) : LIMETICKET_Helper::PageStyle(); ?>


	<?php echo $this->tmpl ? LIMETICKET_Helper::PageTitlePopup("TESTIMONIALS","ADD_A_TESTIMONIAL") : LIMETICKET_Helper::PageTitle("TESTIMONIALS","ADD_A_TESTIMONIAL"); ?>
	<div class='limeticket_kb_comment_add' id='add_comment'>
		<?php $this->comments->DisplayAdd(); ?>
	</div>

	<div id="comments"></div>

	<div class='limeticket_comments_result_<?php echo $this->comments->uid; ?>'></div>

<?php $this->comments->IncludeJS() ?>

<?php echo $this->tmpl ? LIMETICKET_Helper::PageStylePopupEnd() : LIMETICKET_Helper::PageStyleEnd(); ?>