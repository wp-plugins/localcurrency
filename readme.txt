=== Plugin Name ===
Contributors: StephenCronin
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=sjc@scratch99.com&currency_code=&amount=&return=&item_name=WP-LocalCurrency
Tags: currency, exchange rates, currency converter, currency rates, travel, financial
Requires at least: 2.8.0
Tested up to: 4.2.2
Stable tag: 2.9
Show currency values to readers in their local currency (in brackets after the original value).

== Description ==
The [LocalCurrency](http://www.jobsinchina.com/resources/wordpress-plugin-localcurrency/ "WordPress plugin to convert currencies in a post to the visitor's local currency") WordPress plugin allows visitors to see currency values in their local currency (in brackets after the original value). For example: If the site's currency is Chinese yuan and the post contains 10 yuan, a user from Australia will see 10 yuan (AUD$1.53), while a user from US will see 10 yuan (USD$1.39).

= Why Use It? =
I've seen many bloggers write something like: 10 yuan (about $1.50) - because many of their readers don't know how much the yuan (or whatever currency they are using) is worth. LocalCurrency automatically does this for you and tells readers exactly how much it's worth, in their own currency, wherever they are from.

= Features: =
* Determines the reader's country via IP address, using [IP2C](http://firestats.cc/wiki/ip2c)
* Obtains exchange rates from [Yahoo! Finance](http://finance.yahoo.com/)
* Uses 'AJAX' techniques so that converting currency values doesn't delay page load times
* Caches exchange rates locally to minimise calls to Yahoo! Finance
* Only does something if there is a currency value in the post
* Allows visitors to change their currency via a selection box
* Allows the conversion of a currency range (eg $50-100)
* Gives site owner the ability to hide the original value if desired
* Gives site owner the choice of using current or historic rates (ie at time of post)
* Now works with mulitple source curriencies

= How To Use (once plugin is installed) =
Enter any currency values you want converted using the localcurrency shortcode. For example:

	`[localcurrency]$65[/localcurrency]`

This can be done manually through the Visual view in the post editor, or using the LocalCurrency Quicktag in the Code view (select the number to be converted and click the quicktag). 

For a currency range, use a hyphen between values (without spaces), eg:

	`[localcurrency]$65-$75[/localcurrency]`

There is a site-wide Site Currency setting which is used as the 'from' currency. To override this and convert from a different currency for a specific value, specify the 'from' currency, 

	`[localcurrency from="GBP"]£65[/localcurrency]`

The plugin will work with more than one currency per page, but will be much slower.

Note: This plugin used to use the following format: `<!--LCSTART-->`$10`<!--LCEND-->`. This will still work but it is recommended to use the shortcode format shown above. Please do not use both formats on the one page.

= Warning =
The plugin strips non numeric characters (such as $) from between the tags, before converting the value. However, some currency symbols may include numeric characters. For example, 10&#20803; may be stored as 10`&amp;#20803;`. The 20803 will remain after the non numeric characters are stripped and will be considered as part of the value to convert, resulting in an incorrect value.

If you experience this problem, please leave the currency sign outside the tags (ie: `&#20803;[localcurrency]10[/localcurrency]`).

= Compatibility: =
* This plugin requires WordPress 2.8 or above.
* I am not currently aware of any compatibility issues with any other WordPress plugins.

= Support: =
This plugin is officially not supported (due to my time constraints), but if you post problems to the [support forum](http://wordpress.org/support/plugin/localcurrency), I'll try to help.

= Disclaimer =
This plugin is released under the [GPL licence](http://www.gnu.org/copyleft/gpl.html). I do not accept any responsibility for any damages or losses, direct or indirect, that may arise from using the plugin or these instructions. This software is provided as is, with absolutely no warranty. Please refer to the full version of the GPL license for more information.

== Installation ==
1. Download the plugin file and unzip it.
1. Upload the `localcurrency` folder to the `wp-content/plugins/` folder.
1. Activate the LocalCurrency plugin within WordPress.
Note: The plugin is large compared to most WordPress plugins, due the IP2C database used to recognise the reader's country
Alternatively, you can install the plugin automatically through the WordPress Admin interface by going to Plugins -> Add New and searching for LocalCurrency.

= Upgrade =
1. Please update this plugin through the WordPress Admin interface.

== Frequently Asked Questions ==

= Will there be future enhancements to LocalCurrency? =
This plugin was created in my spare time, which is ever-dwindling. I have intentions to update it regularly, but recognise that other things must take precendence. If you have a request, by all means [contact me](http://scratch99.com/contact/) and I'll put it on the list of things to do, but I can't guarantee how long it will take.

== Screenshots ==
No screenshots exist at this time, but you can see the plugin in action on my [Cost of living in China](http://www.jobsinchina.com/blog/the-cost-of-living-in-china/) post.

== Changelog ==

= 2.9 (1st June 2015) =
* New Feature: The plugin now uses the standard WordPress shortcode system. The old format will still work but is not recommended.
* New Feature: The 'from' currency can now be specified for each value to be converted.

= 2.8.1 (21st May 2015) =
* Update: Added Armenian AMD and Georgian GEL.

= 2.8 (21st May 2015) =
* Update: Updated the currency list to reflect which currencies are used in which countries in 2015.
* Minor Change: Changed the order of the dropdown list of currencies on the front end of the site to alphabetical.
* Minor Change: Modified the activation function so that it also deals with updates.
* Minor Change: Tweaked the Powered By wording to make it shorter when there is no link
* Minor Change: Changed the default setting for whether to link to the plugin home page to off.

= 2.7 (23rd October 2014) =
* Fixed Major Bug: The URL required for the call to Yahoo! Finance changed, so conversions stopped happening.

= 2.6 (25th October 2013) =
* Fixed Major Bug: Conversion was broken when there was only one conversion on a page.
* Fixed Bug: Quicktag was no longer appearing.

= 2.5 (4th July 2013) =
* New Feature: Plugin now converts currency ranges, eg $50-100. See the How To Use section for details.

= 2.4 (28th June 2013) =
* Fixed Major Bug: If the value to convert contained a decimal point, the converted value was 100 times what it should be.

= 2.3 (13th November 2012) =
* Fixed Major Bug: Plugin was trying to load local exchange rate JSON file rather than the one from Yahoo.
* Fixed Major Bug: Yahoo has changed the format of the exchange rate JSON file slightly, which broke the plugin.
* Fixed: Removed the nonce check which was failing when sites used caching plugins such as W3TC or WP Super Cache.

= 2.2 (29th September 2010) =
* New Feature: Adding a custom field called force_lc with a value of 1 will force LocalCurrency to run, even if there is no 'shortcode' on the page (only useful where the shortcode is added by another plugin)
* New Feature: A new option called Plugin Firing Priorty controls when LocalCurrency manipulates the post content, for better compatibility with other plugins that manipulate post content.

= 2.1 (28th September 2010) =
* Fixed Major Bug: Users from the site's main location didn't seeing any currency if the Hide Original Price setting was turned on.

= 2.0 (15th September 2010) =
* Major rewrite of underlying structure to improve efficiency and security.
* Added the ability to use Historic Exchange Rates (ie at time of post), rather current rates.
* Added the ability to Hide Original Price, so the user will just see the price in their currency rather than both the original and their currency.
* Added debug mode to assist with troubleshooting.
* Moved the plugin to the official WordPress plugin directory.

= 1.01 (24th February 2008) =
* Fixed: issue with plugin not working when there was only one currency on the page. This was due to the way the JavaScript array was created dynamically by PHP.
* Fixed: issue with plugin not working properly on pages with more than one post (ie home page, archive pages etc). Plugin now works properly, although changing currency only changes it for the one post with the select on it.

= 1.0 (20th February 2008) =
* Initial Release

== Credits ==
* This plugin makes use of some code from the [CurreX plugin](http://chaos-laboratory.com/2007/03/01/currex-ajax-based-currency-converter-widget-for-wordpress/) by miCRoSCoPiC^eaRthLinG.
* This plugin uses [IP2C](http://firestats.cc/wiki/ip2c) to determine which country the visitor is from.
* This plugin uses [Yahoo! Finance](http://finance.yahoo.com/) to determine the exchange rates.