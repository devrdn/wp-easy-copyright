<?php

if ( ! defined( 'ABSPATH' ) ) {
   //echo 'Привет, я просто плагин и вызываюсь, когда меня позовут!';
   die();
}

class SC_Page
{

   public static $is_initialized = false;
   

   public function __construct()
   {
   }

   public static function init()
   {
      if ( ! self::$is_initialized ) {
         self::init_hooks();
      }
   }

   public static function init_hooks()
   {
      self::$is_initialized = true;
      add_action( 'init', array( __CLASS__, 'copyright_post_type_register' ) );
      add_action( 'add_meta_boxes', array( __CLASS__, 'copyright_metabox_add' ) );
      add_filter( 'enter_title_here', array( __CLASS__, 'copyright_change_add_title' ) );
      add_action( 'save_post', array( __CLASS__, 'copyright_metabox_save' ), 10, 2 );
   }

   /**
    * Register New Post Type Copyright
    * 
    * @since 1.0.0
    */
   public static function copyright_post_type_register()
   {

      $labels = array(
         'name' => esc_html__( 'Simple Copyright', 'simple-copy' ),
         'singular_name' => esc_html__( 'Simple Copyright', 'simple-copy' ),
         'menu_name' => esc_html__( 'Simple Copyright', 'simple-copy' ),
         'all_items' => esc_html__( 'All Copyrights', 'simple-copy' ),
         'add_new' => esc_html__( 'Add Copyright', 'simple-copy' ),
         'add_new_item' => esc_html__( 'Add Copyright', 'simple-copy' ),
         'edit_item' => esc_html__( 'Edit Copyright', 'simple-copy' ),
         'new_item' => esc_html__( 'New Copyright', 'simple-copy' ),
         'view_item' => esc_html__( 'View Copyright', 'simple-copy' ),
         'search_items' => esc_html__( 'Search Copyright', 'simple-copy' ),
         'not_found' => esc_html__( 'No Copyright found', 'simple-copy' ),
         'not_found_in_trash' => esc_html__( 'No Copyright found in Trash', 'simple-copy' )
      ); 

      $args = array(
         'public' => true,
         'publicly_queryable' => false,
         'exclude_from_search' => false,
         'show_in_nav_menus' => false,
         'rewrite' => [ 'slug' => 'Simple Copyright' ],
         'has_archive' => false,
         'labels' => $labels,
         'supports' => array('title'),
         'menu_icon' => 'dashicons-admin-site',
      );

      register_post_type( 'simplecopy' , $args );
   }

   public static function copyright_change_add_title( $title ) {
      $screen = get_current_screen();

      if ( 'simplecopy' == $screen->post_type ) {
         $title = esc_html__( 'Add Copyright Title' , 'simplecopy' );
      }

      return $title;
   }

   public static function copyright_metabox_add() {
      add_meta_box(
         'simple-copy-metabox',
         esc_html__( 'Copyright Options', 'simple-copy' ),
         array( __CLASS__ , 'copyright_metabox_callback' ),
         'simplecopy',
         'normal',
         'default'
      );
   }

   public static function copyright_metabox_callback( $post ) {
      $sc_copy_text = get_post_meta( $post->ID, 'sc__copy_text', true );
      $sc_starting_year = get_post_meta( $post->ID, 'sc__starting_year', true );
      $sc_end_year = get_post_meta( $post->ID, 'sc__ending_year', true );

      wp_nonce_field( 'simple_copyright_metabox_save', 'simple_copyright_metabox_save_nonce' );
      ?>
         <p>
            <label for="sc__copy_text">Copyright Text: </label>
            <input type="text" id="sc__copy_text" name="sc__copy_text" value="<?php echo esc_html( $sc_copy_text );?>" maxlength="50">
            <code> * <?php echo esc_html_x('Name of the enterprise / company, etc.', 'simple-copy');?></code>
            <i><?php echo esc_html__('Max length').': 50' ?></i>
         </p>
         <p>
            <label for="sc__starting_year">Starting Year: </label>
            <input type="text" id="sc__starting_year" name="sc__starting_year" value="<?php echo esc_html( $sc_starting_year );?>">
            <code> * <?php echo esc_html_x('Year must be numeric.', 'simple-copy')?> </code>
         </p>
         <p>
            <label for="sc__ending_year">End Year: </label>
            <input type="text" id="sc__ending_year" name="sc__ending_year" value="<?php echo esc_html( $sc_end_year );?>">
            <code> * <?php echo esc_html_x('If year is not specified, the current year will be used.', 'simple-copy')?> </code>
         </p>
         <p>
            <label for="sc__ending_year">Copyright symbol: </label>
            <input type="text" id="sc__ending_year" name="sc__ending_year" value="<?php echo esc_html( $sc_end_year );?>">
            <code> * <?php echo esc_html__('Symbol to be used as copyright (e.g. &copy; (c)  ...). Default symbol: &copy;.');?> </code>
            <i><?php echo esc_html__('Max length').': 1' ?></i>
         </p>
      <?php
   }

  public static function copyright_metabox_save( $post_id, $post ) {

      //check nonce fields
      if ( !isset( $_POST['simple_copyright_metabox_save_nonce'] ) || !wp_verify_nonce( $_POST['simple_copyright_metabox_save_nonce'], 'simple_copyright_metabox_save'  ) ) {
         return $post_id;
      }

      // check autosave
      if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
         return $post_id;
      }

      // check if post is of type 'simplecopy'      
      if ( $post->post_type != 'simplecopy' ) {
         return $post_id;
      }

      // check permissions
      if ( !current_user_can( 'edit_post', $post_id ) ) {
         return $post_id;
      }

      //save copyright text
      if ( empty( $_POST['sc__copy_text'] ) ) {
         delete_post_meta( $post_id, 'sc__copy_text' );
      } else {
         update_post_meta( $post_id, 'sc__copy_text', sanitize_text_field( $_POST['sc__copy_text'] ) );
      }

      //save starting year
      if ( empty( $_POST['sc__starting_year'] ) ) {
         delete_post_meta( $post_id, 'sc__starting_year' );
      } else {
         //check if starting year is valid
         if ( !is_numeric( $_POST['sc__starting_year'] ) ) {
            delete_post_meta( $post_id, 'sc__starting_year' );
         } else {
            update_post_meta( $post_id, 'sc__starting_year', sanitize_text_field( intval( $_POST['sc__starting_year'] ) ) );
         }
      }

      //save ending year
      if ( empty( $_POST['sc__ending_year'] ) ) {
         delete_post_meta( $post_id, 'sc__ending_year' );
      } else {
         //check if ending year is valid
         if ( !is_numeric( $_POST['sc__ending_year'] ) ) {
            delete_post_meta( $post_id, 'sc__ending_year' );
         } else {
            update_post_meta( $post_id, 'sc__ending_year', sanitize_text_field( intval( $_POST['sc__ending_year'] ) ) );
         }
      }
   }
}
