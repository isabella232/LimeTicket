<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'settings.php');

class LIMETICKETAdminHelper
{
	static function PageSubTitle2($title,$usejtext = true)
	{
		// do something
		if ($usejtext)
			$title = JText::_($title);
		
		return str_replace("$1",$title,LIMETICKET_Settings::get('display_h3'));
	}
	
	static function IsFAQs()
	{
		if (JRequest::getVar('option') == "com_fsf")
			return true;
		return false;	
	}
	
	static function IsTests()
	{
		if (JRequest::getVar('option') == "com_fst")
			return true;
		return false;	
	}
	
	static function GetVersion($path = "")
	{
		
		global $fsj_version;
		if (empty($fsj_version))
		{
			if ($path == "") $path = JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_limeticket';
			$file = $path.DS.'limeticket.xml';
			
			if (!file_exists($file))
				return LIMETICKET_Settings::get('version');
			
			$xml = simplexml_load_file($file);
			
			$fsj_version = $xml->version;
		}

		if ($fsj_version == "[VERSION]")
			return LIMETICKET_Settings::get('version');
			
		return $fsj_version;
	}	

	static function GetInstalledVersion()
	{
		return LIMETICKET_Settings::get('version');
	}

	static function toolbarHeader($name)
	{
		JSubMenuHelper::addEntry("<span class='side_header'>" . JText::_($name) . "</span>",'',false);
	}

	static function toolbarItem($name, $link, $view)
	{
		JSubMenuHelper::addEntry(
			JText::_($name),
			$link,
			JRequest::getCmd('view', 'limeticket') == $view || JRequest::getCmd('view', 'limetickets') == $view . "s"
			);
	}
	
	static function DoSubToolbar($bare = false)
	{
		if (!$bare)
		{
			if (JFactory::getUser()->authorise('core.admin', 'com_limeticket'))    
			{        
				JToolBarHelper::preferences('com_limeticket');
			}
			JToolBarHelper::divider();
			JToolBarHelper::help("",false,"http://www.freestyle-joomla.com/comhelp/limeticket/admin-view-" . JRequest::getVar('view'));
		}
		
		self::toolbarItem("COM_LIMETICKET_OVERVIEW","index.php?option=com_limeticket","limeticket");

		self::toolbarHeader("SETTINGS");
		self::toolbarItem("SETTINGS","index.php?option=com_limeticket&view=settings","settings");
		self::toolbarItem("TEMPLATES","index.php?option=com_limeticket&view=templates","templates");
		self::toolbarItem("VIEW_SETTINGS","index.php?option=com_limeticket&view=settingsview","settingsview");
		
		self::toolbarHeader("GENERAL");
		self::toolbarItem("PERMISSIONS","index.php?option=com_limeticket&view=fusers","fuser");
		self::toolbarItem("EMAIL_TEMPLATES","index.php?option=com_limeticket&view=emails","email");
		self::toolbarItem("CUSTOM_FIELDS","index.php?option=com_limeticket&view=fields","field");
		self::toolbarItem("MAIN_MENU_ITEMS","index.php?option=com_limeticket&view=mainmenus","mainmenu");
		self::toolbarItem("MODERATION","index.php?option=com_limeticket&view=tests","test");
					
		self::toolbarHeader("SUPPORT_TICKETS");
		self::toolbarItem("PRODUCTS","index.php?option=com_limeticket&view=prods","prod");
		self::toolbarItem("CATEGORIES","index.php?option=com_limeticket&view=ticketcats","ticketcat");
		self::toolbarItem("DEPARTMENTS","index.php?option=com_limeticket&view=ticketdepts","ticketdept");
		self::toolbarItem("PRIORITIES","index.php?option=com_limeticket&view=ticketpris","ticketpri");
		self::toolbarItem("GROUPS","index.php?option=com_limeticket&view=ticketgroups","ticketgroup");
		self::toolbarItem("STATUSES","index.php?option=com_limeticket&view=ticketstatuss","ticketstatus");
		self::toolbarItem("TICKETS_EMAIL_ACCOUNTS","index.php?option=com_limeticket&view=ticketemails","ticketemail");
		self::toolbarItem("HELP_TEXT","index.php?option=com_limeticket&view=helptexts","helptext");
		
		self::toolbarHeader("OTHER");
		self::toolbarItem("COM_LIMETICKET_KB_CATS","index.php?option=com_limeticket&view=kbcats","kbcat");
		self::toolbarItem("COM_LIMETICKET_KB_ARTICLES","index.php?option=com_limeticket&view=kbarts","kbart");
		self::toolbarItem("COM_LIMETICKET_FAQ_CATEGORIES","index.php?option=com_limeticket&view=faqcats","faqcat");
		self::toolbarItem("COM_LIMETICKET_FAQS","index.php?option=com_limeticket&view=faqs","faq");
		self::toolbarItem("ANNOUNCEMENTS","index.php?option=com_limeticket&view=announces","announce");
		self::toolbarItem("GLOSSARY_ITEMS","index.php?option=com_limeticket&view=glossarys","glossary");
		
		self::toolbarHeader("COM_LIMETICKET_ADMIN");
		self::toolbarItem("LOG","index.php?option=com_limeticket&view=cronlog","cronlog");
		self::toolbarItem("EMAIL_LOG","index.php?option=com_limeticket&view=emaillog","emaillog");
		self::toolbarItem("TICKET_ATTACH_CLEANUP","index.php?option=com_limeticket&view=attachclean","attachclean");
		self::toolbarItem("COM_LIMETICKET_ADMIN","index.php?option=com_limeticket&view=backup","backup");
		self::toolbarItem("PLUGINS","index.php?option=com_limeticket&view=plugins","plugins");

	}	
	
	
	static function IncludeHelp($file)
	{
		$lang = JFactory::getLanguage();
		$tag = $lang->getTag();
		
		$path = JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_limeticket'.DS.'help'.DS.$tag.DS.$file;
		if (file_exists($path))
			return file_get_contents($path);
		
		$path = JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_limeticket'.DS.'help'.DS.'en-GB'.DS.$file;
		
		return file_get_contents($path);
	}
	
