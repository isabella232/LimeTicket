<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @deprecated e575cb3f9f64881ac9672b3e5e932a2f
**/
defined('_JEXEC') or die;


jimport( 'joomla.application.component.view');
jimport( 'joomla.mail.helper' );
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'pagination.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'email.php');

require_once (JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'helper'.DS.'comments.php');

class LimeticketViewTest extends LIMETICKETView
{
	var $product = null;

    function display($tpl = null)
    {
		LIMETICKET_Helper::StylesAndJS(array('accordion'));
		
		$mainframe = JFactory::getApplication();

		$user = JFactory::getUser();
		$userid = $user->id;
		$db = JFactory::getDBO();

		$this->params = LIMETICKET_Settings::GetViewSettingsObj('test');
		$this->test_show_prod_mode = $this->params->get('test_show_prod_mode','accordian');
		$this->test_always_prod_select = $this->params->get('test_always_prod_select','0');
		$layout = LIMETICKET_Input::getCmd('layout');
			
		$this->prodid = LIMETICKET_Input::getInt('prodid');
		if ($this->prodid == "")
			$this->prodid = -1;
				
		$this->products = $this->get('Products');
		//print_p($this->products);
		if (count($this->products) == 0)
			$this->prodid = 0;
		
		$this->comments = new LIMETICKET_Comments("test",$this->prodid);
		if ($this->prodid == -1)
			$this->comments->opt_show_posted_message_only = 1;

		$onlyprodid = LIMETICKET_Input::getCmd('onlyprodid','x');
		if ($onlyprodid != 'x' && $onlyprodid != -1)
		{
			$this->comments->itemid = (int)$onlyprodid;
			$this->comments->show_item_select = false;
		}

		if ($this->params->get('hide_add',0))
		{
			$this->comments->can_add = 0;
		}
			
		if ($layout == "create")
		{
			$this->setupCommentsCreate();	
		}
			
		if ($this->comments->Process())
			return;
			
		if ($layout == "create")
			return $this->displayCreate();
			
		if ($this->prodid != -1)
		{
			return $this->displaySingleProduct();	
		}

		return $this->displayAllProducts();
		
 	}
	
	function setupCommentsCreate()
	{
		$this->comments->opt_display = 0;
		$this->comments->comments_hide_add = 0;
		$this->comments->opt_show_form_after_post = 1;
		$this->comments->opt_show_posted_message_only = 1;
	}
	
	function displayCreate()
	{
		$this->tmpl = LIMETICKET_Input::getCmd('tmpl');
		parent::display();	
	}
	
	function displaySingleProduct()
	{
		$this->product = $this->get('Product');
		$this->products = $this->get('Products');	
		
		LIMETICKET_Translate_Helper::TrSingle($this->product);
 		LIMETICKET_Translate_Helper::Tr($this->products);
		
        $mainframe = JFactory::getApplication();
		$pathway = $mainframe->getPathway();
		if (LIMETICKET_Helper::NeedBaseBreadcrumb($pathway, array( 'view' => 'test' )))	
			$pathway->addItem(JText::_('TESTIMONIALS'), LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=test' ) );
        $pathway->addItem($this->product['title']);
		
		// no product then general testimonials
		if (!$this->product && count($this->products) > 0)
		{
			$this->product = array();
			$this->product['title'] = JText::_('GENERAL_TESTIMONIALS');	
			$this->product['id'] = 0;
			$this->product['description'] = '';
			$this->product['image'] = '/components/com_limeticket/assets/images/generaltests.png';
		}
		
		if ($this->test_always_prod_select)
		{
			$this->comments->show_item_select = 1;
		} else {
			$this->comments->show_item_select = 0;
		}
		
		$this->comments->PerPage(LIMETICKET_Settings::Get('test_comments_per_page'));
				
		parent::display("single");
	}
	
	function displayAllProducts()
	{
		$this->products = $this->get('Products');
		if (!is_array($this->products))
			$this->products = array();
 		LIMETICKET_Translate_Helper::Tr($this->products);
		
		$this->showresult = 1;
		
        $mainframe = JFactory::getApplication();
        $pathway = $mainframe->getPathway();
		if (LIMETICKET_Helper::NeedBaseBreadcrumb($pathway, array( 'view' => 'test' )))	
			$pathway->addItem(JText::_('TESTIMONIALS'), LIMETICKETRoute::_( 'index.php?option=com_limeticket&view=test' ) );
 
		if (LIMETICKET_Settings::get('test_allow_no_product'))
		{
			$noproduct = array();
			$noproduct['id'] = 0;
			$noproduct['title'] = JText::_('GENERAL_TESTIMONIALS');
			$noproduct['description'] = '';
			$noproduct['image'] = '/components/com_limeticket/assets/images/generaltests.png';
			$this->products = array_merge(array($noproduct), $this->products);
		}
		
		if ($this->test_show_prod_mode != "list")
		{
			$idlist = array();
			if (count($this->products) > 0)
			{
				foreach($this->products as &$prod) 
				{
					$prod['comments'] = array();
					$idlist[] = $prod['id'];	
				}
			}
			
			// not in normal list mode, get comments for each product
			
			$this->comments->itemid = $idlist;
			
			$this->comments->GetComments();
						
			foreach($this->comments->_data as &$data)
			{
				if ($data['itemid'] > 0)
					$this->products[$data['itemid']]['comments'][] = $data;
			}
		}
		
		parent::display();
	}
}

