<?php
// Die if this file is called directly.

if ( ! defined( 'ABSPATH' ) ) {
   _e( 'Hello, i\'m just plugin, and i\'m called when wordpress call me!', 'easy-copy');
   die();
}


if ( !class_exists( EasyCopyright_Widget::class ) ) :

/**
 * Class for EasyCopyright Widget
 * 
 * @since   1.0.0
 */
class EasyCopyright_Widget extends WP_Widget {

   /**
    * Widget constuctor
    * Creating new widget (calls parent constructor)
    *
    * @since   1.0.0
    */
   public function __construct() {
      $widget_options = array(
         'classname'    => 'easy_widget',
         'description'  => __( 'Shows Your Own Copyright', 'easy-copy' )
      );
      parent::__construct( 
         'easycopyright_widget', 
         __( 'EasyCopyright Widget' , 'easy-copy' ), 
         $widget_options
      );
   }


   /**
    * Creating widget front-end
    *
    * @since   1.0.0
    */
   public function widget($args, $instance) {
      echo $args['before_widget'];

      // echo copyright
      $id = $instance['id'];
      echo do_shortcode( '[easy-copyright id="' . esc_attr( $id ). '"]' );

      echo $args['after_widget'];
   }

   /**
    * Creating back-end widget form
    *
    * @since   1.0.0
    */
   public function form($instance) {
      $id = isset( $instance['id'] ) ? $instance['id'] : '';
      ?>
         <div class="easy-widget">
            <label for="<?php echo esc_attr( $this->get_field_id('id') ); ?>">Copyright ID: </label>
            <input class="widefat easy-widget__id" type="text" name="<?php echo esc_attr( $this->get_field_name('id') ); ?>" id="<?php echo  esc_attr( $this->get_field_id('id') ); ?>" value="<?php echo esc_attr( $id ); ?>" />
         </div>
      <?php
   }

   /**
    * Updating Widget
    * Replacing old instance with new instance
    * 
    * @since   1.0.0
    */
   public function update($new_instance, $old_instance) {
      $instance = $old_instance;
      $instance['id'] = strip_tags( $new_instance['id'] );
      return $instance;
   }
}

endif;