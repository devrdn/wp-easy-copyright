<?php

// Die if this file is called directly.

if ( ! defined( 'ABSPATH' ) ) {
   _e( 'Hello, i\'m just plugin, and i\'m called when wordpress call me!', 'easy-copy');
   die();
}

if ( ! class_exists( EasyCopyright::class ) ) :


/**
 * The core plugin class.
 * 
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 * 
 * @since 1.0.0
 */
class EasyCopyright
{  
   /**
    * If the class has been initialized.
    *
    * @var     bool
    * @since   1.0.0
    */
   private static $is_initialized = false;

   /**
    * Post Type Name
    * 
    * @since 1.0.0
    */
   public static $post_type = 'easycopy';

   /**
    * The slug of the plugin.
    *
    * @since 1.0.0
    */
   public static $plugin_slug = 'easycopy';

   /**
    * The version of the plugin.
    *
    * @since 1.0.0
    */
   public static $plugin_version = '1.0.0';

   /**
    * Constructor
    * Initialize the class.
    *
    * @since 1.0.0
    */
   public function __construct() {
      if ( ! self::$is_initialized ) {
         self::init_hooks();
         new EasyCopyright_CustomPost();
         new EasyCopyright_Shortcode();
      }
   }

   /**
    * Register easy copyright widget
    *
    * @since 1.0.0
    */
   public static function register_widget() {
      register_widget( 'easycopyright_widget' );
   } 

   /**
    * Initialize the hooks
    *
    * @since 1.0.0
    */
   private static function init_hooks() {
      self::$is_initialized = true;

      // hooks for admin/front styles and scripts
      add_action( 'admin_enqueue_scripts', [ __CLASS__ , 'ec_enqueue_admin' ] );
      add_action( 'wp_enqueue_scripts', [ __CLASS__ , 'ec_enqueue_front' ] );

      // hooks for loading text domain
      add_action( 'plugins_loaded', [ __CLASS__ , 'ec_load_text_domain' ] );

      add_action ( 'widgets_init', [ __CLASS__, 'register_widget'] );
   }


   /**
    * Loads the text domain for the plugin.
    *
    * @since 1.0.0
    */
   public static function ec_load_text_domain() {
      load_plugin_textdomain( 'easy-copy', false, EASYCOPYRIGHT__LANGUAGE_DIR );
   }

   /**
    * Enqueue admin styles and scripts
    *
    * @since 1.0.0
    */
   public static function ec_enqueue_admin()
   {
      wp_enqueue_style( 'ec-admin-style',  EASYCOPYRIGHT__ASSETS_DIR.'/css/admin/style.css' );
      wp_enqueue_script( 'ec-admin-script', EASYCOPYRIGHT__ASSETS_DIR.'/js/admin/scpy-tabs.js', array(), 1.0, 'in_footer' );
   }

   /**
    * Enqueue front styles and scripts
    *
    * @since 1.0.0
    */
   public static function ec_enqueue_front()
   {
      wp_enqueue_style( 'ec-front-style',  EASYCOPYRIGHT__ASSETS_DIR.'/css/front/style.css' );
      wp_enqueue_script( 'ec-front-script', EASYCOPYRIGHT__ASSETS_DIR.'/js/front/script.js', array( 'jquery' ), 1.0, true);
   }

}
   
endif;
   