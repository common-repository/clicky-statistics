<?php
/*
Plugin Name: Clicky Statistics
Plugin URI: http://www.leungeric.com/2008/06/23/wordpress-plugin-clicky-statistics/
Description: A plugin to retrieve Clicky statistics of your website via Clicky API 2.0 with cache supported. Enable the plugin and <a href="options-general.php?page=cs">click here</a> to manage its options
Version: 1.0
Author: Eric Leung
Author URI: http://www.leungeric.com/
*/

/*
Copyright 2008 Eric Leung (email : eric@leungeric.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/* =============================================================================================================*/
/* = Parameters =================================================================================================*/
/* =============================================================================================================*/

$clicky_api_url = 'http://api.getclicky.com/stats/api2?output=php';
$plugin_title = 'Clicky Statistics';

/* =============================================================================================================*/
/* = Varables ===================================================================================================*/
/* =============================================================================================================*/

// data storage
$_opt_cs_obl = 'cs_clicky_obl';
$_opt_cs_icl = 'cs_clicky_icl';
$_opt_cs_icd = 'cs_clicky_icd';
$_opt_cs_p = 'cs_clicky_p';
$_opt_cs_av = 'cs_clicky_av';
$_opt_cs_c = 'cs_clicky_c';
$_opt_cs_ts = 'cs_clicky_ts';
$_opt_cs_sr = 'cs_clicky_sr';
$_opt_cs_v = 'cs_clicky_v';
$_opt_cs_a = 'cs_clicky_a';
$_opt_cs_tt = 'cs_clicky_tt';
$_opt_cs_vo = 'cs_clicky_vo';

// cicky account info
$_opt_cs_id = 'cs_clicky_id';
$_opt_cs_key = 'cs_clicky_key';
$_opt_cs_limit = 'cs_clicky_limit';
$_opt_cs_range = 'cs_clicky_range';

// string relace
$_opt_cs_original1 = 'cs_clicky_original1';
$_opt_cs_original2 = 'cs_clicky_original2';
$_opt_cs_replace1 = 'cs_clicky_replace1';
$_opt_cs_replace2 = 'cs_clicky_replace2';

// cache
$_opt_cs_cache_mins = 'cs_cache_mins';
$_opt_cs_cache_time = 'cs_cache_time';

/* =============================================================================================================*/
/* = Initiation ===================================================================================================*/
/* =============================================================================================================*/

add_action('wp_head', 'check_cs_cache');
add_action('admin_menu', 'add_cs_options');

function add_cs_options() {
	global $plugin_title;
	if (function_exists('add_options_page')) {
		add_options_page($plugin_title, $plugin_title, 8, 'cs', 'cs_options_subpanel');
	}
}
 
 /* =============================================================================================================*/
 /* = Options Panel and Form Processing ============================================================================*/
 /* =============================================================================================================*/

function cs_options_subpanel() {
	global $plugin_title;
	echo "<div class=\"wrap\"><h2>$plugin_title</h2>";
	if (!function_exists('curl_init')) {
		_show_col_curl_warning();	
	} else {
		if (isset($_POST['info_update'])) {
			global $_opt_cs_id, $_opt_cs_key, $_opt_cs_limit, $_opt_cs_cache_mins, $_opt_cs_range;
			global $_opt_cs_original1, $_opt_cs_original2, $_opt_cs_replace1, $_opt_cs_replace2;
	
			$clickyId = $_POST['clicky_id'];
			$clickyKey = $_POST['clicky_key'];
			$clickyLimit = $_POST['clicky_limit'];
			$clickyRange = $_POST['clicky_range'];
			$cacheMins = $_POST['cache_mins'];

			$clickyOriginal1 = $_POST['clicky_original1'];
			$clickyOriginal2 = $_POST['clicky_original2'];
			$clickyReplace1 = $_POST['clicky_replace1'];
			$clickyReplace2 = $_POST['clicky_replace2'];

			update_option($_opt_cs_id, $clickyId);
			update_option($_opt_cs_key, $clickyKey);
			update_option($_opt_cs_limit, $clickyLimit);
			update_option($_opt_cs_range, $clickyRange);
			update_option($_opt_cs_cache_mins, $cacheMins);

			update_option($_opt_cs_original1, $clickyOriginal1);
			update_option($_opt_cs_original2, $clickyOriginal2);
			update_option($_opt_cs_replace1, $clickyReplace1);
			update_option($_opt_cs_replace2, $clickyReplace2);
		}
		_show_cs_form();
	}
}

 /* =============================================================================================================*/
 /* = Options Panel Display ========================================================================================*/
 /* =============================================================================================================*/

