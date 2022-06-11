<?php
// Die if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
   _e("Hello, i'm just plugin, and i'm called when wordpress call me!", 'simple-copy');
   die();
}

if ( !class_exists( SimpleCopyright_Page::class ) ) :

class SimpleCopyright_Page 
{

   /**
    * If the class has been initialized.
    *
    * @var   bool
    * @since 1.0.0
    */
   public static $is_initialized = false;

   /**
    * Constructor
    * Initialize the class
    *
    * @since 1.0.0
    */
   public function __construct() {
      if ( ! self::$is_initialized ) {
         self::init_hooks();
      }
   }

   /**
    * Initialize the hooks
    *
    * @since 1.0.0
    */
   public static function init_hooks()
   {
      // init hooks
      self::$is_initialized = true;
      add_action( 'init', [ __CLASS__, 'copyright_post_type_register' ] );
      add_action( 'add_meta_boxes', [ __CLASS__, 'copyright_metabox_add' ] );
      add_filter( 'enter_title_here', [ __CLASS__, 'copyright_change_add_title' ] );
      add_action( 'save_post', [ __CLASS__, 'copyright_metabox_save' ], 10, 2 );
   }

   /**
    * Register New Post Type Copyright
    * 
    * @since 1.0.0
    */
   public static function copyright_post_type_register()
   {

      $labels = array(
         'name'               => _x( 'Simple Copyright', 'simple-copy' ),
         'singular_name'      => _x( 'Simple Copyright', 'simple-copy' ),
         'menu_name'          => __( 'Simple Copyright', 'simple-copy' ),
         'all_items'          => __( 'All Copyrights', 'simple-copy' ),
         'add_new'            => __( 'Add Copyright', 'simple-copy' ),
         'add_new_item'       => __( 'Add Copyright', 'simple-copy' ),
         'edit_item'          => __( 'Edit Copyright', 'simple-copy' ),
         'new_item'           => __( 'New Copyright', 'simple-copy' ),
         'view_item'          => __( 'View Copyright', 'simple-copy' ),
         'search_items'       => __( 'Search Copyright', 'simple-copy' ),
         'not_found'          => __( 'No Copyrights found', 'simple-copy' ),
         'not_found_in_trash' => __( 'No Copyrights found in Trash', 'simple-copy' )
      ); 

      $args = array(
         'public'              => true,
         'publicly_queryable'  => false,
         'exclude_from_search' => false,
         'show_in_nav_menus'   => false,
         'rewrite'             => [ 'slug' => SimpleCopyright::$plugin_slug ],
         'has_archive'         => false,
         'labels'              => $labels,
         'supports'            => [ 'title' ],
         'menu_icon'           => 'dashicons-admin-site',
      );

      register_post_type( SimpleCopyright::$post_type , $args );
   }

   /**
    * Change 'Add Title' text on Copyright post type
    *
    * @var   string $title
    * @since 1.0.0
    */
   public static function copyright_change_add_title( $title ) {
      $screen = get_current_screen();

      if (  $screen->post_type == SimpleCopyright::$post_type ) {
         $title = esc_html__( 'Add Copyright Title' , 'simple-copy' );
      }

      return $title;
   }

   /**
    * Add Copyright Plugin Metabox
    *
    * @since 1.0.0
    */
   public static function copyright_metabox_add() {
      add_meta_box(
         'simple-copy-metabox',
         __( 'Copyright Options', 'simple-copy' ),
         [ __CLASS__ , 'copyright_metabox_callback' ],
         SimpleCopyright::$post_type,
         'normal',
         'default'
      );
   }

