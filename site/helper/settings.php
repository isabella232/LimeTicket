<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

define("LIMETICKET_IT_KB",1);
define("LIMETICKET_IT_FAQ",2);
define("LIMETICKET_IT_TEST",3);
define("LIMETICKET_IT_NEWTICKET",4);
define("LIMETICKET_IT_VIEWTICKETS",5);
define("LIMETICKET_IT_ANNOUNCE",6);
define("LIMETICKET_IT_LINK",7);
define("LIMETICKET_IT_GLOSSARY",8);
define("LIMETICKET_IT_ADMIN",9);
define("LIMETICKET_IT_GROUPS",10);
define("LIMETICKET_IT_MAINMENU",11);
define("LIMETICKET_IT_MENUITEM",99);


jimport( 'joomla.version' );
require_once( JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'helper.php' );

class LIMETICKET_Settings 
{
	static $limeticket_view_settings;
	
	static function _GetSettings()
	{
		global $limeticket_settings;
		
		if (empty($limeticket_settings))
		{
			LIMETICKET_Settings::_GetDefaults();
			
			$db = JFactory::getDBO();
			$query = 'SELECT * FROM #__limeticket_settings';
			$db->setQuery($query);
			$row = $db->loadAssocList();
			
			if (count($row) > 0)
			{
				foreach ($row as $data)
				{
					$limeticket_settings[$data['setting']] = $data['value'];
				}
			}

			$query = 'SELECT * FROM #__limeticket_settings_big';
			$db->setQuery($query);
			$row = $db->loadAssocList();
			
			if (count($row) > 0)
			{
				foreach ($row as $data)
				{
					$limeticket_settings[$data['setting']] = $data['value'];
				}
			}
		}	
	}
	
	static function _Get_View_Settings()
	{
		if (empty(LIMETICKET_Settings::$limeticket_view_settings))
		{
			LIMETICKET_Settings::_View_Defaults();
			
			$db = JFactory::getDBO();
			$query = 'SELECT * FROM #__limeticket_settings_view';
			$db->setQuery($query);
			$row = $db->loadAssocList();
			
			if (count($row) > 0)
			{
				foreach ($row as $data)
				{
					LIMETICKET_Settings::$limeticket_view_settings[$data['setting']] = $data['value'];
				}
			}
		}	
	}
	