function _show_cs_form() {
	?>
		<form method="post">
		<p>This plug-in retrieves <a href='http://getclicky.com/36624'>Clicky</a> statistics of your website via Clicky API 2.0 with cache supported.</p>
		<p>Before using this plugin, you need to register a  <a href='http://getclicky.com/36624'>Clicky</a> account, and setup <a href='http://api.getclicky.com/goodies/'>Clicky WordPress Plugin</a>.</p>
		<table width="100%" cellspacing="2" cellpadding="5" class="editform"> 

		<tr valign="top"> 
		<th width="120" scope="row"><label for="clicky_id"><?php _e('Clicky Site ID:') ?></label></th> 
		<td><input type="text" name="clicky_id" id="clicky_id" value="<?php form_option('cs_clicky_id'); ?>"/><br /><small><?php _e('What is your site ID? Go to your site\'s dashboard and look at the URL. You should see a "site_id=123" (example) on the end. In this case, 123 would be your site ID.') ?></small></td> 
		</tr>

		<tr valign="top"> 
		<th scope="row"><label for="clicky_key"><?php _e('Clicky Site Key:') ?></label></th> 
		<td><input type="text" name="clicky_key" id="clicky_key" value="<?php form_option('cs_clicky_key'); ?>"/><br /><small><?php _e('The sitekey is a 12-16 character string of random letters and numbers that is unique for every web site and is assigned when you first register your web site with Clicky. Because you don\'t "login" to the API like you would to a normal web site, every request must be authenticated by your sitekey. Available from site preferences page.') ?></small></td> 
		</tr>

		<tr valign="top">
		<th scope="row"><label for="clicky_range"><?php _e('Date Range:') ?></label></th>
		<td><input type="text" name="clicky_range" id="clicky_range" value="<?php form_option('cs_clicky_range'); ?>"/> <?php _e('(default: last-30-days)') ?><br /><small><?php _e('For example: today, yesterday, X-days-ago, last-X-days, previous-X-days, YYYY-MM, this-month, last-month, X-months-ago, this-week, last-week, X-weeks-ago') ?></small></td>
		</tr> 

		<tr valign="top">
		<th scope="row"><label for="clicky_limit"><?php _e('No. of Results:') ?></label></th>
		<td><input type="text" name="clicky_limit" id="clicky_limit" size="3" value="<?php form_option('cs_clicky_limit'); ?>"/> <?php _e('(default: 10)') ?><br /><small><?php _e('The maximum number of results that will be returned') ?></small></td>
		</tr> 

		<tr valign="top">
		<th scope="row"><label for="cache_mins"><?php _e('Cache Timeout:') ?></label></th>
		<td><input type="text" name="cache_mins" id="cache_mins" size="3" value="<?php form_option('cs_cache_mins'); ?>"/> <?php _e('minutes (default: 10)') ?></td>
		</tr> 

		<tr valign="top">
		<th scope="row"><label for="clicky_original1"><?php _e('String Replace:') ?></label></th>
		<td><input type="text" name="clicky_original1" id="clicky_original1"  value="<?php form_option('cs_clicky_original1'); ?>"/> to
		<input type="text" name="clicky_replace1" id="clicky_replace1"  value="<?php form_option('cs_clicky_replace1'); ?>"/></td>
		</tr> 

		<tr valign="top">
		<th scope="row"> </th>
		<td><input type="text" name="clicky_original2" id="clicky_original2"  value="<?php form_option('cs_clicky_original2'); ?>"/> to
		<input type="text" name="clicky_replace2" id="clicky_replace2"  value="<?php form_option('cs_clicky_replace2'); ?>"/><br /><small><?php $blogname = get_option('blogname');  _e("If you are using some SEO plugins, your site name will be appended in all page titles. Use String Replace to strip out the unwanted string for list items. For example, replace \"$blogname\" to \"Homepage\" or \" | $blogname\" to NIL.") ?></small></td>
		</tr> 

		<tr valign="top">
		<th scope="row"><label><?php _e('Usage:') ?></label></th>
		<td>Place <strong>&lt;?php get_clicky_statistics('STATISTIC_TYPE', 'STYLE'); ?&gt;</strong> where you would like the information to appear.<br /><br />
		<strong>STATISTIC_TYPE</strong> (default: summary)
		<ul>
		<li>summary (Clicky ranking, number of visitors, number of actions, total amount of time spent, visitors online)</li>
		<li>pages (popular pages on your site)</li>
		<li>traffic_sources (how visitors are arriving at your site)</li>
		<li>active_visitors (the people who have visited your site the most often)</li>
		<li>outbound_links (popular outbound links)</li>
		<li>incoming_links (popular incoming links)</li>
		<li>incoming_domains (popular incoming domains)</li>
		<li>countries (countries that your visitors are from)</li>
		</ul>
		<strong>STYLE</strong> (default: li)
		<ul>
		<li>li (use &lt;li class="clicky_statistics"&gt;&lt;/li&gt;)</li>
		<li>br (use &lt;br /&gt;)</li>
		</ul>
		</td>
		</tr> 
		</table> 
		<p class="submit">
		<input type="submit" name="info_update" value="<?php _e('Update Options') ?> &raquo;" />
		</p>
		</form>
		</div>
	<?php
}

 /* =============================================================================================================*/
 /* = Warning Display =============================================================================================*/
 /* =============================================================================================================*/

