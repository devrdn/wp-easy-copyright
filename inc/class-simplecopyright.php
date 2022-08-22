<?php

// Die if this file is called directly.

if ( ! defined( 'ABSPATH' ) ) {
   _e( 'Hello, i\'m just plugin, and i\'m called when wordpress call me!', 'simple-copy');
   die();
}

if ( ! class_exists( SimpleCopyright::class ) ) :


/**
 * The core plugin class.
   * 
   * This is used to define internationalization, admin-specific hooks, and
   * public-facing site hooks.
   * 
   * @since 1.0.0
   */
class SimpleCopyright
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
   public static $post_type = 'simplecopy';

   /**
    * The slug of the plugin.
    *
    * @since 1.0.0
    */
   public static $plugin_slug = 'simplecopy';

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
         new SimpleCopyright_CustomPost();
         new SimpleCopyright_Shortcode();
      }
   }

   public static function register_widget() {
      register_widget('simplecopyright_widget');
   } 

   /**
    * Initialize the hooks
    *
    * @since 1.0.0
    */
   private static function init_hooks() {
      self::$is_initialized = true;

      // hooks for admin/front styles and scripts
      add_action( 'admin_enqueue_scripts', [ __CLASS__ , 'sc_enqueue_admin' ] );
      add_action( 'wp_enqueue_scripts', [ __CLASS__ , 'sc_enqueue_front' ] );

      // hooks for loading text domain
      add_action( 'plugins_loaded', [ __CLASS__ , 'sc_load_text_domain' ] );

      add_action ( 'widgets_init', [ __CLASS__, 'register_widget'] );
   }


   /**
    * Loads the text domain for the plugin.
    *
    * @since 1.0.0
    */
   public static function sc_load_text_domain() {
      load_plugin_textdomain( 'simple-copy', false, SIMPLECOPYRIGHT__LANGUAGE_DIR );
   }

   /**
    * Enqueue admin styles and scripts
    *
    * @since 1.0.0
    */
   public static function sc_enqueue_admin()
   {
      wp_enqueue_style( 'sc-admin-style',  SIMPLECOPYRIGHT__ASSETS_DIR.'/css/admin/style.css' );
      wp_enqueue_script( 'sc-admin-script', SIMPLECOPYRIGHT__ASSETS_DIR.'/js/admin/scpy-tabs.js', array(), 1.0, 'in_footer' );
   }

   /**
    * Enqueue front styles and scripts
    *
    * @since 1.0.0
    */
   public static function sc_enqueue_front()
   {
      wp_enqueue_style( 'sc-front-style',  SIMPLECOPYRIGHT__ASSETS_DIR.'assets/css/front/style.css' );
      wp_enqueue_script( 'sc-front-script', SIMPLECOPYRIGHT__ASSETS_DIR.'assets/js/front/script.js', array( 'jquery' ), 1.0, true);
   }

}
   
endif;
   