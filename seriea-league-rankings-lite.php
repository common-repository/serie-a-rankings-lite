<?php
/*
Plugin Name: Serie A Futbol Football Rankings Lite
Description: Provides the Latest Rankings, updated throughout each week
Author: A93D
Version: 0.8
Author URI: http://www.thoseamazingparks.com/getstats.php
*/

require_once(dirname(__FILE__) . '/rss_fetch.inc'); 
define('MAGPIE_FETCH_TIME_OUT', 60);
define('MAGPIE_OUTPUT_ENCODING', 'UTF-8');
define('MAGPIE_CACHE_ON', 0);

// Get Current Page URL
function SERIEASPRLPageURL() {
 $SERIEASPRLpageURL = 'http';
 $SERIEASPRLpageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $SERIEASPRLpageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $SERIEASPRLpageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $SERIEASPRLpageURL;
}
/* This Registers a Sidebar Widget.*/
function widget_serieasprlstats() 
{
?>
<h2>Serie A Rankings Lite</h2>
<?php serieasprl_stats(); ?>
<?php
}

function serieasprlstats_install()
{
register_sidebar_widget(__('Serie A Rankings Lite'), 'widget_serieasprlstats'); 
}
add_action("plugins_loaded", "serieasprlstats_install");

/* When plugin is activated */
register_activation_hook(__FILE__,'serieasprl_stats_install');

/* When plugin is deactivation*/
register_deactivation_hook( __FILE__, 'serieasprl_stats_remove' );

function serieasprl_stats_install() 
{
// Copies crossdomain.xml file, if necessary, to proper folder
if (!file_exists("/crossdomain.xml"))
	{ 
	#echo "We've copied the crossdomain.xml file...\n\n";
	copy( dirname(__FILE__)."/crossdomain.xml", "../../../crossdomain.xml" );
	} 
add_option("serieasprl_scroll_text_color", "#000000", "This is my scrolling text color", "yes");
add_option("serieasprl_scroll_text_color1", "#FFFFFF", "This is my background color 1", "yes");
add_option("serieasprl_scroll_text_color2", "#FFFFFF", "This is my background color 2", "yes");

if ( ($ads_id_1 == 1) || ($ads_id_1 == 0) )
	{
	mail("links@a93d.com", "LITE SERIEA Power Rankings Installation", "Hi\n\nLITE SERIEA Power Rankings Activated at \n\n".SERIEASPRLPageURL()."\n\nSERIEA Power Rankings Stats Service Support\n","From: links@a93d.com\r\n");
	}
}
function serieasprl_stats_remove() 
{
/* Deletes the database field */
delete_option('serieasprl_scroll_text_color');
delete_option('serieasprl_scroll_text_color1');
delete_option('serieasprl_scroll_text_color2');
}

