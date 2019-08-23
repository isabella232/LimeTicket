<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @deprecated 891d8a06a3fdcac9b66c75268ed46fe4
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view' );
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'support_ticket.php');

class limeticketViewImage extends LIMETICKETView
{
	function display($tpl = null)
	{
		$fileid = LIMETICKET_Input::getInt('fileid'); 
		
		$key = LIMETICKET_Input::getCmd('key'); 
		$decoded = LIMETICKET_Helper::decrypt(LIMETICKET_Helper::base64url_decode($key), LIMETICKET_Helper::getEncKey("file"));

		if ($fileid != $decoded)
			exit;

		$db = JFactory::getDBO();
		$sql = "SELECT * FROM #__limeticket_ticket_attach WHERE id = " . $fileid;
		$db->setQuery($sql);
		$attach = $db->loadObject();

		$image = in_array(strtolower(pathinfo($attach->filename, PATHINFO_EXTENSION)), array('jpg','jpeg','png','gif'));
			
		$image_file = JPATH_SITE.DS.LIMETICKET_Helper::getAttachLocation().DS."support".DS.$attach->diskfile;
		
		require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'files.php');
		LIMETICKET_File_Helper::OutputImage($image_file, pathinfo($attach->filename, PATHINFO_EXTENSION));	
	}
}