	static $langs;
	static $lang_bykey;
	static function DisplayLanguage($language)
	{
		if (empty(LIMETICKETAdminHelper::$langs))
		{
			LIMETICKETAdminHelper::LoadLanguages();
		}
		
		if (array_key_exists($language, LIMETICKETAdminHelper::$lang_bykey))
			return LIMETICKETAdminHelper::$lang_bykey[$language]->text;
		
		return "";
	}
	
	static function LoadLanguages()
	{		
		$deflang = new stdClass();
		$deflang->value = "*";
		$deflang->text = JText::_('JALL');
		
		LIMETICKETAdminHelper::$langs = array_merge(array($deflang) ,JHtml::_('contentlanguage.existing'));
		
		foreach (LIMETICKETAdminHelper::$langs as $lang)
		{
			LIMETICKETAdminHelper::$lang_bykey[$lang->value] = $lang;	
		}		
	}
	
	static function GetLanguagesForm($value)
	{
		if (empty(LIMETICKETAdminHelper::$langs))
		{
			LIMETICKETAdminHelper::LoadLanguages();
		}
		
		return JHTML::_('select.genericlist',  LIMETICKETAdminHelper::$langs, 'language', 'class="inputbox" size="1" ', 'value', 'text', $value);
	}
	
	static $access_levels;
	static $access_levels_bykey;
	
	static function DisplayAccessLevel($access)
	{
		if (empty(LIMETICKETAdminHelper::$access_levels))
		{
			LIMETICKETAdminHelper::LoadAccessLevels();
		}
		
		if (array_key_exists($access, LIMETICKETAdminHelper::$access_levels_bykey))
			return LIMETICKETAdminHelper::$access_levels_bykey[$access];
		
		return "";
		
	}
	
	static function LoadAccessLevels()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('a.id AS value, a.title AS text');
		$query->from('#__viewlevels AS a');
		$query->group('a.id, a.title, a.ordering');
		$query->order('a.ordering ASC');
		$query->order($query->qn('title') . ' ASC');

		$key = '1fedb5ecf7f3d8874730cd21d1d31d62';

