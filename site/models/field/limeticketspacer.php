<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

JFormHelper::loadFieldClass('text');

/**
 * For displaying either a HTML text based on what is configured as part of the form
 * or will display the value of the form in a box.
 **/


class JFormFieldLIMETICKETSpacer extends JFormFieldText
{
	protected $type = 'LIMETICKETSpacer';
	
	function __construct()
	{
		parent::__construct();
	}
	
	protected function getInput()
	{
		$height = $this->element['limeticketspacer_height'];
		
		if (!$height)
		$height = 250;
		
		return "<div style='height:" . $height . "px;'></div>";
	}
}
