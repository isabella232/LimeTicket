<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class CalendarPlugin extends LIMETICKETCustFieldPlugin
{
	var $name = "Date Popup";
	var $min_popup_height = 260;
	
	var $default_params = array(
		'format' => '',
		'use_time' => 0,
		'today_default' => 0,
		'no_past' => 0
		);
	
	function DisplaySettings($params) // passed object with settings in
	{
		$values = $this->parseParams($params);

		ob_start();
		include JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'plugins'.DS.'custfield'.DS.'calendar'.DS.'form.php';
		$result = ob_get_clean();

		return $result;
	}
	
	function SaveSettings() // return object with settings in
	{
		return $this->encodeParams( array ( 
			'format'		=> LIMETICKET_Input::getString('cal_format'),
			'use_time'		=> LIMETICKET_Input::getInt('cal_use_time'),
			'today_default'		=> LIMETICKET_Input::getInt('cal_today_default'),
			'no_past'		=> LIMETICKET_Input::getInt('cal_no_past')
			));
	}

	function Input($current, $params, $context, $id) // output the field for editing
	{
		if (array_key_exists("custom_" . $id . "_raw", $_POST))
			$current = $_POST["custom_" . $id . "_raw"];
		
		$params = $this->parseParams($params);
		
		LIMETICKET_Helper::StylesAndJS(array('calendar'));

		$display = $current;
				
		if ($params->today_default && ($current == "" || $current == 0))
		{
			if ($params->use_time)
			{
				$current = date("Y-m-d H:i:s");	
			} else {
				$current = date("Y-m-d");	
			}
			
			// need to convert the date into cal format specified
			if ($params->format)
			{
				$display = date($this->DXtoPhpFormat($params->format),strtotime($current));
			} else {
				$display = $current;	
			}
		} else if ($current != "")
		{
			// needs adjusting by timezone here
			$display = date($this->DXtoPhpFormat($params->format),strtotime($current));
		}
		LIMETICKET_Translate_Helper::CalenderLocale();

		$output = "<input type='text' name='custom_$id' id='custom_$id' value='$display'>";
		$output .= "<input type='hidden' name='custom_{$id}_raw' id='custom_{$id}_raw' value='$current'>";
		$output .= "<script>";
		$output .= "
		jQuery(document).ready(function () {
			myCalendar = new dhtmlXCalendarObject('custom_$id');
			myCalendar.loadUserLanguage('" . LIMETICKET_Translate_Helper::CalenderLocaleCode()  . "');
			myCalendar.attachEvent('onTimeChange', function(d){
				document.getElementById('custom_$id').value = dhx4.date2str(d, this._dateFormat);
				";
		if ($params->use_time)
		{
			$output .= " var raw = this.getFormatedDate('%Y-%m-%d %H:%i:%s');\n";
		} else {
			$output .= " var raw = this.getFormatedDate('%Y-%m-%d');\n";
		}
		$output .= "
				jQuery('#custom_{$id}_raw').val(raw);
							});
			myCalendar.attachEvent('onClick',function(date){
				\n";
				
		if ($params->use_time)
		{
			$output .= " var raw = this.getFormatedDate('%Y-%m-%d %H:%i:%s');\n";
		} else {
			$output .= " var raw = this.getFormatedDate('%Y-%m-%d');\n";
		}
		$output .= "
				jQuery('#custom_{$id}_raw').val(raw);
			})
			";
			
		if ($params->no_past)
			$output .= "myCalendar.setSensitiveRange('". date("Y-m-d") . "', null);\n";
		
		
		if ($params->format)
		{
			$output .= "myCalendar.setDateFormat('{$params->format}');\n";
		}
		
		if (!$params->use_time)
		{
			$output .= "myCalendar.hideTime();\n";
		}
		
		$output .= "});";
		$output .= "</script>";
		return $output;
	}
	
	function DXtoPhpFormat($format)
	{
		return str_replace("%","",$format);	
	}
	
	function Display($value, $params, $context, $id) // output the field for display
	{
		if ($value == "")
			return "";
		
		$params = $this->parseParams($params);

		if ($params->format)
		{
			$date = JFactory::getDate($value);
			$value = $date->format($this->DXtoPhpFormat($params->format));
		}
		
		return $value;
	}
	
	function Save($id, $params, $value = "")
	{
		$value = LIMETICKET_Input::getString("custom_{$id}_raw");

		return $value;
	}

	function CanEdit()
	{
		return true;	
	}
}