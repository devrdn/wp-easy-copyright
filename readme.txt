=== Simple Copyright ===
Contributors: rdndev
Tags: copyright, shortcode
Requires at least: 5.9
Tested up to: 5.9
Stable tag: 1.0.0
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

WordPress plugin that allows you to add copyrights to the site using shortcodes

== Description ==

WordPress simple copyright plugin is used to add copyright to your site using shortcode via admin-panel.

You can add, edit, delete copyrights in `Simple Copyright` wordpress submenu.

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
[simple-copyright id='COPYRIGHT_ID_HERE']
```
**In PHP Code**: 
```php 
do_shortcode( '[simple-copyright id='COPYRIGHT_ID_HERE']' );
```

== Changelog ==

= 1.0.0 =

* First public plugin version