function _show_cs_curl_warning() {
	?>
		<div class="error">
		<h3>Clicky Statistics needs the php cURL library to be installed</h3>
		<p>Clicky Statistics uses the cURL php library to connect to the Clicky website. 
		This doesn't seem to be available with your current php configuration - it has 
		possibly been disabled in your php.ini file.<br /><br />Please contact your 
		System Administrator or Service Provider for information.</p>
		</div>	
	<?php
}

 /* =============================================================================================================*/
 /* = Display Stored Data ==========================================================================================*/
 /* =============================================================================================================*/

function get_clicky_statistics($type='summary', $style='li') {
	global $_opt_cs_obl, $_opt_cs_icl, $_opt_cs_icd, $_opt_cs_p, $_opt_cs_av, $_opt_cs_c, $_opt_cs_ts, $_opt_cs_sr, $_opt_cs_v, $_opt_cs_a, $_opt_cs_tt, $_opt_cs_vo;
	global $_opt_cs_original1, $_opt_cs_original2, $_opt_cs_replace1, $_opt_cs_replace2, $cr;
	global $_opt_cs_range;
	
	$clickyRange = get_option($_opt_cs_range);
	
	if($clickyRange == ''){
		$clickyRange = 'last-30-days';
	}
	
	if($type == 'outbound_links'){
		$msg = get_option($_opt_cs_obl);
	}else if ($type == 'incoming_links'){
		$msg = get_option($_opt_cs_icl);
	}else if ($type == 'incoming_domains'){
		$msg = get_option($_opt_cs_icd);
	}else if ($type == 'pages'){
		$msg = get_option($_opt_cs_p);
	}else if ($type == 'active_visitors'){
		$msg = get_option($_opt_cs_av);
	}else if ($type == 'countries'){
		$msg = get_option($_opt_cs_c);
	}else if ($type == 'traffic_sources'){
		$msg = get_option($_opt_cs_ts);
	}else if ($type == 'summary'){
		$msg = '
		<li class="clicky_statistics">Site Rank: '.get_option($_opt_cs_sr).'</li>
		<li class="clicky_statistics">Visitors: '.get_option($_opt_cs_v).'</li>
		<li class="clicky_statistics">Actions: '.get_option($_opt_cs_a).'</li>
		<li class="clicky_statistics">Time Spent: '.get_option($_opt_cs_tt).'</li>
		<li class="clicky_statistics">Visitors Online: '.get_option($_opt_cs_vo).'</li>
		<li class="clicky_statistics"><small>(<a href="http://getclicky.com/36624" alt="Clicky Web Analytics" title="Clicky Web Analytics">Clicky Statistics</a>: '.$clickyRange.')</small></li>
		';
	}
	
	$msg = str_replace(get_option($_opt_cs_original1), get_option($_opt_cs_replace1), $msg);
	$msg = str_replace(get_option($_opt_cs_original2), get_option($_opt_cs_replace2), $msg);

	$cr = "<!-- Clicky Statistics ($type : $style) from http://www.leungeric.com -->";
	
	if($style == 'br'){
		$msg = str_replace('<li class="clicky_statistics">','', $msg);
		$msg = str_replace('</li>','<br />', $msg);
		echo $cr.$msg.$cr;
	}else{
		echo $cr.$msg.$cr;
	}
}

 /* =============================================================================================================*/
 /* = Check Message Expiry  ========================================================================================*/
 /* =============================================================================================================*/

