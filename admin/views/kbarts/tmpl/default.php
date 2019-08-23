<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>

<script type="text/javascript">

Joomla.submitbutton = function(pressbutton) {
	if (pressbutton == 'resetviews' || pressbutton == 'resetrating') {
		if (document.adminForm.boxchecked.value==0){
			alert('<?php echo JText::_('LIMETICKET_PLEASE_MAKE_A_SELECTION', true); ?>');
			return false;
		}
		if (confirm('<?php echo JText::_('LIMETICKET_ARE_YOU_SURE', true); ?>')){
			submitform( pressbutton );
		} 
	} else if (pressbutton == 'autosort') {
		if (confirm('<?php echo JText::_('This will sort your KB Articles alphabetically. It cannot be undone!', true); ?>')){
			submitform( pressbutton );
		} 
	} else {
		submitform( pressbutton );
	}
}

jQuery('li#toolbar-resetviews a').unbind('click');
jQuery('li#toolbar-resetviews a').attr('onclick','');
jQuery('li#toolbar-resetviews a').click(function () {
	if (document.adminForm.boxchecked.value==0){
		alert('<?php echo JText::_('LIMETICKET_PLEASE_MAKE_A_SELECTION', true); ?>');
		return false;
	}
	if (confirm('<?php echo JText::_('LIMETICKET_ARE_YOU_SURE', true); ?>')){
		submitform( 'resetviews' );
	} 
});

jQuery('li#toolbar-resetrating a').unbind('click');
jQuery('li#toolbar-resetrating a').attr('onclick','');
jQuery('li#toolbar-resetrating a').click(function () {
	if (document.adminForm.boxchecked.value==0){
		alert('<?php echo JText::_('LIMETICKET_PLEASE_MAKE_A_SELECTION', true); ?>');
		return false;
	}
	if (confirm('<?php echo JText::_('LIMETICKET_ARE_YOU_SURE', true); ?>')){
		submitform( 'resetrating' );
	} 
});

</script>

