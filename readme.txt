=== Plugin Name ===
Contributors: StephenCronin
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=sjc@scratch99.com&currency_code=&amount=&return=&item_name=WP-LocalCurrency
Tags: currency, exchange rates, currency converter, currency rates, travel, financial
Requires at least: 2.8.0
Tested up to: 3.4.1
Stable tag: 2.2
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
* Gives site owner the ability to hide the original value if desired
* Gives site owner the choice of using current or historic rates (ie at time of post).

= How To Use (once plugin is installed) =
Enter any currency values you want converted within `<-LCSTART->` and `<-LCEND->` tags. This can be done through the Code view. Simply select the number to be converted and click the LocalCurrency Quicktag. This should enter the tags for you. For example:
	`<-LCSTART->`$10`<-LCEND->`

Note: If you disable the plugin, the tags will remain in your post but will not be shown, because they are in a HTML comment.

= Warning =
The plugin strips non numeric characters (such as $) from between the tags, before converting the value. However, some currency symbols may include numeric characters. For example, 10&#20803; may be stored as 10&amp#20803;. The 20803 will remain after the non numeric characters are stripped and will be considered as part of the value to convert, resulting in an incorrect value.

If you experience this problem, simply leave the currency sign outside the tags (ie: <-LCSTART->10<-LCEND->&#20803;).

= Compatibility: =
* This plugin requires WordPress 2.8 or above.
* I am not currently aware of any compatibility issues with any other WordPress plugins.

= Support: =
This plugin is officially not supported (due to my time constraints), but if you leave a comment on the plugin's home page or [contact me](http://www.scratch99.com/contact/), I should be able to help.

= Disclaimer =
This plugin is released under the [GPL licence](http://www.gnu.org/copyleft/gpl.html). I do not accept any responsibility for any damages or losses, direct or indirect, that may arise from using the plugin or these instructions. This software is provided as is, with absolutely no warranty. Please refer to the full version of the GPL license for more information.

== Installation ==
1. Download the plugin file and unzip it.
1. Upload the `localcurrency` folder to the `wp-content/plugins/` folder.
1. Activate the LocalCurrency plugin within WordPress.
Note: The plugin is large compared to most WordPress plugins, due the IP2C database used to recognise the reader's country
Alternatively, you can install the plugin automatically through the WordPress Admin interface by going to Plugins -> Add New and searching for LocalCurrency.

= Upgrade =
1. Download the plugin file and unzip it.
1. Upload the `localcurrency` folder to the `wp-content/plugins/` folder, overwriting the existing files.
1. Deactivate the LocalCurrency plugin within WordPress, then reactivate it (to make sure any new settings are created).
Alternatively, you can update this plugin through the WordPress Admin interface.

== Frequently Asked Questions ==

= Will there be future enhancements to LocalCurrency? =
This plugin was created in my spare time, which is ever-dwindling. I have intentions to update it regularly, but recognise that other things must take precendence. If you have a request, by all means [contact me](http://www.scratch99.com/contact/) and I'll put it on the list of things to do, but I can't guarantee how long it will take.

== Screenshots ==
No screenshots exist at this time, but you can see the plugin in action on my 
[Cost of living in China](http://www.jobsinchina.com/blog/the-cost-of-living-in-china/) post.

== Changelog ==

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