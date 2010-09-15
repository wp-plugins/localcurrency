<?php
// This file is called on Admin pages

	// ****** Register the options page ******
	if (function_exists('add_options_page')) {
		add_options_page('LocalCurrency Settings', 'LocalCurrency', 'update_plugins', basename(__FILE__), 'localcurrency_admin_page');
	}
	// ***********************************


	// ****** FUNCTION TO CREATE ADMIN PAGE CONTENT ******
	function localcurrency_admin_page(){

		// get the localcurrency options
		$localcurrency_options = get_option('localcurrency_options');
		
		// Post the form
		if (isset($_POST['localcurrency_options_submit']) && check_admin_referer('localcurrency_admin_page_submit')) {
			$localcurrency_options['site_currency'] = esc_html($_POST['site_currency']);
			if ($_POST['link_on']=='true') {$localcurrency_options['link_on'] = 'true';} else {$localcurrency_options['link_on'] = 'false';} 
			if ($_POST['hide_base_price']=='true') {$localcurrency_options['hide_base_price'] = 'true';} else {$localcurrency_options['hide_base_price'] = 'false';} 
			if ($_POST['freeze_prices']=='true') {$localcurrency_options['freeze_prices'] = 'true';} else {$localcurrency_options['freeze_prices'] = 'false';} 
			if ($_POST['debug']=='true') {$localcurrency_options['debug'] = 'true';} else {$localcurrency_options['debug'] = 'false';} 
			update_option('localcurrency_options', $localcurrency_options);
			echo '<div id="message" class="updated"><p><strong>';
			_e('Options saved.');
			echo '</strong></p></div>';
		} 

		// now drop out of php to create the HTML for the Options page
	?>

		<div class="wrap"> 
			<h2>LocalCurrency Settings</h2>			
			<div id="poststuff">
			
				<div class="stuffbox">
				
					<h3>General Settings</h3>
					<div class="inside">
						<!-- Start Form (Posts to this page) -->
						<form name="localcurrency_options_form" action="" method="post">
						<?php if (function_exists(wp_nonce_field)) {wp_nonce_field('localcurrency_admin_page_submit'); }?>
					
						<h4>Site Currency:</h4>
						<p>The currency you use when writing your posts. The plugin will convert values from this currency to the reader's currency.
					<?php
						echo '<select name="site_currency" id="site_currency">'."\n";
						foreach ($localcurrency_options['currency_list'] as $key => $value){
							echo '<option value="'.$key.'" ';
							if ($key == $localcurrency_options['site_currency']) {echo 'selected="selected"';}
							echo '>'.$value['name'].'</option>'."\n";
						}	
						echo '</select>';
					?>
						</p>
						
						<h4>Hide Original Price:</h4>
						<p><input type="checkbox" name="hide_base_price" id="hide_base_price" value="true" <?php if ($localcurrency_options['hide_base_price']=='true') {echo 'checked="checked"';}?> />
						By default, the plugin displays the visitor's local currency in brackets, after the original value, eg if the post contains 10 yuan, a user from Australia will see 10 yuan (AUD$1.53). Turning this setting on will hide the original price, eg if the post contains 10 yuan, a user from Australia will see AUD$1.53.</p>
						
						<h4>Historic Exchange Rates:</h4>
						<p><input type="checkbox" name="freeze_prices" id="freeze_prices" value="true" <?php if ($localcurrency_options['freeze_prices']=='true') {echo 'checked="checked"';}?> />
						By default, the plugin displays the visitor's local currency based on the current exchange rate. Select this parameter if you want the use the exchange rate at the time of the post, rather than the current one. Note: not all exchange rates will be stored - if there is no rate for the user's currency, the price will not be converted.</p>
						
						<h4>Show Link:</h4>
						<p><input type="checkbox" name="link_on" id="link_on" value="true" <?php if ($localcurrency_options['link_on']=='true') {echo 'checked="checked"';}?> />
						By default, a link to the plugin home page is included on pages where there are values to be converted. Deselect this parameter if you want to disable the link. Note: It is not possible to turn off the link to Yahoo! Finance due to legal requirements.</p>
						
						<h4>Debug Mode:</h4>
						<p><input type="checkbox" name="debug" id="debug" value="true" <?php if ($localcurrency_options['debug']=='true') {echo 'checked="checked"';}?> />
						Only needed if feedback is required for support reasons.</p>

						<!-- Show Update Button -->
						<div class="submit">
						<input type="submit" name="localcurrency_options_submit" value="<?php _e('Update Options &raquo;') ?>"/>
						</div>	


						<!-- End Form -->
						</form>
						<div style="clear:both"></div>
					</div> <!-- class="inside" -->
				</div> <!-- class="stuffbox" -->


				<!-- Usage Section -->
				<div class="stuffbox">
					<h3>Usage</h3>
					<div class="inside">
						<h4>USAGE:</h4>
						<p>Enter any currency values you want converted within &lt;--LCSTART--&gt; and &lt;--LCEND--&gt; tags. This can be done through the Code view. Simply select the number to be converted and click the LocalCurrency Quicktag. This should enter the tags for you.</p>
						
						<h4>EXAMPLE:</h4>
						<p>&lt;--LCSTART--&gt;$10&lt;--LCEND--&gt;</p>
						
						<h4>WARNING:</h4>
						<p>The plugin strips non numeric characters (such as $) from between the tags, before converting the value. However, some currency symbols may include numeric characters. For example, 10&#20803; may be stored as 10&amp#20803;. The 20803 will remain after the non numeric characters are stripped and will be considered as part of the value to convert, resulting in an incorrect value.</p>
						<p>If you experience this problem, simply leave the currency sign outside the tags (ie: &lt;--LCSTART--&gt;10&lt;--LCEND--&gt;&#20803;).</p>
						<p>Note: The $ sign is not affected by this.</p>
					</div> <!-- class="inside" -->
				</div> <!-- class="stuffbox" -->


				<!-- Thank You Section -->
				<div class="stuffbox">
					<h3>Thank You</h3>
					<div class="inside">
						<p>Thank you for using the LocalCurrency plugin. If you like this plugin, you may like to:</p>
						<ul style="font-size:1em; margin:12px 40px; list-style:disc">
						<li>Link to the <a href="http://www.jobsinchina.com/resources/wordpress-plugin-localcurrency/">plugin's home page</a> so others can find out about it</li>
						<li>Support ongoing development by <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=sjc@scratch99.com&currency_code=&amount=&return=&item_name=Buy+Me+A+Drink">making a donation</a></li>
						<li>Check out <a href="http://www.scratch99.com/wordpress-plugins-by-stephen-cronin/">my other WordPress plugins</a></li>
						<li><a href="http://www.scratch99.com/feed/">Subscribe to my feed</a> to learn about new plugins I'm working on</li>
						</ul>
						<p>I also provide <a href="http://www.scratch99.com/services/">wordpress and web development services</a>.
						<p><small>Copyright 2008 - 2010 by Stephen Cronin. Released under the GNU General Public License (version 2 or later).</small></p>
					</div> <!-- class="inside" -->
				</div> <!-- class="stuffbox" -->


			</div> <!-- end id="poststuff" -->
		</div> <!-- end class="wrap" -->


	<?php
	}
	// *********** END  LOCALCURRENCY_ADMIN_PAGE  FUNCTION ***********

?>