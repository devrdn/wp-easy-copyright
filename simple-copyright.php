<?php
/**
 * Plugin Name: Simple Copyright
 * Description: A plugin to manage copyright information for MSU.
 * Version: 0.0.1
 * Author: Nikken Plugins
 * Text Domain: simplecopy
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Copyright: Nikken Plugins
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2022 Nikken Plugins, Inc.
*/


if ( !defined( 'ABSPATH' ) ) {
   //echo 'Привет, я просто плагин и вызываюсь, когда меня позовут!';
   die();
}

define( 'SIMPLECOPY___PLUGIN_DIR', plugin_dir_path( __FILE__ ) );


require_once( SIMPLECOPY___PLUGIN_DIR . 'inc/class-SC-page.php' );

class SimpleCopyright
{

   private static $is_initialized = false;

   public static $instance;

   public function __construct() {

   }

   public static function init() {
      if ( ! self::$is_initialized ) {
         SC_Page::init_hooks();
      }
   }

}

add_action( 'plugins_loaded', array( 'SimpleCopyright', 'init' ) );


/*
if (class_exists('MsuCopy')) {
   $msu_copy = new MsuCopy();
   $msu_copy->init_actions();
}
*/