		// Get the options.
		$db->setQuery($query);
		LIMETICKETAdminHelper::$access_levels = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum())
		{
			JError::raiseWarning(500, $db->getErrorMsg());
			return null;
		}

		foreach (LIMETICKETAdminHelper::$access_levels as $al)
		{
			LIMETICKETAdminHelper::$access_levels_bykey[$al->value] = $al->text;
		}	
	}
	
	static function GetAccessForm($value)
	{
		return JHTML::_('access.level',	'access',  $value, 'class="inputbox" size="1"', false);
	}
	
	static $filter_lang;
	static $filter_access;
	static function LA_GetFilterState()
	{
		$mainframe = JFactory::getApplication();
		LIMETICKETAdminHelper::$filter_lang	= $mainframe->getUserStateFromRequest( 'la_filter.'.'limeticket_filter_language', 'limeticket_filter_language', '', 'string' );
		LIMETICKETAdminHelper::$filter_access	= $mainframe->getUserStateFromRequest( 'la_filter.'.'limeticket_filter_access', 'limeticket_filter_access', 0, 'int' );
	}
	
	static function LA_Filter($nolangs = false)
	{
		if (empty(LIMETICKETAdminHelper::$access_levels))
		{
			LIMETICKETAdminHelper::LoadAccessLevels();
		}
		
		if (!$nolangs && empty(LIMETICKETAdminHelper::$langs))
		{
			LIMETICKETAdminHelper::LoadLanguages();
		}
	
		if (empty(LIMETICKETAdminHelper::$filter_lang))
		{
			LIMETICKETAdminHelper::LA_GetFilterState();
		}
		
		$options = LIMETICKETAdminHelper::$access_levels;		
		array_unshift($options, JHtml::_('select.option', 0, JText::_('JOPTION_SELECT_ACCESS')));
		echo JHTML::_('select.genericlist',  $options, 'limeticket_filter_access', 'class="inputbox" size="1"  onchange="document.adminForm.submit( );"', 'value', 'text', LIMETICKETAdminHelper::$filter_access);
		
		if (!$nolangs)
		{
			$options = LIMETICKETAdminHelper::$langs;		
			array_unshift($options, JHtml::_('select.option', '', JText::_('JOPTION_SELECT_LANGUAGE')));
			echo JHTML::_('select.genericlist',  $options, 'limeticket_filter_language', 'class="inputbox" size="1"  onchange="document.adminForm.submit( );"', 'value', 'text', LIMETICKETAdminHelper::$filter_lang);
		}
	}
	
	static function LA_Header($obj, $nolangs = false)
	{
		if (!$nolangs)
		{
			?>
 			<th width="1%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort',   'LANGUAGE', 'language', @$obj->lists['order_Dir'], @$obj->lists['order'] ); ?>
			</th>
			<?php
		}
			
		?>
 		<th width="1%" nowrap="nowrap">
			<?php echo JHTML::_('grid.sort',   'ACCESS_LEVEL', 'access', @$obj->lists['order_Dir'], @$obj->lists['order'] ); ?>
		</th>
		<?php
	}
	
	static function LA_Row($row, $nolangs = false)
	{
		if (!$nolangs)
		{
			?>
			<td>
				<?php echo LIMETICKETAdminHelper::DisplayLanguage($row->language); ?></a>
			</td>
			<?php
		}
			
		?>
		<td>
			<?php echo LIMETICKETAdminHelper::DisplayAccessLevel($row->access); ?></a>
		</td>
		<?php
	}
	
	static function LA_Form($item, $nolangs = false)
	{
		?>
		<tr>
			<td width="135" align="right" class="key">
				<label for="title">
					<?php echo JText::_("JFIELD_ACCESS_LABEL"); ?>:
				</label>
			</td>
			<td>
				<?php echo LIMETICKETAdminHelper::GetAccessForm($item->access); ?>
			</td>
		</tr>
			
		<?php
		if (!$nolangs)
		{
		?>

			<tr>
				<td width="135" align="right" class="key">
					<label for="title">
						<?php echo JText::_("JFIELD_LANGUAGE_LABEL"); ?>:
					</label>
				</td>
				<td>
					<?php echo LIMETICKETAdminHelper::GetLanguagesForm($item->language); ?>
				</td>
			</tr>
				
		<?php
		}
	}
	
	static function HTMLDisplay($text, $chars = 100)
	{
		$stripped = strip_tags($text);
		$output = substr($stripped, 0, $chars); 
		if (strlen($stripped) > $chars)	$output .= "&hellip;";	

		return $output;
	}
}