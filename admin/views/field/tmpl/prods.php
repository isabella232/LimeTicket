<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<h1><?php echo JText::_("PRODUCTS_SELECTED_FOR_FIELD"); ?> <?php echo $this->field->description; ?></h1>
<?php

foreach ($this->products as $product)
{
		echo "<h3>".$product->title ."</h3>";
}

if (count($this->products) == 0)
	echo "<h3>None Selected</h3>";