<form action="<?php echo LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=kbarts' );?>" method="post" name="adminForm" id="adminForm">
<?php $ordering = (strpos($this->lists['order'], "ordering") !== FALSE); ?>
<?php JHTML::_('behavior.modal'); ?>
<div id="editcell">
	<table>
		<tr>
			<td width="100%">
				<?php echo JText::_("FILTER"); ?>:
				<input type="text" name="search" id="search" value="<?php echo LIMETICKET_Helper::escape($this->lists['search']);?>" class="text_area" onchange="document.adminForm.submit();" title="<?php echo JText::_("FILTER_BY_TITLE_OR_ENTER_ARTICLE_ID");?>"/>
				<button class='btn btn-default' onclick="this.form.submit();"><?php echo JText::_("GO"); ?></button>
				<button class='btn btn-default' onclick="document.getElementById('search').value='';this.form.getElementById('kb_cat_id').value='0';this.form.getElementById('ispublished').value='-1';this.form.submit();"><?php echo JText::_("RESET"); ?></button>
			</td>
			<td nowrap="nowrap">
				<?php
				echo $this->lists['prods'];
				echo $this->lists['cats'];
				echo $this->lists['published'];
				?>
				<?php LIMETICKETAdminHelper::LA_Filter(); ?>
			</td>
		</tr>
	</table>

    <table class="adminlist table table-striped">
    <thead>

        <tr>
			<th width="5">#</th>
            <th width="20">
   				<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
			</th>
            <th>
                <?php echo JHTML::_('grid.sort',   'Title', 'title', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>           
			<th>
                <?php echo JHTML::_('grid.sort',   'Created', 'created', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
			<th>
                <?php echo JHTML::_('grid.sort',   'Modified', 'modified', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
            <th  class="title" width="8%" nowrap="nowrap">
                <?php echo JHTML::_('grid.sort',   'Category', 'cattitle', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
            <th width="1%" class="title" nowrap="nowrap">
                <?php echo JText::_("PRODUCTS"); ?>
            </th>
            <th width="1%" class="title" nowrap="nowrap">
                <?php echo JText::_("RATING"); ?>
            </th>
            <th width="1%" class="title" nowrap="nowrap">
                <?php echo JHTML::_('grid.sort',   'Views', 'views', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
            </th>
			<th width="1%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',   'Published', 'published', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
			</th>
 			<?php LIMETICKETAdminHelper::LA_Header($this); ?>
            <th width="<?php echo LIMETICKETJ3Helper::IsJ3() ? '130px' : '8%'; ?>">
				<?php echo JHTML::_('grid.sort',   'Order', 'ordering', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				<?php if ($ordering) echo JHTML::_('grid.order',  $this->data ); ?>
			</th>
		</tr>
    </thead>
    <?php
    $k = 0;
    for ($i=0, $n=count( $this->data ); $i < $n; $i++)
    {
        $row = $this->data[$i];
        $checked    = JHTML::_( 'grid.id', $i, $row->id );
        $link = LIMETICKETRoute::_( 'index.php?option=com_limeticket&controller=kbart&task=edit&cid[]='. $row->id );

    	$published = LIMETICKET_GetPublishedText($row->published);

        ?>
        <tr class="<?php echo "row$k"; ?>">
            <td>
                <?php echo $row->id; ?>
            </td>
           	<td>
   				<?php echo $checked; ?>
			</td>
			<td>
			    <a href="<?php echo $link; ?>"><?php echo $row->title; ?></a>
			</td>
            <td>
				<?php if ($row->created != "0000-00-00 00:00:00"): ?>
					<?php echo LIMETICKET_Helper::Date($row->created, LIMETICKET_DATETIME_MID); ?>
				<?php else: ?>
					-
				<?php endif; ?>
			</td>
			<td>
                <?php if ($row->modified != "0000-00-00 00:00:00"): ?>
					<?php echo LIMETICKET_Helper::Date($row->modified, LIMETICKET_DATETIME_MID); ?>
				<?php else: ?>
					-
				<?php endif; ?>
            </td>
            <td>
                <?php echo $row->cattitle ? $row->cattitle : "Uncategorized" ; ?>
            </td>
            <td align='center'>
				<?php if ($row->allprods) { ?>
					<?php echo JText::_("ALL"); ?>
				<?php } else { ?>
				<?php $link = LIMETICKETRoute::_('index.php?option=com_limeticket&tmpl=component&controller=kbart&view=kbart&task=prods&kb_art_id=' . $row->id); ?>
					<a class="modal" title="<?php echo JText::_("VIEW"); ?>"  href="<?php echo $link; ?>" rel="{handler: 'iframe', size: {x: 400, y: 300}}"><?php echo JText::_("VIEW"); ?></a>
				<?php } ?>
			</td>
			<td align='center' nowrap>
			
				<?php if ($row->rating == "") $row->rating = "0"; ?>
				<?php if ($row->ratingdetail == "") $row->ratingdetail = "0|0|0"; ?>   
                <?php list($rate_up,$rate_same,$rate_down) = explode("|",$row->ratingdetail) ; ?>
                <?php if ($rate_up == "") $rate_up = 0; ?>
                <?php if ($rate_same == "") $rate_same = 0; ?>
                <?php if ($rate_down == "") $rate_down = 0; ?>
                <?php 
					$output = "<div style=\"font-size:160%\">";
                $output .= "&nbsp;&nbsp;<img src=\"". JURI::base() . "../components/com_limeticket/assets/images/rate_up.png\" width=\"16\" height=\"16\" />&nbsp;" . $rate_up . "&nbsp;&nbsp;";
                $output .= "&nbsp;&nbsp;<img src=\"". JURI::base() . "../components/com_limeticket/assets/images/rate_same.png\" width=\"16\" height=\"16\" />&nbsp;" . $rate_same . "&nbsp;&nbsp;";
                $output .= "&nbsp;&nbsp;<img src=\"". JURI::base() . "../components/com_limeticket/assets/images/rate_down.png\" width=\"16\" height=\"16\" />&nbsp;" . $rate_down . "&nbsp;&nbsp;";
                /*//$output .= '<img src=\"<?php echo JURI::base(); ?>/components/com_limeticket/assets/images/rate_same.png\" width=\"16\" height=\"16\" />$rate_same';
                //$output .= '<img src=\"<?php echo JURI::base(); ?>/components/com_limeticket/assets/images/rate_down.png\" width=\"16\" height=\"16\" />$rate_down';
					//$output = htmlentities($output);*/
					$output .= "</div>";
                ?>
				<a href='#' onclick="return false;" class='limeticketTip limeticket_highlight' title='Rating Details::<?php echo $output ?>'>
					<?php echo $row->rating; ?>
				</a>
 
            </td>
        	<td align="center">
				<?php echo $row->views; ?>
			</td>
			<td align="center">
				<a href="javascript:void(0);" class="jgrid btn btn-micro" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $row->published ? 'unpublish' : 'publish' ?>')">
					<?php echo $published; ?>
				</a>
			</td>
			<?php LIMETICKETAdminHelper::LA_Row($row); ?>
			<td class="order">
			<?php if ($ordering) : ?>
				<span><?php echo $this->pagination->orderUpIcon( $i ); ?></span>
				<span><?php echo $this->pagination->orderDownIcon( $i, $n ); ?></span>
			<?php endif; ?>
				<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" <?php if (!$ordering) {echo 'disabled="disabled"';} ?> class="text_area" style="text-align: center" />
			</td>
		</tr>
        <?php
        $k = 1 - $k;
    }
    ?>

    </table>
</div>

<?php if (LIMETICKETJ3Helper::IsJ3()): ?>
	<div class='pull-right'>
		<?php echo $this->pagination->getLimitBox(); ?>
</div>
<?php endif; ?>
<?php echo $this->pagination->getListFooter(); ?>

<input type="hidden" name="option" value="com_limeticket" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="kbart" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>
