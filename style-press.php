<?php
/*
Plugin Name: Style Press
Plugin URI: http://stylepress.grandslambert.com/
Description: Style Press gives you control to add custom style sheet definitions site-wide, on post types, and on individual posts. 
Author: GrandSlambert
Version: 0.1
Author: GrandSlambert
Author URI: http://wordpress.grandslambert.com/

**************************************************************************

Copyright (C) 2009-2010 GrandSlambert

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General License for more details.

You should have received a copy of the GNU General License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

**************************************************************************

*/

class stylePress {

     /* Plugin settings */
    var $menuName = 'style-press';
    var $pluginName = 'Style Press';
    var $version = '0.1';
    var $optionsName	= 'style-press-options';

    function stylePress() {
        /* Load Langague Files */
        $langDir = dirname( plugin_basename(__FILE__) ) . '/lang';
        load_plugin_textdomain( 'style-press', false, $langDir, $langDir );

        $this->pluginName = __('Style Press', 'style-press');
        $this->pluginPath = WP_CONTENT_DIR . '/plugins/' . plugin_basename(dirname(__FILE__));
        $this->pluginURL = get_option('siteurl') . '/wp-content/plugins/' . plugin_basename(dirname(__FILE__));
        $this->loadSettings();

        /* Wordpress Hooks */
        add_action('admin_menu', array(&$this, 'addAdminPages'));
        add_action('wp_head', array($this, 'add_header'), 1000);
        add_action('admin_head', array($this, 'admin_header') );
        add_filter('plugin_action_links', array(&$this, 'addConfigureLink'), 10, 2);
        add_action('admin_init', array(&$this, 'registerOptions'));
        add_action('admin_menu', array(&$this, 'add_meta_boxes'));
        add_action('save_post', array(&$this, 'save_post'));
    }

    /**
     *  Load the plugin settings
     */
    function loadSettings() {
        if (!$this->options = get_option($this->optionsName)) {
            $this->options['sitewide']      = '';
            $this->options['post_types']    = $this->get_post_types();
        }

        /* Required settings */
        if (!$this->options['post_types']) {
            $this->options['post_types']    = array();
        }
    }

    /**
     * Get all post types.
     */
    function get_post_types() {
        if (!is_array($this->types)) {
            if (function_exists(get_post_types)) {
                $this->types = get_post_types(array('public'=>true));
            } else {
                $this->types = array('post','page');
            }
        }

        return $this->types;
    }

    /**
     * Add items to the header of the web site.
     */
    function add_header() {
        global $wp_query, $post;

        echo "<!-- Styles added by Style Press. -->\n";
        echo '<style type="text/css" media="screen">' ."\n";

        if ($this->options['sitewide']) {
            echo $this->options['sitewide'];
        }

        if (is_single() and $this->options['post'] and $post->post_type == 'post') {
            echo $this->options['post'];
        }

        if (is_page() and $this->options['page']) {
            echo $this->options['page'];
        }

        foreach ($this->get_post_types() as $type) {
            if ($post->post_type == $type and $this->options[$type]) {
                echo $this->options[$type];
            }
        }

        echo get_post_meta($post->ID, '_style_press_css', true);

        echo  "\n</style>\n";
        echo "<!-- End Style Press styles. -->\n";
    }

    /**
     * Add Meta Boxes
     */
    function add_meta_boxes() {
        global $post;

        if( function_exists( 'add_meta_box' )) {

            foreach ($this->options['post_types'] as $type) {
                add_meta_box( 'style_press_css', __( 'Style Press CSS', 'style-press', 'high' ),
                    array(&$this, 'style_press_box'), $type, 'advanced' );
            }
        } else {
            foreach ($this->options['post_types'] as $type) {
                add_action('dbx_'. $type . '_advanced', 'myplugin_old_custom_box' );
            }
        }
    }

    /**
     * Save the custom CSS for a post.
     *
     * @global <object> $post
     * @param <integer> $post_id
     * @return <integer>
     */
    function save_post($post_id) {
        if ( wp_verify_nonce( $_POST['style_press_noncename'], 'style_press_css' )) {
            $key = '_style_press_css';
            $value = $_POST['style_press_css'];

            if(get_post_meta($post_id, $key) == "") {
                add_post_meta($post_id, $key, $value, true);
            } elseif($value != get_post_meta($post_id, $key.'_value', true)) {
                update_post_meta($post_id, $key, $value);
            } elseif($value == "") {
                delete_post_meta($post_id, $key, get_post_meta($post_id, $key, true));
            }
        }
        return $post_id;
    }

    /**
     * Style Press CSS inner contents
     */
    function style_press_box() {
        global $post;

        echo '<input type="hidden" name="style_press_noncename" id="style_press_noncename" value="' . wp_create_nonce( 'style_press_css' ) . '" />';
        echo '<textarea class="style-press-textarea" rows="10" cols="50" name="style_press_css" id="style_press_css">' . get_post_meta($post->ID, '_style_press_css', true) . '</textarea>';
    }

