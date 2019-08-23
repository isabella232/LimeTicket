<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<?php if (empty($catdepth)) $catdepth = 1; ?>
<div class='limeticket_kb_catlist' id='limeticket_kb_catlist'>
	<?php if (empty($this->hide_choose) && count($this->cats) > 0): ?>
		<?php //echo LIMETICKET_Helper::PageSubTitle("PLEASE_CHOOSE_A_CATEGORY"); ?>
	<?php endif; ?>
	
	<?php if ($this->main_cat_colums > 1): ?>
		<?php $colwidth = floor(100 / $this->main_cat_colums) . "%"; ?>
	
		<table width='100%' cellspacing="0" cellpadding="0">
		<?php $column = 1; ?>
		
		<?php foreach ($this->cats as &$cat) : ?>
			<?php 
				$curcatid = LIMETICKET_Input::getInt('catid'); 
				if ((int)$cat['parcatid'] != (int)$curcatid)
					continue;
			?>
			
	        <?php if ($column == 1) : ?>
	        	<tr><td width='<?php echo $colwidth; ?>' valign='top'>
	        <?php else: ?>
	        	<td width='<?php echo $colwidth; ?>' valign='top'>
	        <?php endif; ?>

			<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_cat.php'); ?>
			
		    <?php if ($column == $this->main_cat_colums): ?>
		            </td></tr>
		    <?php else: ?>
		        	</td>
		    <?php endif; ?>
		     
		    <?php        
		        $column++;
		        if ($column > $this->main_cat_colums)
		            $column = 1;
		    ?>
		<?php endforeach; ?>

		<?php	
		if ($column > 1)
		{ 
			while ($column <= $this->main_cat_colums)
			{
				echo "<td valign='top'><div></div></td>";	
				$column++;
			}
			echo "</tr>"; 
			$column = 1;
		}
		?>

		</table> 	
		
	<?php else: ?>
	
		<?php if ($this->cats): ?>
			<?php foreach ($this->cats as &$cat): ?>
				<?php 
						$curcatid = LIMETICKET_Input::getInt('catid'); 
					if ((int)$cat['parcatid'] != (int)$curcatid)
						continue;
				?>
				<?php include $this->snippet(JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'kb'.DS.'snippet'.DS.'_cat.php'); ?>
			<?php endforeach; ?>
		<?php endif; ?>
	<?php endif; ?>
</div>
