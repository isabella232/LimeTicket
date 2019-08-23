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


class FsssViewSettingsView extends JViewLegacy
{
	
	function display($tpl = null)
	{
		JHTML::_('behavior.modal');
		
		$document = JFactory::getDocument();
		$document->addScriptDeclaration("\nvar limeticket_settings_url = '" . JRoute::_('index.php?option=com_limeticket&view=settings', false) . "';\n");
		$document->addScript(JURI::root().'administrator/components/com_limeticket/assets/js/settings.js'); 

		$what = JRequest::getString('what','');
		$this->tab = JRequest::getVar('tab');
		
		if (JRequest::getVar('task') == "cancellist")
		{
			$mainframe = JFactory::getApplication();
			$link = LIMETICKETRoute::_('index.php?option=com_limeticket&view=limetickets',false);
			$mainframe->redirect($link);
			return;			
		}
		
		$settings = LIMETICKET_Settings::GetAllViewSettings(); // CHANGE
		$db	= JFactory::getDBO();

		if ($what == "save")
		{
			$data = JRequest::get('POST',JREQUEST_ALLOWRAW);

			foreach ($data as $setting => $value)
				if (array_key_exists($setting,$settings))
				{
					$settings[$setting] = $value;
				}
			
			foreach ($settings as $setting => $value)
			{
				if (!array_key_exists($setting,$data))
				{
					$settings[$setting] = 0;
					$value = 0;	
				}
				
				$qry = "REPLACE INTO #__limeticket_settings_view (setting, value) VALUES ('";
				$qry .= LIMETICKETJ3Helper::getEscaped($db, $setting) . "','";
				$qry .= LIMETICKETJ3Helper::getEscaped($db, $value) . "')";
				$db->setQuery($qry);$db->Query();
			}

			$link = 'index.php?option=com_limeticket&view=settingsview#' . $this->tab;
			
			if (JRequest::getVar('task') == "save")
				$link = 'index.php?option=com_limeticket';

			$mainframe = JFactory::getApplication();
			$mainframe->redirect($link, JText::_("View_Settings_Saved"));		
			exit;
		} else {
		
			$document = JFactory::getDocument();
			//$document->addStyleSheet(JURI::root().'administrator/components/com_limeticket/assets/css/js_color_picker_v2.css'); 
			//$document->addScript(JURI::root().'administrator/components/com_limeticket/assets/js/color_functions.js'); 
			//$document->addScript(JURI::root().'administrator/components/com_limeticket/assets/js/js_color_picker_v2.js'); 

			$this->settings = $settings;

			JToolBarHelper::title( JText::_("FREESTYLE_SUPPORT_PORTAL") .' - '. JText::_("VIEW_SETTINGS") , 'limeticket_viewsettings' );
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

}


