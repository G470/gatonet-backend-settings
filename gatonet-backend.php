<?php
/* Plugin name: Gatonet Backend
   Plugin URI: http://gatonet.de/wp/gatonet-backend
   Author: Steffen Görg
   Author URI: http://gatonet.de
   Version: 1.0
   Description: Custom backend functionalities
   Max WP Version: 3.9
   Text Domain: gatonet-backend

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class GatonetCustomizer {


    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            'Gatonet CMS', 
            'manage_options', 
            'gatonet-setting-admin', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'my_option_name' );
        ?>
        <div class="wrap">
            <h2>My Settings</h2>           
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'my_option_group' );   
                do_settings_sections( 'my-setting-admin' );
                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'my_option_group', // Option group
            'my_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'My Custom Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'my-setting-admin' // Page
        );  

        add_settings_field(
            'id_number', // ID
            'ID Number', // Title 
            array( $this, 'id_number_callback' ), // Callback
            'my-setting-admin', // Page
            'setting_section_id' // Section           
        );      

        add_settings_field(
            'title', 
            'Title', 
            array( $this, 'title_callback' ), 
            'my-setting-admin', 
            'setting_section_id'
        );      
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['id_number'] ) )
            $new_input['id_number'] = absint( $input['id_number'] );

        if( isset( $input['title'] ) )
            $new_input['title'] = sanitize_text_field( $input['title'] );

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter your settings below:';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function id_number_callback()
    {
        printf(
            '<input type="text" id="id_number" name="my_option_name[id_number]" value="%s" />',
            isset( $this->options['id_number'] ) ? esc_attr( $this->options['id_number']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function title_callback()
    {
        printf(
            '<input type="text" id="title" name="my_option_name[title]" value="%s" />',
            isset( $this->options['title'] ) ? esc_attr( $this->options['title']) : ''
        );
    }





    /**
     * START THE MAGIC FROM HERE...
     * ----------------------------
     */
    
	/**
	 * Include CSS file for MyPlugin.
	 
	 */
	public function gn_scripts_and_styles(){
	function myplugin_scripts() {
	    wp_register_style( 'foo-styles',  plugin_dir_url( __FILE__ ) . 'assets/foo-styles.css' );
	    wp_enqueue_style( 'foo-styles' );
	}
	add_action( 'wp_enqueue_scripts', 'myplugin_scripts' );
	}


    /**
     *  REMOVE VERSION NUMBERS
     *  Remove ?ver=x.x from css and js good idea in general...
     
     */
    public function gn_remove_versions(){
	function remove_cssjs_ver( $src ) {
	 if( strpos( $src, '?ver=' ) )
	 $src = remove_query_arg( 'ver', $src );
	 return $src;
	}
	add_filter( 'style_loader_src', 'remove_cssjs_ver', 10, 2 );
	add_filter( 'script_loader_src', 'remove_cssjs_ver', 10, 2 );

	}




} // Class end

if( is_admin() ){
    $gatonet_settings_page = new GatonetCustomizer();
}



    /**
     * Custom login screen 
  
     */
		function gn_custom_login_logo() { 
		    wp_enqueue_style( 'custom-login', plugin_dir_url( __FILE__ ) . 'assets/css/admin-login.css' );
		 }


		add_action( 'login_enqueue_scripts', 'gn_custom_login_logo' );

		// link
		function gn_custom_login_logo_url() {
		    return get_bloginfo( 'url' );
		}

		add_filter( 'login_headerurl', 'gn_custom_login_logo_url' );

		// title
		function gn_custom_login_logo_url_title() {
		    return get_bloginfo( 'blogname' )."BOOOOM";
		}

		add_filter( 'login_headertitle', 'gn_custom_login_logo_url_title' );


/**
 * Remove all version numbers
 
 */
function gn_remove_version() {
return '';
}
add_filter('the_generator', 'gn_remove_version');





/**
 * Include Admin CSS
 
 */
function gn_custom_css() {
	wp_enqueue_style( 'wpb-google-fonts', 'https://fonts.googleapis.com/css?family=Roboto:400,300,500', false ); 
	wp_enqueue_style( 'custom-login', plugin_dir_url( __FILE__ ) . 'assets/css/admin-styles.css' );
}

add_action('admin_head', 'gn_custom_css');




?>