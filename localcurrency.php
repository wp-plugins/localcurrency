<?php
/*
Plugin Name: LocalCurrency
Plugin URI: http://www.jobsinchina.com/resources/wordpress-plugin-localcurrency/
Description: Show currency values to readers in their local currency (in brackets after the original value). For example: If the site?s currency is Chinese yuan and the post contains <em>10 yuan</em>, a user from Australia will see <em>10 yuan (AUD$1.53)</em>, while a user from US will see <em>10 yuan (USD$1.39)</em>.
Version: 2.1
Date: 28th September 2010
Author: Stephen Cronin
Author URI: http://www.scratch99.com/
   
   Copyright 2008 - 2010 Stephen Cronin  (email : sjc@scratch99.com)

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
    
Uses IP2C (http://firestats.cc/wiki/ip2c) to determine user's country
Uses Yahoo! Finance (http://finance.yahoo.com) for conversion rates
*/


// ****** SETUP ACTIONS AND FILTERS ******
register_activation_hook(__FILE__, 'create_localcurrency_options');
add_action('admin_menu', 'localcurrency_admin');
add_action('admin_footer', 'localcurrency_footer_admin');
add_action('wp_print_styles', 'localcurrency_scripts');
add_action('wp_head', 'localcurrency_head');
add_action('publish_page', 'localcurrency_publish');
add_action('publish_post', 'localcurrency_publish');
add_filter( 'plugin_action_links', 'localcurrency_settings_link', 10, 2 );
add_filter('the_content', 'localcurrency');		
// **************************************

// ****** FUNCTION TO CREATE OPTIONS AND DEFAULTS ON ACTIVATION ******
function create_localcurrency_options() {
	require_once('localcurrency-activate.php');
}
// ******************************************************************

// ****** FUNCTION TO CREATE THE ADMIN PAGE ******
function localcurrency_admin() {
	require_once('localcurrency-admin.php');
}
// **********************************************

// ****** CODE TO ADD SETTINGS LINK TO PLUGIN PAGE (2.8 Only) ******
function localcurrency_settings_link($links, $file) {
	// Static so we don't call plugin_basename on every plugin row. Thanks to Joost de Valk's Sociable for this code.
	static $this_plugin;
	if (!$this_plugin) {
		$this_plugin = plugin_basename(__FILE__);
	}
	if ($file == $this_plugin) {
		$settings_link = '<a href="options-general.php?page=localcurrency-admin.php">' . __('Settings') . '</a>';
		array_unshift( $links, $settings_link ); // before other links
	}
 	return $links;
}
// ****** END CODE TO ADD SETTINGS LINK TO PLUGIN PAGE ******

