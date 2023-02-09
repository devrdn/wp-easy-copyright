<?php
// Die if this file is called directly.
if ( ! defined( 'ABSPATH' ) ) {
   _e("Hello, i'm just plugin, and i'm called when wordpress call me!", 'easy-copy');
   die();
}

if ( !class_exists( EasyCopyright_CustomPost::class ) ) :

class EasyCopyright_CustomPost
{

   /**
    * If the class has been initialized.
    *
    * @param   bool
    * @since 1.0.0
    */
   public static $is_initialized = false;

   /**
    * Fields for the `information section`  metabox.
    *
    * @since 1.0.0
    */
   private static $fields_information;

   /**
    * Fields for the `items order section`  metabox.
    */
   private static $fields_order;
   
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
      
      // custom column hooks
      add_filter( 'manage_easycopy_posts_columns', [ __CLASS__, 'copyright_filter_post_columns' ] );
      add_action( 'manage_easycopy_posts_custom_column', [ __CLASS__, 'copyright_post_columns_data' ], 10, 2 );
   }

   /**
    * Init Settings
    */
   public static function init_settings() {
      self::$fields_information = [
         '_easy_copyright_name' => [
            'label'     => __('Copyright Name', 'easy-copy'),
            'type'      => 'text',
            'desc'      => __('Name of the enterprise / company, etc', 'easy-copy'),
            'maxlength' => 100,
            'default'   => 'none',
            'class'     => 'easy__copy_name'
         ],
         '_easy_start_year' => [
            'label'     => __('Start Year', 'easy-copy'),
            'type'      => 'number',
            'desc'      => __( 'Copyright Start Year', 'easy-copy' ),
            'maxlength' => 4,
            'default'   => 'none',
            'class'     => 'easy__copy_name'
         ], 
         '_easy_end_year' => [
            'label'      => __( 'End Year', 'easy-copy' ),
            'type'       => 'number',
            'desc'       => __( 'Copyright End Year', 'easy-copy' ),
            'maxlength'  => 4,
            'default'    => 'Current Year',
            'class'      => 'easy__end_year'
         ],
         '_easy_symbol' => [
            'label'     => __( 'Symbol', 'easy-copy' ),
            'type'      => 'text',
            'desc'      => __( 'Copyright Symbol', 'easy-copy' ),
            'maxlength' => 3,
            'default'   => '&copy;',
            'class'     => 'easy__symbol'
         ],
         '_easy_extra_text' => [
            'label'     => __( 'Extra Text', 'easy-copy' ),
            'type'      => 'text',
            'desc'      => __( 'Copyright extra text (e.g. all rights reserved)', 'easy-copy' ),
            'maxlength' => 100,
            'default'   => 'All Rights Reserved',
            'class'     => 'easy__extra_text'
         ]
      ];

      self::$fields_order = [
         '_easy_item_order' => [
            'label'     => __( 'Item Order', 'easy-copy' ),
            'type'      => 'text',
            'desc'      => __( 'Copyright information order', 'easy-copy' ),
            'maxlength' => 300,
            'default'   => '[symbol] [start_year] [end_year] [copyright_name]. [extra_text].',
            'class'     => 'easy__item_order',
            'info'      => [
               'copyright_name'  =>  __( 'Name of the enterprise / company, etc', 'easy-copy'),
               'start_year'      =>  __( 'Copyright Start Year', 'easy-copy'),
               'end_year'        =>  __( 'Copyright End Year', 'easy-copy'),
               'symbol'          =>  __( 'Copyright Symbol', 'easy-copy'),
               'extra_text'      =>  __( 'Copyright extra text', 'easy-copy')
            ]
         ]
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
         'name'               => _x( 'Easy Copyright', 'easy-copy' ),
         'singular_name'      => _x( 'Easy Copyright', 'easy-copy' ),
         'menu_name'          => __( 'Easy Copyright', 'easy-copy' ),
         'all_items'          => __( 'All Copyrights', 'easy-copy' ),
         'add_new'            => __( 'Add Copyright', 'easy-copy' ),
         'add_new_item'       => __( 'Add Copyright', 'easy-copy' ),
         'edit_item'          => __( 'Edit Copyright', 'easy-copy' ),
         'new_item'           => __( 'New Copyright', 'easy-copy' ),
         'view_item'          => __( 'View Copyright', 'easy-copy' ),
         'search_items'       => __( 'Search Copyright', 'easy-copy' ),
         'not_found'          => __( 'No Copyrights found', 'easy-copy' ),
         'not_found_in_trash' => __( 'No Copyrights found in Trash', 'easy-copy' )
      ); 

      $args = array(
         'public'              => true,
         'publicly_queryable'  => false,
         'exclude_from_search' => false,
         'show_in_nav_menus'   => false,
         'rewrite'             => [ 'slug' => EasyCopyright::$plugin_slug ],
         'has_archive'         => false,
         'labels'              => $labels,
         'supports'            => [ 'title' ],
         'menu_icon'           => 'dashicons-admin-site'
      );

      register_post_type( EasyCopyright::$post_type , $args );
   }

   /**
    * Customize the post columns for easy-copyright post type
    *
    * @return array $columns
    * @since 1.0.0
    */
   public static function copyright_filter_post_columns( $columns ) {
      $columns['shortcode'] = __( 'Shortcode', 'easy-copy' );
      $columns['modified']  = __('Last Modified', 'easy-copy');
      return $columns;
   }

   /**
    * Add data to the custom columns to the easy-copyright post type
    * 
    * @param string  $column  Name of the colums
    * @param int     $post_id Current post ID
    *
    * @since 1.0.0
    */
   public static function copyright_post_columns_data( $column, $post_id ) {
      $post = get_post($post_id);

      switch ( $column ) {
         
         case 'shortcode':
            if ( 'publish' == $post->post_status ) {
               echo '<code>[easy-copyright id="'. esc_attr( $post_id ).'"]</code>';
            } else {
               _e( 'Save copyright in order to see shortcode', 'easy-copy' );
            }
            break;

         case 'modified':
            the_modified_date();
            echo '&nbsp;';
            the_modified_time();
            break;

      }
   }

   /**
    * Change 'Add Title' text on Copyright post type
    *
    * @param   string $title
    * @since 1.0.0
    */
   public static function copyright_change_add_title( $title ) {
      $screen = get_current_screen();

      if (  $screen->post_type == EasyCopyright::$post_type ) {
         $title = esc_html__( 'Add Copyright Title' , 'easy-copy' );
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
         'easy-copy-metabox',
         __( 'Copyright Options', 'easy-copy' ),
         [ __CLASS__ , 'copyright_metabox_callback' ],
         EasyCopyright::$post_type,
         'normal',
         'default'
      );
   }

   /**
    * Copyright Metabox Callback
    *
    * @param   Object $post
    * @since   1.0.0
    */
   public static function copyright_metabox_callback( $post ) {
      
      $copyright_nonce_name = 'easy_copyright_nonce_'.$post->ID; // nonce name
      $easy_info = self::copyright_get_metabox_data( $post->ID, false ); // get data from post meta
   
      wp_nonce_field( 'easy_copyright_metabox_save', $copyright_nonce_name );
      ?>
                 

      <div class="easy-metabox-wrap"> 
         <div class="tabs">
            <div class="tabs-trigger">
               <a href="#easy-form" class="tabs-trigger__item active-trigger">Information</a>
               <a href="#easy-order" class="tabs-trigger__item">Items Order</a>
            </div>
            <div class="tabs-content">
               <div id="easy-form" class="tabs-content__item active-tab">
                  <?php
                     foreach ( self::$fields_information as $field_name => $field_data ):
                  ?> 
                  <div class="easy-metabox-field">
                     <div class="easy-metabox-field__label">
                        <label for='<?php echo esc_attr( $field_name ); ?>'><?php echo esc_attr( $field_data['label'] ); ?></label>
                     </div>
                     <div class="easy-metabox-field__options">
                        <div class="easy-metabox-field__input">
                              <input type='<?php echo  esc_attr( $field_data['type'] ); ?>' id='<?php echo esc_attr( $field_name ); ?>' 
                                 name='<?php echo esc_attr( $field_name ); ?>' value='<?php echo esc_attr ($easy_info[ $field_name ] )?>'
                                 maxlength='<?php echo esc_attr( $field_data['maxlength'] ); ?>'
                              />
                           <span>Default: <code><?php echo esc_attr( $field_data['default'] ); ?></code></span>
                        </div>
                        <div class="easy-metabox-field__desc">
                           <span> * <?php echo esc_attr( $field_data['label'] ); ?></span>
                        </div>
                     </div>
                  </div>
                  <?php endforeach; ?>
               </div>
               <div id="easy-order" class="tabs-content__item">
                  
                  <?php
                     foreach( self::$fields_order as $field_name => $field_data):
                  ?>
                  <div class="easy-metabox-field">
                     <div class="easy-metabox-field__info">
                        <div class="easy-metabox-field__label">
                           <label for='<?php echo esc_attr( $field_name ); ?>'><h3><?php echo esc_attr( $field_data['label'] ); ?></h3></label>
                        </div>
                        <div class="easy-metabox-field__info">
                           <?php foreach( $field_data['info'] as $info_name => $info_desc ): ?>
                              <div class="easy-item">
                                 <span class="easy-item__name"><code>[<?php echo esc_attr( $info_name ); ?>]</code>&nbsp;-&nbsp;<?php echo esc_attr( $info_desc ); ?></span>
                              </div>
                           <?php endforeach; ?>
                        </div>
                     </div>
                     <div class="easy-metabox-field__options">
                        <div class="easy-metabox-field__input">
                              <input type='<?php echo esc_attr( $field_data['type'] ); ?>' id='<?php echo esc_attr( $field_name ); ?>' 
                                 name='<?php echo esc_attr( $field_name ); ?>' 
                                 maxlength='<?php echo esc_attr( $field_data['maxlength'] ); ?>'
                                 value='<?php echo esc_attr( $easy_info[ $field_name ] )?>'
                              />
                           <span>Default: <code><?php echo esc_attr( $field_data['default'] ); ?></code></span>
                        </div>
                        <div class="easy-metabox-field__desc">
                           <span> * <?php echo esc_attr( $field_data['desc'] ); ?></span>
                        </div>
                     </div>
                  </div>
                  <? endforeach; ?>
               </div>  
            </div>
         </div>
      </div>
      <?php
   }

   /**
    * Get Copyright Metabox Data
    *
    * @param     int   $post_id
    * @param     bool  $unset_fields if true, unset empty values
    * @since   1.0.0
    */
   public static function copyright_get_metabox_data ( $post_id, $unset_fields = true ) {
      
      $fields = self::copyright_get_fields_name(); // fields to get from db
      $fields[] = self::copyright_get_field_order_name();

      $easy_info = []; // init array
      
      foreach ( $fields as $field ) {
         $easy_info[ $field ] = get_post_meta( $post_id, $field, true );
         if ( $unset_fields && empty( $easy_info[ $field ] ) ) {
            unset( $easy_info[ $field ] );
         }
      }
          
      return $easy_info;
   }

  /**
   * Save Copyright Metabox
   * 
   * @since 1.0.0
   */
  public static function copyright_metabox_save( $post_id, $post ) {

      //check nonce fields
      $copyright_nonce_name = 'easy_copyright_nonce_'.$post_id;
      if ( !isset( $_POST[ $copyright_nonce_name ] ) || !wp_verify_nonce( $_POST[ $copyright_nonce_name ], 'easy_copyright_metabox_save'  ) ) {
         return $post_id;
      }

      // check autosave
      if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
         return $post_id;
      }

      // check if post is of type 'easycopy'      
      if ( $post->post_type != EasyCopyright::$post_type ) {
         return $post_id;
      }

      // check permissions
      if ( !current_user_can( 'edit_post', $post_id ) ) {
         return $post_id;
      }

      // save metabox data
      $fields = self::copyright_get_fields_data();
      $fields_order = self::copyright_get_field_order_info();
      $fields_order_name = self::copyright_get_field_order_name();
      
      // save main fields
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

      // save extra fields (order)
      if( empty( $_POST[ $fields_order_name ]  ) ) {
         delete_post_meta( $post_id, $fields_order_name );
      } 

      // save fields order
      if( strlen( $_POST[ $fields_order_name ] ) < $fields_order['maxlength'] ) {
         update_post_meta( $post_id, $fields_order_name, sanitize_text_field( $_POST[ $fields_order_name ] ) );
      } else {
         delete_post_meta( $post_id, $fields_order_name ); 
      }

      return $post_id;
   }
   
   /**
    * @return array|null  field id's
    */
   public static function copyright_get_fields_name() {
      return array_keys( self::$fields_information );
   }

   /**
    * @return array|null  field data
    */
   public static function copyright_get_fields_data() {
      return self::$fields_information;
   }

   /**
    * @return array|null copyright order of fields information
    */
   public static function copyright_get_field_order_info() {
      return self::$fields_order[ self::copyright_get_field_order_name() ];
   }
   
   /**
    * @return string order field name
    */
   public static function copyright_get_field_order_name() {
      return array_key_first( self::$fields_order );
   }
}

endif; // class_exists