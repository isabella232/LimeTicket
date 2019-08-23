<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @deprecated 146cda91be7b8dface8f30d88284580e
*/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');

require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'admin_helper.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'content.php');

class FssViewAdmin_Content extends LIMETICKETView
{	
	function display($tpl = null)
	{
		LIMETICKET_Helper::noBots();
		LIMETICKET_Helper::noCache();
		
		$this->layout = LIMETICKET_Input::getCmd('layout',  LIMETICKET_Input::getCmd('_layout', ''));
		$this->view = LIMETICKET_Input::getCmd('view',  LIMETICKET_Input::getCmd('_view', ''));
		
		if (!LIMETICKET_Permission::PermAnyContent())
			return LIMETICKET_Admin_Helper::NoPerm();
		
		$this->type = LIMETICKET_Input::getCmd('type','');
		
		if ($this->type != "")
			return $this->displayType();
		
		$this->artcounts = LIMETICKET_ContentEdit::getArticleCounts();
		parent::display();
	}
	
	function displayType()
	{
		require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'content'.DS.$this->type.'.php');
		$class = "LIMETICKET_ContentEdit_{$this->type}";
		$content = new $class();
			
		$content->layout = $this->layout;
		$content->type = $this->type;
		$content->view = $this->view;
			
		LIMETICKET_Helper::IncludeModal();
			
		$content->Display();
	}
	
}