	static function _GetDefaults()
	{
		global $limeticket_settings;
		
		if (empty($limeticket_settings))
		{
			$limeticket_settings = array();
			
			$limeticket_settings['version'] = 0;
			$limeticket_settings['fsj_username'] = '';
			$limeticket_settings['fsj_apikey'] = '';
			
			$limeticket_settings['jquery_include'] = "auto";
			
			$limeticket_settings['captcha_type'] = 'none';

			$limeticket_settings['recaptcha_public'] = '';
			$limeticket_settings['recaptcha_private'] = '';
			$limeticket_settings['comments_moderate'] = 'none';
			$limeticket_settings['comments_hide_add'] = 1;
			$limeticket_settings['email_on_comment'] = '';
			$limeticket_settings['comments_who_can_add'] = 'anyone';
			
			$limeticket_settings['test_use_email'] = 1;
			$limeticket_settings['test_use_website'] = 1;
			$limeticket_settings['commnents_use_email'] = 1;
			$limeticket_settings['commnents_use_website'] = 1;
			
			$limeticket_settings['hide_powered'] = 0;
			$limeticket_settings['announce_use_content_plugins'] = 0;
			$limeticket_settings['announce_use_content_plugins_list'] = 0;
			$limeticket_settings['announce_comments_allow'] = 1;
			$limeticket_settings['announce_comments_per_page'] = 0;
			$limeticket_settings['announce_per_page'] = 10;
			
			$limeticket_settings['kb_rate'] = 1;
			$limeticket_settings['kb_comments'] = 1;
			$limeticket_settings['kb_view_top'] = 0;
			
			$limeticket_settings['kb_show_views'] = 1;
			$limeticket_settings['kb_show_recent'] = 1;
			$limeticket_settings['kb_show_recent_stats'] = 1;
			$limeticket_settings['kb_show_viewed'] = 1;
			$limeticket_settings['kb_show_viewed_stats'] = 1;
			$limeticket_settings['kb_show_rated'] = 1;
			$limeticket_settings['kb_show_rated_stats'] = 1;
			$limeticket_settings['kb_show_dates'] = 1;
			$limeticket_settings['kb_use_content_plugins'] = 0;
			$limeticket_settings['kb_show_art_related'] = 1;
			$limeticket_settings['kb_show_art_products'] = 1;
			$limeticket_settings['kb_show_art_attach'] = 1;
			$limeticket_settings['kb_show_art_attach_filenames'] = 1;
			
			$limeticket_settings['kb_contents_auto'] = 0;
			$limeticket_settings['kb_smaller_subcat_images'] = 0;
			$limeticket_settings['kb_comments_per_page'] = 0;
			$limeticket_settings['kb_prod_per_page'] = 5;
			$limeticket_settings['kb_art_per_page'] = 10;
			$limeticket_settings['kb_print'] = 1;
			$limeticket_settings['kb_auto_open_single_cat'] = 0;
			$limeticket_settings['kb_popup_width'] = 820;
			
			$limeticket_settings['test_moderate'] = 'none';
			$limeticket_settings['test_email_on_submit'] = '';
			$limeticket_settings['test_allow_no_product'] = 1;
			$limeticket_settings['test_who_can_add'] = 'anyone';
			$limeticket_settings['test_hide_empty_prod'] = 1;
			$limeticket_settings['test_comments_per_page'] = 0;

			$limeticket_settings['skin_style'] = 0;
			$limeticket_settings['support_entire_row'] = 0;
			$limeticket_settings['support_autoassign'] = 0;
			$limeticket_settings['support_handler_fallback'] = '';
			
			//$limeticket_settings['support_assign_open'] = 0;
			$limeticket_settings['support_assign_reply'] = 0;
			$limeticket_settings['support_user_attach'] = 1;
			$limeticket_settings['support_lock_time'] = 30;
			$limeticket_settings['support_show_msg_counts'] = 1;
			$limeticket_settings['support_captcha_type'] = 'none';
			$limeticket_settings['support_access_level'] = 1;
			$limeticket_settings['support_open_access_level'] = 1;
			$limeticket_settings['support_reference'] = "{4L}-{4L}-{4L}";
			$limeticket_settings['support_list_template'] = "classic";
			$limeticket_settings['support_user_template'] = "classic";
			$limeticket_settings['support_custom_register'] = "";
			$limeticket_settings['support_custom_lost_username'] = "";
			$limeticket_settings['support_custom_lost_password'] = "";
			$limeticket_settings['support_no_logon'] = 0;
			$limeticket_settings['support_no_register'] = 0;
			$limeticket_settings['support_info_cols'] = 1;
			$limeticket_settings['support_info_cols_user'] = 1;
			$limeticket_settings['support_choose_handler'] = 'none';
			$limeticket_settings['support_assign_for_user'] = 0;
			$limeticket_settings['support_dont_check_dupe'] = 1;
			$limeticket_settings['support_admin_refresh'] = 0;
			$limeticket_settings['support_only_admin_open'] = 0;
			$limeticket_settings['allow_raw_html_messages'] = 0;
			$limeticket_settings['support_attach_max_size'] = '';
			$limeticket_settings['support_attach_max_size_admins'] = '';
			$limeticket_settings['support_attach_types'] = '';
			$limeticket_settings['support_attach_types_admins'] = '';
			$limeticket_settings['support_attach_use_old_system'] = 0;
			$limeticket_settings['support_update_satatus_on_draft'] = 0;

			$limeticket_settings['support_user_reply_width'] = 56;
			$limeticket_settings['support_user_reply_height'] = 10;
			$limeticket_settings['support_admin_reply_width'] = 56;
			$limeticket_settings['support_admin_reply_height'] = 10;
			$limeticket_settings['ticket_label_width'] = 200;
			$limeticket_settings['support_subject_size'] = 35;			
			$limeticket_settings['support_subject_message_hide'] = '';	
			$limeticket_settings['support_subject_format'] = '';
			$limeticket_settings['support_subject_format_blank'] = 1;		
			$limeticket_settings['support_filename'] = 0;			
			$limeticket_settings['support_unreg_password_highlight'] = 0;	
			
			$limeticket_settings['support_open_accord'] = 1;		
			$limeticket_settings['support_open_cat_prefix'] = '<i class="icon-circle" style="position: relative;top: -1px;margin-right: 2px;margin-left: 3px;font-size: 50%;"></i>';		
			
			$limeticket_settings['support_subject_at_top'] = 0;
			$limeticket_settings['support_sel_prod_dept'] = 1;
			$limeticket_settings['open_show_error_alert'] = 1;
			
			$limeticket_settings['support_tabs_allopen'] = 0;	
			$limeticket_settings['support_tabs_allclosed'] = 0;
			$limeticket_settings['support_tabs_all'] = 0;			
			$limeticket_settings['ticket_prod_per_page'] = 20;
			$limeticket_settings['ticket_per_page'] = 10;
			$limeticket_settings['support_hide_super_users'] = 1;	
			$limeticket_settings['support_no_admin_for_user_open'] = 0;
			$limeticket_settings['support_profile_itemid'] = '';
			
			$limeticket_settings['support_restrict_prod'] = 0;
			$limeticket_settings['support_restrict_prod_view'] = 0;
			
			$limeticket_settings['display_head'] = '';
			$limeticket_settings['display_foot'] = '';
			$limeticket_settings['use_joomla_page_title_setting'] = 0;
			$limeticket_settings['title_prefix'] = 1;
			$limeticket_settings['browser_prefix'] = -1;
			
			$limeticket_settings['page_headingout'] = 1;
			
			$limeticket_settings['support_email_link_unreg'] = '';
			$limeticket_settings['support_email_link_reg'] = '';
			$limeticket_settings['support_email_link_admin'] = '';
			$limeticket_settings['support_email_link_pending'] = '';
			$limeticket_settings['support_email_no_domain'] = '';
			$limeticket_settings['support_email_include_autologin'] = 0;
			$limeticket_settings['support_email_include_autologin_handler'] = 0;
			
			// these 3 are not needed anymore, but are still used in some legacy code for some reason
			// They need all references to them removing
			$limeticket_settings['css_hl'] = '#f0f0f0';
			$limeticket_settings['css_tb'] = '#ffffff';
			$limeticket_settings['css_bo'] = '#e0e0e0';

			$limeticket_settings['display_h1'] = '<h1>$1</h1>';
			$limeticket_settings['display_h2'] = '<h2>$1</h2>';
			$limeticket_settings['display_h3'] = '<h3>$1</h3>';
			
			$limeticket_settings['display_style'] = '';
			$limeticket_settings['display_popup_style'] = '';
			$limeticket_settings['display_module_style'] = '';

			$limeticket_settings['support_email_on_create'] = 0;
			$limeticket_settings['support_email_handler_on_create'] = 0;
			$limeticket_settings['support_email_on_reply'] = 0;
			$limeticket_settings['support_email_handler_on_reply'] = 0;
			$limeticket_settings['support_email_handler_on_forward'] = 0;
			$limeticket_settings['support_email_handler_on_private'] = 0;
			$limeticket_settings['support_email_handler_on_pending'] = 0;
			$limeticket_settings['support_email_on_close'] = 0;
			$limeticket_settings['support_email_on_close_no_dropdown'] = 0;
			
			$limeticket_settings['support_email_all_admins'] = 0;
			$limeticket_settings['support_email_all_admins_only_unassigned'] = 0;
			$limeticket_settings['support_email_all_admins_ignore_auto'] = 0;
			$limeticket_settings['support_email_all_admins_can_view'] = 0;
			
			$limeticket_settings['support_user_can_close'] = 1;
			$limeticket_settings['support_user_can_reopen'] = 1;
			$limeticket_settings['support_user_can_change_status'] = 0;
			$limeticket_settings['support_user_show_close_reply'] = 0;
			$limeticket_settings['support_advanced_department'] = 1;
			$limeticket_settings['support_advanced_search'] = 1;
			$limeticket_settings['support_product_manual_category_order'] = 0;
			$limeticket_settings['support_allow_unreg'] = 0;
			$limeticket_settings['support_unreg_type'] = 0;
			$limeticket_settings['support_unreg_domain_restrict'] = 0;
			$limeticket_settings['support_unreg_domain_list'] = '';
			$limeticket_settings['support_delete'] = 1;
			$limeticket_settings['support_advanced_default'] = 0;
			$limeticket_settings['support_sceditor'] = 1;
			$limeticket_settings['support_altcat'] = 0;
			$limeticket_settings['support_insertpopup'] = 0;
			$limeticket_settings['support_simple_userlist_tabs'] = 0;
			$limeticket_settings['support_simple_userlist_search'] = 0;
			$limeticket_settings['support_user_show_reply_always'] = 0;
			$limeticket_settings['support_user_reply_under'] = 0;
			$limeticket_settings['support_user_reverse_messages'] = 0;
			
			$limeticket_settings['ticket_link_target'] = 1;
			
			$limeticket_settings['support_cronlog_keep'] = 5;
			$limeticket_settings['support_emaillog_keep'] = 365;

			$limeticket_settings['support_hide_priority'] = 0;
			$limeticket_settings['support_default_priority'] = '';
			$limeticket_settings['support_hide_handler'] = 0;
			$limeticket_settings['support_hide_category'] = 0;
			$limeticket_settings['support_hide_users_tickets'] = 0;
			$limeticket_settings['support_hide_tags'] = 0;
			$limeticket_settings['support_email_unassigned'] = '';
			$limeticket_settings['support_email_admincc'] = '';
			$limeticket_settings['messages_at_top'] = 0;
			$limeticket_settings['time_tracking'] = '';
			$limeticket_settings['time_tracking_require_note'] = 1;
			$limeticket_settings['time_tracking_type'] = '';
			$limeticket_settings['absolute_last_open'] = 0;
			
			$limeticket_settings['reports_separator'] = ',';


			$limeticket_settings['support_email_from_name'] = '';
			$limeticket_settings['support_email_from_address'] = '';
			$limeticket_settings['support_email_site_name'] = '';
			
			$limeticket_settings['support_email_file_user'] = 1;
			$limeticket_settings['support_email_file_handler'] = 0;
			$limeticket_settings['support_email_bcc_handler'] = 0;
			$limeticket_settings['support_email_send_empty_handler'] = 0;

			$limeticket_settings['support_ea_check'] = 0;
			$limeticket_settings['support_ea_all'] = 0;
			$limeticket_settings['support_ea_reply'] = 0;
			$limeticket_settings['support_ea_type'] = 0;
			$limeticket_settings['support_ea_host'] = '';
			$limeticket_settings['support_ea_port'] = '';
			$limeticket_settings['support_ea_username'] = '';
			$limeticket_settings['support_ea_password'] = '';
			$limeticket_settings['support_ea_mailbox'] = '';

			$limeticket_settings['support_basic_name'] = '';
			$limeticket_settings['support_basic_username'] = '';
			$limeticket_settings['support_basic_email'] = '';
			$limeticket_settings['support_basic_messages'] = '';

			$limeticket_settings['glossary_faqs'] = 1;
			$limeticket_settings['glossary_kb'] = 1;
			$limeticket_settings['glossary_announce'] = 1;
			$limeticket_settings['glossary_support'] = 1;
			$limeticket_settings['glossary_link'] = 1;
			$limeticket_settings['glossary_title'] = 0;
			$limeticket_settings['glossary_use_content_plugins'] = 0;
			$limeticket_settings['glossary_ignore'] = '';
			$limeticket_settings['glossary_exclude'] = "a,script,pre,h1,h2,h3,h4,h5,h6";
			$limeticket_settings['glossary_show_read_more'] = 1;
			$limeticket_settings['glossary_all_letters'] = 0;
			
			$limeticket_settings['glossary_read_more_text'] = "Click for more info";
			$limeticket_settings['glossary_word_limit'] = 0;
			$limeticket_settings['glossary_case_sensitive'] = 0;
			
			$limeticket_settings['faq_popup_width'] = 650;
			$limeticket_settings['faq_popup_height'] = 375;
			$limeticket_settings['faq_use_content_plugins'] = 0;
			$limeticket_settings['faq_use_content_plugins_list'] = 0;
			$limeticket_settings['faq_per_page'] = 10;
			$limeticket_settings['faq_cat_prefix'] = 1;
			$limeticket_settings['faq_multi_col_responsive'] = 0;
			
			// 1.9 comments stuff
			$limeticket_settings['comments_announce_use_custom'] = 0;
			$limeticket_settings['comments_kb_use_custom'] = 0;
			$limeticket_settings['comments_test_use_custom'] = 0;	
			$limeticket_settings['comments_general_use_custom'] = 0;		
			$limeticket_settings['comments_testmod_use_custom'] = 0;	
			
			$limeticket_settings['announce_use_custom'] = 0;		
			$limeticket_settings['announcemod_use_custom'] = 0;		
			$limeticket_settings['announcesingle_use_custom'] = 0;		
			
			// date format stuff
			$limeticket_settings['date_dt_short'] = '';
			$limeticket_settings['date_dt_long'] = '';
			$limeticket_settings['date_d_short'] = '';
			$limeticket_settings['date_d_long'] = '';
			$limeticket_settings['timezone_offset'] = 0;
			
			$limeticket_settings['mainmenu_moderate'] = 1;
			$limeticket_settings['mainmenu_support'] = 1;
			
			$limeticket_settings['prodimg_size'] = 1;
			$limeticket_settings['prodimg_width'] = 64;
			$limeticket_settings['prodimg_height'] = 64;
			
			$limeticket_settings['prodimg_sm_size'] = 1;
			$limeticket_settings['prodimg_sm_width'] = 24;
			$limeticket_settings['prodimg_sm_height'] = 24;
			
			$limeticket_settings['use_sef_compat'] = 1;
			$limeticket_settings['css_indirect'] = 0;
			$limeticket_settings['hide_warnings'] = 0;
			$limeticket_settings['attach_location'] = 'components'.DS.'com_limeticket'.DS.'files';
			$limeticket_settings['attach_storage_filename'] = 0;
			$limeticket_settings['debug_reports'] = 0;
			$limeticket_settings['search_extra_like'] = 0;
			
			$limeticket_settings['popup_js'] = "";
			$limeticket_settings['popup_css'] = "";
			
			$limeticket_settings['bootstrap_template'] = "";
			$limeticket_settings['bootstrap_css'] = 'limeticketonly';
			$limeticket_settings['bootstrap_js'] = 'yes';
			$limeticket_settings['bootstrap_textcolor'] = 0;
			$limeticket_settings['bootstrap_icomoon'] = 0;
			$limeticket_settings['bootstrap_modal'] = 0;
			$limeticket_settings['bootstrap_border'] = '#ccc';
			$limeticket_settings['artisteer_fixes'] = 0;
			$limeticket_settings['bootstrap_variables'] = '';
			$limeticket_settings['bootstrap_v3'] = 0;
			$limeticket_settings['bootstrap_pribtn'] = 'btn-primary';
			
			$limeticket_settings['sceditor_theme'] = 'default';
			$limeticket_settings['sceditor_content'] = 'default';
			$limeticket_settings['sceditor_emoticons'] = 0;
			$limeticket_settings['sceditor_buttonhide'] = '';
			$limeticket_settings['sceditor_paste_user'] = '';
			$limeticket_settings['sceditor_paste_admin'] = '';
			
			// user simple view optiosn
			$limeticket_settings['user_hide_all_details'] = 0;
			$limeticket_settings['user_hide_title'] = 0;
			$limeticket_settings['user_hide_id'] = 0;
			$limeticket_settings['user_hide_user'] = 0;
			$limeticket_settings['user_hide_cc'] = 0;
			$limeticket_settings['user_hide_product'] = 0;
			$limeticket_settings['user_hide_department'] = 0;
			$limeticket_settings['user_hide_category'] = 0;
			$limeticket_settings['user_hide_updated'] = 0;
			$limeticket_settings['user_hide_handler'] = 0;
			$limeticket_settings['user_hide_status'] = 0;
			$limeticket_settings['user_hide_priority'] = 0;
			$limeticket_settings['user_hide_custom'] = 0;
			$limeticket_settings['user_hide_print'] = 0;
			$limeticket_settings['user_hide_key'] = 0;
			

			$limeticket_settings['email_send_multiple'] = 'multi';			
			$limeticket_settings['email_send_override'] = 0;
			$limeticket_settings['email_send_mailer'] = 'mail';
			$limeticket_settings['email_send_from_email'] = '';
			$limeticket_settings['email_send_from_name'] = '';
			$limeticket_settings['email_send_smtp_auth'] = 0;
			$limeticket_settings['email_send_smtp_security'] = 'none';
			$limeticket_settings['email_send_smtp_port'] = 0;
			$limeticket_settings['email_send_smtp_username'] = '';
			$limeticket_settings['email_send_smtp_password'] = '';
			$limeticket_settings['email_send_smtp_host'] = 'localhost';
			$limeticket_settings['email_send_sendmail_path'] = '/usr/sbin/sendmail';
			
			
			$limeticket_settings['sticky_menus_type'] = '';
			$limeticket_settings['sticky_menus'] = '';

			$limeticket_settings['allow_edit_no_audit'] = false;
			$limeticket_settings['forward_product_handler'] = 'auto';
			$limeticket_settings['forward_handler_handler'] = 'unchanged';
			
			$limeticket_settings['suport_dont_cc_handler'] = 0;
			
			$limeticket_settings['ratings_per_message'] = 0;
			$limeticket_settings['ratings_per_message_change'] = 0;
			$limeticket_settings['ratings_per_message_admin_overview'] = 0;
			$limeticket_settings['ratings_ticket'] = 0;
			$limeticket_settings['ratings_ticket_change'] = 0;
			
			$limeticket_settings['open_search_live'] = 0;
			$limeticket_settings['open_search_enabled'] = 0;
		}	
	}

