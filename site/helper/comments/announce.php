<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class LIMETICKET_Comments_Handler_Announce extends LIMETICKET_Comments_Handler
{
	var $ident = 4;	
	
	function __construct($parent) 
	{
		$this->comments = $parent;
		$this->comments->use_comments = LIMETICKET_Settings::get('announce_comments_allow');
		$this->comments->opt_display = 1;	
		
		$this->short_thanks = 1;
		$this->email_title = "An Announcement comment";
		$this->email_article_type = JText::_('ANNOUNCEMENT');
		$this->description = JText::_('ANNOUNCEMENT');	
		$this->descriptions = JText::_('ANNOUNCEMENTS');	
		$this->long_desc = JText::_('COMMENTS_ANNOUNCEMENTS');
		
		$this->article_link = "index.php?option=com_limeticket&view=announce&announceid={id}";
		
		$this->table = "#__limeticket_announce";
		$this->has_published = 1;
		$this->field_title = "title";
		$this->field_id = "id";
	}
}	   						 		