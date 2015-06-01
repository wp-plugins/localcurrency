<?php
/*
Plugin Name: LocalCurrency
Plugin URI: http://www.jobsinchina.com/resources/wordpress-plugin-localcurrency/
Description: Show currency values to readers in their local currency (in brackets after the original value). For example: If the site's currency is Chinese yuan and the post contains <em>10 yuan</em>, a user from Australia will see <em>10 yuan (AUD$1.53)</em>, while a user from US will see <em>10 yuan (USD$1.39)</em>.
Version: 2.9
Date: 1st June 2015
Author: Stephen Cronin
Author URI: http://scratch99.com/
   
   Copyright 2008 - 2015 Stephen Cronin  (email : sjc@scratch99.com)

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

/*
Note: Parts of this plugin are 7 years old and it is due a total rewrite, which is planned for version 3.0
*/

// set the current plugin version
$localcurrency_version = '2.9';

// get the options
$localcurrency_options = get_option('localcurrency_options');

// ****** SETUP ACTIONS AND FILTERS ******
add_action('admin_menu', 'localcurrency_admin');
add_action( 'admin_print_footer_scripts', 'localcurrency_add_quicktags' );
add_action('wp_print_styles', 'localcurrency_scripts');
add_action('wp_head', 'localcurrency_head');
add_action('publish_page', 'localcurrency_publish');
add_action('publish_post', 'localcurrency_publish');
add_filter( 'plugin_action_links', 'localcurrency_settings_link', 10, 2 );
add_filter('the_content', 'localcurrency', $localcurrency_options['priority']);		
// **************************************

