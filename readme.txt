=== Easy Copyright ===
Contributors: rdndev
Tags: copyright, shortcode
Requires at least: 5.9
Tested up to: 6.1
Stable tag: 1.0.1
Requires PHP: 7.3
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

WordPress plugin that allows you to add copyrights to the site using shortcodes

== Description ==

WordPress 'Easy Copyright' plugin is used to add copyright to your site using shortcode via admin-panel.

You can add, edit, delete copyrights in `Easy Copyright` wordpress submenu.

In order to add copyright you need to press `Add New Copyright` button in the specified menu and fill required fields.

## Tabs

When you add copyright, you can see two tabs.

* Information
* Item Order

**Information Tab** — Information about copyright (Copyright Name, Start Year, etc.)

**Item Order** — Copyright component display order (E.g. \[copyright_name\] \[start_year\] — \[end_year\] ... )

_Note_: You can add additional text in item order tab.

## How to use?

When you save copyright, shortcode displays.

You can use shortcode in wordpress editors or in php code.

**In WordPress Editors**: 
```
[easy-copyright id='COPYRIGHT_ID_HERE']
```
**In PHP Code**: 
```
do_shortcode( "[easy-copyright id='COPYRIGHT_ID_HERE']" );
```

== Changelog ==

= 1.0.0 =   

* First public plugin version

= 1.0.1 =   

* Fix — Bug with `assets` directory path

= 1.0.2 =   

* Test — Plugin tested on PHP version 7.3
* Feat — Added support for 7.3 PHP version