=== Simple Login Captcha ===
Contributors: nnikolov
Tags: captcha, login, simple, spam, security, antispam
Requires at least: 3.5
Tested up to: 5.6
Requires PHP: 5.2
Stable tag: 1.3.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds a simple 3-digit number captcha on the login form.

== Description ==

A simple captcha for the WordPress login form. To be able to login, the user is required to enter a random 3-digit number in a text field. 

The correct number is displayed above the field by a small JavaScript code. Compatible with the WooCommerce login form. Compatible with multisite.

= Demo =

[https://nikolaydev.com/wp-login.php](https://nikolaydev.com/wp-login.php)

= Simple =

* No complicated features
* No settings
* No image generation
* No API
* No sessions
* No cookies
* No IP address detection
* No personal data collection
* No vulnerabilities in the programming code

= Recommendation =

Bots can also try to login with the XML-RPC feature of WordPress! Very rarely plugins also need this (like the Jetpack plugin). But if you don't use it, I recommend that you disable it. You can use the super simple one-line plugin [Disable XML-RPC](https://wordpress.org/plugins/disable-xml-rpc/).

== Installation ==
 
Simply install and activate the plugin, like you would any normal plugin. There are no settings.
 
1. Visit your [Plugins Add New Screen](https://codex.wordpress.org/Plugins_Add_New_Screen)
2. Find the plugin by searching for: Simple Login Captcha
3. Install the plugin, by clicking the "Install Now" button
4. Activate the plugin, by clicking the "Activate" button

== Screenshots ==

1. WordPress login form with the simple captcha
2. Viewing the number of blocked and passed requests this month

== Changelog ==

= 1.3.2 - 16 December 2020 =
* Added: Translated language files for Polish (thanks to Karol).

= 1.3.1 - 16 December 2019 =
* Fixed: The language files were not loaded if they were only present in the plugin languages folder (and not in wp-content/languages/plugins).

= 1.3.0 - 13 December 2019 =
* Added: Compatibility with the WooCommerce login page.
* Added: Translated language files for German (thanks to [Tilo](https://wordpress.org/support/users/netsales/)).
* Improved: The gray container of the security code now has a slight border.

= 1.2.0 - 10 September 2019 =
* Improved: In a multisite, when activated on separate sub-sites only, now uses one global database table.
* Fixed: In a multisite, when network activated, it would not work in login pages of separate sub-sites. It would work only on the main site login page, and on the login pages for other site, it would not let anyone in.
* Fixed: Changed the label tag surrounding the "Security Code" text, to a span tag. Since there is no field to fill there, it was not correct to use it.

= 1.1.0 - 4 May 2019 =
* Improved: The correct number is now displayed as a result of a small JavaScript calculation (so not in plain text).
* Updated: Language files.

= 1.0.0 - 11 April 2019 =
* Initial release.