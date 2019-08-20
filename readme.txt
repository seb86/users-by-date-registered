=== Users by Date Registered ===
Contributors:      sebd86
Donate link:       https://sebdumont.xyz/donate/
Tags:              users, date, filter, admin, administration
Requires PHP:      5.6
Requires at least: 4.5
Tested up to:      5.2.2
Stable tag:        1.0.7
License:           GPLv2 or later
License URI:       http://www.gnu.org/licenses/gpl-2.0.html

Allows you to see the dates users registered on and filter the users by date.

== Description ==

This simple plugin adds a new column to the users table displaying the date the user registered and allows you to filter the users by date.

= Support =
Use the WordPress.org forums for [community support](https://wordpress.org/support/plugin/users-by-date-registered). If you spot a bug, you can of course log it on [Github](https://github.com/seb86/users-by-date-registered/issues) instead where I can act upon it more efficiently.

= Please Leave a Review =
Your ratings make a big difference. If you like Users by Date Registered, please consider spending a minute or two [leaving a review](https://wordpress.org/support/plugin/users-by-date-registered/reviews/?rate=5#postform) and tell me what you think about the plugin.

**More information**

- Other [WordPress plugins](http://profiles.wordpress.org/sebd86/) by [Sébastien Dumont](https://sebastiendumont.com/)
- Contact Sébastien on Twitter: [@sebd86](https://twitter.com/sebd86)
- If you're a developer yourself, follow or contribute to the [Users by Date Registered plugin on GitHub](https://github.com/seb86/users-by-date-registered)

== Installation ==

Installing "Users by Date Registered" can be done either by searching for "Users by Date Registered" via the "Plugins > Add New" screen in your WordPress dashboard, or by using the following steps:

1. Download the plugin via WordPress.org
2. Upload the ZIP file through the 'Plugins > Add New > Upload' screen in your WordPress dashboard.
3. Activate the plugin through the 'Plugins' menu in WordPress.

= Upgrading =

Automatic updates should work like a charm; as always though, ensure you backup your site just in case.

== Changelog ==

= v1.0.7 - 20th August 2019 =

* Added: The date you have selected to filter the users by is shown on the side with the change made to the dropdown.
* Changed: Dropdown is no longer selected once a date is set to allow you change again with the addition of showing the filter option in the bottom.
* Fixed: Date registered column no longer overrides other columns to NOT be sortable. Spotted by [@benbranyon](https://github.com/benbranyon)
* Tweaked: The dropdown now shows and works in the bottom of the users table. 🎊
* Tweaked: Changed the use of `get_post_time()` with `mysql2date()` for a more accurate conversion of the date.
* Tweaked: Date registered now shows X days ago time difference within a 7 day window instead of 1 day.

= v1.0.6 - 18th July 2019 =

* Checked: Compatibility with WordPress 5.2
* Fixed: `$val` is returned for default if column is not registered.

= v1.0.5 - 16th February 2019 = 

* New: Ready for WordPress 5.1 release. 🎊

= v1.0.4 - 30th November 2018 = 

* Checked: Compatibility with WordPress 5.0

= v1.0.3 - 17th November 2017 =

* Added     - A prefix to all functions to prevent compatibility issues with other filters or plugins.
* Changed   - User filter now only shows at the top of the users table.
* Changed   - mysql2date() now uses the site date format.
* Fixed     - Users returning empty when "All Dates" is selected.
* Improved  - Inline documentation.
* Corrected - Localized strings missing the text domain.

= v1.0.2 - 17th March 2017 =

* Corrected - Textdomain to match plugin slug.
* Fixed - Compatibility with PHP 7.
* Fixed - Filter button to identify if the table was filtered from the top or bottom of the table.

= 1.0.1 - 14th December 2014 =

* Fixed - Query issue by removing $wpdb->prepare

= 1.0.0 - 16th August 2014 =

* Initial release
