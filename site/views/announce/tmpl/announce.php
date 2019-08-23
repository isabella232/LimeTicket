<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;
?>
<?php echo LIMETICKET_Helper::PageStyle(); ?>
<?php $announce = $this->announce; ?>
<?php echo LIMETICKET_Helper::PageTitle("ANNOUNCEMENTS",$announce['title']); ?>

<?php 

$this->parser->SetVar('editpanel', $this->content->EditPanel($announce));
$this->parser->SetVar('date', LIMETICKET_Helper::Date($announce['added'],LIMETICKET_DATE_MID));
$this->parser->setVar('title', LIMETICKET_Helper::PageSubTitle($announce['title']));
$this->parser->setVar('subtitle', $announce['subtitle']);

$authid = $announce['author'];
$user = JFactory::getUser($authid);
if ($user->id > 0)
{
	$this->parser->setVar('author', $user->name);	
	$this->parser->setVar('author_username', $user->username);	
} else {
	$this->parser->setVar('author', JText::_('UNKNOWN'));	
	$this->parser->setVar('author_username', JText::_('UNKNOWN'));	
}

if (LIMETICKET_Settings::get( 'glossary_announce' )) {
	$this->parser->setVar('body', LIMETICKET_Glossary::ReplaceGlossary($announce['body'])); 
} else {
	$this->parser->setVar('body', $announce['body']); 
}

if (LIMETICKET_Settings::get( 'glossary_announce' )) {
	$this->parser->setVar('fulltext', LIMETICKET_Glossary::ReplaceGlossary($announce['fulltext'])); 
} else {
	$this->parser->setVar('fulltext', $announce['fulltext']); 
}

echo $this->parser->Parse();

if (LIMETICKET_Settings::get('announce_comments_allow') == 1)
{
	$this->comments->DisplayComments();
} else if (LIMETICKET_Settings::get('announce_comments_allow') == 2)
{
	$comments = JPATH_SITE . '/components/com_jcomments/jcomments.php';
	if (file_exists($comments)) {
		require_once($comments);
		echo JComments::showComments($announce['id'], 'com_limeticket_announce', $announce['title']);
	}
}

?>

<?php include JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'_powered.php'; ?>

<?php if (LIMETICKET_Settings::get( 'glossary_announce' )) echo LIMETICKET_Glossary::Footer(); ?>

<?php echo LIMETICKET_Helper::PageStyleEnd(); ?>

<script>
<?php include JPATH_SITE.DS.'components'.DS.'com_limeticket'.DS.'assets'.DS.'js'.DS.'content_edit.js'; ?>
</script>
