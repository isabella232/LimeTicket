<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;


jimport( 'joomla.application.component.view' );
require_once (JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_limeticket'.DS.'settings.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'parser.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'fields.php');


class FsssViewTemplates extends JViewLegacy
{
	
	function display($tpl = null)
	{
		JHTML::_('behavior.modal');

		$document = JFactory::getDocument();
		$document->addScriptDeclaration("\nvar limeticket_settings_url = '" . JRoute::_('index.php?option=com_limeticket&view=settings', false) . "';\n");
		$document->addScript(JURI::root().'administrator/components/com_limeticket/assets/js/settings.js'); 
		
		if (JRequest::getVar('task') == "cancellist")
		{
			$mainframe = JFactory::getApplication();
			$link = LIMETICKETRoute::_('index.php?option=com_limeticket&view=limetickets',false);
			$mainframe->redirect($link);
			return;			
		}

		$what = JRequest::getString('what','');
		$this->tab = JRequest::getVar('tab');
		
		$settings = LIMETICKET_Settings::GetAllSettings();
		$db	= JFactory::getDBO();
		
		if ($what == "testref")
		{
			return $this->TestRef();
		} else if ($what == "save")
		{

			$large = LIMETICKET_Settings::GetLargeList();
			$templates = LIMETICKET_Settings::GetTemplateList();
			$intpltable = LIMETICKET_Settings::StoreInTemplateTable();
			
			// save support custom setting
			$head = JRequest::getVar('support_list_head', '', 'post', 'string', JREQUEST_ALLOWRAW);
			$row = JRequest::getVar('support_list_row', '', 'post', 'string', JREQUEST_ALLOWRAW);

			$qry = "REPLACE INTO #__limeticket_templates (template, tpltype, value) VALUES ('custom', 1, '" . LIMETICKETJ3Helper::getEscaped($db, $head) . "')";
			$db->setQuery($qry);$db->Query();
			$qry = "REPLACE INTO #__limeticket_templates (template, tpltype, value) VALUES ('custom', 0, '" . LIMETICKETJ3Helper::getEscaped($db, $row) . "')";
			$db->setQuery($qry);$db->Query();

			unset($_POST['support_list_head']);
			unset($_POST['support_list_row']);
			
			$head = JRequest::getVar('support_user_head', '', 'post', 'string', JREQUEST_ALLOWRAW);
			$row = JRequest::getVar('support_user_row', '', 'post', 'string', JREQUEST_ALLOWRAW);

			$qry = "REPLACE INTO #__limeticket_templates (template, tpltype, value) VALUES ('usercustom', 1, '" . LIMETICKETJ3Helper::getEscaped($db, $head) . "')";
			$db->setQuery($qry);$db->Query();
			$qry = "REPLACE INTO #__limeticket_templates (template, tpltype, value) VALUES ('usercustom', 0, '" . LIMETICKETJ3Helper::getEscaped($db, $row) . "')";
			$db->setQuery($qry);$db->Query();

			unset($_POST['support_user_head']);
			unset($_POST['support_user_row']);
			
			// save templates
			$intpltable = LIMETICKET_Settings::StoreInTemplateTable();
			foreach($intpltable as $template)
			{
				$value = JRequest::getVar($template, '', 'post', 'string', JREQUEST_ALLOWRAW);
				$qry = "REPLACE INTO #__limeticket_templates (template, tpltype, value) VALUES ('".LIMETICKETJ3Helper::getEscaped($db, $template)."', 2, '".LIMETICKETJ3Helper::getEscaped($db, $value)."')";
				$db->setQuery($qry);
				$db->Query();
			}
		
			// large settings
			foreach($large as $setting)
			{
				if (!array_key_exists($setting,$templates))
					continue;
	
				$value = JRequest::getVar($setting, '', 'post', 'string', JREQUEST_ALLOWRAW);
				$qry = "REPLACE INTO #__limeticket_settings_big (setting, value) VALUES ('";
				$qry .= LIMETICKETJ3Helper::getEscaped($db, $setting) . "','";
				$qry .= LIMETICKETJ3Helper::getEscaped($db, $value) . "')";
				//echo $qry."<br>";
				$db->setQuery($qry);$db->Query();

				$qry = "DELETE FROM #__limeticket_settings WHERE setting = '".LIMETICKETJ3Helper::getEscaped($db, $setting)."'";
				//echo $qry."<br>";
				$db->setQuery($qry);$db->Query();

				unset($_POST[$setting]);
			}		
			
			
			
			$data = JRequest::get('POST',JREQUEST_ALLOWRAW);

			foreach ($data as $setting => $value)
				if (array_key_exists($setting,$settings))
					$settings[$setting] = $value;
			
			foreach ($settings as $setting => $value)
			{
				if (!array_key_exists($setting,$data))
				{
					$settings[$setting] = 0;
					$value = 0;	
				}
				
				if (!array_key_exists($setting,$templates))
					continue;

				if (array_key_exists($setting,$large))
					continue;

				$qry = "REPLACE INTO #__limeticket_settings (setting, value) VALUES ('";
				$qry .= LIMETICKETJ3Helper::getEscaped($db, $setting) . "','";
				$qry .= LIMETICKETJ3Helper::getEscaped($db, $value) . "')";
				$db->setQuery($qry);$db->Query();
				//echo $qry."<br>";
			}

			//exit;
			$link = 'index.php?option=com_limeticket&view=templates#' . $this->tab;
			
			if (JRequest::getVar('task') == "save")
				$link = 'index.php?option=com_limeticket';

			$mainframe = JFactory::getApplication();
			$mainframe->redirect($link, JText::_("Settings_Saved"));		
			exit;
		} else if ($what == "customtemplate") {
			$this->CustomTemplate();
			exit;	
		} else {
			
			// load other templates
			$intpltable = LIMETICKET_Settings::StoreInTemplateTable();
			$tpls = array();
			foreach($intpltable as $template)
			{
				$settings[$template] = '';
				$settings[$template. '_default'] = '';
				$tpls[] = LIMETICKETJ3Helper::getEscaped($db, $template);
			}
			$tpllist = "'" . implode("', '", $tpls) . "'";
			$qry = "SELECT * FROM #__limeticket_templates WHERE template IN ($tpllist)";
			$db->setQuery($qry);
			$rows = $db->loadAssocList();
			if (count($rows) > 0)
			{	
				foreach ($rows as $row)
				{
					if ($row['tpltype'] == 2)
					{
						$settings[$row['template']] = $row['value'];
					} else if ($row['tpltype'] == 3) {
						$settings[$row['template'] . '_default'] = $row['value'];
					}
				}
			}

			
			// load ticket template stuff
			$qry = "SELECT * FROM #__limeticket_templates WHERE template = 'custom'";
			$db->setQuery($qry);
			$rows = $db->loadAssocList();
			if (count($rows) > 0)
			{	
				foreach ($rows as $row)
				{
					if ($row['tpltype'] == 1)
					{
						$settings['support_list_head'] = $row['value'];
					} else if ($row['tpltype'] == 0) {
						$settings['support_list_row'] = $row['value'];
					}
				}
			} else {
				$settings['support_list_head'] = '';
				$settings['support_list_row'] = '';
			}
			
			$qry = "SELECT * FROM #__limeticket_templates WHERE template = 'usercustom'";
			$db->setQuery($qry);
			$rows = $db->loadAssocList();
			if (count($rows) > 0)
			{	
				foreach ($rows as $row)
				{
					if ($row['tpltype'] == 1)
					{
						$settings['support_user_head'] = $row['value'];
					} else if ($row['tpltype'] == 0) {
						$settings['support_user_row'] = $row['value'];
					}
				}
			} else {
				$settings['support_user_head'] = '';
				$settings['support_user_row'] = '';
			}
			
			$qry = "SELECT * FROM #__limeticket_templates WHERE tpltype = 2";
			$db->setQuery($qry);
			$rows = $db->loadAssocList();
			if (count($rows) > 0)
			{	
				foreach ($rows as $row)
				{
					$settings[$row['template']] = $row['value'];
				}
			}

			$document = JFactory::getDocument();
			//$document->addStyleSheet(JURI::root().'administrator/components/com_limeticket/assets/css/js_color_picker_v2.css'); 
			//$document->addScript(JURI::root().'administrator/components/com_limeticket/assets/js/color_functions.js'); 
			//$document->addScript(JURI::root().'administrator/components/com_limeticket/assets/js/js_color_picker_v2.js'); 

			$document->addScript(JURI::root().'administrator/components/com_limeticket/assets/js/codemirror/codemirror.js'); 
			$document->addScript(JURI::root().'administrator/components/com_limeticket/assets/js/codemirror/modes/css/css.js'); 
			$document->addScript(JURI::root().'administrator/components/com_limeticket/assets/js/codemirror/modes/javascript/javascript.js'); 
			$document->addScript(JURI::root().'administrator/components/com_limeticket/assets/js/codemirror/modes/xml/xml.js'); 
			$document->addScript(JURI::root().'administrator/components/com_limeticket/assets/js/codemirror/modes/htmlmixed/htmlmixed.js'); 
			$document->addStyleSheet(JURI::root().'administrator/components/com_limeticket/assets/css/codemirror/codemirror.css'); 

			$this->settings = $settings;

			JToolBarHelper::title( JText::_("FREESTYLE_SUPPORT_PORTAL") .' - '. JText::_("TEMPLATES") , 'limeticket_templates' );
			JToolBarHelper::apply();
			JToolBarHelper::save();
			JToolBarHelper::cancel('cancellist');
			LIMETICKETAdminHelper::DoSubToolbar();
			parent::display($tpl);
		}
	}

	function ParseParams(&$aparams)
	{
		$out = array();
		$bits = explode(";",$aparams);
		foreach ($bits as $bit)
		{
			if (trim($bit) == "") continue;
			$res = explode(":",$bit,2);
			if (count($res) == 2)
			{
				$out[$res[0]] = $res[1];	
			}
		}
		return $out;	
	}

	function CustomTemplate()
	{
		$template = JRequest::getVar('name');
		$db	= JFactory::getDBO();
		$qry = "SELECT * FROM #__limeticket_templates WHERE template = '" . LIMETICKETJ3Helper::getEscaped($db, $template) . "'";
		$db->setQuery($qry);
		$rows = $db->loadAssocList();
		$output = array();
		foreach ($rows as $row)
		{
			if ($row['tpltype'])
			{
				$output['head'] = $row['value'];
			} else {
				$output['row'] = $row['value'];
			}
		}
		echo json_encode($output);
		exit;	
	}
}


