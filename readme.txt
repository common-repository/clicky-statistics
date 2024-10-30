=== Plugin Name ===
Contributors: Eric Leung
Donate link: http://www.leungeric.com/2008/06/23/wordpress-plugin-clicky-statistics/
Tags: clicky, statistics
Requires at least: 2.5.1
Tested up to: 2.5.1
Stable tag: 1.0

A plugin to retrieve Clicky statistics of your website via Clicky API 2.0 with cache supported. 

== Description ==

This plug-in retrieves Clicky statistics of your website via Clicky API 2.0 with cache supported.

You may define:

   1. Types of statistics to be displayed
   2. Statistics date range
   3. The maximum number of results that will be returned
   4. Cache timeout duration
   5. Multiple string replacements

Before using this plugin, you need to register a Clicky account, and setup Clicky WordPress Plugin.

== Installation ==

1. Upload all files to wp-content/plugins/
2. Activate the plugin on the plugin screen
3. Go to Settings -> Clicky Statistics to enter your site ID, site key and manage other settings

== Frequently Asked Questions ==

= What is site id? =

Go to your site's dashboard and look at the URL. You should see a "site_id=123" (example) on the end. In this case, 123 would be your site ID.

= What is site key? =

The sitekey is a 12-16 character string of random letters and numbers that is unique for every web site and is assigned when you first register your web site with Clicky. Because you don't "login" to the API like you would to a normal web site, every request must be authenticated by your sitekey. Available from site preferences page.

== Screenshots ==

1. Clicky Statistics Settings Page
2. Clicky Statistics Output Sample

== A brief Markdown Example ==

Supported statistics:

*  summary (Clicky ranking, number of visitors, number of actions, total amount of time spent, visitors online)
* pages (popular pages on your site)
* traffic_sources (how visitors are arriving at your site)
* active_visitors (the people who have visited your site the most often)
* outbound_links (popular outbound links)
* incoming_links (popular incoming links)
* incoming_domains (popular incoming domains)
* countries (countries that your visitors are from)

Supported styles:

* li (use &lt;li class="clicky_statistics"&gt;&lt;/li&gt;)
* br (use &lt;br /&gt;)