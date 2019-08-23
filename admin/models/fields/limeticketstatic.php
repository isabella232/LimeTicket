<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

JFormHelper::loadFieldClass('text');

class JFormFieldLIMETICKETStatic extends JFormFieldText
{
	protected $type = 'LIMETICKETStatic';

	protected function getLabel()
	{
		return "<div class='fsj_form_subsection_header'>".parent::getLabel() . "</div>";	
	}
	protected function getInput()
	{
		if (isset($this->element->content)) return $this->element->content;
	}
}
