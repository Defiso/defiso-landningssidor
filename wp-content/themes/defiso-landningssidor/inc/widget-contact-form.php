<?php

// Block direct requests
if ( !defined('ABSPATH') )
  die('-1');
  
  
add_action( 'widgets_init', function(){
     register_widget( 'ladningpage_contact_widget' );
}); 

/**
 * Adds ladningpage_contact_widget widget.
 */
class ladningpage_contact_widget extends WP_Widget {

  /**
   * Register widget with WordPress.
   */
  function __construct() {
    parent::__construct(
      'ladningpage_contact_widget', // Base ID
      __('Kontaktformulär', 'text_domain'), // Name
      array( 'description' => __( 'Ett Kontaktformulär som kan användas i valfritt widget-fält. Smidigt!', 'text_domain' ), ) // Args
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
    $text = empty($instance['text']) ? '' : $instance['text'];

    /*
     * Show placeholder instead of label. Label is currently commented out.
     */

    echo '<p class="description">' . $text . '</p>';
    echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
    echo '<p>';
    // echo 'Ditt namn <br/>';
    echo '<input type="text" placeholder="Ditt namn" name="cf-name" value="' . ( isset( $_POST["cf-name"] ) ? esc_attr( $_POST["cf-name"] ) : '' ) . '" size="40" data-validation="required" data-validation-error-msg="Fyll i ditt namn" />';
    echo '</p>';
    echo '<p>';
    // echo 'Din e-postadress <br/>';
    echo '<input type="email" placeholder="Din e-postadress" name="cf-email" value="' . ( isset( $_POST["cf-email"] ) ? esc_attr( $_POST["cf-email"] ) : '' ) . '" size="40" data-validation="email" data-validation-error-msg="Fyll i en korrekt epostadress" />';
    echo '</p>';
    echo '<p>';
    // echo 'Telefonnummer<br/>';
    echo '<input type="text" placeholder="Telefonnummer" name="cf-subject" value="' . ( isset( $_POST["cf-subject"] ) ? esc_attr( $_POST["cf-subject"] ) : '' ) . '" size="40" data-validation="alphanumeric" data-validation-error-msg="Fyll i telefonnummer" />';
    echo '</p>';
    echo '<p>';
    // echo 'Meddelande<br/>';
    echo '<textarea rows="10" cols="35" placeholder="Meddelande" data-validation="required" data-validation-error-msg="Fyll i ditt meddelande" name="cf-message">' . ( isset( $_POST["cf-message"] ) ? esc_attr( $_POST["cf-message"] ) : '' ) . '</textarea>';
    echo '</p>';
    echo '<p><input type="submit" class="button" name="cf-submitted" value="Skicka"></p>';
    echo '</form>';
    echo '<div id="message" style="display:none"><strong>Tack för ditt meddelande. Vi kommer att höra av oss till dig inom kort.</strong></div>';
     
    // if the submit button is clicked, send the email
    if ( isset( $_POST['cf-submitted'] ) ) {
 
        // sanitize form values
        $name    = sanitize_text_field( $_POST["cf-name"] );
        $email   = sanitize_email( $_POST["cf-email"] );
        $subject = sanitize_text_field( $_POST["cf-subject"] );
        $message = esc_textarea( $_POST["cf-message"] );
 
        // get the blog administrator's email address
        $to = $landingpage['text-client-email'];
 
        $headers = "From: $name <$email>" . "\r\n";
 
        // If email has been process for sending, display a success message
        if ( wp_mail( $to, $subject, $message, $headers ) ) {
            echo '<div>';
            echo '<p>Thanks for contacting me, expect a response soon.</p>';
            echo '</div>';
            $_POST['cf-submitted'] = array();
        } else {
            echo 'Ett fel inträffade och formuläret kunde inte skickas.';
        }
    }
    
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
    $text = $instance['text'];
    ?>
    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
      <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
    </p>
    <p>
      <label>Text:</label>
      <textarea class="widefat" name="<?php echo $this->get_field_name('text') ?>">
        <?php echo esc_attr($text) ?>
      </textarea>
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
    $instance['text'] = $new_instance['text'];
    return $instance;
  }

} // class ladningpage_contact_widget