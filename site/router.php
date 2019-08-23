<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

defined('_JEXEC') or die;

if (!class_exists('JComponentRouterBase'))
{
	class JComponentRouterBase {}
}

if (!defined("DS")) define('DS', DIRECTORY_SEPARATOR);

class LIMETICKETRouter extends JComponentRouterBase
{
	public function build(&$query)
	{
		$view = "";
		$layout = "";

		if (isset($query['Itemid']))
		{
			$app = JFactory::getApplication();
			$menu   = $app->getMenu();
			$item = $menu->getItem($query['Itemid']);

			if ($item)
			{

				if (isset($item->query['view']))
				{
					$view = $item->query['view'];

					if (isset($query['view']) && $item->query['view'] == $query['view'])
					unset($query['view']);
				}

				if (isset($item->query['layout']))
				{
					$layout = $item->query['layout'];

					if (isset($query['layout']) && $item->query['layout'] == $query['layout'])
					unset($query['layout']);

				}
			}
		}

		$segments = array();
		if (isset($query['view']))
		{
			$view = $query['view'];
			$segments[] = $query['view'];
			unset($query['view']);
		}
		
		if (isset($query['layout']))
		{
			$layout = $query['layout'];
			$segments[] = $query['layout'];
			unset($query['layout']);
		}

		$method = "build_" . ($view ? $view : '') . ($layout ? '_'.$layout : '');
		if (method_exists($this, $method)) $this->$method($query, $segments);

		return $segments;
	}

	public function parse(&$segments)
	{
		$parts = array();
		$mode = array();

		$app = JFactory::getApplication();
		$menu   = $app->getMenu();
		$active   = $menu->getActive();

		if (isset($active->query['view'])) $parts['view'] = $active->query['view'];
		if (isset($active->query['layout'])) $parts['layout'] = $active->query['layout'];

		$view = reset($segments);
		if ($view != "" && strpos($view, "-") === false && $this->view_exists($view))
		{
			$parts['view'] = $view;
			array_shift($segments);
		}

		$layout = reset($segments);
		if ($layout != "" && strpos($layout, "-") === false && $this->layout_exists($parts['view'], $layout))
		{
			$parts['layout'] = $layout;
			array_shift($segments);
		}

		$method = "parse_" . (isset($parts['view']) ? $parts['view'] : '') . (isset($parts['layout']) ? '_'.$parts['layout'] : '');

		//echo $method."<br>";
		if (method_exists($this, $method)) $this->$method($parts, $segments);

		return $parts;
	}

	static $lookup_data = array();

	private function lookup_alias($type, $value, $table, $display = "title", $where = "published = 1")
	{
		if (!array_key_exists($type, static::$lookup_data))
		{
			$db = JFactory::getDBO();
			$db->setQuery("SELECT id, $display FROM $table WHERE $where");
			static::$lookup_data[$type] = $db->loadObjectList("id");
		}

		if (array_key_exists($value, static::$lookup_data[$type])) 
			return "-" . strtolower(preg_replace("/[^A-Za-z0-9]/", '-', static::$lookup_data[$type][$value]->$display));

		return "";
	}

	private function view_exists($view)
	{
		return file_exists(JPATH_SITE.DS."components".DS."com_limeticket".DS."views".DS.$view);
	}

	private function layout_exists($view, $layout)
	{
		//echo "LE: " . JPATH_SITE.DS."components".DS."com_limeticket".DS."views".DS.$view.DS."tmpl".DS.$layout.".php" . "<br>";
		if (file_exists(JPATH_SITE.DS."components".DS."com_limeticket".DS."views".DS.$view.DS."tmpl".DS.$layout.".php")) return true;
		if (file_exists(JPATH_SITE.DS."components".DS."com_limeticket".DS."views".DS.$view.DS."layout.".$layout.".php")) return true;	
		return false;
	}

