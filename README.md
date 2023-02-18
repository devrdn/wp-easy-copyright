# WordPress EasyCopyright plugin

## About

WordPress 'Easy Copyright' plugin is used to add copyright to your te using shortcode via admin-panel.

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
```php
do_shortcode( '[easy-copyright id='COPYRIGHT_ID_HERE']' );
```