// ****** FUNCTION TO ADD LINKS AND STYLES TO HEAD SECTION ******
function localcurrency_head(){
	// Don't add script to the head unless this post has currency tags (keep page size down).
	global $post;
	$localcurrency_options = get_option('localcurrency_options');
	$content = $post->post_content;
	if (stristr($content,'<!--LCSTART')) {
		echo "<!-- Start of LocalCurrency plugin additions -->\n";
		// drop to HTML for the Script
?>
		<script type="text/javascript">
		//<![CDATA[
		// function to send ajax call to get rate via server 
		function localCurrency(lcfrom,lcto,lcvalues,postid) {
			// Generate the request object 
			if (window.XMLHttpRequest) { 	// Mozilla & other compliant browsers
				lc_request = new XMLHttpRequest();
			} else if (window.ActiveXObject) { 	// Internet Explorer
				lc_request = new ActiveXObject("Microsoft.XMLHTTP");
			}
			// If we couldn't create a request object, DO NOTHING.
			if (!lc_request) {
				return false;
			}
			// Open the connection, set where response will go and send request.
			lc_request.open('GET', '<?php echo wp_nonce_url(plugins_url(dirname(plugin_basename(__FILE__))).'/getexchangerate.php', 'localcurrency-lets-get-some-rates-shall-we'); ?>&from='+lcfrom+'&to='+lcto, true);
			lc_request.onreadystatechange = function() {
				if (lc_request.readyState == 4 && lc_request.status == 200) {
					// set the rate variable to the response from the server
					var lc_rate = lc_request.responseText;
					<?php if (current_user_can('update_plugins') && $localcurrency_options['debug'] == 'true') { ?>
						alert("Conversion Rate: " + lc_rate);
					<?php } ?>
					// if a rate is returned
					if (lc_rate) {
						// get the correct symbol for the user's currency
						var lcsymbol = '';
						for (var j = 0; j <= localcurrency_array.length-1; j++) {
							if (localcurrency_array[j]['id'] == lcto) {
								lcsymbol = localcurrency_array[j]['symbol'];
							}
						}
						// go through each currency occurrence. 
						for (var i = 0; i <= lcvalues.length-1; i++) { 
							lc_div = document.getElementById("localcurrency"+postid+"-"+i);	// get the div element for this occurrence
							if (lcfrom != lcto) {	// If from and to currency are different work out the value and update the div
								lc_converted = lcvalues[i] * lc_rate;
							<?php if ($localcurrency_options['hide_base_price'] == 'true') { ?>
								lc_div.innerHTML = '&nbsp;' + lcto + lcsymbol + lc_converted.toFixed(2);
							<?php } else { ?>
								lc_div.innerHTML = '&nbsp;(' + lcto + lcsymbol + lc_converted.toFixed(2) + ')';
							<?php } ?>
							<?php if (current_user_can('update_plugins') && $localcurrency_options['debug'] == 'true') { ?>
								alert("Value "+i+" : " + lcvalues[i]);
								alert("Converted "+i+" : " + lc_converted.toFixed(2));
							<?php } ?>
							} else {	// else set the div to empty (ie don't want $10(USD$10)).
							<?php if ($localcurrency_options['hide_base_price'] == 'true') { ?>
								lc_div.innerHTML = '&nbsp;' + lcto + lcsymbol + lcvalues[i];
							<?php } else { ?>
								lc_div.innerHTML ='';
							<?php } ?>
							}
						}
					}
					else {
						// go through each currency occurrence. 
						var lcsymbol = '';
						for (var j = 0; j <= localcurrency_array.length-1; j++) {
							if (localcurrency_array[j]['id'] == lcfrom) {
								lcsymbol = localcurrency_array[j]['symbol'];
							}
						}
						for (var i = 0; i <= lcvalues.length-1; i++) { 
							lc_div = document.getElementById("localcurrency"+postid+"-"+i);	// get the div element for this occurrence
							<?php if ($localcurrency_options['hide_base_price'] == 'true') { ?>
								lc_div.innerHTML = '&nbsp;' + lcfrom + lcsymbol + lcvalues[i];
							<?php } else { ?>
								lc_div.innerHTML ='';
							<?php } ?>
						}
					
					}
				}
			}
			lc_request.send(null);
		}
		//]]>
		</script>
<?php  
		echo "<!-- End of LocalCurrency plugin additions -->\n";	
	}
}
// *************************************************************

// ****** START FUNCTION: ECI_ADD_STYLESHEET - ENQUE CSS FILE ******
function localcurrency_scripts() {
	global $post;
	$content = $post->post_content;
	// only call them if the post includes the tag
	if (stristr($content,'<!--LCSTART')) {	
		$plugin_name = dirname(plugin_basename(__FILE__));
		wp_enqueue_script('localcurrency', plugins_url($plugin_name.'/includes/localcurrency.js'));
	}
}
// ***************************************************************

// ****** FUNCTION RUN ON PUBLISH TO INITIATE THE RATES REQUEST ******
function localcurrency_publish() {
	global $post;
	// if we haven't already stored current rates, get them and store them in post meta and in options
	if (!get_post_meta($post->ID, '_lc_rates', true)) {
		$lc_current_rates = get_from_yahoo();
		if ($lc_current_rates) {
			update_post_meta($post->ID, '_lc_rates', $lc_current_rates);
			update_post_meta($post->ID, '_lc_rates_date', time()-86400);
			$localcurrency_options = get_option('localcurrency_options');
			$localcurrency_options['exchange_rates'] = $lc_current_rates;
			$localcurrency_options['last_updated'] = time()-86400;
			update_option('localcurrency_options', $localcurrency_options);
		}
	}
}
// *****************************************************************