	// Per section looksups and builds
	private function build_kb(&$query, &$segments)
	{
		if (isset($query['what']))
		{
			$segments[] = $query['what'];
			unset($query['what']);
		}
		if (isset($query['prodid']))
		{
			$segments[] = "p-" . $query['prodid'] . static::lookup_alias("_prod", $query['prodid'], "#__limeticket_prod", "title");
			unset($query['prodid']);
		}
		if (isset($query['catid']))
		{
			$segments[] = "c-" . $query['catid'] . static::lookup_alias("kb_cat", $query['catid'], "#__limeticket_kb_cat");
			unset($query['catid']);
		}	
		if (isset($query['kbartid']))
		{
			$segments[] = "a-" . $query['kbartid'] . static::lookup_alias("kb_art", $query['kbartid'], "#__limeticket_kb_art");
			unset($query['kbartid']);
		}	
	}

	private function parse_kb(&$parts, &$segments)
	{
		foreach ($segments as $segment)
		{

			if ($segment == "recent" || $segment == "viewed" || $segment == "rated")
			{
				$parts["what"] = $segment;
				continue;
			}

			$split = explode("-", $segment);
			if (count($split) > 0)
			{
				$type = $split[0];
				$value = $split[1];

				switch ($type)
				{
					case 'p':
						$type = 'prodid';
						break;
					case 'c':
						$type = 'catid';
						break;
					case 'a':
						$type = 'kbartid';
						break;
				}

				$parts[$type] = $value;
			}
		}
	}

	private function build_faq(&$query, &$segments)
	{
		if (isset($query['catid']))
		{
			if ($query['catid'] == -2)
			{
				$segments[] = "search";
			} elseif ($query['catid'] == -6)
			{
				$segments[] = "all";
			} elseif ($query['catid'] == -4)
			{
				$segments[] = "tags";
			} elseif ($query['catid'] == -5)
			{
				$segments[] = "featured";
			} else {
				$segments[] = "c-" . $query['catid'] . static::lookup_alias("faq_cat", $query['catid'], "#__limeticket_faq_cat");
			}
			unset($query['catid']);
		}	
		if (isset($query['faqid']))
		{
			$segments[] = "f-" . $query['faqid'] . static::lookup_alias("faq_faq", $query['faqid'], "#__limeticket_faq_faq", "question");
			unset($query['faqid']);
		}	
		if (isset($query['tag']))
		{
			$segments[] = "t-" . $query['tag'];
			unset($query['tag']);
		}	
	}

	private function parse_faq(&$parts, &$segments)
	{
		foreach ($segments as $segment)
		{
			if ($segment == "search")
			{
				$parts['catid'] = -2;
				continue;
			}
			if ($segment == "all")
			{
				$parts['catid'] = -6;
				continue;
			}
			if ($segment == "tags")
			{
				$parts['catid'] = -4;
				continue;
			}
			if ($segment == "featured")
			{
				$parts['catid'] = -5;
				continue;
			}
			
			$split = explode("-", $segment);
			if (count($split) > 0)
			{
				$type = $split[0];
				$value = $split[1];

				switch ($type)
				{
					case 'c':
						$type = 'catid';
						break;
					case 'f':
						$type = 'faqid';
						break;
					case 't':
						$type = 'tag';
						break;
				}

				$parts[$type] = $value;
			}
		}
	}

	private function build_glossary(&$query, &$segments)
	{
		if (isset($query['letter']))
		{
			$segments[] = $query['letter'];
			unset($query['letter']);
		}
	}

	private function parse_glossary(&$parts, &$segments)
	{
		if (count($segments) > 0)
		{	
			$parts['letter'] = reset($segments);
		}
	}

	private function build_glossary_word(&$query, &$segments)
	{
		if (isset($query['word']))
		{
			$segments[] = $query['word'] . static::lookup_alias("glossary_words", $query['word'], "#__limeticket_glossary", "word");
			unset($query['word']);
		}
	}

	private function parse_glossary_word(&$parts, &$segments)
	{
		if (count($segments) > 0)
		{	
			$split = explode("-", reset($segments));
			$parts['word'] = $split[0];
		}
	}
	
