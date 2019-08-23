<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class LIMETICKET_Table
{
	static $cols;
	static $curcol;
	
	static function TableOpen()
	{
		LIMETICKET_Table::$curcol = 1;
		$fullwidth = "";
		if (LIMETICKET_Table::$cols > 1)
		$fullwidth = "width:100% !important;";
		
		echo "<table class='table table-borderless table-condensed table-narrow table-valign' style='min-width:300px;$fullwidth'>";
		
		if (LIMETICKET_Table::$cols > 1)
		{
			echo "<tr>";
			for ($i = 0 ; $i < LIMETICKET_Table::$cols ; $i++)
			{
				echo "<td colspan='2' width='" . floor(100 / LIMETICKET_Table::$cols) . "%'></td>";
			}
			echo "</tr>";
		}
	}

	static function TableClose()
	{
		echo "</table>";
	}

	static function ColStart($class = '')
	{
		if (LIMETICKET_Table::$curcol == 1)
		{
			echo "<tr class='$class'>";		
		} else {
			echo "";
		}
		LIMETICKET_Table::$curcol++;
	}

	static function ColEnd()
	{
		if (LIMETICKET_Table::$curcol > LIMETICKET_Table::$cols)
		{
			echo "</tr>";	
			LIMETICKET_Table::$curcol = 1;
		} else {
			
		}
	}
	
}