function check_cs_cache() {
	global $_opt_cs_cache_mins;
	global $_opt_cs_cache_time;

	$cache_mins = get_option($_opt_cs_cache_mins);

	if ($cache_mins == '') {
		$cache_mins = 10;
	}
	
	$cache_time = $cache_mins * 60;
	$now = time();
	$lsmod = get_option($_opt_cs_cache_time);
	
	if ($lsmod == '') {
		$lsmod = 0;
	}
	
	$cache_expired = ($now - $lsmod) > $cache_time;
	
	if ($cache_expired) {
		update_cs_message();
	}
}

 /* =============================================================================================================*/
 /* = Update Cache  ===============================================================================================*/
 /* =============================================================================================================*/

function update_cs_message() {
	global $clicky_api_url, $_opt_cs_id, $_opt_cs_key, $_opt_cs_limit, $_opt_cs_range, $_opt_cs_cache_time;
	global $_opt_cs_obl, $_opt_cs_icl, $_opt_cs_icd, $_opt_cs_p, $_opt_cs_av, $_opt_cs_c, $_opt_cs_ts, $_opt_cs_sr, $_opt_cs_v, $_opt_cs_a, $_opt_cs_tt, $_opt_cs_vo;

	$clickyId = get_option($_opt_cs_id);
	$clickyKey = get_option($_opt_cs_key);
	$clickyLimit = get_option($_opt_cs_limit);
	$clickyDate = get_option($_opt_cs_range);
	
	if($clickyDate == ''){
		$clickyDate = 'last-30-days';
	}

	if($clickyLimit == ''){
		$clickyLimit = '10';
	}

	if ($clickyId != '' && $clickyKey != '') {

		$title = get_cs_from_url($clicky_api_url."&date=$clickyDate&site_id=$clickyId&sitekey=$clickyKey&type=links-outbound&limit=$clickyLimit", 1);
		if($title != ''){	update_option($_opt_cs_obl, $title);	}

		$title = get_cs_from_url($clicky_api_url."&date=$clickyDate&site_id=$clickyId&sitekey=$clickyKey&type=links&limit=$clickyLimit", 1);
		if($title != ''){	update_option($_opt_cs_icl, $title);	}

		$title = get_cs_from_url($clicky_api_url."&date=$clickyDate&site_id=$clickyId&sitekey=$clickyKey&type=links-domains&limit=$clickyLimit", 1);
		if($title != ''){	update_option($_opt_cs_icd, $title);	}

		$title = get_cs_from_url($clicky_api_url."&date=$clickyDate&site_id=$clickyId&sitekey=$clickyKey&type=pages&limit=$clickyLimit", 1);
		if($title != ''){	update_option($_opt_cs_p, $title);	}

		$title = get_cs_from_url($clicky_api_url."&date=$clickyDate&site_id=$clickyId&sitekey=$clickyKey&type=visitors-most-active&limit=$clickyLimit", 0);
		if($title != ''){	update_option($_opt_cs_av, $title);	}

		$title = get_cs_from_url($clicky_api_url."&date=$clickyDate&site_id=$clickyId&sitekey=$clickyKey&type=countries&limit=$clickyLimit", 0);
		if($title != ''){	update_option($_opt_cs_c, $title);		}

		$title = get_cs_from_url($clicky_api_url."&date=$clickyDate&site_id=$clickyId&sitekey=$clickyKey&type=traffic-sources&limit=$clickyLimit", 0);
		if($title != ''){	update_option($_opt_cs_ts, $title);	}

		$title = get_cs_from_url($clicky_api_url."&date=$clickyDate&site_id=$clickyId&sitekey=$clickyKey&type=site-rank&limit=$clickyLimit", 2);
		if($title != ''){	update_option($_opt_cs_sr, $title);	}

		$title = get_cs_from_url($clicky_api_url."&date=$clickyDate&site_id=$clickyId&sitekey=$clickyKey&type=visitors&limit=$clickyLimit", 2);
		if($title != ''){	update_option($_opt_cs_v, $title);		}

		$title = get_cs_from_url($clicky_api_url."&date=$clickyDate&site_id=$clickyId&sitekey=$clickyKey&type=actions&limit=$clickyLimit", 2);
		if($title != ''){	update_option($_opt_cs_a, $title);		}

		$title = get_cs_from_url($clicky_api_url."&date=$clickyDate&site_id=$clickyId&sitekey=$clickyKey&type=time-total-pretty&limit=$clickyLimit", 2);
		if($title != ''){	update_option($_opt_cs_tt, $title);	}

		$title = get_cs_from_url($clicky_api_url."&date=$clickyDate&site_id=$clickyId&sitekey=$clickyKey&type=visitors-online&limit=$clickyLimit", 2);
		if($title != ''){	update_option($_opt_cs_vo, $title);	}

		update_option($_opt_cs_cache_time, time());
	}
}
	
 /* =============================================================================================================*/
 /* = Get Source  =================================================================================================*/
 /* =============================================================================================================*/

function get_cs_from_url($url,$type=1) {
	global $_opt_cs_range;
	$clickyLimit = get_option($_opt_cs_limit);

	$msg = '';
	$page = '';
	
	if (function_exists('curl_init')) {
		$curl_session = curl_init($url);
		curl_setopt($curl_session, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl_session, CURLOPT_CONNECTTIMEOUT, 4);
		curl_setopt($curl_session, CURLOPT_TIMEOUT, 8);
		$page = curl_exec($curl_session);
		curl_close($curl_session);
	}
	
	if ($page == '') {
		return '';
	}

	$source  = unserialize($page);
	foreach ($source AS $item){

		if($item['url'] == ''){
			$item['url'] = 'http://'.$item['title'];
		}
		
		if($type == 0){
			$msg .= "<li class=\"clicky_statistics\">".$item['title']."$ratioString (".$item['value_percent']."%)</li>";
		}else if ($type == 2){
			$msg .= $item['value'];
		}else {
			$msg .= "<li class=\"clicky_statistics\"><a href='".$item['url']."'>".$item['title']."</a> $ratioString</li>";
		}
	}
	return $msg;
}
?>