// ****** FUNCTION TO GET EXCHANGE RATES FROM YAHOO ******
function get_from_yahoo() {

	// set the host name variable and get the file
	$lc_date_to_get = date("Ymd",time()-86400);
	$lc_ajax_host = 'http://finance.yahoo.com/connection/currency-converter-cache?date='.$lc_date_to_get.'&output=json';
	$lc_ajax_host = 'http://localhost/wpr/fromyahoo.js';
	$lc_ajax_data = wp_remote_get($lc_ajax_host);

	// if it returns an error, then lets get out of here.
	if (is_wp_error($lc_ajax_data)) {
		return;
	}
	
	// grab the body, remove the JSONP wrapper, then decode it
	$lc_ajax_data = $lc_ajax_data['body'];
	$lc_ajax_data = str_replace("YAHOO.Finance.CurrencyConverter.addConversionRates(",'',$lc_ajax_data);
	//$lc_ajax_data = str_replace(",\n]\n}\n}\n);",']}}',$lc_ajax_data);
	$lc_ajax_data = substr($lc_ajax_data,0,strrpos($lc_ajax_data,',')).']}}';
	$lc_ajax_data = json_decode($lc_ajax_data,true);
	
	// if the output of json_decode isn't an array, let's get out of here.
	if (!is_array($lc_ajax_data)) {
		return;
	}

	// create an array to return
	$lc_array_to_return = array();
	foreach ($lc_ajax_data['list']['resources'] as $key => $value) {
		$lc_array_to_return[substr($value['resource']['fields']['symbol'],0,3)] = $value['resource']['fields']['price'];
	}
	
	return $lc_array_to_return;
}
// *******************************************************

// ****** MAIN FUNCTION ******
function localcurrency($content){
	global $post;
	$localcurrency_options = get_option('localcurrency_options');

	// only do anything if this post has currency tags (ie there is something to convert)
	if (stristr($content,'<!--LCSTART-->')) {
		// find out where the reader is from - if we can't find out, set default currency to $USD
		$post_id = $post->ID;
		$usercurrency = localcurrency_userlocation();
		if (!$usercurrency) {$usercurrency = 'USD';}
		// loop through content, extracting values, adding them to an array and replacing tags with the span tags.
		$i = 0;
		while (stristr($content,'<!--LCSTART-->')) {
			$startpos = strpos($content,'<!--LCSTART-->') + 14;
			$endpos = strpos($content,'<!--LCEND-->');
			$value = substr($content, $startpos, $endpos - $startpos);
			$value = preg_replace ('/[^\d]/', '', $value) ;
			$value = intval($value);
			$lc_values[$i] = $value;
			if ($localcurrency_options['hide_base_price'] == 'true') {
				$data = $data . substr($content, 0, $startpos - 15) . '<span id="localcurrency'. $post_id.'-'.$i . '"> '.substr($content, $startpos, $endpos - $startpos).'</span>';
			}
			else {
				$data = $data . str_replace('<!--LCSTART-->', '', substr($content, 0, $endpos)) . '<span id="localcurrency'. $post_id.'-'.$i . '"></span>';
			}
			$content = substr($content, $endpos+12);
			$i++;
		}
		// create script string to appeand to content. First create the value array in JavaScript.
		$script = "\n" . '<script type="text/javascript"> '. "\nvar lcValues$post_id = new Array(";
		if (count($lc_values)>1) { 
			foreach ($lc_values as $key => $value){
				if ($key < ($i-1)){
					$script .= $value . ',';
				}
				else {
					$script .= $value . ");\n";
				}
			}
		} 
		else {	// can't create an array that just has one integer element using Array()
			$script .= "1);\nlcValues".$post_id."[0]=" . $lc_values[0] . ";\n";
		}
		// call the function to convert the currencies in lcValues
		$script .= 'localCurrency("'.$localcurrency_options['site_currency'].'","'.$usercurrency.'",lcValues'.$post_id.",$post_id);\n</script>\n";
		// call function to create form which allows user to change currency. Note: action will never be used, but needed for validation
		$script .= '<br /><form name="lc_change'.$post_id.'" id="lc_change'.$post_id.'" action="'. get_permalink() .'" method="post">'."\nShow currencies in \n";
 		$script .= '<script type="text/javascript"> '. "\nlocalCurrencyUserSelection('".$localcurrency_options['site_currency']."','$usercurrency',$post_id);\n";
		$script .= "</script>\n<noscript>[Please enable JavaScript to change the currency used on this page]</noscript>\n";
		$script .= "<br /><small>Powered by";
		if ($localcurrency_options['link_on']=='true') {
			$script .= ' <a href="http://www.jobsinchina.com/resources/wordpress-plugin-localcurrency/" title="The LocalCurrency Plugin For WordPress" target="_blank">LocalCurrency</a>';
		} else {
			$script .= ' the LocalCurrency plugin for WordPress';
		}
		$script .= '. Rates from <a href="http://finance.yahoo.com" title="Visit Yahoo! Finance" target="_blank">Yahoo! Finance</a></small></form>'."\n";
		// return filtered content
		return $data . $content . $script;
	}
	return $content;
}
// *************************************************************