    /**
     * Add necessary code to the head section for the administration pages.
     */
    function admin_header() {
        global $post;
        if (preg_match('/style-press/', $_SERVER['REQUEST_URI'])) {
            print "<link rel='stylesheet' href='" . $this->pluginURL . "/style-press.css' type='text/css' media='all' />";

            foreach ($this->get_post_types() as $type) {
                $post_types_js.= "document.getElementById('style_press_box_" . $type . "').style.display = 'none';\n";
            }

            ?>
<script type="text/javascript"><!--//--><![CDATA[//><!--
    function tftShowTab(tab) {
        document.getElementById('style_press_box_sitewide').style.display = 'none';
            <?php echo $post_types_js; ?>
                    document.getElementById('style_press_box_' + tab).style.display = 'block';
                    document.getElementById('style_press_box_' + tab).focus();
                }
                //--><!]]>
</script>
        <?php
        }

    }

    /**
     * Add settings vars to the whitelist for forms.
     *
     * @param array $whitelist
     * @return array
     */
    function whitelistOptions($whitelist) {
        if (is_array($whitelist)) {
            $option_array = array($this->pluginName => $this->optionsName);
            $whitelist = array_merge($whitelist, $option_array);
        }

        return $whitelist;
    }

    /**
     * Add the admin page for the settings panel.
     *
     * @global string $wp_version
     */
    function addAdminPages() {
        global $wp_version;

        add_options_page($this->pluginName, $this->pluginName, 'manage-options', $this->menuName, array(&$this, 'optionsPanel'));
        add_submenu_page( 'themes.php', $this->pluginName, $this->pluginName, 'edit_themes', $this->menuName, array(&$this, 'optionsPanel'));

        // Use the bundled jquery library if we are running WP 2.5 or above
        if (version_compare($wp_version, '2.5', '>=')) {
            wp_enqueue_script('jquery', false, false, '1.2.3');
        }
    }

    /**
     * Add a configuration link to the plugins list.
     *
     * @staticvar object $this_plugin
     * @param array $links
     * @param array $file
     * @return array
     */
    function addConfigureLink($links, $file) {
        static $this_plugin;

        if (!$this_plugin) {
            $this_plugin = plugin_basename(__FILE__);
        }

        if ($file == $this_plugin) {
            $settings_link = '<a href="' . get_option('siteurl') . '/wp-admin/options-general.php?page=' . $this->menuName . '">' . __('Settings') . '</a>';
            array_unshift($links, $settings_link);
        }

        return $links;
    }

    /**
     * Settings management panel.
     */
    function optionsPanel() {
        include($this->pluginPath . '/options-panel.php');
    }

    /**
     * Display the current version number
     * @return string
     */
    function showVersion() {
        return $this->version;
    }

    /**
     * Register the options for Wordpress MU Support
     */
    function registerOptions() {
        register_setting( $this->optionsName, $this->optionsName);
    }

    /**
     * Display the list of contributors.
     * @return boolean
     */
    function contributorList() {
        $this->showFields = array('NAME', 'LOCATION' , 'COUNTRY');
        print '<ul>';

        $xml_parser = xml_parser_create();
        xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, true);
        xml_set_element_handler($xml_parser, array($this,"startElement"), array($this, "endElement") );
        xml_set_character_data_handler($xml_parser, array($this, "characterData") );

        if (!(@$fp = fopen('http://grandslambert.com/xml/style-press/contributors.xml', "r"))) {
            print 'There was an error getting the list. Try again later.';
            return;
        }

        while ($data = fread($fp, 4096)) {
            if (!xml_parse($xml_parser, $data, feof($fp))) {
                die(sprintf("XML error: %s at line %d",
                    xml_error_string(xml_get_error_code($xml_parser)),
                    xml_get_current_line_number($xml_parser)));
            }
        }

        xml_parser_free($xml_parser);
        print '</ul>';
    }

    /**
     * XML Start Element Procedure.
     */
    function startElement($parser, $name, $attrs) {
        if ($name == 'NAME') {
            print '<li class="rp-contributor">';
        }
        elseif ($name == 'ITEM') {
            print '<br><span class="rp_contributor_notes">Contributed: ';
        }

        if ($name == 'URL') {
            $this->makeLink = true;
        }
    }

    /**
     * XML End Element Procedure.
     */
    function endElement($parser, $name) {
        if ($name == 'ITEM') {
            print '</li>';
        }
        elseif ($name == 'ITEM') {
            print '</span>';
        }
        elseif ( in_array($name, $this->showFields)) {
            print ', ';
        }
    }

    /**
     * XML Character Data Procedure.
     */
    function characterData($parser, $data) {
        if ($this->makeLink) {
            print '<a href="http://' . $data . '" target="_blank">' . $data . '</a>';
            $this->makeLink = false;
        } else {
            print $data;
        }
    }
}

/* Instantiate the Widget */
$STYLEPRESS = new stylePress;
