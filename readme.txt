=== Users by Date Registered ===
Contributors:      sebd86
Donate link:       https://sebastiendumont.com/plugin/users-date-registered/
Tags:              users, date, filter, admin
Requires at least: 4.5
Tested up to:      4.9
Stable Tag:        1.0.3
License:           GPLv2 or later
License URI:       http://www.gnu.org/licenses/gpl-2.0.html

Allows you to filter your users by date registered.

== Description ==

This simple plugin allows you to filter your users by date registered. A new column also displays the date the user registered on your site.

= Support =
Use the WordPress.org forums for [community support](https://wordpress.org/support/plugin/users-by-date-registered). If you spot a bug, you can of course log it on [Github](https://github.com/seb86/Users-by-Date-Registered/issues) instead where I can act upon it more efficiently.

= Please Leave a Review =
Your ratings make a big difference. If you like Users by Date Registered, please consider spending a minute or two [leaving a review](https://wordpress.org/support/plugin/users-by-date-registered/reviews/?rate=5#postform) and tell me what you think about the plugin.

**More information**

- Other [WordPress plugins](http://profiles.wordpress.org/sebd86/) by [Sébastien Dumont](https://sebastiendumont.com/)
- Contact Sébastien on Twitter: [@sebd86](http://twitter.com/sebd86)
- If you're a developer yourself, follow or contribute to the [Users by Date Registered plugin on GitHub](https://github.com/seb86/Users-by-Date-Registered)

== Installation ==

Installing "Users by Date Registered" can be done either by searching for "Users by Date Registered" via the "Plugins > Add New" screen in your WordPress dashboard, or by using the following steps:

1. Download the plugin via WordPress.org
2. Upload the ZIP file through the 'Plugins > Add New > Upload' screen in your WordPress dashboard.
3. Activate the plugin through the 'Plugins' menu in WordPress.

= Upgrading =

Automatic updates should work like a charm; as always though, ensure you backup your site just in case.

== Changelog ==

= v1.0.3 - 17th November 2017 =
* ADDED     - A prefix to all functions to prevent compatibility issues with other filters or plugins.
* CHANGED   - User filter now only shows at the top of the users table.
* CHANGED   - mysql2date() now uses the site date format.
* FIXED     - Users returning empty when "All Dates" is selected.
* IMPROVED  - Inline documentation.
* CORRECTED - Localized strings missing the text domain.

= v1.0.2 - 17th March 2017 =
* CORRECTED - Textdomain to match plugin slug.
* FIXED - Compatibility with PHP 7.
* FIXED - Filter button to identify if the table was filtered from the top or bottom of the table.

= 1.0.1 - 14th December 2014 =
* FIXED - Query issue by removing $wpdb->prepare

= 1.0.0 - 16th August 2014 =
* Initial release