// ****** FUNCTION TO FIND WHERE USER IS FROM ******
function localcurrency_userlocation() {
	require_once(trailingslashit(WP_PLUGIN_DIR). dirname(plugin_basename(__FILE__)).'/ip2c/ip2c.php');
	$ip = $_SERVER['REMOTE_ADDR'];
	$ip2c = new ip2country();
	$res = $ip2c->get_country($ip);
	if ($res == false) {
	  return false;
	}  
	else
	{
		$localcurrency_options = get_option('localcurrency_options');
		return $localcurrency_options['currency_locationlist'][$res['id2']];
	}
}
// ************************************************

// ****** FUNCTION TO ADD QUICKTAGS TO WRITE POST PAGE ******
function localcurrency_footer_admin() {
	// only on the edit and new post screens
	if ((stristr($_SERVER['REQUEST_URI'],'wp-admin/post.php') || stristr($_SERVER['REQUEST_URI'],'wp-admin/post-new.php')) && current_user_can('update_plugins')) {
	
		// Based on WP-AddQuicktag (http://bueltge.de/wp-addquicktags-de-plugin/120/)
		$numberofstyles=1;	// allows for multiple styles, we only need one, but no need to change.
		echo <<<EOT
		<script type="text/javascript">		
			if(wpaqToolbar = document.getElementById("ed_toolbar")){
				var wpaqNr, wpaqBut, wpaqStart, wpaqEnd;
EOT;
				for ($i = 1; $i <= $numberofstyles; $i++){
					$b = array("text"=>"LocalCurrency","title"=>"Insert tags for LocalCurrency","start"=>"<!--LCSTART-->","end"=>"<!--LCEND-->");
					$start = preg_replace('![\n\r]+!', "\\n", $b['start']);
					$start = str_replace("'", "\'", $start);
					$end = preg_replace('![\n\r]+!', "\\n", $b['end']);
					$end = str_replace("'", "\'", $end);
					echo <<<EOT
							wpaqStart = '{$start}';
							wpaqEnd = '{$end}';
							wpaqNr = edButtons.length;
							edButtons[wpaqNr] = new edButton('ed_wpaq{$i}','{$b['txt']}',wpaqStart, wpaqEnd,'');
							var wpaqBut = wpaqToolbar.lastChild;
							while (wpaqBut.nodeType != 1){
								wpaqBut = wpaqBut.previousSibling;
							}
							wpaqBut = wpaqBut.cloneNode(true);
							wpaqToolbar.appendChild(wpaqBut);
							wpaqBut.value = '{$b['text']}';
							wpaqBut.title = '{$b['title']}';
							wpaqBut.buttonno = wpaqNr;
							wpaqBut.onclick = function () {edInsertTag(edCanvas, parseInt(this.buttonno));}
							wpaqBut.id = "ed_wpaq{$i}";
EOT;
					}
			echo <<<EOT
				}
			</script>
EOT;
		}
	}
// ********************************************************

?>