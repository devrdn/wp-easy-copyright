<?php
/**
 * Plugin Name: Simple Copyright
 * Description: A plugin to manage copyright information for MSU.
 * Version: 0.0.1
 * Author: Nikken Plugins
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Copyright: Nikken Plugins
 * Text Domain: simple-copy
 * Domain Path: /lang
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

if ( ! defined( 'ABSPATH' ) ) {
   _x("Hello, i'm just plugin, and i'm called when wordpress call me!", 'simplecopy');
   die();
}

define( 'SIMPLECOPY___PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

if ( !class_exists( 'SC_Page' ) ) { 
   require_once( SIMPLECOPY___PLUGIN_DIR . 'inc/class-SC-page.php' );
}

if ( !class_exists( 'SimpleCopyright' ) ) :

class SimpleCopyright
{

   
   /**
    * If the class has been initialized.
    *
    * @var bool
    * @since 1.0.0
    */
   private static $is_initialized = false;

   /**
    * Post Type Name
    * 
    * @since 1.0.0
    */
   public static $post_type = 'simplecopy';

   /**
    * The slug of the plugin.
    *
    * @since 1.0.0
    */
   public static $plugin_slug = 'simplecopy';

   /**
    * The single instance of SimpleCopyright.
    * 
    * @var SimpleCopyright
    * @since 1.0.0
    */
   public static $instance;

   /**
    * Constructor
    * Initialize the class.
    *
    * @since 1.0.0
    */
   public function __construct() {
      if ( ! self::$is_initialized ) {
         self::init_hooks();
         new SC_Page();
      }
   }

   /**
    * Fetches the singleton instance of SimpleCopyright class.
    *
    * @since 1.0.0
    * @return object
    */
   public static function get_instance() {
      if ( !self::$instance ) {
         self::$instance = new self();
      }
      return self::$instance;
   }


   /**
    * Initialize the hooks
    *
    * @since 1.0.0
    */
   public static function init_hooks() {
      self::$is_initialized = true;

      // hooks for admin/front styles and scripts
      add_action( 'admin_enqueue_scripts', array( __CLASS__ , 'sc_enqueue_admin' ) );
      add_action( 'wp_enqueue_scripts', array( __CLASS__ , 'sc_enqueue_front' ) );

      // hooks for loading text domain
      add_action( 'plugins_loaded', array ( __CLASS__, 'load_simple_copyright_text_domain'));
   }

   /**
    * Loads the text domain for the plugin.
    *
    * @since 1.0.0
    */
   public static function load_simple_copyright_text_domain() {
      load_plugin_textdomain( 'simplecopy', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
   }

   /**
    * Enqueue admin styles and scripts
    *
    * @since 1.0.0
    */
   public static function sc_enqueue_admin()
   {
      wp_enqueue_style( 'sc-admin-style',  plugins_url( 'assets/css/admin/style.css', __FILE__ ) );
      wp_enqueue_script( 'sc-admin-script', plugins_url( 'assets/js/admin/script.js', __FILE__ ), array( 'jquery' ), 1.0, true );
   }

   /**
    * Enqueue front styles and scripts
    *
    * @since 1.0.0
    */
   public static function sc_enqueue_front()
   {
      wp_enqueue_style( 'sc-front-style',  plugins_url( 'assets/css/admin/style.css', __FILE__ ) );
      wp_enqueue_script( 'sc-front-script', plugins_url( 'assets/js/admin/script.js', __FILE__ ), array( 'jquery' ), 1.0, true);
   }

   
}

/**
 * Returns the instance of SimpleCopyright class.
 * 
 * @since 1.0.0
 */
function SimpleCopyright() {
   return SimpleCopyright::get_instance();
}

SimpleCopyright(); // load the class

endif;