	private function build_announce(&$query, &$segments)
	{
		if (isset($query['announceid']))
		{
			$segments[] = $query['announceid'] . static::lookup_alias("announce", $query['announceid'], "#__limeticket_announce");
			unset($query['announceid']);
		}
	}

	private function parse_announce(&$parts, &$segments)
	{
		if (count($segments) > 0)
		{	
			$split = explode("-", reset($segments));
			$parts['announceid'] = $split[0];
		}
	}
	
	private function build_test(&$query, &$segments)
	{
		if (isset($query['prodid']))
		{
			if ($query['prodid'] == -1)
			{
				//$segments[] = "all";
			} elseif ($query['prodid'] == 0 || $query['prodid'] == -2)
			{
				$segments[] = "general";
			} else {
				$segments[] = "p-" . $query['prodid'] . static::lookup_alias("prod", $query['prodid'], "#__limeticket_prod");
			}
			unset($query['prodid']);
		}
		
		if (isset($query['comm_page']))
		{
			if ($query['comm_page'] > 1)
				$segments[] = "page-" . $query['comm_page'];
			unset($query['comm_page']);
		}	
	}

	private function parse_test(&$parts, &$segments)
	{
		foreach ($segments as $segment)
		{
			if ($segment == "general")
			{
				$parts['prodid'] = -2;
				continue;
			}
			
			$split = explode("-", $segment);
			if (count($split) > 0)
			{
				$type = $split[0];
				$value = $split[1];

				switch ($type)
				{
					case 'p':
						$type = 'prodid';
						break;
					case 'page':
						$type = 'comm_page';
						break;
				}

				$parts[$type] = $value;
			}
		}
	}
	
	private function build_admin_support(&$query, &$segments)
	{
		if (isset($query['tickets']))
		{
			if ($query['tickets'] == "allopen")
			{
				$segments[] = "open";
			} else if ($query['tickets'] == "closed")
			{
				$segments[] = "closed";
			} else if ($query['tickets'] == "all")
			{
				$segments[] = "all";
			} else if ($query['tickets'] == -1)
			{
				$segments[] = "search";
				unset($query['what']);
			} else {
				$segments[] = "s-" . $query['tickets'] . static::lookup_alias("status", $query['tickets'], "#__limeticket_ticket_status");
			}
			unset($query['tickets']);
		}
		if (isset($query['searchtype']))
		{
			$segments[] = $query['searchtype'];
			unset($query['searchtype']);
		}		
		if (isset($query['showbasic']))
		{
			$segments[] = 'showbasic';
			unset($query['showbasic']);
		}		
	}

	private function parse_admin_support(&$parts, &$segments)
	{
		foreach ($segments as $segment)
		{
			if ($segment == "open")
			{
				$parts['tickets'] = 'allopen';
				continue;
			}
			if ($segment == "closed")
			{
				$parts['tickets'] = 'closed';
				continue;
			}
			if ($segment == "all")
			{
				$parts['tickets'] = 'all';
				continue;
			}
			
			if ($segment == "search")
			{
				$parts['tickets'] = -1;
				$parts['what'] = 'search';
				continue;
			}
			if ($segment == "showbasic")
			{
				$parts['showbasic'] = 1;
				continue;
			}
			if ($segment == "advanced")
			{
				$parts['searchtype'] = "advanced";
				continue;
			}
			if ($segment == "basic")
			{
				$parts['searchtype'] = "basic";
				continue;
			}
			
			
			$split = explode("-", $segment);
			if (count($split) > 1)
			{
				$type = $split[0];
				$value = $split[1];

				switch ($type)
				{
					case 's':
						$type = 'tickets';
						break;
				}

				$parts[$type] = $value;
			}
		}
	}

	private function build_admin_support_new(&$query, &$segments)
	{
		if (isset($query['type']))
		{
			$segments[] = $query['type'];
			unset($query['type']);
		}
	}

