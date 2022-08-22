<?php
// Die if this file is called directly.

if ( ! defined( 'ABSPATH' ) ) {
   _e( 'Hello, i\'m just plugin, and i\'m called when wordpress call me!', 'simple-copy');
   die();
}


if ( !class_exists( SimpleCopyright_Widget::class ) ) :

/**
 * Class for SimpleCopyright Widget
 * 
 * @since   0.0.1
 */
class SimpleCopyright_Widget extends WP_Widget {

   /**
    * Widget constuctor
    * Creating new widget (calls parent constructor)
    *
    * @since   0.0.1
    */
   public function __construct() {
      $widget_options = array(
         'classname'    => 'scpy_widget',
         'description'  => 'Shows Your Own Copyright',
      );
      parent::__construct( 
         'simplecopyright_widget', 
         esc_html__( 'SimpleCopyright Widget' , 'simple-copy' ), 
         $widget_options,
      );
   }


   /**
    * Creating widget front-end
    *
    * @since   0.0.1
    */
   public function widget($args, $instance) {
      echo $args['before_widget'];

      // echo copyright
      $id = $instance['id'];
      echo do_shortcode( '[simple-copyright id="'.$id.'"]' );

      echo $args['after_widget'];
   }

   /**
    * Creating back-end widget form
    *
    * @since   0.0.1
    */
   public function form($instance) {
      $id = isset( $instance['id'] ) ? $instance['id'] : '';
      ?>
         <div class="scpy-widget">
            <label for="<?php echo $this->get_field_id('id'); ?>">Copyright ID: </label>
            <input class="widefat scpy-widget__id" type="text" name="<?php echo $this->get_field_name('id'); ?>" id="<?php echo $this->get_field_id('id'); ?>" value="<?php echo esc_attr($id); ?>" />
         </div>
      <?php
   }

   /**
    * Updating Widget
    * Replacing old instance with new instance
    * 
    * @since   0.0.1
    */
   public function update($new_instance, $old_instance) {
      $instance = $old_instance;
      $instance['id'] = strip_tags( $new_instance['id'] );
      return $instance;
   }
}

endif;