	// return a list of settings that are used on the templates section
	static function GetTemplateList()
	{
		$template = array();
		//$template[] = "display_style";
		//$template[] = "display_popup_style";
		//$template[] = "display_h1";
		//$template[] = "display_h2";
		//$template[] = "display_h3";
		//$template[] = "display_head";
		//$template[] = "display_foot";
		//$template[] = "display_popup";
		$template[] = "support_list_template";
		$template[] = "support_user_template";
		
		$template[] = "comments_announce_use_custom";
		$template[] = "comments_test_use_custom";
		$template[] = "comments_kb_use_custom";
		$template[] = "comments_general_use_custom";
		$template[] = "comments_testmod_use_custom";
		$template[] = "announce_use_custom";
		$template[] = "announcemod_use_custom";
		$template[] = "announcesingle_use_custom";
		
		$res = array();
		foreach($template as $setting)
		{
			$res[$setting] = $setting;
		}
		return $res;	
	}
	
	static function StoreInTemplateTable()
	{
		$intpl = array();
		$intpl[] = "comments_general";	
		$intpl[] = "comments_announce";	
		$intpl[] = "comments_kb";	
		$intpl[] = "comments_test";	
		$intpl[] = "comments_testmod";	
		$intpl[] = "announce";	
		$intpl[] = "announcemod";	
		$intpl[] = "announcesingle";	
		
		$res = array();
		foreach($intpl as $setting)
		{
			$res[$setting] = $setting;
		}
		return $res;	
	}
		
