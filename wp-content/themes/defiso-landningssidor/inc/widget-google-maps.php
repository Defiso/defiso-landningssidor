<?php

// Block direct requests
if ( !defined('ABSPATH') )
  die('-1');
  
  
add_action( 'widgets_init', function(){
     register_widget( 'ladningpage_google_maps_widget' );
}); 

/**
 * Adds ladningpage_contact_widget widget.
 */
class ladningpage_google_maps_widget extends WP_Widget {


  /**
   * Register widget with WordPress.
   */
  function __construct() {
    parent::__construct(
      'ladningpage_google_maps_widget', // Base ID
      __('Google Maps', 'text_domain'), // Name
      array( 'description' => __( 'Google Maps infogat där du vill ha det. Smart!', 'text_domain' ), ) // Args
    );
  }

  /**
   * Front-end display of widget.
   *
   * @see WP_Widget::widget()
   *
   * @param array $args     Widget arguments.
   * @param array $instance Saved values from database.
   */
  public function widget( $args, $instance ) {
    global $landingpage;
    echo $args['before_widget'];
    if ( ! empty( $instance['title'] ) ) {
      echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
    }
      echo '<img src="https://maps.googleapis.com/maps/api/staticmap?center='. $landingpage['text-client-adress'] . ',' . $landingpage['text-client-zip'] . ',' . $landingpage['text-client-city'] .'&zoom=17&size=600x300&maptype=roadmap
&markers=color:blue%7Clabel:S%7C11211">';
    echo $args['after_widget'];
  }

  /**
   * Back-end widget form.
   *
   * @see WP_Widget::form()
   *
   * @param array $instance Previously saved values from database.
   */
  public function form( $instance ) {
    if ( isset( $instance[ 'title' ] ) ) {
      $title = $instance[ 'title' ];
    }
    else {
      $title = __( 'Kontakta oss', 'text_domain' );
    }
    ?>
    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
      <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
    </p>
    <?php 
  }

  /**
   * Sanitize widget form values as they are saved.
   *
   * @see WP_Widget::update()
   *
   * @param array $new_instance Values just sent to be saved.
   * @param array $old_instance Previously saved values from database.
   *
   * @return array Updated safe values to be saved.
   */
  public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

    return $instance;
  }

} // class ladningpage_contact_widget