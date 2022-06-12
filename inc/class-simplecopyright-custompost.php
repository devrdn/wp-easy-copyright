<?php
// Die if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
   _e("Hello, i'm just plugin, and i'm called when wordpress call me!", 'simple-copy');
   die();
}

if ( !class_exists( SimpleCopyright_CustomPost::class ) ) :

class SimpleCopyright_CustomPost
{

   /**
    * If the class has been initialized.
    *
    * @var   bool
    * @since 1.0.0
    */
   public static $is_initialized = false;

   /**
    * Fields for the metabox.
    *
    * @since 1.0.0
    */
   private static $fields;
   
   /**
    * Constructor
    * Initialize the class
    *
    * @since 1.0.0
    */
   public function __construct() {
      if ( ! self::$is_initialized ) {
         self::init_hooks();
         self::init_settings();
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
      add_action( 'add_meta_boxes', [ __CLASS__, 'copyright_metabox_add' ], 100 );
      add_filter( 'enter_title_here', [ __CLASS__, 'copyright_change_add_title' ] );
      add_action( 'save_post', [ __CLASS__, 'copyright_metabox_save' ], 10, 2 );
   }

   /**
    * Init Settings
    */
   public static function init_settings() {
      self::$fields = [
         '_scpy_copy_name' => [
            'label'     => __('Copyright Name', 'simple-copy'),
            'type'      => 'text',
            'desc'      => __('Name of the enterprise / company, etc.', 'simple-copy'),
            'maxlength' => 100,
            'class'     => 'scpy__copy_name'
         ],
         '_scpy_start_year' => [
            'label'     => __('Start Year', 'simple-copy'),
            'type'      => 'number',
            'desc'      => __( 'Copyright Start Year.', 'simple-copy' ),
            'maxlength' => 4,
            'class'     => 'scpy__copy_name'
         ], 
         '_scpy_end_year' => [
            'label'      => __( 'End Year', 'simple-copy' ),
            'type'       => 'number',
            'desc'       => __( 'Copyright End Year.', 'simple-copy' ),
            'maxlength'  => 4,
            'class'      => 'scpy__end_year'
         ],
         '_scpy_symbol' => [
            'label'     => __( 'Symbol', 'simple-copy' ),
            'type'      => 'text',
            'desc'      => __( 'Copyright Symbol.', 'simple-copy' ),
            'maxlength' => 3,
            'class'     => 'scpy__symbol'
         ],
      ];
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

      // metabox for copyright post type
      add_meta_box(
         'simple-copy-metabox',
         __( 'Copyright Options', 'simple-copy' ),
         [ __CLASS__ , 'copyright_metabox_callback' ],
         SimpleCopyright::$post_type,
         'normal',
         'default'
      );

      // metabox for shortcode 
      add_meta_box(
         'simple-copy-metabox-shortcode',
         __( 'Shortcode', 'simple-copy' ),
         [ __CLASS__ , 'copyright_metabox_callback' ],
         SimpleCopyright::$post_type,
         'side',
         'low'
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
      $scpy_info = self::copyright_get_metabox_data( $post->ID, false ); // get data from post meta
      
      wp_nonce_field( 'simple_copyright_metabox_save', $copyright_nonce_name );
      ?>

      <div class="scpy-metabox-wrap"> 
      <?php
         foreach ( self::$fields as $field_name => $field_data ) { 
            ?> 
            <p class="scpy-metabox-field">
               <label for='<?php echo $field_name; ?>'><?php echo $field_data['label']; ?></label>
               <input type='<?php echo $field_data['type']; ?>' id='<?php echo $field_name; ?>' name='<?php echo $field_name; ?>' value='<?php echo $scpy_info[ $field_name ]?>'/>
               <code> * <?php echo $field_data['desc']; ?></code>
               <?php
                  if ( isset( $field_data['maxlength'] ) ) { ?>
                     <i><?php _e('Max Length: ', 'simple-copy'); echo $field_data['maxlength']; ?></i>
                  <?php }
               ?>
            </p>
         <?php
         }
      ?>
      </div>
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
      
      $fields = self::copyright_get_fields_name(); // get fields
      $scpy_info = []; // init array
      
      foreach ( $fields as $field ) {
         $scpy_info[ $field ] = get_post_meta( $post_id, $field, true );
         if ( $unset_fields && empty( $scpy_info[ $field ] ) ) {
            unset( $scpy_info[ $field ] );
         }
      }
          
      return $scpy_info;
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



      // save metabox data
      $fields = self::copyright_get_fields_data();

      foreach ( $fields as $id => $field ) {
         if ( empty( $_POST[ $id ] ) ) {
            delete_post_meta( $post_id, $id );
         } else {
            if ( $field['type'] == 'number' ) {
               if ( !is_numeric( $_POST[ $id ] ) ) {
                  delete_post_meta( $post_id, $id );
               } else {
                  update_post_meta( $post_id, $id, sanitize_text_field( intval( $_POST[ $id ] ) ) );
               }
            } else {
               update_post_meta( $post_id, $id, sanitize_text_field( $_POST[ $id ] ) );
            }
         }
      }
   }
   
   /**
    * @return array|null  field id's
    */
   public static function copyright_get_fields_name() {
      return array_keys(self::$fields);
   }
   /**
    * @return array|null  field data
    */
   public static function copyright_get_fields_data() {
      return self::$fields;
   }
}

endif; // class_exists