<?php
/**
 * @package LimeTicket Support System
 * @author LimeSurvey GmbH / Freestyle Joomla
 * @copyright (C) 2019 LimeSurvey GmbH  /  Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');
jimport('joomla.application.component.model');
jimport('joomla.installer.installer');
jimport('joomla.installer.helper');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.archive');
jimport('joomla.filesystem.path');


class Status {
	var $STATUS_FAIL = 'Failed';
	var $STATUS_SUCCESS = 'Success';
	var $infomsg = array();
	var $errmsg = array();
	var $status;
}
$install_status = array();
global $install_status;

function InstallExtras()
{
	global $install_status;
	
	$pkg_path = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_limeticket'.DS.'extensions'.DS;
	
	if ($dir = opendir($pkg_path))
	{
		while (($file = readdir($dir)) !== false) 
		{
			if ($file == "." || $file == "..") continue;
			if (stripos($file,".zip") < 1) continue;
			
			$installer = new JInstaller();
			$installer->setOverwrite(true);	
			
			$status = new Status();
			$status->file = $pkg_path.$file;
			$status->status = $status->STATUS_FAIL;

			$package = JInstallerHelper::unpack( $pkg_path.$file );
			$status->package = print_r($package,true);
			if( $installer->install( $package['dir'] ) )
			{
				$status->status = $status->STATUS_SUCCESS;
			}
			else
			{
				$status->errmsg[]="Unable to install $file";
			}
		
			$install_status[$file] = $status;
		
			JInstallerHelper::cleanupInstall( $pkg_path.$file, $package['dir'] );
		}
	}
}

function com_install()
{
	require_once (JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_limeticket'.DS.'updatedb.php');
	
	$updater = new LIMETICKETUpdater();
	$updater->Process();
	
	// think this has to be done last
	InstallExtras();
	
	LIMETICKET_Done();
}

function LIMETICKET_Done()
{
	global $install_status;
?>

<h1>LimeTicket Support Portal Installation Completed</h1>
<?php if (count($install_status) > 0): ?>
<table class="adminlist table table-striped">
	<thead>
		<tr>
			<th class="title">Sub Component</th>
			<th width="60%">Status</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
	</tfoot>
	<tbody>
	<?php
		$i=0; 
		foreach ( $install_status as $component => $status ) {?>
		<tr class="row<?php echo $i; ?>">
			<td class="key"><?php echo $component; ?></td>
			<td>
				<?php echo ($status->status == $status->STATUS_SUCCESS)? '<strong>Installed</strong>' : '<em>Not Installed</em>'?>
				<?php if (count($status->errmsg) > 0 ) {
						foreach ( $status->errmsg as $errmsg ) {
       						echo '<br/>Error: ' . $errmsg;
						}
				} ?>
				<?php if (count($status->infomsg) > 0 ) {
						foreach ( $status->infomsg as $infomsg ) {
       						echo '<br/>Info: ' . $infomsg;
						}
				} ?>
			</td>
		</tr>	
	<?php
			if ($i=0){ $i=1;} else {$i = 0;}; 
		}?>
		
	</tbody>
</table>
<?php endif; ?>	

<?php
}
?>



