<?php

function html_form_code() {
    echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
    echo '<p>';
    echo 'Ditt namn <br/>';
    echo '<input type="text" name="cf-name" pattern="[a-zA-Z0-9 ]+" value="' . ( isset( $_POST["cf-name"] ) ? esc_attr( $_POST["cf-name"] ) : '' ) . '" size="40" />';
    echo '</p>';
    echo '<p>';
    echo 'Din e-postadress <br/>';
    echo '<input type="email" name="cf-email" value="' . ( isset( $_POST["cf-email"] ) ? esc_attr( $_POST["cf-email"] ) : '' ) . '" size="40" />';
    echo '</p>';
    echo '<p>';
    echo 'Telefonnummer <br/>';
    echo '<input type="text" name="cf-number" value="' . ( isset( $_POST["cf-subject"] ) ? esc_attr( $_POST["cf-subject"] ) : '' ) . '" size="40" />';
    echo '</p>';
    echo '<p>';
    echo 'Meddelande <br/>';
    echo '<textarea rows="10" cols="35" name="cf-message">' . ( isset( $_POST["cf-message"] ) ? esc_attr( $_POST["cf-message"] ) : '' ) . '</textarea>';
    echo '</p>';
    echo '<p><input type="submit" name="cf-submitted" value="Skicka"></p>';
    echo '</form>';
}
 
function deliver_mail() {
 
    // if the submit button is clicked, send the email
    if ( isset( $_POST['cf-submitted'] ) ) {
 
        // sanitize form values
        $name    = sanitize_text_field( $_POST["cf-name"] );
        $email   = sanitize_email( $_POST["cf-email"] );
        $subject = "Meddelande från Landningssidan";
        $message = 'Kundens telefonnummer: ' . sanitize_text_field( $_POST["cf-number"] ) . "\r\n\r\n" . 'Meddelande från kunden:' . "\r\n" . esc_textarea( $_POST["cf-message"] );
 
        // get the blog administrator's email address
        $to = get_option( 'admin_email' );
 
        $headers = "From: $name <$email>" . "\r\n";
 
        // If email has been process for sending, display a success message
        if ( wp_mail( $to, $subject, $message, $headers ) ) {
            echo '<div>';
            echo '<p>Thanks for contacting me, expect a response soon.</p>';
            echo '</div>';
        } else {
            echo 'An unexpected error occurred';
        }
    }
}
 
function cf_shortcode() {
    ob_start();
    deliver_mail();
    html_form_code();
 
    return ob_get_clean();
}
 
add_shortcode( 'kontaktformulär', 'cf_shortcode' );
 
?>