	private function parse_admin_support_new(&$parts, &$segments)
	{
		if (count($segments) > 0)
		{	
			$parts['type'] = reset($segments);
		}
	}
	
	private function build_admin_support_ticket(&$query, &$segments)
	{
		if (isset($query['ticketid']))
		{
			$segments[] = $this->ticket_alias($query['ticketid']);
			unset($query['ticketid']);
		}
	}

	private function parse_admin_support_ticket(&$parts, &$segments)
	{
		if (count($segments) > 0)
		{	
			$split = explode("-", reset($segments));
			$parts['ticketid'] = $split[0];
		}
	}
	
	private function build_admin_support_reply(&$query, &$segments)
	{
		if (isset($query['type']))
		{
			$segments[] = $query['type'];
			unset($query['type']);
		}
		if (isset($query['ticketid']))
		{
			$segments[] = $this->ticket_alias($query['ticketid']);
			unset($query['ticketid']);
		}
	}

	private function parse_admin_support_reply(&$parts, &$segments)
	{
		foreach ($segments as $segment)
		{
			if ($segment == "handler")
			{
				$parts['type'] = "handler";
				continue;
			}
			if ($segment == "private")
			{
				$parts['type'] = "private";
				continue;
			}
			if ($segment == "product")
			{
				$parts['type'] = "product";
				continue;
			}
			if ($segment == "reply")
			{
				$parts['type'] = "reply";
				continue;
			}
			if ($segment == "user")
			{
				$parts['type'] = "user";
				continue;
			}
			$split = explode("-", $segment);
			if ($split[0] > 0) $parts['ticketid'] = $split[0];
		}
	}
	
	private function ticket_alias($ticketid)
	{
		if (class_exists("SupportTickets"))
		{
			if (array_key_exists($ticketid, SupportTickets::$ref_lookup))
				return SupportTickets::$ref_lookup[$ticketid];
		}

		if ($ticketid < 1)
		{
			return "";
		}

		$db = JFactory::getDBO();
		$db->setQuery("SELECT title FROM #__limeticket_ticket_ticket WHERE id = " . $ticketid);
		$obj = $db->loadObject();
		if (!$obj) return $ticketid;
		return $ticketid . "-" . strtolower(preg_replace("/[^A-Za-z0-9]/", '-', $obj->title));
	}

	private function build_admin_support_signature(&$query, &$segments)
	{
		unset($query['tmpl']);
		if (isset($query['task']) && $query['task'] == "signature.setdefault")
		{
			$segments[] = "default-" . $query['sigid'] . static::lookup_alias("ticket_fragment", $query['sigid'], "#__limeticket_ticket_fragments", "description", "1");
			unset($query['task']);
			unset($query['sigid']);
		} elseif (isset($query['task']) && $query['task'] == "signature.delete")
		{
			$segments[] = "delete-" . $query['deleteid'] . static::lookup_alias("ticket_fragment", $query['deleteid'], "#__limeticket_ticket_fragments", "description", "1");
			unset($query['task']);
			unset($query['deleteid']);
		} else {
			if (isset($query['sigid']))
			{
				if ($query['sigid'] == -1)
				{
					$segments[] = "new";
				} else {
					$segments[] = "edit-" . $query['sigid'] . static::lookup_alias("ticket_fragment", $query['sigid'], "#__limeticket_ticket_fragments", "description", "1");
				}
				unset($query['sigid']);
			}	
		}	
	}

	private function parse_admin_support_signature(&$parts, &$segments)
	{
		$parts['tmpl'] = 'component';
		foreach ($segments as $segment)
		{
			if ($segment == "new")
			{
				$parts['sigid'] = -1;
				continue;
			}
			
			$split = explode("-", $segment);
			if (count($split) > 1)
			{
				$type = $split[0];
				$value = $split[1];

				switch ($type)
				{
					case 'default':
						$parts['task'] = "signature.setdefault";
						$type = 'sigid';
						break;
					case 'edit':
						$type = 'sigid';
						break;
					case 'delete':
						$parts['task'] = "signature.delete";
						$type = 'deleteid';
						break;
				}

				$parts[$type] = $value;
			}
		}
	}