	static function GetLargeList()
	{
		$large = array();
		$large[] = "display_style";
		$large[] = "display_popup_style";
		$large[] = "display_module_style";
		$large[] = "display_h1";
		$large[] = "display_h2";
		$large[] = "display_h3";
		$large[] = "display_head";
		$large[] = "display_foot";
		$large[] = "popup_js";
		$large[] = "popup_css";
		$large[] = "bootstrap_variables";
		
		$res = array();
		foreach($large as $setting)
		{
			$res[$setting] = $setting;
		}
		return $res;	
	}
	
	static function get($setting)
	{
		global $limeticket_settings;
		LIMETICKET_Settings::_GetSettings();
		return $limeticket_settings[$setting];	
	}
	
	static function set($setting, $value)
	{
		global $limeticket_settings;
		LIMETICKET_Settings::_GetSettings();
		$limeticket_settings[$setting] = $value;	
	}
	
	static function reload()
	{
		global $limeticket_settings;
		$limeticket_settings = null;
		LIMETICKET_Settings::_GetSettings();
	}
	
	static function &GetAllSettings()
	{
		global $limeticket_settings;
		LIMETICKET_Settings::_GetSettings();
		return $limeticket_settings;	
	}
	
	static function &GetAllViewSettings()
	{
		LIMETICKET_Settings::_Get_View_Settings();
		return LIMETICKET_Settings::$limeticket_view_settings;	
	}
	