// ****** FUNCTION TO UPDATE THE OPTIONS ON UPGRADE (IF NECESSARY) ******
function update_localcurrency_options() {

	// get the plugin options
	$localcurrency_options = get_option('localcurrency_options');

	// if it's not the latest version, let's make sure that we have all the settings we need for this version
	if ( empty( $localcurrency_options[ 'version' ] ) || version_compare( $localcurrency_version, $localcurrency_options[ 'version' ], '>' ) ) {
		$localcurrency_options[ 'version' ] = $localcurrency_version;
		require_once('localcurrency-activate.php');
		update_option('localcurrency_options', $localcurrency_options);
	}

}
add_action( 'init', 'update_localcurrency_options', 0 );
// **********************************************************************

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
	if (stristr($content,'<!--LCSTART') || has_shortcode( $content, 'localcurrency' ) || get_post_meta($post->ID, 'force_lc', true) == 1 ) {
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
			// parse the passed array of conversions and extract from values so we can loop through them
			lcvaluesSorted = lcvalues.slice().sort(function(a,b) {return (a.from > b.from) ? 1 : ((b.from > a.from) ? -1 : 0);} );
			var lcCurrentCurrency = "";
			var lcCurrenciesToConvert = [];
			for (var i = 0; i < lcvaluesSorted.length; i++) {
				if (lcvaluesSorted[i].from != lcCurrentCurrency){
					lcCurrentCurrency = lcvaluesSorted[i].from;
					lcCurrenciesToConvert.push(lcvaluesSorted[i].from);
				}
			}
			// now loop through the from values and get the rates needed
			for (i = 0; i < lcCurrenciesToConvert.length; i++) {

				lcfrom = lcCurrenciesToConvert[i];

				//if we only have 1 currency on this page, use async as it's quicker
				if (lcCurrenciesToConvert.length==1) {
					var lcAsync = true;
				}
				else {
					var lcAsync = false;
				}

				// Open the connection, set where response will go and send request.
				lc_request.open('GET', '<?php echo wp_nonce_url(plugins_url(dirname(plugin_basename(__FILE__))).'/getexchangerate.php', 'localcurrency-lets-get-some-rates-shall-we'); ?>&from='+lcfrom+'&to='+lcto, lcAsync);
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
							for (var i = 0; i < lcvalues.length; i++) {
								// only do soemthing if this one is the current from value
								if ( lcfrom == lcvalues[i].from) {
									lc_div = document.getElementById("localcurrency"+postid+"-"+i);	// get the div element for this occurrence
									if (lcfrom != lcto) {	// If from and to currency are different work out the value and update the div
										// if the value has a "_" then it's a range, else it's a single value
										if ( lcvalues[i].amount.indexOf('_') != -1 ) {
											var thisValueArray = lcvalues[i].amount.split("_");
										<?php if ($localcurrency_options['hide_base_price'] == 'true') { ?>
											lc_converted =  '&nbsp;' + lcto + lcsymbol + (thisValueArray[0] * lc_rate).toFixed(2) + ' - ' + lcsymbol + (thisValueArray[1] * lc_rate).toFixed(2);
										<?php } else { ?>
											lc_converted = '&nbsp;(' + lcto + lcsymbol + (thisValueArray[0] * lc_rate).toFixed(2) + ' - ' + lcsymbol + (thisValueArray[1] * lc_rate).toFixed(2) + ')';
										<?php } ?>
										}
										else {
										<?php if ($localcurrency_options['hide_base_price'] == 'true') { ?>
											lc_converted =  '&nbsp;' + lcto + lcsymbol + (lcvalues[i].amount * lc_rate).toFixed(2);
										<?php } else { ?>
											lc_converted =  '&nbsp;(' + lcto + lcsymbol + (lcvalues[i].amount * lc_rate).toFixed(2) + ')';
										<?php } ?>
										}
										lc_div.innerHTML = lc_converted;
									<?php if (current_user_can('update_plugins') && $localcurrency_options['debug'] == 'true') { ?>
										alert("Value "+i+" : " + lcvalues[i].amount);
										alert("Converted "+i+" : " + lc_converted);
									<?php } ?>
									} else {	// else set the div to empty (ie don't want $10(USD$10)).
									<?php if ($localcurrency_options['hide_base_price'] == 'true') { ?>
										lc_div.innerHTML = '&nbsp;' + lcto + lcsymbol + lcvalues[i].amount;
									<?php } else { ?>
										lc_div.innerHTML ='';
									<?php } ?>
									}
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
	if (stristr($content,'<!--LCSTART') || has_shortcode( $content, 'localcurrency' ) || get_post_meta($post->ID, 'force_lc', true) == 1 ) {
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
	$lc_ajax_data = wp_remote_get($lc_ajax_host);
	
	// if it returns an error, then lets get out of here.
	if (is_wp_error($lc_ajax_data)) {
		return;
	}
	
	// grab the body, remove the JSONP wrapper, then decode it
	$lc_ajax_data = $lc_ajax_data['body'];
	$lc_ajax_data = str_replace("YAHOO.Finance.CurrencyConverter.addConversionRates(",'',$lc_ajax_data);
	$lc_ajax_data = substr($lc_ajax_data,0,strrpos($lc_ajax_data,');'));
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
			// cater for ranges
			$value = str_replace( '-' , '_' , $value );
			$value = preg_replace ('/[^0-9._]*/', '', $value);
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

		// create script string to appand to content. First create the value array in JavaScript.
		$script = "\n" . '<script type="text/javascript">'. "\n";
		$script .= "function lcValue(from,to,amount) {
	  this.from = from;
	  this.to = to;
	  this.amount = amount;
	}";
		$script .= "\nvar lcValues$post_id = new Array();\n";
		foreach ($lc_values as $key => $value){
			$script .= 'lcValues' . $post_id . '[' . $key . '] = new lcValue("' . $localcurrency_options['site_currency'] . '","' . $usercurrency . '","' . $value . '");' . "\n";
		}
		// call the function to convert the currencies in lcValues
		$script .= 'localCurrency("'.$localcurrency_options['site_currency'].'","'.$usercurrency.'",lcValues'.$post_id.",$post_id);\n</script>\n";
		$script .= "</script>\n";

		// call function to create form which allows user to change currency. Note: action will never be used, but needed for validation
		$script .= '<br /><form name="lc_change'.$post_id.'" id="lc_change'.$post_id.'" action="'. get_permalink() .'" method="post">'."\nShow currencies in \n";
 		$script .= '<script type="text/javascript"> '. "\nlocalCurrencyUserSelection('".$localcurrency_options['site_currency']."','$usercurrency',$post_id);\n";
		$script .= "</script>\n<noscript>[Please enable JavaScript to change the currency used on this page]</noscript>\n";
		$script .= "<br /><small>Powered by";
		if ($localcurrency_options['link_on']=='true') {
			$script .= ' <a href="http://www.jobsinchina.com/resources/wordpress-plugin-localcurrency/" title="The LocalCurrency Plugin For WordPress" target="_blank">LocalCurrency</a>';
		} else {
			$script .= ' LocalCurrency';
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

// ****** FUNCTION TO ADD QUICKTAG ******
function localcurrency_add_quicktags() {
	if ( wp_script_is( 'quicktags' ) ) {
?>
	<script type="text/javascript">
	QTags.addButton( 'localcurrency', 'LocalCurrency', '[localcurrency]', '[/localcurrency]', '', 'Insert tags for LocalCurrency' );
	</script>
<?php
	}
}
// **************************************


// ****** DEAL WITH SHORTCODES ******

// global array of values added via shortcode
$lc_sc_values = array();

// [localcurrency showselect="true"]$65[/localcurrency]
function localcurrency_shortcode( $atts, $content ) {

	// get options and set default from currency
	$localcurrency_options = get_option('localcurrency_options');
	if ( empty( $localcurrency_options['site_currency'] ) ) {
		$localcurrency_options['site_currency'] = 'USD';
	}

	extract( shortcode_atts( array(
		'from' => $localcurrency_options['site_currency'],
		'showselect' => 'true'
	), $atts ) );

	global $post, $lc_sc_values;

	// find out where the reader is from - if we can't find out, set default currency to $USD
	$post_id = $post->ID;
	$usercurrency = localcurrency_userlocation();
	if (!$usercurrency) {
		$usercurrency = 'USD';
	}

	// cater for ranges
	$value = str_replace( '-' , '_' , $content );
	$value = preg_replace ('/[^0-9._]*/', '', $value );
	$lc_sc_values[] = array( 'from' => $from, 'to' => $usercurrency, 'value' => $value );
	if ($localcurrency_options['hide_base_price'] == 'true') {
		$content = '<span id="localcurrency'. $post_id .'-'. (count($lc_sc_values)-1) . '"> '.$content.'</span>';
	}
	else {
		$content = $content . '<span id="localcurrency'. $post_id.'-'. (count($lc_sc_values)-1) . '"></span>';
	}

	return $content;

}
add_shortcode( 'localcurrency', 'localcurrency_shortcode' );



function localcurrency_shortcode_script( $content ) {

	// check if $lc_sc_values contains anything. If so, the shortcode has been used and we need to add the JavaScript to process it, otherwise, let's bail.
	global $lc_sc_values;
	if ( count( $lc_sc_values ) <= 0 ) {
		return $content;
	}

	// get the post id and the options.
	global $post;
	$post_id = $post->ID;
	$localcurrency_options = get_option('localcurrency_options');

	// create script string to appand to content. First create the value array in JavaScript.
	$script = "\n" . '<script type="text/javascript">'. "\n";
	$script .= "function lcValue(from,to,amount) {
  this.from = from;
  this.to = to;
  this.amount = amount;
}";
	$script .= "\nvar lcValues$post_id = new Array();\n";
	foreach ($lc_sc_values as $key => $value){
		$script .= 'lcValues' . $post_id . '[' . $key . '] = new lcValue("' . $value['from'] . '","' . $value['to'] . '","' . $value['value'] . '");' . "\n";
	}
	// call the function to convert the currencies in lcValues
	$script .= 'localCurrency(lcValues' . $post_id . '[0].from,lcValues' . $post_id . '[0].to,lcValues'.$post_id.',' . $post_id . ');' . "\n";
	$script .= "</script>\n";
	// call function to create form which allows user to change currency. Note: action will never be used, but needed for validation
	$script .= '<br /><form name="lc_change'.$post_id.'" id="lc_change'.$post_id.'" action="'. get_permalink() .'" method="post">'."\nShow currencies in \n";
	$script .= '<script type="text/javascript"> '. "\nlocalCurrencyUserSelection('".$localcurrency_options['site_currency']."',lcValues" . $post_id . "[0].to," . $post_id . ");\n";
	$script .= "</script>\n<noscript>[Please enable JavaScript to change the currency used on this page]</noscript>\n";
	$script .= "<br /><small>Powered by";
	if ($localcurrency_options['link_on']=='true') {
		$script .= ' <a href="http://www.jobsinchina.com/resources/wordpress-plugin-localcurrency/" title="The LocalCurrency Plugin For WordPress" target="_blank">LocalCurrency</a>';
	} else {
		$script .= ' LocalCurrency';
	}
	$script .= '. Rates from <a href="http://finance.yahoo.com" title="Visit Yahoo! Finance" target="_blank">Yahoo! Finance</a></small></form>'."\n";
	// return filtered content
	return $content . $script;

}
add_filter('the_content', 'localcurrency_shortcode_script', 10000 );
// **********************************************

?>