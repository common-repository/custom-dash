<?php
/**
 * Main plugin class. Iinitialises hooks and filters as well as setting up
 * middleware used throughout the plugin.
 * Any plugin-wide housekeeping processes may be placed here.
 *
 * @package iascd-customdash
 * @since   1.0.0
 */

declare( strict_types = 1 );

namespace Iascd\CustomDash;
use Iascd\CustomDash\Admin\IascdSettings;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * @since   1.0.0
 * @package Iascd\CustomDash
 * @author  Arun Sharma <dextorlobo@gmail.com>
 */
class IascdPlugin {

	private $iascd_text_or_logo;
	private $iascd_logo_text;
	private $iascd_logo_url;
	private $iascd_logo_width;
	private $iascd_logo_height;
	private $iascd_login_header_url;

	/**
	 * IascdPlugin constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// admin hooks.
		if ( is_admin() ) {
			add_action( 'after_setup_theme', array ( $this, 'iascd_register_settings' ) );
			add_action( 'admin_enqueue_scripts', array ( $this, 'iascd_enqueue_admin_script' ) );
		}

		// front-end hooks.
		add_action( 'login_head', array ( $this, 'iascd_login_logo_css_cb' ) );
		add_filter( 'login_headertext', array ( $this, 'iascd_login_header_text_cb' ) );
		add_filter( 'login_headerurl', array ( $this, 'iascd_login_header_url_cb' ) );
	}

	/**
	 * Registering settings pages
	 * 
	 * @since 1.0.0
	 */
	public function iascd_register_settings() {
		new IascdSettings();
	}

	/**
	 * Enqueue a script in the WordPress admin only on settings_page_iascd_general page.
	 *
	 * @param string $hook Hook suffix for the current admin page.
	 * @since 1.0.0
	 */
	public function iascd_enqueue_admin_script( $hook ) {
		if ( 'settings_page_iascd_general' == $hook ) {
			wp_enqueue_script( 'jquery' );
			wp_enqueue_media();
			wp_enqueue_script( 'iascd-custom-script', plugin_dir_url( __FILE__ ) . 'assets/iascd-custom-script.js', array( 'jquery' ), IASCD_PLUGIN_VERSION, true );
		}
	}

	/**
	 * Custom WordPress admin login logo css.
	 * 
	 * @since 1.0.0
	 */
	public function iascd_login_logo_css_cb() {
		$iascd_options                = get_option( 'iascd_general_options' );
		$this->iascd_text_or_logo     = ( isset( $iascd_options['iascd_general_text_or_logo'] ) && ! empty( $iascd_options['iascd_general_text_or_logo'] ) ) ? sanitize_text_field( $iascd_options['iascd_general_text_or_logo'] ) : '';
		$this->iascd_logo_text        = ( isset( $iascd_options['iascd_general_text_logo'] ) && ! empty( $iascd_options['iascd_general_text_logo'] ) ) ? sanitize_text_field( $iascd_options['iascd_general_text_logo'] ) : '';
		$this->iascd_logo_url         = ( isset( $iascd_options['iascd_general_login_logo'] ) && ! empty( $iascd_options['iascd_general_login_logo'] ) ) ? sanitize_url( $iascd_options['iascd_general_login_logo'] ) : '';
		$this->iascd_logo_height      = ( isset( $iascd_options['iascd_general_logo_height'] ) && ! empty( $iascd_options['iascd_general_logo_height'] ) ) ? sanitize_text_field( $iascd_options['iascd_general_logo_height'] ) . 'px' : '84px';
		$this->iascd_logo_width       = ( isset( $iascd_options['iascd_general_logo_width'] ) && ! empty( $iascd_options['iascd_general_logo_width'] ) ) ? sanitize_text_field( $iascd_options['iascd_general_logo_width'] ) . 'px' : '84px';
		$this->iascd_login_header_url = ( isset( $iascd_options['iascd_general_login_header_url'] ) && ! empty( $iascd_options['iascd_general_login_header_url'] ) ) ? sanitize_url( $iascd_options['iascd_general_login_header_url'] ) : '';

		if ( empty( $this->iascd_text_or_logo ) ) {
			return;
		}

		$this->iascd_logo_height = apply_filters( 'iascd_cusotm_logo_height', $this->iascd_logo_height );
		$this->iascd_logo_width  = apply_filters( 'iascd_cusotm_logo_width', $this->iascd_logo_width );

		$iascd_text_indent = '-9999px;';

		if ( $this->iascd_text_or_logo == 'text' ) {
			$this->iascd_logo_url = 'none';
			$iascd_text_indent    = '0';
		}

		if ( ! empty( $this->iascd_logo_url ) ) {
			echo '<style type="text/css"> h1 a { 
				background-image:url( ' . esc_url( $this->iascd_logo_url ) . ' ) !important;
				height:' . esc_attr( $this->iascd_logo_height ) . ' !important;
				width:' . esc_attr( $this->iascd_logo_width ) . ' !important;
				background-size:100% !important;
				line-height:inherit !important;
				text-indent: ' . esc_attr( $iascd_text_indent ) . ' !important;
			}</style>';
		}
	}

	/**
	 * Login screen header text.
	 * 
	 * @since 1.0.0
	 */
	public function iascd_login_header_text_cb( $login_header_text ) {
		if ( $this->iascd_text_or_logo == 'text' && ! empty( $this->iascd_logo_text ) ) {
			return $this->iascd_logo_text;
		}

		return $login_header_text;
	}

	/**
	 * Login screen header url.
	 * 
	 * @since 1.0.0
	 */
	public function iascd_login_header_url_cb( $iascd_login_header_url ) {
		if ( $this->iascd_login_header_url ) {
			return $this->iascd_login_header_url;
		}

		return $iascd_login_header_url;
	}
}