	static function _View_Defaults()
	{
		// FAQS
		
		// When Showing list of Categories
		LIMETICKET_Settings::$limeticket_view_settings['faqs_hide_allfaqs'] = 0;
		LIMETICKET_Settings::$limeticket_view_settings['faqs_hide_tags'] = 0;
		LIMETICKET_Settings::$limeticket_view_settings['faqs_hide_search'] = 0;
		LIMETICKET_Settings::$limeticket_view_settings['faqs_show_featured'] = 0;
		LIMETICKET_Settings::$limeticket_view_settings['faqs_num_cat_colums'] = 1;
		LIMETICKET_Settings::$limeticket_view_settings['faqs_view_mode_cat'] = 'accordian';
		LIMETICKET_Settings::$limeticket_view_settings['faqs_view_mode_incat'] = 'accordian';
		
		// When Showing list of FAQs
		LIMETICKET_Settings::$limeticket_view_settings['faqs_view_mode'] = 'accordian';
		LIMETICKET_Settings::$limeticket_view_settings['faqs_enable_pages'] = 1;
		
		// Glossary
		LIMETICKET_Settings::$limeticket_view_settings['glossary_use_letter_bar'] = 0;
		LIMETICKET_Settings::$limeticket_view_settings['glossary_show_search'] = 0;
		LIMETICKET_Settings::$limeticket_view_settings['glossary_long_desc'] = 0;
		
		// Testimonials
		LIMETICKET_Settings::$limeticket_view_settings['test_test_show_prod_mode'] = 'accordian';
		LIMETICKET_Settings::$limeticket_view_settings['test_test_pages'] = 1;
		LIMETICKET_Settings::$limeticket_view_settings['test_test_always_prod_select'] = 0;
		
		
		// KB
		
		// Main Page
		LIMETICKET_Settings::$limeticket_view_settings['kb_main_show_prod'] = 1;
		LIMETICKET_Settings::$limeticket_view_settings['kb_main_show_cat'] = 0;
		LIMETICKET_Settings::$limeticket_view_settings['kb_main_show_sidebyside'] = 0;
		LIMETICKET_Settings::$limeticket_view_settings['kb_main_show_search'] = 0;
		
		// Main Page - Products List Settings		
		LIMETICKET_Settings::$limeticket_view_settings['kb_main_prod_colums'] = 1;
		LIMETICKET_Settings::$limeticket_view_settings['kb_main_prod_search'] = 1;
		LIMETICKET_Settings::$limeticket_view_settings['kb_main_prod_pages'] = 0;
		
		// Main Page - Category List Settings
		LIMETICKET_Settings::$limeticket_view_settings['kb_main_cat_mode'] = 'normal';
		LIMETICKET_Settings::$limeticket_view_settings['kb_main_cat_arts'] = 'normal';
		LIMETICKET_Settings::$limeticket_view_settings['kb_main_cat_colums'] = 1;
		
		// When Product Selected
		LIMETICKET_Settings::$limeticket_view_settings['kb_prod_cat_mode'] = 'accordian';
		LIMETICKET_Settings::$limeticket_view_settings['kb_prod_cat_arts'] = 'normal';
		LIMETICKET_Settings::$limeticket_view_settings['kb_prod_cat_colums'] = 1;
		LIMETICKET_Settings::$limeticket_view_settings['kb_prod_search'] = 1;
		
		// When Product and Category Selected
		LIMETICKET_Settings::$limeticket_view_settings['kb_cat_cat_mode'] = 'accordian';
		LIMETICKET_Settings::$limeticket_view_settings['kb_cat_cat_arts'] = 'normal';
		LIMETICKET_Settings::$limeticket_view_settings['kb_cat_art_pages'] = 1;
		LIMETICKET_Settings::$limeticket_view_settings['kb_cat_search'] = 1;		
		LIMETICKET_Settings::$limeticket_view_settings['kb_cat_desc'] = 1;		
	}
	
