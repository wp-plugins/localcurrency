<?php
/*
Receives AJAX calls from the LocalCurrency plugin, requests lastest rate from http://www.yahoo.com/, then returns rate to plugin. 
*/

// load WordPress so we have access to the functions we need
require_once('../../../wp-config.php');
require_once(ABSPATH . 'wp-settings.php');

// lets just make sure there's nothing bad in the URL
foreach ($_GET as $key => $value) {
	$_GET[$key] = htmlentities(stripslashes($value));	
}

// don't proceed if we don't have enough info (nonce removed in 2.3 because of clash with caching plugin)
if (!isset($_GET['from']) || !isset($_GET['to'])) {
	return;
}

// get parameters passed with URL
$lc_from = $_GET['from'];
$lc_to = $_GET['to'];

// get current options
$localcurrency_options = get_option('localcurrency_options');

// if using old rates
if ($localcurrency_options['freeze_prices'] == 'true') {
	global $post;
	if ($lc_rates_to_use = get_post_meta($post->ID, '_lc_rates', true)) {
		$conversion_rate = get_conversion_local($lc_from, $lc_to, $lc_rates_to_use, true);
	}
	else {
		return;
	}
}
else {
	// if we have rates from the last day, use them (note *2 because we store date from -86400 in first place)
	if ($localcurrency_options['exchange_rates'] && $localcurrency_options['last_updated'] >= (time() - (86400*2))) {
		$conversion_rate = get_conversion_local($lc_from, $lc_to, $localcurrency_options['exchange_rates'], false);
	}
	// else get them from yahoo and store them
	else {
		$lc_rates_to_use = get_from_yahoo();
		if ($lc_rates_to_use) {
			$localcurrency_options['exchange_rates'] = $lc_rates_to_use;
			$localcurrency_options['last_updated'] = time()-86400;
			update_option('localcurrency_options', $localcurrency_options);
			$conversion_rate = get_conversion_local($lc_from, $lc_to, $lc_rates_to_use, false);	
		}
		// get them the old way
		else {
			$conversion_rate = get_conversion($lc_from, $lc_to);
		}
	}
}

// return the data from the server
echo $conversion_rate;


// *** New function to return conversion rate from locally stored file ***
function get_conversion_local($lc_from, $lc_to, $lc_rates_to_use, $lc_frozen) {
	if ($lc_from == 'USD' && $lc_rates_to_use[$lc_to]) {
		$conversion_rate = $lc_rates_to_use[$lc_to];	
	}
	elseif ($lc_to == 'USD'  && $lc_rates_to_use[$lc_from]) {
		$conversion_rate = 1 / $lc_rates_to_use[$lc_from];
	}
	elseif ($lc_rates_to_use[$lc_from] && $lc_rates_to_use[$lc_to]) {
		$conversion_rate = $lc_rates_to_use[$lc_to] / $lc_rates_to_use[$lc_from];
	}
	// get them the old way unless we're using frozen rates
	else {
		if (!$lc_frozen) {
			$conversion_rate = get_conversion($lc_from, $lc_to);
		}
		else {
			return;
		}
	}
	return $conversion_rate;
}
// **********************************************

// *** Old function to fetch the version from yahoo ***
// based on CurreX (http://chaos-laboratory.com/2007/03/01/currex-ajax-based-currency-converter-widget-for-wordpress/)
function get_conversion( $cur_from, $cur_to )
{
	if(strlen($cur_from)!=0 && strlen($cur_to)!=0) {
		$host="download.finance.yahoo.com";
		$fp = @fsockopen($host, 80, $errno, $errstr, 30);
		
		if (!$fp) {
			return false;
		}
		else {
			$file="/d/quotes.csv";
			$str = "?s=".$cur_from.$cur_to."=X&f=sl1d1t1ba&e=.csv";
			$out = "GET ".$file.$str." HTTP/1.0\r\n";
		    $out .= "Host: download.finance.yahoo.com\r\n";
			$out .= "Connection: Close\r\n\r\n";
			@fputs($fp, $out);
			$data = '';
			while (!@feof($fp)) {
				$data .= @fgets($fp, 128);
			}
			@fclose($fp);
			@preg_match("/^(.*?)\r?\n\r?\n(.*)/s", $data, $match);
			$data =$match[2];
			$search = array ("'<script[^>]*?>.*?</script>'si","'<[\/\!]*?[^<>]*?>'si","'([\r\n])[\s]+'","'&(quot|#34);'i","'&(amp|#38);'i","'&(lt|#60);'i","'&(gt|#62);'i","'&(nbsp|#160);'i","'&(iexcl|#161);'i","'&(cent|#162);'i","'&(pound|#163);'i","'&(copy|#169);'i","'&#(\d+);'e");
			$replace = array ("","","\\1","\"","&","<",">"," ",chr(161),chr(162),chr(163),chr(169),"chr(\\1)");
			$data = @preg_replace($search, $replace, $data);
			$result = split(",",$data);
			return $result[1];
		}
	} 		
}
// *** end function ***

?>