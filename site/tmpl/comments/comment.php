<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<?php 

if (!$this->parser)
{
	$this->parser = new LIMETICKETParser();
	$this->parser->loadTemplate($this->template,$this->template_type);
}

$this->parser->Clear();

$moderation = "";
if (LIMETICKET_Permission::CanModerate() && array_key_exists('id',$this->comment)) {
	$lbl_type = "success";
	if ($this->comment['published'] == 0)
		$lbl_type = "info";
	if ($this->comment['published'] == 2)
		$lbl_type = "warning";
	$moderation .= '<span class="pull-right label label-'.$lbl_type.'">';
	$show_tick = ""; 
	$show_cross = ""; 
	$show_delete = "";
	$show_edit = "";
	if ($this->comment['published'] == 1) 
	{
		$show_tick = "style='display: none'";
		$show_delete = "style='display: none'";
	} else if ($this->comment['published'] == 2) 
	{
		$show_cross = "style='display: none'";
	} else if ($this->comment['published'] == 0) 
	{
		$show_delete = "style='display: none'";
	}	
	if (!$this->opt_no_edit)	
		$moderation .= "<img id='limeticket_comment_{$this->uid}_{$this->comment['id']}_edit' {$show_edit} src='". JURI::root( true )."/components/com_limeticket/assets/images/edit_16.png' width='16' height='16' onclick='limeticket_edit_comment({$this->uid}, {$this->comment['id']})' style='cursor:pointer' title='".JText::_('EDIT_COMMENT')."'>";
	
	$moderation .= "<img id='limeticket_comment_{$this->uid}_{$this->comment['id']}_tick' {$show_tick} src='". JURI::root( true )."/components/com_limeticket/assets/images/save_16.png' width='16' height='16' onclick='limeticket_approve_comment({$this->uid}, {$this->comment['id']})' style='cursor:pointer' title='".JText::_('APPROVE_COMMENT')."' >";
	$moderation .= "<img id='limeticket_comment_{$this->uid}_{$this->comment['id']}_cross' {$show_cross} src='". JURI::root( true )."/components/com_limeticket/assets/images/cancel_16.png' width='16' height='16' onclick='limeticket_remove_comment({$this->uid}, {$this->comment['id']})' style='cursor:pointer' title='".JText::_('REMOVE_COMMENT')."'>";
	$moderation .= "<img id='limeticket_comment_{$this->uid}_{$this->comment['id']}_delete' {$show_delete} src='". JURI::root( true )."/components/com_limeticket/assets/images/delete_16.png' width='16' height='16' onclick='limeticket_delete_comment({$this->uid}, {$this->comment['id']})' style='cursor:pointer' title='".JText::_('DELETE_COMMENT')."'>";
	$moderation .= "</span>";
}

if (!$this->use_website)
	$this->comment['website'] = "";

if (!array_key_exists('custom',$this->comment))
	$this->comment['custom'] = array();

if (!is_array($this->comment['custom']))
	$this->comment['custom'] = unserialize($this->comment['custom']);

$custom = array();
if ($this->customfields)
{
	foreach($this->customfields as &$field)
	{
		if (array_key_exists('custom_' . $field['id'],$this->comment))
		{
			$val = $this->comment['custom_' . $field['id']];
			$this->parser->SetVar('custom_' . $field['id'], trim($val));
			$this->parser->SetVar('custom' . $field['id'], trim($val));
			if (strlen(trim($val)) > 0)
			{
				$custom[] =	$val;
			}
		}
		if (array_key_exists($field['id'],$this->comment['custom']))
		{
			$val = $this->comment['custom'][$field['id']];
			$this->comment['custom_' . $field['id']] = $val;
			$this->parser->SetVar('custom_' . $field['id'], trim($val));
			$this->parser->SetVar('custom' . $field['id'], trim($val));
			if (strlen(trim($val)) > 0)
			{
				$custom[] =	$val;
			}
		}
	}
}

if ($this->opt_max_length > 0 && strlen($this->comment['body']) > $this->opt_max_length)
{
	$randno = mt_rand(100000,999999);
	$result = array();
	$is_trimmed = false;
	$result[] = "<div id='test_short_".$randno."'>";
	$result[] = LIMETICKET_Helper::truncate($this->comment['body'], $this->opt_max_length, $is_trimmed, '');
	
	if ($is_trimmed)
	{
		$result[] = "&hellip; <a href='#' onclick='expand_test(" . $randno . ");return false;'>" . JText::_("MOD_LIMETICKET_TEST_READ_MORE") . "</a><div id='test_full_".$randno."' style='display:none'>" . $this->comment['body'] . "</div>";
		$result[] = "</div>";
		$this->comment['body'] = trim(implode($result));
	}
}

$this->comment['body'] = str_replace("\n","<br />",$this->comment['body']);

$this->comment['body'] = str_replace("\n","<br />",$this->comment['body']);
$this->parser->AddVars($this->comment);

$this->parser->SetVar('divid', "limeticket_comment_{$this->uid}_{$this->comment['id']}");

if (count($custom) > 0)
{
	$this->parser->SetVar('custom', implode(", ", $custom));	
} else {
	$this->parser->SetVar('custom', "");	
}

$this->parser->SetVar('created_nice', LIMETICKET_Helper::Date($this->comment['created'], LIMETICKET_DATETIME_SHORT));
$this->parser->SetVar('date', LIMETICKET_Helper::Date($this->comment['created'], LIMETICKET_DATE_SHORT));
$this->parser->SetVar('moderation',$moderation);

//print_p($this->comment);
//print_p($this->parser);

echo $this->parser->getTemplate();
		  	  			 	 		 