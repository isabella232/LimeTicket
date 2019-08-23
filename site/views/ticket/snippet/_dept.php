<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<li id='dept_cont_<?php echo $dept->id; ?>'	class="media  highlight department">
	<div class="pull-left">
		<?php if ($dept->image) : ?>
			<img class="media-object pointer" onclick="jQuery('#deptid').val('<?php echo $dept->id; ?>'); jQuery('#deptselect').submit(); return false;" src="<?php echo JURI::root( true ); ?>/images/limeticket/departments/<?php echo LIMETICKET_Helper::escape($dept->image); ?>">
		<?php endif; ?>
	</div>
	<div class="media-body">
		<h4 class="media-heading"><a href="#" onclick="jQuery('#deptid').val('<?php echo $dept->id; ?>'); jQuery('#deptselect').submit(); return false;"><?php echo $dept->title ?></a></h4>
		<?php echo $dept->description; ?>
	</div>
</li>
