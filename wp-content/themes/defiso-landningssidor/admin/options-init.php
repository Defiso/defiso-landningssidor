<?php

/**
  ReduxFramework Sample Config File
  For full documentation, please visit: https://docs.reduxframework.com
 * */

if (!class_exists('defiso_landningssidor_Redux_Framework_config')) {

    class defiso_landningssidor_Redux_Framework_config {

        public $args        = array();
        public $sections    = array();
        public $theme;
        public $ReduxFramework;

        public function __construct() {

            if (!class_exists('ReduxFramework')) {
                return;
            }

            // This is needed. Bah WordPress bugs.  ;)
            if ( true == Redux_Helpers::isTheme( __FILE__ ) ) {
                $this->initSettings();
            } else {
                add_action('plugins_loaded', array($this, 'initSettings'), 10);
            }

        }

        public function initSettings() {

            // Just for demo purposes. Not needed per say.
            $this->theme = wp_get_theme();

            // Set the default arguments
            $this->setArguments();

            // Set a few help tabs so you can see how it's done
            $this->setHelpTabs();

            // Create the sections and fields
            $this->setSections();

            if (!isset($this->args['opt_name'])) { // No errors please
                return;
            }

            // If Redux is running as a plugin, this will remove the demo notice and links
            add_action( 'redux/loaded', array( $this, 'remove_demo' ) );
            
            // Function to test the compiler hook and demo CSS output.
            // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
            //add_filter('redux/options/'.$this->args['opt_name'].'/compiler', array( $this, 'compiler_action' ), 10, 2);
            
            // Change the arguments after they've been declared, but before the panel is created
            //add_filter('redux/options/'.$this->args['opt_name'].'/args', array( $this, 'change_arguments' ) );
            
            // Change the default value of a field after it's been set, but before it's been useds
            //add_filter('redux/options/'.$this->args['opt_name'].'/defaults', array( $this,'change_defaults' ) );
            
            // Dynamically add a section. Can be also used to modify sections/fields
            //add_filter('redux/options/' . $this->args['opt_name'] . '/sections', array($this, 'dynamic_section'));

            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
        }

        /**

          This is a test function that will let you see when the compiler hook occurs.
          It only runs if a field	set with compiler=>true is changed.

         * */
        function compiler_action($options, $css) {
            //echo '<h1>The compiler hook has run!';
            //print_r($options); //Option values
            //print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )

            /*
              // Demo of how to use the dynamic CSS and write your own static CSS file
              $filename = dirname(__FILE__) . '/style' . '.css';
              global $wp_filesystem;
              if( empty( $wp_filesystem ) ) {
                require_once( ABSPATH .'/wp-admin/includes/file.php' );
              WP_Filesystem();
              }

              if( $wp_filesystem ) {
                $wp_filesystem->put_contents(
                    $filename,
                    $css,
                    FS_CHMOD_FILE // predefined mode settings for WP files
                );
              }
             */
        }

        /**

          Custom function for filtering the sections array. Good for child themes to override or add to the sections.
          Simply include this function in the child themes functions.php file.

          NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
          so you must use get_template_directory_uri() if you want to use any of the built in icons

         * */
        function dynamic_section($sections) {
            //$sections = array();
            $sections[] = array(
                'title' => __('Section via hook', 'redux-framework-demo'),
                'desc' => __('<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'redux-framework-demo'),
                'icon' => 'el-icon-paper-clip',
                // Leave this as a blank section, no options just some intro text set above.
                'fields' => array()
            );

            return $sections;
        }

        /**

          Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.

         * */
        function change_arguments($args) {
            //$args['dev_mode'] = true;

            return $args;
        }

        /**

          Filter hook for filtering the default value of any given field. Very useful in development mode.

         * */
        function change_defaults($defaults) {
            $defaults['str_replace'] = 'Testing filter hook!';

            return $defaults;
        }

        // Remove the demo link and the notice of integrated demo from the redux-framework plugin
        function remove_demo() {

            // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
            if (class_exists('ReduxFrameworkPlugin')) {
                remove_filter('plugin_row_meta', array(ReduxFrameworkPlugin::instance(), 'plugin_metalinks'), null, 2);

                // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
                remove_action('admin_notices', array(ReduxFrameworkPlugin::instance(), 'admin_notices'));
            }
        }

        public function setSections() {

            /**
              Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
             * */
            // Background Patterns Reader
            $sample_patterns_path   = ReduxFramework::$_dir . '../sample/patterns/';
            $sample_patterns_url    = ReduxFramework::$_url . '../sample/patterns/';
            $sample_patterns        = array();

            if (is_dir($sample_patterns_path)) :

                if ($sample_patterns_dir = opendir($sample_patterns_path)) :
                    $sample_patterns = array();

                    while (( $sample_patterns_file = readdir($sample_patterns_dir) ) !== false) {

                        if (stristr($sample_patterns_file, '.png') !== false || stristr($sample_patterns_file, '.jpg') !== false) {
                            $name = explode('.', $sample_patterns_file);
                            $name = str_replace('.' . end($name), '', $sample_patterns_file);
                            $sample_patterns[]  = array('alt' => $name, 'img' => $sample_patterns_url . $sample_patterns_file);
                        }
                    }
                endif;
            endif;

            ob_start();

            $ct             = wp_get_theme();
            $this->theme    = $ct;
            $item_name      = $this->theme->get('Name');
            $tags           = $this->theme->Tags;
            $screenshot     = $this->theme->get_screenshot();
            $class          = $screenshot ? 'has-screenshot' : '';

            $customize_title = sprintf(__('Customize &#8220;%s&#8221;', 'redux-framework-demo'), $this->theme->display('Name'));
            
            ?>
            <div id="current-theme" class="<?php echo esc_attr($class); ?>">
            <?php if ($screenshot) : ?>
                <?php if (current_user_can('edit_theme_options')) : ?>
                        <a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize" title="<?php echo esc_attr($customize_title); ?>">
                            <img src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview'); ?>" />
                        </a>
                <?php endif; ?>
                    <img class="hide-if-customize" src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview'); ?>" />
                <?php endif; ?>

                <h4><?php echo $this->theme->display('Name'); ?></h4>

                <div>
                    <ul class="theme-info">
                        <li><?php printf(__('By %s', 'redux-framework-demo'), $this->theme->display('Author')); ?></li>
                        <li><?php printf(__('Version %s', 'redux-framework-demo'), $this->theme->display('Version')); ?></li>
                        <li><?php echo '<strong>' . __('Tags', 'redux-framework-demo') . ':</strong> '; ?><?php printf($this->theme->display('Tags')); ?></li>
                    </ul>
                    <p class="theme-description"><?php echo $this->theme->display('Description'); ?></p>
            <?php
            if ($this->theme->parent()) {
                printf(' <p class="howto">' . __('This <a href="%1$s">child theme</a> requires its parent theme, %2$s.') . '</p>', __('http://codex.wordpress.org/Child_Themes', 'redux-framework-demo'), $this->theme->parent()->display('Name'));
            }
            ?>

                </div>
            </div>

            <?php
            $item_info = ob_get_contents();

            ob_end_clean();

            $sampleHTML = '';
            if (file_exists(dirname(__FILE__) . '/info-html.html')) {
                /** @global WP_Filesystem_Direct $wp_filesystem  */
                global $wp_filesystem;
                if (empty($wp_filesystem)) {
                    require_once(ABSPATH . '/wp-admin/includes/file.php');
                    WP_Filesystem();
                }
                $sampleHTML = $wp_filesystem->get_contents(dirname(__FILE__) . '/info-html.html');
            }

            // ACTUAL DECLARATION OF SECTIONS
            $this->sections[] = array(
                'title'     => __('Grundinställningar', 'redux-framework-demo'),
                'icon'      => 'el-icon-cogs',
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
                    array(
                        'id'        => 'section-media-start',
                        'type'      => 'section',
                        'title'     => __('Branding', 'redux-framework-demo'),
                        'subtitle'  => __('Med följande alternativ kan ni styra brandingen för kunden', 'redux-framework-demo'),
                        'indent'    => true // Indent all options below until the next 'section' option is set.
                    ),
                    array(
                        'id'        => 'media-customer-logo',
                        'type'      => 'media',
                        'title'     => __('Kundens logotyp', 'redux-framework-demo'),
                        'subtitle'  => __('Accepterade filformat är .jpg, .png och .svg', 'redux-framework-demo'),
                    ),
                    array(
                        'id'        => 'color-primary-theme-color',
                        'type'      => 'color',
                        'title'     => __('Primär färg', 'redux-framework-demo'),
                        'subtitle'  => __('Denna färg används bland annat som bakgrund i navigationen (default: #2AA8CC).', 'redux-framework-demo'),
                        'default'   => '#2AA8CC',
                        'validate'  => 'color',
                        'output'    => array('background-color' => '.main-navigation')
                    ),
                    array(
                        'id'        => 'color-navbar-hover',
                        'type'      => 'color',
                        'title'     => __('Färger på menyknappar', 'redux-framework-demo'),
                        'default'   => '#000000',
                        'validate'  => 'color',
                        'output'    => array('background-color' => '.main-navigation a:hover, .current_page_item a')
                    ),
                    array(
                        'id'        => 'color-secondary-theme-color',
                        'type'      => 'color',
                        'title'     => __('Rubrikfärger', 'redux-framework-demo'),
                        'default'   => '#000000',
                        'validate'  => 'color',
                        'output'    => array('color' => 'h1, h1 a, h2')
                    ),
                    array(
                        'id'        => 'color-buttons',
                        'type'      => 'color',
                        'title'     => __('Färg på knappar', 'redux-framework-demo'),
                        'default'   => '#62B06E',
                        'validate'  => 'color',
                        'output'    => array('background-color' => 'button, .button')
                    ),
                    array(
                        'id'        => 'section-media-end',
                        'type'      => 'section',
                        'indent'    => false // Indent all options below until the next 'section' option is set.
                    ),
                    array(
                        'id'        => 'switch-custom-css',
                        'type'      => 'switch',
                        'title'     => __('Vill du koda egen css?', 'redux-framework-demo'),
                        'subtitle'  => __('Om du aktiverar denna funktion kommer du kunna infoga egen css-kod i head', 'redux-framework-demo'),
                        'default'   => 0,
                        'on'        => 'Aktivera',
                        'off'       => 'Avaktivera',
                    ),
                    array(
                        'id'        => 'ace-editor-custom-css',
                        'required'  => array('switch-custom-css', '=', '1'),
                        'type'      => 'ace_editor',
                        'title'     => __('CSS Code', 'redux-framework-demo'),
                        'subtitle'  => __('Paste your CSS code here.', 'redux-framework-demo'),
                        'mode'      => 'css',
                        'theme'     => 'monokai',
                        'desc'      => 'Possible modes can be found at <a href="http://ace.c9.io" target="_blank">http://ace.c9.io/</a>.',
                        'default'   => "#header{\nmargin: 0 auto;\n}"
                    ),
                    array(
                        'id'        => 'switch-custom-js',
                        'type'      => 'switch',
                        'title'     => __('Vill du koda egen js?', 'redux-framework-demo'),
                        'subtitle'  => __('Om du aktiverar denna funktion kommer du kunna infoga egen js-kod inan body stängs', 'redux-framework-demo'),
                        'default'   => 0,
                        'on'        => 'Aktivera',
                        'off'       => 'Avaktivera',
                    ),
                    array(
                        'id'        => 'ace-editor-custom-js',
                        'required'  => array('switch-custom-js', '=', '1'),
                        'type'      => 'ace_editor',
                        'title'     => __('js Code', 'redux-framework-demo'),
                        'subtitle'  => __('Paste your js code here.', 'redux-framework-demo'),
                        'mode'      => 'javascript',
                        'theme'     => 'monokai',
                        'desc'      => 'Koden infogas precis innan body stängs.',
                        'default'   => "jQuery(document).ready(function(){\n\n});"
                    ),
                    array(
                        'id'       => 'text-client-adress',
                        'type'     => 'text',
                        'title'    => __('Adress till kunden', 'redux-framework-demo'),
                    ),
                    array(
                        'id'       => 'text-client-city',
                        'type'     => 'text',
                        'title'    => __('Kundens postort', 'redux-framework-demo'),
                    ),
                    array(
                        'id'       => 'text-client-zip',
                        'type'     => 'text',
                        'title'    => __('Kundens postnummer', 'redux-framework-demo'),
                    ),
                    array(
                        'id'       => 'text-UA',
                        'type'     => 'text',
                        'title'    => __('Google Analytics', 'redux-framework-demo'),
                        'subtitle'  => __('Klistra in ditt ID, UA-XXXXXXX', 'redux-framework-demo')
                    ),
                    array(
                        'id'       => 'text-freespee',
                        'type'     => 'text',
                        'title'    => __('FreeSpee ID', 'redux-framework-demo'),
                        'subtitle'  => __('Klistra in ditt ID, xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxxx', 'redux-framework-demo')
                    )
                ),
            );

            $this->sections[] = array(
                'type' => 'divide',
            );

            $this->sections[] = array(
                'icon'      => 'el-icon-home',
                'title'     => __('Startsida', 'redux-framework-demo'),
                'fields'    => array(
                    array(
                        'id'        => 'slider-switch',
                        'type'      => 'switch',
                        'title'     => __('Vill du ha en bildslider?', 'redux-framework-demo'),
                        'subtitle'  => __('Om du inte aktiverar slidern får du istället en fast bild', 'redux-framework-demo'),
                        'default'   => 0,
                        'on'        => 'Aktivera',
                        'off'       => 'Avaktivera',
                    ),
                    array(
                        'id'        => 'slides-client-slider',
                        'type'      => 'slides',
                        'required'  => array('slider-switch', '=', '1'),
                        'title'     => __('Bildspel', 'redux-framework-demo'),
                        'subtitle'  => __('Mata in frivilligt antal bilder', 'redux-framework-demo'),
                        'default'   => false,
                        'placeholder'   => array(
                            'title'         => __('Rubrik', 'redux-framework-demo'),
                            'description'   => __('Beskrivning', 'redux-framework-demo'),
                            'url'           => __('Länk', 'redux-framework-demo'),
                        ),
                    ),
                    array(
                        'id'        => 'switch-autoslide',
                        'type'      => 'switch',
                        'required'  => array('slider-switch', '=', '1'),
                        'title'     => __('Vill du att bilderna ska byta automatiskt?', 'redux-framework-demo'),
                        'default'   => 0,
                        'on'        => 'Aktivera',
                        'off'       => 'Avaktivera',
                    ),
                    array(
                        'id'        => 'slider-image-slider',
                        'type'      => 'slider',
                        'required'  => array('switch-autoslide', '=', '1'),
                        'title'     => __('Tidsintervall för bildspel', 'redux-framework-demo'),
                        'subtitle'  => __('Ange tidsintervall för bilder i millisekunder.', 'redux-framework-demo'),
                        'desc'      => __('1000ms = 1s', 'redux-framework-demo'),
                        "default"   => 5000,
                        "min"       => 1000,
                        "step"      => 100,
                        "max"       => 10000,
                        'display_value' => 'label'
                    ),
                    array(
                        'id'        => 'media-client-jumbotron',
                        'required'  => array('slider-switch', '=', '0'),
                        'type'      => 'media',
                        'title'     => __('Topbanner', 'redux-framework-demo'),
                        'subtitle'  => __('Accepterade filformat är .jpg, .png och .svg', 'redux-framework-demo'),
                    ),
                    array(
                        'id'       => 'text-client-jumbotron',
                        'required'  => array('slider-switch', '=', '0'),
                        'type'     => 'text',
                        'title'    => __('Text på Topbanner', 'redux-framework-demo'),
                    ),
                    array(
                        'id'        => 'multi-text-checkmarks',
                        'type'      => 'multi_text',
                        'title'     => __('Bockar på startsida', 'redux-framework-demo'),
                        'subtitle'  => __('Nämn några starka fördelar med kundens företag', 'redux-framework-demo'),
                    ),
                    array(
                        'id'        => 'switch-front-boxes',
                        'type'      => 'switch',
                        'title'     => __('Vill du ha tre boxar på startsidan?', 'redux-framework-demo'),
                        'default'   => 0,
                        'on'        => 'Aktivera',
                        'off'       => 'Avaktivera',
                    ),
                    array(
                        'id'        => 'switch-front-boxes-links',
                        'required'  => array('switch-front-boxes', '=', '1'),
                        'type'      => 'switch',
                        'title'     => __('Ska boxarna ha klickbara knappar under sig?', 'redux-framework-demo'),
                        'default'   => 1,
                        'on'        => 'Aktivera',
                        'off'       => 'Avaktivera',
                    ),
                    array(
                        'id'        => 'media-front-box-1',
                        'required'  => array('switch-front-boxes', '=', '1'),
                        'type'      => 'media',
                        'title'     => __('Bild till box 1', 'redux-framework-demo'),
                        'subtitle'  => __('Accepterade filformat är .jpg, .png och .svg', 'redux-framework-demo'),
                    ),
                    array(
                        'id'        => 'textarea-front-box-1',
                        'type'      => 'textarea',
                        'required'  => array('switch-front-boxes', '=', '1'),
                        'title'     => __('Text till box 1', 'redux-framework-demo'),
                    ),
                    array(
                        'id'       => 'text-front-box-1-link',
                        'required'  => array('switch-front-boxes-links', '=', '1'),
                        'type'     => 'text',
                        'title'    => __('Måladress för knappen', 'redux-framework-demo'),
                    ),
                    array(
                        'id'        => 'media-front-box-2',
                        'required'  => array('switch-front-boxes', '=', '1'),
                        'type'      => 'media',
                        'title'     => __('Bild till box 2', 'redux-framework-demo'),
                        'subtitle'  => __('Accepterade filformat är .jpg, .png och .svg', 'redux-framework-demo'),
                    ),
                    array(
                        'id'        => 'textarea-front-box-2',
                        'type'      => 'textarea',
                        'required'  => array('switch-front-boxes', '=', '1'),
                        'title'     => __('Text till box 2', 'redux-framework-demo'),
                    ),
                    array(
                        'id'       => 'text-front-box-2-link',
                        'required'  => array('switch-front-boxes-links', '=', '1'),
                        'type'     => 'text',
                        'title'    => __('Måladress för knappen', 'redux-framework-demo'),
                    ),
                    array(
                        'id'        => 'media-front-box-3',
                        'required'  => array('switch-front-boxes', '=', '1'),
                        'type'      => 'media',
                        'title'     => __('Bild till box 3', 'redux-framework-demo'),
                        'subtitle'  => __('Accepterade filformat är .jpg, .png och .svg', 'redux-framework-demo'),
                    ),
                    array(
                        'id'        => 'textarea-front-box-3',
                        'type'      => 'textarea',
                        'required'  => array('switch-front-boxes', '=', '1'),
                        'title'     => __('Text till box 3', 'redux-framework-demo'),
                    ),
                    array(
                        'id'       => 'text-front-box-3-link',
                        'required'  => array('switch-front-boxes-links', '=', '1'),
                        'type'     => 'text',
                        'title'    => __('Måladress för knappen', 'redux-framework-demo'),
                    )
                )
            );

            $theme_info  = '<div class="redux-framework-section-desc">';
            $theme_info .= '<p class="redux-framework-theme-data description theme-uri">' . __('<strong>Theme URL:</strong> ', 'redux-framework-demo') . '<a href="' . $this->theme->get('ThemeURI') . '" target="_blank">' . $this->theme->get('ThemeURI') . '</a></p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-author">' . __('<strong>Author:</strong> ', 'redux-framework-demo') . $this->theme->get('Author') . '</p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-version">' . __('<strong>Version:</strong> ', 'redux-framework-demo') . $this->theme->get('Version') . '</p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-description">' . $this->theme->get('Description') . '</p>';
            $tabs = $this->theme->get('Tags');
            if (!empty($tabs)) {
                $theme_info .= '<p class="redux-framework-theme-data description theme-tags">' . __('<strong>Tags:</strong> ', 'redux-framework-demo') . implode(', ', $tabs) . '</p>';
            }
            $theme_info .= '</div>';

            $this->sections[] = array(
                'title'     => __('Import / Export', 'redux-framework-demo'),
                'desc'      => __('Med hjälp av denna funktion kan du importera eller exportera inställningar mellan olika kunders landningssidor.', 'redux-framework-demo'),
                'icon'      => 'el-icon-refresh',
                'fields'    => array(
                    array(
                        'id'            => 'opt-import-export',
                        'type'          => 'import_export',
                        'title'         => 'Import Export',
                        'subtitle'      => 'Save and restore your Redux options',
                        'full_width'    => false,
                    ),
                ),
            );                     
                    
            $this->sections[] = array(
                'type' => 'divide',
            );

            if (file_exists(trailingslashit(dirname(__FILE__)) . 'README.html')) {
                $tabs['docs'] = array(
                    'icon'      => 'el-icon-book',
                    'title'     => __('Documentation', 'redux-framework-demo'),
                    'content'   => nl2br(file_get_contents(trailingslashit(dirname(__FILE__)) . 'README.html'))
                );
            }
        }

        public function setHelpTabs() {

            // Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
            $this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-1',
                'title'     => __('Theme Information 1', 'redux-framework-demo'),
                'content'   => __('<p>This is the tab content, HTML is allowed.</p>', 'redux-framework-demo')
            );

            $this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-2',
                'title'     => __('Theme Information 2', 'redux-framework-demo'),
                'content'   => __('<p>This is the tab content, HTML is allowed.</p>', 'redux-framework-demo')
            );

            // Set the help sidebar
            $this->args['help_sidebar'] = __('<p>This is the sidebar content, HTML is allowed.</p>', 'redux-framework-demo');
        }

        /**

          All the possible arguments for Redux.
          For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments

         * */
        public function setArguments() {

            $theme = wp_get_theme(); // For use with some settings. Not necessary.

            $this->args = array(
                'opt_name' => 'landingpage',
                'page_slug' => 'landningssidor_options',
                'page_title' => 'Inställningar',
                'update_notice' => true,
                'admin_bar' => true,
                'menu_type' => 'submenu',
                'menu_title' => 'Temainställningar',
                'allow_sub_menu' => false,
                'page_parent' => 'options-general.php',
                'customizer' => true,
                'default_mark' => '*',
                'hints' => 
                array(
                  'icon' => 'el-icon-question-sign',
                  'icon_position' => 'right',
                  'icon_size' => 'normal',
                  'tip_style' => 
                  array(
                    'color' => 'light',
                  ),
                  'tip_position' => 
                  array(
                    'my' => 'top left',
                    'at' => 'bottom right',
                  ),
                  'tip_effect' => 
                  array(
                    'show' => 
                    array(
                      'duration' => '500',
                      'event' => 'mouseover',
                    ),
                    'hide' => 
                    array(
                      'duration' => '500',
                      'event' => 'mouseleave unfocus',
                    ),
                  ),
                ),
                'output' => true,
                'output_tag' => true,
                'compiler' => true,
                'page_icon' => 'icon-themes',
                'page_permissions' => 'manage_options',
                'save_defaults' => true,
                'show_import_export' => true,
                'transient_time' => '3600',
                'network_sites' => true,
                'dev_mode' => false
              );

            $theme = wp_get_theme(); // For use with some settings. Not necessary.
            $this->args["display_name"] = $theme->get("Name");
            $this->args["display_version"] = $theme->get("Version");

        }

    }
    
    global $reduxConfig;
    $reduxConfig = new defiso_landningssidor_Redux_Framework_config();
}

/**
  Custom function for the callback referenced above
 */
if (!function_exists('defiso_landningssidor_my_custom_field')):
    function defiso_landningssidor_my_custom_field($field, $value) {
        print_r($field);
        echo '<br/>';
        print_r($value);
    }
endif;

/**
  Custom function for the callback validation referenced above
 * */
if (!function_exists('defiso_landningssidor_validate_callback_function')):
    function defiso_landningssidor_validate_callback_function($field, $value, $existing_value) {
        $error = false;
        $value = 'just testing';

        /*
          do your validation

          if(something) {
            $value = $value;
          } elseif(something else) {
            $error = true;
            $value = $existing_value;
            $field['msg'] = 'your custom error message';
          }
         */

        $return['value'] = $value;
        if ($error == true) {
            $return['error'] = $field;
        }
        return $return;
    }
endif;