	static function GetViewSettingsObj($view)
	{
		// return a view setting object that can be used in place of the getPageParameters object
		// needs info about what view we are in, and access to the view settings
		LIMETICKET_Settings::_Get_View_Settings();
			
		return new LIMETICKET_View_Settings($view, LIMETICKET_Settings::$limeticket_view_settings);
	}
}

class LIMETICKET_View_Settings
{
	var $view;
	var $settings;
	var $mainframe;
	
	function __construct($view, $settings)
	{
		$this->view = $view;
		$this->settings = $settings;
		
		$this->mainframe = JFactory::getApplication();
		$this->params = $this->mainframe->getPageParameters('com_limeticket');
		
		//print_p($this->settings);
		//print_p($this->params);
	}
	
	function get($var, $default = '')
	{
		$key = $this->view . "_" . $var;
		
		//echo "Get : $key (Def: $default) = ";

		$value = $this->params->get($var,"XXXXXXXX");
		if ($value != "XXXXXXXX")
		{
			if (!array_key_exists($key, $this->settings))
			{
				//echo $value . " (missing)<br>";
				return $value;
			}
		
			if ($value != -1)
			{
				//echo $value . " (set)<br>";
				return $value;
			}
		}
		
		//echo $this->settings[$key] . " (global)<br>";
		return $this->settings[$key];
	}
}

