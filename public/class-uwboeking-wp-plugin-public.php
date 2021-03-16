<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       brandom.nl
 * @since      1.0.0
 *
 * @package    Uwboeking_Wp_Plugin
 * @subpackage Uwboeking_Wp_Plugin/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Uwboeking_Wp_Plugin
 * @subpackage Uwboeking_Wp_Plugin/public
 * @author     Geert van Dijk <geert@brandom.nl>
 */
class Uwboeking_Wp_Plugin_Public {

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

	private $options;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->options = get_option('brn_uwboeking_store');

		add_shortcode('uwboeking_lijst', array($this, 'sc_archive'));
		add_shortcode('uwboeking_enkel', array($this, 'sc_single'));
		add_shortcode('uwboeking_zoek', array($this, 'sc_search'));
	}

	public function sc_search($atts) {
		$css = $this->options['disable_css'] != true;
		$custom_css = $this->options['custom_css_search'];
		$arch_url = $this->options['arch_url'];
		include plugin_dir_path( __FILE__ ) . 'partials/uwboeking-search.php';
	}

	public function sc_archive($atts) {
		$css = $this->options['disable_css'] != true;
		if (isset($_GET['acc_id'])) {
			$feed = file_get_contents('http://www.uwboeking.com/class/class.SearchBookAPIx.php?authID='.$this->options['api_key'].'&lang='.$this->options['lang'].'&houseID='.$_GET['acc_id']);
			$acc = simplexml_load_string($feed);
			$acc = $acc->verblijf;
			$lang = $this->options['lang'];
			$custom_css = $this->options['custom_css_single'];
			$slider = $this->options['disable_slider'] != true;
			$arch_url = $this->options['arch_url'];
			include plugin_dir_path( __FILE__ ) . 'partials/uwboeking-single.php';
		} else {
			$van = isset($_GET['begin']) ? strtotime($_GET['begin']) : 1;
			$tot = isset($_GET['eind']) ? strtotime($_GET['eind']) : 1;
			$pers = isset($_GET['personen']) ? $_GET['personen'] : 1;
			$feed = file_get_contents('http://www.uwboeking.com/class/class.SearchBookAPI.php?authID='.$this->options['api_key'].'&lang='.$this->options['lang'].'&aantalPersonen='.$pers.'&datumVan='.$van.'&datumTot='.$tot);
			$accs = simplexml_load_string($feed);
			$custom_css = $this->options['custom_css_archive'];
			include plugin_dir_path( __FILE__ ) . 'partials/uwboeking-archive.php';
		}
	}

	public function sc_single($atts) {
		$a = shortcode_atts(array(
			'id' => '0'
		));
		$feed = file_get_contents('http://www.uwboeking.com/class/class.SearchBookAPIx.php?authID='.$this->options['api_key'].'&lang='.$this->options['lang'].'&houseID='.$a['id']);
		$acc = simplexml_load_string($feed);
		$acc = $acc->verblijf;
		$lang = $this->options['lang'];
		$css = $this->options['disable_css'] != true;
		$slider = $this->options['disable_slider'] != true;
		$custom_css = $this->options['custom_css_single'];
		$arch_url = $this->options['arch_url'];
		include plugin_dir_path( __FILE__ ) . 'partials/uwboeking-single.php';
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/uwboeking-wp-plugin-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/uwboeking-wp-plugin-public.js', array( 'jquery' ), $this->version, false );

	}

}