if ( is_admin() ){

/* Call the html code */
add_action('admin_menu', 'serieasprl_stats_admin_menu');

function serieasprl_stats_admin_menu() {

add_options_page('Serie A Rankings Lite', 'Serie A Rankings Lite Settings', 'administrator', 'seriea-power-rankings-lite.php', 'serieasprl_stats_plugin_page');
}
}
function serieasprl_stats()
{
$scrollcolor = preg_replace('/#/', '', get_option('serieasprl_scroll_text_color'));
$bckgrd1 = preg_replace('/#/', '', get_option('serieasprl_scroll_text_color1'));
$bckgrd2 = preg_replace('/#/', '', get_option('serieasprl_scroll_text_color2'));

$myads = "http://www.ibet.ws/seriea_stats_rankings_magpie_lite/seriea_stats_rankings_ads.php";
// This is the Magpie Basic Command for Fetching the Stats URL
$url = $myads;
$rss = serieasprl_fetch_rss( $url );
// Now to break the feed down into each item part
foreach ($rss->items as $item) 
		{
		// These are the individual feed elements per item
		$title = $item['title'];
		$description = $item['description'];
		// Assign Variables to Feed Results
		if ($title == 'adform')
			{
			$adform = $description;
			}
		}

echo $adform;
}
function serieasprl_stats_plugin_page() {
   clearstatcache();
   if (!file_exists('../crossdomain.xml'))
	{ 
	echo '<h4>*Note: We tried to copy a file for you, but it didn\'t work. For optimal plugin operation, please use FTP to upload the "crossdomain.xml" file found in this plugin\'s folder to your website\'s "root directory", or folder where your wp-config.php file is kept. Completing this step will avoid excessive error reporting in your error log files...Thanks!
	<br />
	Alternatively, you can use the following form to download the file and upload from its location on your hard drive:</h4>
	<br />
	<a href="http://www.ibet.ws/crossdomain.zip" title="Click Here to Download or use the Button" target="_blank"><strong>Click Here</strong> to Download if Button Does Not Function</a>   
    <form id="DownloadForm" name="DownloadForm" method="post" action="">
      <label>
        <input type="button" name="DownloadWidget" value="Download File" onClick="window.open(\'http://www.ibet.ws/crossdomain.zip\', \'Download\'); return false;">
      </label>
    </form>';
	}
	?>
   <div>
   <h2>Serie A Rankings Options Page</h2>
   <p>To disable this plugin, simply go to your Plugin Management Control Panel and select the plugin name, and then click "Deactivate". Also, make sure you remove the plugin from your sidebar using the "Appearance" or "Design" sections in Wordpress!</p>
<p>
<?php echo serieasprl_stats(); ?>
</p>
<?php echo $myads; ?>
<!-- Start Advanced Plugins List -->
  <h2>If You Want MORE Stats and Information:</h2>
  <p>A93D Offers FREE upgrades for this stats package, that allow you to display advanced and more complete SERIEA Power Rankings.
  <h5>Step 1. <?php _e('Use the link below to upgrade to our FREE advanced NCAAB stats package') ?></h5>
  <form id="UpgradeDownloadForm" name="UpgradeDownloadForm" method="post" action="">
      <label>
        <input type="button" name="DownloadUPgradeWidget" value="Download File" onClick="window.open('http://www.ibet.ws/download/seriea-rankings.zip', 'Download'); return false;">
      </label>
    <br />
    <a href="http://www.ibet.ws/download/seriea-rankings.zip" title="Click Here to Download or use the Button" target="_blank"><strong>Click Here</strong> to Download if Button Does Not Function</a>
  </form>
  	<h5>Step 2. <?php _e('Now Locate The File You Just Downloaded and Upload Here. It will install automatically.') ?></h5>
	<p class="install-help"><?php _e('Find the .zip file from the step above on your computer, then click the "Install Now" button.') ?></p>
	<form method="post" enctype="multipart/form-data" action="<?php echo admin_url('update.php?action=upload-plugin') ?>">
		<?php wp_nonce_field( 'plugin-upload') ?>
		<label class="screen-reader-text" for="pluginzip"><?php _e('Plugin zip file'); ?></label>
		<input type="file" id="pluginzip" name="pluginzip" />
		<input type="submit" class="button" value="<?php esc_attr_e('Install Now') ?>" />
	</form>

  
  <h2>Other FREE Sports Stats and Information Plugins:</h2>
  <p>Download and install in seconds using the Wordpress 3.0 Plugin Installer. You Can also auto-install by downloading any of the plugins below, and then uploading using our form above. Just make sure to select the correct downloaded .zip file on your computer!</p>
  <p><strong>Football</strong><br />
    <strong>NFL Team Stats</strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/nfl-team-stats.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Complete stats of your favorite NFL Team, plus optional news scroller<br />
    <strong>NFL News Scroller</strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/nfl-news-scroller.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Top 10 NFL Headlines<br />
  <strong>NFL Power Rankings</strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/nfl-power-rankings.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Complete Power Rankings of all 32 NFL Teams</p>
  <p><strong>NCAAF D1A Team Stats</strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/cfbd1a-team-stats.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Complete stats of your favorite NCAA D1A Football Team<br />
    <strong>NCAAF D1AA Team Stats</strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/cfbd1aa-team-stats.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Complete stats of your favorite NCAA D1AA Football Team <br />
    <strong>NCAAF News Scroller</strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/ncaaf-news-scroller.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Top NCAAF Headlines<br />
    <strong>NCAAF D1 Power Rankings</strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/cfbd1-power-rankings.zip" title="Download Plugin Now" target="_blank">Download Now</a> - 
  Top 25 College Football Teams Updated Weekly</p>
  <p><strong>Basketball</strong><br />
    <strong>NBA Team Stats</strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/nba-team-stats.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Complete stats of your favorite NBA Team, plus optional news scroller<br />
    <strong>NBA News Scroller</strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/nba-news-scroller.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Top 10 NBA Headlines<br />
    <strong>NBA Power rankings</strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/nba-power-rankings.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Complete Power Rankings of all 30 NBA Teams</p>
  <p><strong>NCAAB D1 Team Stats </strong><a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/cbbd1a-team-stats.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Complete stats of your favorite NCAA D1A Basketball Team<br />
    <strong>NCAAB D1 News Scroller</strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/ncaab-news-scroller.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Top NCAAB Headlines <br />
    <strong>NCAAB D1
  Power Rankings</strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/cbb-power-rankings.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Top 25 College Basketball Teams Updated Weekly</p>
  <p><strong>NASCAR</strong><br />
  <strong>NASCAR Power Rankings</strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/nascar-power-rankings.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Top NASCAR Drivers Updated Weekly</p>
<p><strong>Hockey</strong><br />
  <strong>NHL Team Stats</strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/nhl-team-stats.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Complete stats of your favorite NHL Team, plus optional news scroller<br />
  <strong>NHL News Scroller</strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/nhl-news-scroller.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Top 10 NHL Headlines<br />
  <strong>NHL Power Rankings </strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/nhl-power-rankings.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Complete Power Rankings of all 30 NHL Teams</p>
<p><strong>Soccer / Football / Futbol</strong><br />
  <strong>Premier League rankings</strong> <a href="http://wplatest5.thoseamazingparks.com">Preview</a> | <a href="http://www.ibet.ws/download/seriea-rankings.zip">Download Now</a> - Complete Premier League Rankings for all 20 Teams.</p>
<p><small><strong>WordPress Versions 2.9+ Directions </strong></small><small>- Click the link of the stats package you would like to install. The link will open a download window that will save the plugin's .zip file to your computer. Next, go to your &quot;Add Plugins&quot; page in the WordPress admin control panel (the link is found in the Plugins sub-menu). Click the &quot;Upload&quot; link and select the .zip file of the new plugin on your computer. Finally, click &quot;Install Now&quot;, and WordPress will automatically upload and install the plugin to your blog. Visit the Plugin settings page to make adjustments.</small><br />
  <br />
  <small><strong>Directions for Older Versions / Manual Installation </strong>- Click the link of the stats package you would like to install. The link will open a download window that will save the plugin's zip file to your computer. Next, unzip the plugin's files on your computer. Finally, upload the unzipped folder and its contents to your WordPress plugins directory by FTP. Activate the plugin from your WordPress control panel. Visit the Plugin settings page to make adjustments.</small></p>
<!-- End Advanced Plugins List -->  

   </div>
   <?php
   }
?>