<?php
  add_action('admin_menu', 'custom_description');
  add_action('save_post', 'save_custom_description');
  add_action('wp_head','insert_custom_description');

  function custom_description() {
    add_meta_box('custom_description', 'Meta-beskrivning', 'custom_description_input_function', 'post', 'normal', 'high');
    add_meta_box('custom_description', 'Meta-beskrivning', 'custom_description_input_function', 'page', 'normal', 'high');
  }
  function custom_description_input_function() {
    global $post;
    echo '<input type="hidden" name="custom_description_input_hidden" id="custom_description_input_hidden" value="'.wp_create_nonce('custom-description-nonce').'" />';
    echo '<input type="text" name="custom_description_input" id="custom_description_input" style="width:100%;" value="'.get_post_meta($post->ID,'_custom_description',true).'" />';
  }
  function save_custom_description($post_id) {
    if (!wp_verify_nonce($_POST['custom_description_input_hidden'], 'custom-description-nonce')) return $post_id;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
    $customDescription = $_POST['custom_description_input'];
    update_post_meta($post_id, '_custom_description', $customDescription);
  }
  function insert_custom_description() {
    if (have_posts()) : the_post();
      $customDescription = get_post_meta(get_the_ID(), '_custom_description', true);
      if ($customDescription) {
        echo '<meta name="description" content="'. $customDescription .'">';

      }
    endif;
    rewind_posts();
  }
?>