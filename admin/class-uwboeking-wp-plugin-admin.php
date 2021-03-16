<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       brandom.nl
 * @since      1.0.0
 *
 * @package    Uwboeking_Wp_Plugin
 * @subpackage Uwboeking_Wp_Plugin/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Uwboeking_Wp_Plugin
 * @subpackage Uwboeking_Wp_Plugin/admin
 * @author     Geert van Dijk <geert@brandom.nl>
 */
class Uwboeking_Wp_Plugin_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	private $page_sections;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_action('admin_init', array($this, 'register_settings')); // fixme
		add_action('admin_menu', array($this,'options_page'));
		add_action( 'whitelist_options', array( $this, 'whitelist_custom_options_page' ),11 );
	}

	public function whitelist_custom_options_page( $whitelist_options ){
    // Custom options are mapped by section id; Re-map by page slug.
    foreach($this->page_sections as $page => $sections ){
		$whitelist_options[$page] = array();
		foreach( $sections as $section )
			if( !empty( $whitelist_options[$section] ) )
				foreach( $whitelist_options[$section] as $option )
					$whitelist_options[$page][] = $option;
			}
		return $whitelist_options;
	}

	// Wrapper for wp's `add_settings_section()` that tracks custom sections
	private function add_settings_section( $id, $title, $cb, $page ){
		add_settings_section( $id, $title, $cb, $page );
		if( $id != $page ){
			if( !isset($this->page_sections[$page]))
				$this->page_sections[$page] = array();
			$this->page_sections[$page][$id] = $id;
		}
	}

	
	function options_page() {
		add_options_page('UwBoeking-instellingen', 'UwBoeking-instellingen', 'manage_options', 'uwboeking_wp_plugin', array(&$this, 'render_options_page'));
	}

	function render_options_page() {
		?>
		    <h2>UwBoeking-instellingen</h2>
		    <form action="options.php" method="post">
        <?php 
        settings_fields( 'brn_uwboeking_options' );
        do_settings_sections( 'uwboeking_wp_plugin' ); ?>
        	<input name="submit" class="button button-primary" type="submit" value="Opslaan" />
    	</form>
		<?
	}

	public function register_settings() {
		register_setting('brn_uwboeking_options', 'brn_uwboeking_store', array(&$this,'brn_uwboeking_options_validate'));
		$this->add_settings_section('api_key', 'API Instellingen', array(&$this,'brn_uwboeking_api'), 'uwboeking_wp_plugin');
		add_settings_field( 'brn_uwboeking_api_key', 'API Key', array(&$this,'brn_uwboeking_api_key'), 'uwboeking_wp_plugin', 'api_key' );
		add_settings_field( 'brn_uwboeking_language', 'Taal', array(&$this,'brn_uwboeking_language'), 'uwboeking_wp_plugin', 'api_key' );
		add_settings_field( 'brn_uwboeking_archpage', 'Pagina waar de archief-shortcode geplaatst is', array(&$this,'brn_uwboeking_archpage'), 'uwboeking_wp_plugin', 'api_key' );
		add_settings_section('looks', 'Uiterlijk', 'brn_uwboeking_api', 'uwboeking_wp_plugin');
		add_settings_field( 'brn_uwboeking_disable_css', 'Standaardstijl uitschakelen', array(&$this,'brn_uwboeking_disable_css'), 'uwboeking_wp_plugin', 'looks' );
		add_settings_field( 'brn_uwboeking_disable_slider', 'Slider uitschakelen', array(&$this,'brn_uwboeking_disable_slider'), 'uwboeking_wp_plugin', 'looks' );
		add_settings_field( 'brn_uwboeking_custom_css_archive', 'Aanvullende CSS (overzicht)', array(&$this,'brn_uwboeking_custom_css_archive'), 'uwboeking_wp_plugin', 'looks' );
		add_settings_field( 'brn_uwboeking_custom_css_single', 'Aanvullende CSS (doorklik)', array(&$this,'brn_uwboeking_custom_css_single'), 'uwboeking_wp_plugin', 'looks' );
		add_settings_field( 'brn_uwboeking_custom_css_search', 'Aanvullende CSS (zoekformulier)', array(&$this,'brn_uwboeking_custom_css_search'), 'uwboeking_wp_plugin', 'looks' );
	}

	public function brn_uwboeking_options_validate($input) {
		return $input;
	}

	public function brn_uwboeking_api_key() {
		$options = get_option('brn_uwboeking_store');
		echo "<input id='brn_uwboeking_api_key' name='brn_uwboeking_store[api_key]' type='text' value='" . esc_attr( $options['api_key'] ) . "' />";
	}

	public function brn_uwboeking_language() {
		$options = get_option('brn_uwboeking_store');
		echo "<input id='brn_uwboeking_language' name='brn_uwboeking_store[lang]' type='text' value='" . esc_attr( $options['lang'] ) . "' />";
	}

	public function brn_uwboeking_archpage() {
		$options = get_option('brn_uwboeking_store');
		echo "<input id='brn_uwboeking_archpage' name='brn_uwboeking_store[arch_url]' type='text' value='" . esc_attr( $options['arch_url'] ) . "' />";
	}

	public function brn_uwboeking_disable_css() {
		$options = get_option('brn_uwboeking_store');
		$checked = $options['disable_css'] == 1 ? ' checked="checked"' : '';
		echo "<input id='brn_uwboeking_disable_css' name='brn_uwboeking_store[disable_css]' type='checkbox' value='1'" . $checked . "/>";
	}

	public function brn_uwboeking_disable_slider() {
		$options = get_option('brn_uwboeking_store');
		$checked = $options['disable_slider'] == 1 ? ' checked="checked"' : '';
		echo "<input id='brn_uwboeking_disable_slider' name='brn_uwboeking_store[disable_slider]' type='checkbox' value='1'" . $checked . "/>";
	}

	public function brn_uwboeking_custom_css_archive() {
		$options = get_option('brn_uwboeking_store');
		echo "<textarea id='brn_uwboeking_css_archive' name='brn_uwboeking_store[custom_css_archive]'>" . esc_attr( $options['custom_css_archive'] ) . "</textarea>";
	}

	public function brn_uwboeking_custom_css_single() {
		$options = get_option('brn_uwboeking_store');
		echo "<textarea id='brn_uwboeking_css_single' name='brn_uwboeking_store[custom_css_single]'>" . esc_attr( $options['custom_css_single'] ) . "</textarea>";
	}

	public function brn_uwboeking_custom_css_search() {
		$options = get_option('brn_uwboeking_store');
		echo "<textarea id='brn_uwboeking_css_search' name='brn_uwboeking_store[custom_css_search]'>" . esc_attr( $options['custom_css_search'] ) . "</textarea>";
	}

	public function brn_uwboeking_api() {
		// tekst kan hier
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Uwboeking_Wp_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Uwboeking_Wp_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/uwboeking-wp-plugin-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Uwboeking_Wp_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Uwboeking_Wp_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/uwboeking-wp-plugin-admin.js', array( 'jquery' ), $this->version, false );

	}

}
