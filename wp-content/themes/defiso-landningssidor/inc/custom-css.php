<?php if ($landingpage['switch-custom-css']) {
    add_action('wp_head','insert_custom_css');
    function insert_custom_css() {
      global $landingpage;
      echo '<style> '. $landingpage['ace-editor-custom-css'] .' </style>';
    }
  }
?>