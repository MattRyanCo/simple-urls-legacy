=== Simple URLs Legacy ===
Contributors: rynonet, nathanrice, studiopress, cliffpaulick, marksabbath, modernnerd
Donate link: https://mattryan.co/go/plugin-donate-link/
Tags: redirect, click tracking, custom post types
Requires at least: 5.9
Tested up to: 6.1
Stable tag: 0.10.2
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Simple URLs Legacy is a fork of the orignial plugin by Nathan Rice and Studiopress team. As such, it is a complete URL management system that allows you create, manage, and track outbound links from your site by using custom post types and 301 redirects, as it was meant to be.

== Description ==

Simple URLs Legacy is a fork of the orignial plugin by Nathan Rice and Studiopress team. As such, it is a complete URL management system that allows you create, manage, and track outbound links from your site by using custom post types and 301 redirects, as it was meant to be.

== Usage ==

Simple URLs Legacy adds a new custom post type to your admin menu, where you can create, edit, delete, and manage URLs. It stores click counts in the form of a custom field on that custom post type, so it scales really well.

And, by avoiding page based redirects, which is the current trend in masking affiliate links, we avoid any issues with permalink conflicts, and therefore avoid any performance issues.

1. Upload the entire `simple-urls-legacy` folder to the `/wp-content/plugins/` directory
2. DO NOT change the name of the `simple-urls-legacy` folder
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Navigate to `Settings > Permalinks`. That's it. Just open that screen and WordPRess automatically flushes redirects. Trust me.
5. Navigate to the `Simple URLs Legacy` menu
6. Create a new URL, or manage existing URLs.
7. Publish and use the URLs however you want!

== Frequently Asked Questions ==

= When I try to access my new URL, I'm getting a 404 (not found) error =

Sounds like you didn't complete the installation instructions :-)

Navigate to `Settings > Permalinks` and save them. No need to change anything, just click the save button.

= Can I change the URL structure to use something other than /go/ ??? =

Yes, by using the filter `simple_urls_legacy_slug`.

Usage:
```
add_filter( 'simple_urls_legacy_slug', function(){
    return 'redirect-me';
});
```
The text "redirect-me" will replace the default "go" in example.com/go/my-simple-url.
Eg. example.com/redirect-me/my-simple-url/

== Screenshots ==

1. The URL management screen
2. The URL create/edit screen

== Changelog ==

= 0.10.2  =
* 11/25/2022 #5FTF #WebDevStudios
* Applied WordPress coding standards
* Tweaked plugin description.
* Added required license declaration in readme.txt

= 0.10.1  =
* 10/28/2022 #5FTF #WebDevStudios
* Added conflict notice in admin if Simple URLs plugin is running.
* Reworked constants for better readability.
* Added build/dependency source files back in with updated dependencies.

= 0.10.0  =
* 08/09/2022
* Reverted plugin to last known state under original author - Nathan Rice (v0.9.9).
* Renamed plugin from Simple URLs to Simple URLs Legacy.
* URL structure modifier filter changed from 'simple_urls_slug' to 'simple_urls_legacy_slug'.
* Reworked naming conventions to use simple-urls-legacy namespace and package name.
* Removed build/dependency source files.

= 0.9.9 =
* Fixed URLs not properly redirecting.

= 0.9.8 =
* Coding standards.
* Added composer.
* Added Circle CI integration.
* Added new filter to change the slug.
* Added REST support to ensure Simple URLs appear in list when linking text.
* Added cast string to int to avoid type error.

= 0.9.7 =
* WordPress compatibility.

= 0.9.6 =
* Add plugin header i18n
* Add textdomain loader

= 0.9.5 =
* Changed messages and labels.
* Updated textdomain.
* Generated POT file.

= 0.9.4 =
* Fixed saving bug

= 0.9.3 =
* Removed capability line from the register function. Users with permission to edit posts can create/edit URLs.
* Bumped to show compatibility with WordPress 3.0.4

= 0.9.2 =
* Fixed a type in the plugin URL
* Bumped to show compatibility with WordPress 3.0.2

= 0.9.1 =
* Fixed bug with URLs with ampersands in them
* Added `'with_front' => false` to the post type registration

= 0.9 =
* Initial Beta Release