function LIMETICKET_GetAllMenus()
{
	static $getmenus;
	
	if (empty($getmenus))
	{
		$where = array();
		$where[] = 'menutype != "main"';
		$where[] = 'type = "component"';
		$where[] = 'link LIKE "%option=com_limeticket%"';
		$where[] = 'published = 1';
		
		$query = 'SELECT title, id, link FROM #__menu';
		$query .= ' WHERE ' . implode(" AND ", $where);
		
		$db    = JFactory::getDBO();
		$db->setQuery($query);
		$getmenus = $db->loadObjectList();
	}
	//print_p($getmenus);
	
	return $getmenus;
}

function LIMETICKET_GetMenus($menutype)
{
	$getmenus = LIMETICKET_GetAllMenus();
	
	//echo "<br>Menu Type : $menutype<br>-<br>";
	$have = array();
	$not = array();
	
	switch ($menutype)
	{
	case LIMETICKET_IT_KB:
		$have['view'] = "kb";
		$not['layout'] = "";
		break;						
	case LIMETICKET_IT_FAQ:
		$have['view'] = "faq";
		$not['layout'] = "";
		break;						
	case LIMETICKET_IT_TEST:
		$have['view'] = "test";
		$not['layout'] = "";
		break;						
	case LIMETICKET_IT_NEWTICKET:
		$have['view'] = "ticket";
		$have['layout'] = "open";
		break;
	case LIMETICKET_IT_VIEWTICKETS:
		$have['view'] = "ticket";
		$not['layout'] = "";
		break;						
	case LIMETICKET_IT_ANNOUNCE:
		$have['view'] = "announce";
		$not['layout'] = "";
		break;						
	case LIMETICKET_IT_GLOSSARY:
		$have['view'] = "glossary";
		$not['layout'] = "";
		break;						
	case LIMETICKET_IT_ADMIN:
		$have['view'] = "admin";
		$not['layout'] = "";
		break;						
	default:
		return array();							
	}
	
	$results = array();
	
	if (count($getmenus) > 0)
	{
		foreach ($getmenus as $object)
		{ 
			$linkok = 1;
		
			$link = strtolower(substr($object->link,strpos($object->link,"?")+1));
			//echo $link."<br>";
			$parts = explode("&",$link);
		
			$inlink = array();
		
			foreach($parts as $part)
			{
				list($key,$value) = explode("=",$part);
				$inlink[$key] = $value;
			
				if (array_key_exists($key,$not))
				{
					//echo "Has ".$key."<br>";
					$linkok = 0;
				}
			}
				
			foreach ($have as $key => $value)
			{		
				if (!array_key_exists($key,$inlink))
				{
					//echo "Doesnt have ".$key."<br>";
					$linkok = 0;	
				} else {
					if ($inlink[$key] != $value)
					{
						//echo "Value mismatch for ".$key." - " . $value . " should be " . $inlink[$key] . "<br>";
						$linkok = 0;
					}
				}				
			}
		
			if ($linkok)
			{
				$results[] = $object;
				//echo "VALID : " . $link . "<br>";	
			}	
		}
	}
	
	return $results;
}
