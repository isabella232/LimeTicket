<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<div class='limeticket_main'>
<form action="<?php echo LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=cronlog' );?>" method="post" name="adminForm" id="adminForm">
<p class="limeticket_admin_header">

	Task: <?php echo $this->tasks; ?>
	Date: <?php echo $this->dates; ?>

	<button class="btn btn-default" onclick="this.form.submit();"><?php echo JText::_("GO"); ?></button>
	<button class="btn btn-default" onclick="this.form.getElementById('taskname').value='';this.form.getElementById('date').value='';this.form.submit();"><?php echo JText::_("RESET"); ?></button>
	<button class="btn btn-default" onclick="return ClearCron();">Clear Cron Log</button>

</p>
<input type="hidden" name="task" value="" id="task">
<input type="hidden" name="page" value="<?php echo $this->page; ?>" id="page">

<?php if (count($this->rows) == 0): ?>
	<div>No log data found</div>
<?php else :?>

		<table class="table table-striped table-condensed table-bordered" style="width:40%; float:left;">
			<tr>
				<th align="left">Task</th>
				<th align="left">When</th>
				<th align="left">Title</th>
			</tr>
<?php foreach ($this->rows as $row): ?>
			<tr id="row_<?php echo $row->id; ?>" onmouseenter="showRow(<?php echo $row->id; ?>);" class='hlrow'>
				<td nowrap><?php echo $row->cron ?></td>
				<td nowrap><?php echo LIMETICKET_Helper::Date($row->when,LIMETICKET_DATETIME_MYSQL); ?></td>
				<td><?php echo $row->title; ?></td>
				<td id="entry_<?php echo $row->id; ?>" style='display: none'><?php echo $row->log; ?></td>
				<td id="data_<?php echo $row->id; ?>" style='display: none'>
					<?php echo $this->outputData($row); ?>
				</td>
			</tr>
<?php endforeach; ?>
		</table>

<div id="current_log" style="display: none;"></div>

<div style="clear: both;"></div>

	<?php if ($this->pagecount > 1) : ?>
	<div class="pagination">
		<ul>
		<?php for ($i = 0; $i < $this->pagecount; $i++) : ?>
			<?php if ($i == $this->page) :?>
				<li class="active"><a><?php echo ($i+1); ?></a></li>
			<?php else: ?>
				<li><a href="#" onclick='SetPage(<?php echo $i; ?>); return false;'><?php echo ($i+1); ?></a></li>
			<?php endif; ?>
		<?php endfor; ?>
		</ul>
	</div>
	<?php endif; ?>
<?php endif; ?>
<script>

function ClearCron()
{
	if (confirm('Are you sure? This cannot be undone.'))
	{
		document.getElementById('task').value='clear';
		return true;
	}

	return false;
}

function SetPage(page)
{
	document.getElementById('page').value=page;
	document.getElementById('adminForm').submit();
}

function showRow(rowid)
{
	jQuery('#current_log').html(jQuery('#entry_' + rowid).html());
	jQuery('#current_log').append("<hr />");
	jQuery('#current_log').append(jQuery('#data_' + rowid).html());
	jQuery('#current_log').show();
}

</script>

<style>
#current_log {
	position: fixed;
	bottom: 38px;
	border:1px solid #ccc;
	border-radius: 5px;
	padding: 5px 4px;
	left: 41%;
	top: 168px;
	right: 6px;
	overflow-y: auto;
}
.hlrow {
	cursor: pointer;
}

.hlrow:hover td {
	background-color: #f3f3ff !important;
}
</style>

</div>