   /**
    * Copyright Metabox Callback
    *
    * @var     array $post
    * @since   1.0.0
    */
   public static function copyright_metabox_callback( $post ) {
      
      $copyright_nonce_name = 'simple_copyright_nonce_'.$post->ID; // nonce name

      $sc_info = self::copyright_get_metabox_data( $post->ID, false ); // get data from post meta
      
      wp_nonce_field( 'simple_copyright_metabox_save', $copyright_nonce_name );
      ?>
         <p>
            <label for="_sc_copy_text">Copyright Text: </label>
            <input type="text" id="_sc_copy_text" name="_sc_copy_text" value="<?php echo esc_html( $sc_info['_sc_copy_text'] );?>" maxlength="50">
            <code> * <?php echo _e('Name of the enterprise / company, etc.', 'simple-copy');?></code>
            <i><?php echo _e('Max length', 'simple-copy').': 50' ?></i>
         </p>
         <p>
            <label for="_sc_starting_year">Starting Year: </label>
            <input type="number" id="_sc_starting_year" name="_sc_starting_year" value="<?php echo esc_html( $sc_info['_sc_starting_year'] );?>">
            <code> * <?php echo _e('Year must be numeric.', 'simple-copy')?> </code>
         </p>
         <p>
            <label for="_sc_ending_year">End Year: </label>
            <input type="number" id="_sc_ending_year" name="_sc_ending_year" value="<?php echo esc_html( $sc_info['_sc_ending_year'] );?>">
            <code> * <?php echo _e('If year is not specified, the current year will be used.', 'simple-copy')?> </code>
         </p>
         <p>
            <label for="_sc_symbol">Copyright symbol: </label>
            <input type="text" id="_sc_symbol" name="_sc_symbol" value="<?php echo esc_html( $sc_info['_sc_symbol'] );?>">
            <code> * <?php echo _e('Symbol to be used as copyright (e.g. &copy; (c)  ...). Default symbol: &copy;.', 'simple-copy');?> </code>
            <i><?php echo _e('Max length', 'simple-copy').': 3' ?></i>
         </p>
      <?php
   }

   /**
    * Get Copyright Metabox Data
    *
    * @var     int   $post_id
    * @var     bool  $unset_fields if true, unset empty values
    * @since   1.0.0
    */
   public static function copyright_get_metabox_data ( $post_id, $unset_fields = true ) {
      
      $fields = [
         '_sc_copy_text',
         '_sc_starting_year',
         '_sc_ending_year',
         '_sc_symbol',
      ];      

      foreach ( $fields as $field ) {
         $sc_info[ $field ] = get_post_meta( $post_id, $field, true );
         if ( $unset_fields ) {
            if ( empty( $sc_info[ $field ] ) ) {
               unset( $sc_info[ $field ] );
            }
            
         }
      }
      
      return $sc_info;
   }

  /**
   * Save Copyright Metabox
   * 
   * @since 1.0.0
   */
  public static function copyright_metabox_save( $post_id, $post ) {

      //check nonce fields
      $copyright_nonce_name = 'simple_copyright_nonce_'.$post_id;
      if ( !isset( $_POST[ $copyright_nonce_name ] ) || !wp_verify_nonce( $_POST[ $copyright_nonce_name ], 'simple_copyright_metabox_save'  ) ) {
         return $post_id;
      }

      // check autosave
      if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
         return $post_id;
      }

      // check if post is of type 'simplecopy'      
      if ( $post->post_type != SimpleCopyright::$post_type ) {
         return $post_id;
      }

      // check permissions
      if ( !current_user_can( 'edit_post', $post_id ) ) {
         return $post_id;
      }

      //save copyright text
      if ( empty( $_POST['_sc_copy_text'] ) ) {
         delete_post_meta( $post_id, '_sc_copy_text' );
      } else {
         update_post_meta( $post_id, '_sc_copy_text', sanitize_text_field( $_POST['_sc_copy_text'] ) );
      }

      //save starting year
      if ( empty( $_POST['_sc_starting_year'] ) ) {
         delete_post_meta( $post_id, '_sc_starting_year' );
      } else {
         //check if starting year is valid
         if ( !is_numeric( $_POST['_sc_starting_year'] ) ) {
            delete_post_meta( $post_id, '_sc_starting_year' );
         } else {
            update_post_meta( $post_id, '_sc_starting_year', sanitize_text_field( intval( $_POST['_sc_starting_year'] ) ) );
         }
      }

      //save ending year
      if ( empty( $_POST['_sc_ending_year'] ) ) {
         delete_post_meta( $post_id, '_sc_ending_year' );
      } else {
         //check if ending year is valid
         if ( !is_numeric( $_POST['_sc_ending_year'] ) ) {
            delete_post_meta( $post_id, '_sc_ending_year' );
         } else {
            update_post_meta( $post_id, '_sc_ending_year', sanitize_text_field( intval( $_POST['_sc_ending_year'] ) ) );
         }
      }

      //save symbol
      if ( empty( $_POST['_sc_symbol'] ) ) {
         delete_post_meta( $post_id, '_sc_symbol' );
      } else {
         if ( strlen( $_POST['_sc_symbol'] ) > 3 ) {
            delete_post_meta( $post_id, '_sc_symbol' );
         } else {
            update_post_meta( $post_id, '_sc_symbol', sanitize_text_field( $_POST['_sc_symbol'] ) );
         }
      }
   
   }
}

endif; // class_exists