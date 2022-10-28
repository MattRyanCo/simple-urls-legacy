# Simple URLs Legacy by Matt Ryan

Plugin that allows you to create custom redirects for pages and posts via a custom field.

## Description

Simple URLs Legacy is a fork of the orignial plugin by Nathan Rice and Studiopress team. As such, it is a complete URL management system that allows you create, manage, and track outbound links from your site by using custom post types and 301 redirects, as it was meant to be.

## Usage

Simple URLs Legacy adds a new custom post type to your admin menu, where you can create, edit, delete, and manage URLs. It stores click counts in the form of a custom field on that custom post type, so it scales really well.

And, by avoiding page based redirects, which is the current trend in masking affiliate links, we avoid any issues with permalink conflicts, and therefore avoid any performance issues.

Note, the Lasso / Lasso Lite version of Simple URLs must be deactivated to use this legacy version of the plugin. A notice to this effect is displayed in the admin if you activate this version while the Lasso / Lasso Lite version is active. 

- Upload the entire `simple-urls-legacy` folder to the `/wp-content/plugins/` directory
- DO NOT change the name of the `simple-urls-legacy` folder
- Activate the plugin through the 'Plugins' menu in WordPress
- Navigate to `Settings > Permalinks`. That's it. Just open that screen and it saves. Trust me.
- Navigate to the `Simple URLs Legacy` menu
- Create a new URL, or manage existing URLs.
- Publish and use the URLs however you want!