	private function build_admin_support_canned(&$query, &$segments)
	{
		unset($query['tmpl']);
		if (isset($query['cannedid']))
		{
			if ($query['cannedid'] == -1)
			{
				$segments[] = "new";
			} else {
				$segments[] = "edit-" . $query['cannedid'] . static::lookup_alias("ticket_fragment", $query['cannedid'], "#__limeticket_ticket_fragments", "description", "1");
			}
			unset($query['cannedid']);
		}		
		if (isset($query['deleteid']))
		{
			$segments[] = "delete-" . $query['deleteid'] . static::lookup_alias("ticket_fragment", $query['deleteid'], "#__limeticket_ticket_fragments", "description", "1");
			unset($query['deleteid']);
		}
	}

	private function parse_admin_support_canned(&$parts, &$segments)
	{
		$parts['tmpl'] = 'component';
		foreach ($segments as $segment)
		{
			if ($segment == "new")
			{
				$parts['cannedid'] = -1;
				continue;
			}
						
			$split = explode("-", $segment);
			if (count($split) > 1)
			{
				$type = $split[0];
				$value = $split[1];

				switch ($type)
				{
					case 'edit':
						$type = 'cannedid';
						break;
					case 'delete':
						$type = 'deleteid';
						break;
				}

				$parts[$type] = $value;
			}
		}
	}
	
	private function build_ticket_view(&$query, &$segments)
	{
		if (isset($query['ticketid']))
		{
			$segments[] = $this->ticket_alias($query['ticketid']);
			unset($query['ticketid']);
		}
	}

	private function parse_ticket_view(&$parts, &$segments)
	{
		if (count($segments) > 0)
		{	
			$split = explode("-", reset($segments));
			$parts['ticketid'] = $split[0];
		}
	}

	private function build_ticket(&$query, &$segments)
	{
		$this->build_ticket_support($query, $segments);
	}

	private function build_ticket_support(&$query, &$segments)
	{
		if (isset($query['tickets']))
		{
			if ($query['tickets'] == "allopen")
			{
				$segments[] = "open";
			} else if ($query['tickets'] == "open")
			{
				$segments[] = "open";
			} else if ($query['tickets'] == "closed")
			{
				$segments[] = "closed";
			} else if ($query['tickets'] == "all")
			{
				$segments[] = "all";
			} else if ($query['tickets'] == -1)
			{
				$segments[] = "search";
				unset($query['what']);
			} else {
				$segments[] = "s-" . $query['tickets'] . static::lookup_alias("status", $query['tickets'], "#__limeticket_ticket_status");
			}
			unset($query['tickets']);
		}
	}

	private function parse_ticket(&$parts, &$segments)
	{
		foreach ($segments as $segment)
		{
			if ($segment == "open")
			{
				$parts['tickets'] = 'open';
				continue;
			}
			if ($segment == "closed")
			{
				$parts['tickets'] = 'closed';
				continue;
			}
			if ($segment == "all")
			{
				$parts['tickets'] = 'all';
				continue;
			}
				
			$split = explode("-", $segment);
			if (count($split) > 1)
			{
				$type = $split[0];
				$value = $split[1];

				switch ($type)
				{
					case 's':
						$type = 'tickets';
						break;
				}

				$parts[$type] = $value;
			}
		}
	}
	
}


/**
	* Content router functions
	*
	* These functions are proxys for the new router interface
	* for old SEF extensions.
	*
	* @deprecated  4.0  Use Class based routers instead
	*/
function LIMETICKETBuildRoute(&$query)
{
	$router = new LIMETICKETRouter;

	return $router->build($query);
}

function LIMETICKETParseRoute($segments)
{
	$router = new LIMETICKETRouter;

	return $router->parse($segments);
}
		  	 	   			  	