<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class LIMETICKET_Admin_Permissions
{
	static function prep_custom_rules($form, $field_id, $type, $key, $label)
	{
		$xml = '<field name="'.$field_id.'" type="limeticketrules" label=""
        translate_label="false" class="inputbox" filter="rules"
        component="com_limeticket" section="" validate="rules" tab_id="' . $label . '">';
		
		switch ($type)
		{
			case 'products':
				$items = SupportHelper::getProducts();
				break;
			case 'departments':
				$items = SupportHelper::getDepartments();
				break;
			case 'categories':
				$items = SupportHelper::getCategories();
				break;
			case 'reports':
				$xml .= '<action name="limeticket.reports" title="VIEW_REPORTS" description="" />';
				$xml .= '<action name="limeticket.reports.all" title="VIEW_ALL_REPORTS" description="" />';

				$items = self::getReports();
				break;
		}
		
		$first = true;
		foreach ($items as $item)
		{
			if ($type == "reports" && $first)
			{
				$xml .= '<action name="'.$key.'.'.htmlspecialchars($item->id).'" title="' . htmlspecialchars($item->title). '" description="" heading="Reports" />';
				$first = false;	
			} else {
				$xml .= '<action name="'.$key.'.'.htmlspecialchars($item->id).'" title="' . htmlspecialchars($item->title). '" description="" />';
			}
		}
		
		$xml .= '</field>';
		
		$field = new JFormFieldRules();
		
		
		$field = $form->getField("$field_id"); 
	
		$field->setup(simplexml_load_string($xml), $form->getValue($field_id));
		
		return $field;
	}
	
	static function getReports()
	{
		require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'views'.DS.'admin_report'.DS.'view.html.php');
		
		$rep = new FssViewAdmin_Report();
		$reports = $rep->GetReports();
		
		$lang = JFactory::getLanguage();
		foreach ($reports as $report)
		{
			$lang->load("report_" . $report->name . ".sys", JPATH_SITE.DS.'components'.DS.'com_limeticket');
			$report->id = $report->name;	
		}
		
		return $reports;
	}
}