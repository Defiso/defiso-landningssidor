<?php
  add_action('admin_menu', 'custom_title');
  add_action('save_post', 'save_custom_title');
  add_action('wp_head','insert_custom_title');

  function custom_title() {
    add_meta_box('custom_title', 'Title-tagg', 'custom_title_input_function', 'post', 'normal', 'high');
    add_meta_box('custom_title', 'Title-tagg', 'custom_title_input_function', 'page', 'normal', 'high');
  }
  function custom_title_input_function() {
    global $post;
    echo '<input type="hidden" name="custom_title_input_hidden" id="custom_title_input_hidden" value="'.wp_create_nonce('custom-title-nonce').'" />';
    echo '<input type="text" name="custom_title_input" id="custom_title_input" style="width:100%;" value="'.get_post_meta($post->ID,'_custom_title',true).'" />';
  }
  function save_custom_title($post_id) {
    if (!wp_verify_nonce($_POST['custom_title_input_hidden'], 'custom-title-nonce')) return $post_id;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
    $customTitle = $_POST['custom_title_input'];
    update_post_meta($post_id, '_custom_title', $customTitle);
  }
  function insert_custom_title() {
    if (have_posts()) : the_post();
      $customTitle = get_post_meta(get_the_ID(), '_custom_title', true);
      if ($customTitle) {
      echo "<title>$customTitle</title>";
        } else {
        echo "<title>";
          bloginfo('name'); echo ' - '; bloginfo('description');
        echo "</title>";
      }
    endif;
    rewind_posts();
  }
?>