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
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'tickethelper.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'fields.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'mailer.php');

class LimeticketsViewSettings extends JViewLegacy
{
	
	function display($tpl = null)
	{
		LIMETICKET_CSSParse::OutputCSS('components/com_limeticket/assets/css/bootstrap/bootstrap_limeticketonly.less');
		
		$document = JFactory::getDocument();
		$document->addScriptDeclaration("\nvar limeticket_settings_url = '" . JRoute::_('index.php?option=com_limeticket&view=settings', false) . "';\n");
		$document->addScript(JURI::root().'administrator/components/com_limeticket/assets/js/settings.js'); 
		LIMETICKET_Helper::StylesAndJS(array('csstest'));

		JHTML::_('behavior.modal');

		$what = JRequest::getString('what','');
		$this->tab = JRequest::getVar('tab');

		if (JRequest::getVar('task') == "cancellist")
		{
			$mainframe = JFactory::getApplication();
			$link = LIMETICKETRoute::_('index.php?option=com_limeticket&view=limetickets',false);
			$mainframe->redirect($link);
			return;			
		}
		
		$settings = LIMETICKET_Settings::GetAllSettings();
		$db	= JFactory::getDBO();
		
		if ($what == "testref")
		{
			return $this->TestRef();
		} else if ($what == "testdates")
		{
			return $this->testdates();
		} else if ($what == "send_test_email")
		{
			return $this->test_email_send();
		} else if ($what == "save")
		{
			// auto close settings
			{
				$support_autoclose = JRequest::getInt('support_autoclose');	
				$support_autoclose_duration = JRequest::getInt('support_autoclose_duration');	
				$support_autoclose_audit = JRequest::getInt('support_autoclose_audit');	
				$support_autoclose_email = JRequest::getInt('support_autoclose_email');	

				$aparams = "addaudit:$support_autoclose_audit;emailuser:$support_autoclose_email;closeinterval:$support_autoclose_duration;";
				$qry = "UPDATE #__limeticket_cron SET params = '" . LIMETICKETJ3Helper::getEscaped($db, $aparams) . "', published = $support_autoclose, `interval` = 5 WHERE class = 'AutoClose'";
				$db->setQuery($qry);
				//echo $qry."<br>";
				$db->Query();

				unset($_POST['support_autoclose']);
				unset($_POST['support_autoclose_duration']);
				unset($_POST['support_autoclose_audit']);
				unset($_POST['support_autoclose_email']);
			}

			$_POST['email_send_smtp_username'] = $_POST['email_send_smtp_un'];
			$_POST['email_send_smtp_password'] = $_POST['email_send_smtp_pw'];
			
			$large = LIMETICKET_Settings::GetLargeList();
			$templates = LIMETICKET_Settings::GetTemplateList();
			
			// save any large settings that arent in the templates list				
			foreach($large as $setting)
			{
				// skip any setting that is in the templates list
				if (array_key_exists($setting,$templates))
					continue;
	
				// 
				$value = JRequest::getVar($setting, '', 'post', 'string', JREQUEST_ALLOWRAW);
				$qry = "REPLACE INTO #__limeticket_settings_big (setting, value) VALUES ('";
				$qry .= LIMETICKETJ3Helper::getEscaped($db, $setting) . "','";
				$qry .= LIMETICKETJ3Helper::getEscaped($db, $value) . "')";
				$db->setQuery($qry);$db->Query();

				$qry = "DELETE FROM #__limeticket_settings WHERE setting = '".LIMETICKETJ3Helper::getEscaped($db, $setting)."'";
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
				
				// skip any setting that is in the templates list
				if (array_key_exists($setting,$templates))
					continue;

				if (array_key_exists($setting,$large))
					continue;

				$qry = "REPLACE INTO #__limeticket_settings (setting, value) VALUES ('";
				$qry .= LIMETICKETJ3Helper::getEscaped($db, $setting) . "','";
				$qry .= LIMETICKETJ3Helper::getEscaped($db, $value) . "')";
				$db->setQuery($qry);$db->Query();
				//echo $qry."<br>";
			}
			
			LIMETICKET_Settings::reload();
			
			$msg = JText::_("Settings_Saved");
			$msgtytpe = "message";
			
			if (!LIMETICKET_CSSParse::ParseStaticFiles())
			{
				$msg = "<p>LimeTicket Support Portal cannot write to the following files:</p>";	
				$msg .= "<ul>";
				
				foreach (LIMETICKET_CSSParse::$failed as $file)
				{
					$msg .= "<li>" . $file . "</li>";
				}

				$msg .= "</ul>";
				$msg .= "<p>Without this it may not display correctly on your site.</p>";
				
				$msgtytpe = "error";
			}
			
			$link = 'index.php?option=com_limeticket&view=settings#' . $this->tab;
			
			if (JRequest::getVar('task') == "save")
				$link = 'index.php?option=com_limeticket';

			//exit;
			$mainframe = JFactory::getApplication();
			$mainframe->redirect($link, $msg, $msgtytpe);		
			return;
		} else if ($what == "customtemplate") {
			$this->CustomTemplate();
			exit;	
		} else {
		
			$qry = "SELECT * FROM #__limeticket_templates WHERE template = 'custom'";
			$db->setQuery($qry);
			$rows = $db->loadAssocList();
			if (count($rows) > 0)
			{	
				foreach ($rows as $row)
				{
					if ($row['tpltype'])
					{
						$settings['support_list_head'] = $row['value'];
					} else {
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
					if ($row['tpltype'])
					{
						$settings['support_user_head'] = $row['value'];
					} else {
						$settings['support_user_row'] = $row['value'];
					}
				}
			} else {
				$settings['support_user_head'] = '';
				$settings['support_user_row'] = '';
			}


// ##NOT_EXT_START##
			$qry = "SELECT * FROM #__limeticket_cron WHERE class = 'AutoClose' LIMIT 1";
			$db->setQuery($qry);
			$row = $db->loadAssoc();
			if ($row)
			{
				$settings['support_autoclose'] = $row['published'];
				$aparams = $this->ParseParams($row['params']);

				$settings['support_autoclose_duration'] = $aparams['closeinterval'];
				$settings['support_autoclose_audit'] = $aparams['addaudit'];
				$settings['support_autoclose_email'] = $aparams['emailuser'];
			}
// ##NOT_EXT_END##

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

			// load languages
			$lang = JFactory::getLanguage();
			$lang->load("com_config");
			


			JToolBarHelper::title( JText::_("LIMETICKET_PORTAL") .' - '. JText::_("SETTINGS") , 'limeticket_settings' );
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

	function TestRef()
	{
		$format = JRequest::getVar('ref');
		
		$ref = LIMETICKET_Ticket_Helper::createRef(1234,$format);
		echo $ref;
		exit;	
	}
	
	function testdates()
	{
		// test the 4 date formats
		
		$date = time();
		$result = array();
		
		$offset = (int)JRequest::GetVar('offset');
		LIMETICKET_Settings::set('timezone_offset', $offset) ;
		
		$date_dt_short = JRequest::GetVar('date_dt_short');
		if ($date_dt_short == "") $date_dt_short = JText::_('DATE_FORMAT_LC4') . ', H:i';
		$result['date_dt_short'] = $this->testdate($date, $date_dt_short);
		
		$date_dt_long = JRequest::GetVar('date_dt_long');
		if ($date_dt_long == "") $date_dt_long = JText::_('DATE_FORMAT_LC3') . ', H:i';
		$result['date_dt_long'] = $this->testdate($date, $date_dt_long);
		
		$date_d_short = JRequest::GetVar('date_d_short');
		if ($date_d_short == "") $date_d_short = JText::_('DATE_FORMAT_LC4');
		$result['date_d_short'] = $this->testdate($date, $date_d_short);
				
		$date_d_long = JRequest::GetVar('date_d_long');
		if ($date_d_long == "") $date_d_long = JText::_('DATE_FORMAT_LC3');
		$result['date_d_long'] = $this->testdate($date, $date_d_long);
		
		$result['timezone_offset'] = $this->testdate($date, 'Y-m-d H:i:s');
		echo json_encode($result);
		exit;
	}
	
	function testdate($date, $format)
	{
		/*$date = new JDate($date, new DateTimeZone("UTC"));
		$date->setTimezone(LIMETICKET_Helper::getTimezone());
		return $date->format($format, true);*/
		
		return LIMETICKET_Helper::Date($date, LIMETICKET_DATE_CUSTOM, $format);
	}

	function PerPage($var)
	{
		echo "<select name='$var'>";
		
		$values = array(0 => JText::_('All'), 1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5', 10 => '10', 15 => '15', 20 => '20', 25 => '25', 30 => '30', 50 => '50', 100 => '100');
		
		foreach ($values as $val => $text)
		{
			echo "<option value='$val' ";
			if ($this->settings[$var] == $val) echo " SELECTED";
			echo ">" . $text . "</option>";
		}
		
		echo "</select>";
	}

	function test_email_send()
	{
		$target = JRequest::getVar('email');

		$mailer = new LIMETICKETMailer();
		$mailer->addTo($target);
		$mailer->setSubject("LimeTicket Support Portal Test Email");
		$mailer->setBody("This is a test email send by LimeTicket Support Portal\n\n" . "Sent at " . date("Y-m-d, H:i:s"));
		$mailer->send();

		$app = JFactory::getApplication();
		$mq = $app->getMessageQueue();

		$session = JFactory::getSession();
		$session->set('application.queue', null);

		if ($mq && is_array($mq))
		{
			foreach ($mq as $message)
			{
				echo "<div class='alert alert-{$message['type']}' style='margin-top:8px;'>{$message['message']}</div>";	
			}
		} else {
				echo "<div class='alert alert-success' style='margin-top:8px;'>Test email message sent</div>";
		}
		exit;
	}
}


