<?php
/**
 * IascdSettings class.
 *
 * @package iascd-customdash
 * @since   1.0.0
 */

declare( strict_types = 1 );

namespace Iascd\CustomDash\Admin;

class IascdSettings {

	/**
	 * IascdSettings constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		/**
		 * Register our iascd_general_settings_init to the admin_init action hook.
		 */
		add_action( 'admin_init', array( $this, 'iascd_general_settings_init' ) );

		/**
		 * Register our iascd_general_options_page to the admin_menu action hook.
		 */
		add_action( 'admin_menu', array( $this, 'iascd_general_options_page' ) );

		$plugin = 'custom-dash/custom-dash.php';
		add_filter( "plugin_action_links_$plugin", array( $this, 'iascd_settings_link' ) );
	}
	
	/**
	 * custom option and settings
	 */
	public function iascd_general_settings_init() {
		// Register a new setting for "iascd_general" page.
		register_setting( 'iascd_general', 'iascd_general_options',
		array(
			'type'              => 'array',
			'sanitize_callback' => array( $this, 'iascd_sanitize_fields' )
		) );
	
		// Register a new section in the "iascd_general" page.
		add_settings_section(
			'iascd_general_section_developers',
			__( 'Custom settings', 'iascd_general' ), array( $this, 'iascd_general_section_developers_callback' ),
			'iascd_general'
		);

		// Register a new field in the "iascd_general_section_developers" section, inside the "iascd_general" page.
		add_settings_field(
			'iascd_general_text_or_logo', // As of WP 4.6 this value is used only internally.
			// Use $args' label_for to populate the id inside the callback.
			__( 'Text Or Logo', 'iascd_general' ),
			array( $this, 'iascd_general_text_or_logo_cb' ),
			'iascd_general',
			'iascd_general_section_developers',
			array(
				'label_for' => 'iascd_general_text_or_logo',
				'class'     => 'iascd_general_row regular-text',
			)
		);

		// Register a new field in the "iascd_general_section_developers" section, inside the "iascd_general" page.
		add_settings_field(
			'iascd_general_text_logo', // As of WP 4.6 this value is used only internally.
			// Use $args' label_for to populate the id inside the callback.
			__( 'Text Logo', 'iascd_general' ),
			array( $this, 'iascd_general_text_logo_cb' ),
			'iascd_general',
			'iascd_general_section_developers',
			array(
				'label_for' => 'iascd_general_text_logo',
				'class'     => 'iascd_general_row regular-text',
			)
		);

		// Register a new field in the "iascd_general_section_developers" section, inside the "iascd_general" page.
		add_settings_field(
			'iascd_general_login_logo', // As of WP 4.6 this value is used only internally.
			// Use $args' label_for to populate the id inside the callback.
			__( 'Image Logo', 'iascd_general' ),
			array( $this, 'iascd_general_login_logo_cb' ),
			'iascd_general',
			'iascd_general_section_developers',
			array(
				'label_for' => 'iascd_general_login_logo',
				'class'     => 'iascd_general_row regular-text',
			)
		);

		// Register a new field in the "iascd_general_section_developers" section, inside the "iascd_general" page.
		add_settings_field(
			'iascd_general_logo_height', // As of WP 4.6 this value is used only internally.
			// Use $args' label_for to populate the id inside the callback.
			__( 'Logo Heigth', 'iascd_general' ),
			array( $this, 'iascd_general_logo_height_cb' ),
			'iascd_general',
			'iascd_general_section_developers',
			array(
				'label_for' => 'iascd_general_logo_height',
				'class'     => 'iascd_general_row regular-text',
			)
		);

		// Register a new field in the "iascd_general_section_developers" section, inside the "iascd_general" page.
		add_settings_field(
			'iascd_general_logo_width', // As of WP 4.6 this value is used only internally.
			// Use $args' label_for to populate the id inside the callback.
			__( 'Logo Width', 'iascd_general' ),
			array( $this, 'iascd_general_logo_width_cb' ),
			'iascd_general',
			'iascd_general_section_developers',
			array(
				'label_for' => 'iascd_general_logo_width',
				'class'     => 'iascd_general_row regular-text',
			)
		);

		// Register a new field in the "iascd_general_section_developers" section, inside the "iascd_general" page.
		add_settings_field(
			'iascd_general_login_header_url', // As of WP 4.6 this value is used only internally.
			// Use $args' label_for to populate the id inside the callback.
			__( 'Logo URL', 'iascd_general' ),
			array( $this, 'iascd_general_login_header_url_cb' ),
			'iascd_general',
			'iascd_general_section_developers',
			array(
				'label_for' => 'iascd_general_login_header_url',
				'class'     => 'iascd_general_row regular-text',
			)
		);
	}

	/**
	 * Page heading section callback function.
	 *
	 * @param array $args  The settings array, defining title, id, callback.
	 */
	public function iascd_general_section_developers_callback( $args ) {
		?>
		<p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Login screen logo settings', 'iascd_general' ); ?></p>
		<?php
	}

	/**
	 * Text or logo callback function.
	 *
	 * @param array $args
	 */
	public function iascd_general_text_or_logo_cb( $args ) {
		// Get the value of the setting we've registered with register_setting()
		$iascd_options = get_option( 'iascd_general_options' );
		$iascd_options[ $args['label_for'] ] = ( ! empty( $iascd_options[ $args['label_for'] ] ) ) ? $iascd_options[ $args['label_for'] ] : '';
		?>
		<label><input type="radio" class="<?php echo esc_attr( $args['class'] ); ?>" id="<?php echo esc_attr( $args['label_for'] ); ?>" name="iascd_general_options[<?php echo esc_attr( $args['label_for'] ); ?>]" value="text" <?php checked( 'text', $iascd_options[ $args['label_for'] ] ); ?> />Text</label><br />
		<label><input type="radio" class="<?php echo esc_attr( $args['class'] ); ?>" id="<?php echo esc_attr( $args['label_for'] ); ?>" name="iascd_general_options[<?php echo esc_attr( $args['label_for'] ); ?>]" value="logo" <?php checked( 'logo', $iascd_options[ $args['label_for'] ] ); ?> />Image</label><br />  
		<p class="description" id="tagline-description">Selete type of logo to display on login screen</p>
		<?php
	}

	/**
	 * Text logo callback function.
	 *
	 * @param array $args
	 */
	public function iascd_general_text_logo_cb( $args ) {
		// Get the value of the setting we've registered with register_setting()
		$iascd_options = get_option( 'iascd_general_options' );
		$iascd_options[ $args['label_for'] ] = ( ! empty( $iascd_options[ $args['label_for'] ] ) ) ? sanitize_text_field( $iascd_options[ $args['label_for'] ] ) : '';
		?>
		<input type='text' class="<?php echo esc_attr( $args['class'] ); ?>" id="<?php echo esc_attr( $args['label_for'] ); ?>" name="iascd_general_options[<?php echo esc_attr( $args['label_for'] ); ?>]" value="<?php echo esc_attr( $iascd_options[ $args['label_for'] ] ) ?>">
		<p class="description" id="tagline-description">Enter your text for logo</p>
		<?php
	}

	/**
	 * Login logo callback function.
	 *
	 * @param array $args
	 */
	public function iascd_general_login_logo_cb( $args ) {
		// Get the value of the setting we've registered with register_setting()
		$iascd_options = get_option( 'iascd_general_options' );
		$iascd_options[ $args['label_for'] ] = ( ! empty( $iascd_options[ $args['label_for'] ] ) ) ? sanitize_url( $iascd_options[ $args['label_for'] ] ) : '';
		?>
		<input type='text' class="<?php echo esc_attr( $args['class'] ); ?>" id="<?php echo esc_attr( $args['label_for'] ); ?>" name="iascd_general_options[<?php echo esc_attr( $args['label_for'] ); ?>]" value="<?php echo esc_attr( $iascd_options[ $args['label_for'] ] ) ?>">
		<input type="button" name="iascd-upload-btn" id="iascd_upload_btn" class="button-secondary" value="Upload Logo">
		<p class="description" id="tagline-description">Upload a logo image</p>
		<?php
	}

	/**
	 * Logo height callback function.
	 *
	 * @param array $args
	 */
	public function iascd_general_logo_height_cb( $args ) {
		// Get the value of the setting we've registered with register_setting()
		$iascd_options = get_option( 'iascd_general_options' );
		$iascd_options[ $args['label_for'] ] = ( ! empty( $iascd_options[ $args['label_for'] ] ) ) ? sanitize_text_field( $iascd_options[ $args['label_for'] ] ) : '';
		?>
		<input type='text' class="<?php echo esc_attr( $args['class'] ); ?>" id="<?php echo esc_attr( $args['label_for'] ); ?>" name="iascd_general_options[<?php echo esc_attr( $args['label_for'] ); ?>]" value="<?php echo esc_attr( $iascd_options[ $args['label_for'] ] ) ?>">
		<p class="description" id="tagline-description">Enter logo height (px)</p>
		<?php
	}

	/**
	 * Logo width callback function.
	 *
	 * @param array $args
	 */
	public function iascd_general_logo_width_cb( $args ) {
		// Get the value of the setting we've registered with register_setting()
		$iascd_options = get_option( 'iascd_general_options' );
		$iascd_options[ $args['label_for'] ] = ( ! empty( $iascd_options[ $args['label_for'] ] ) ) ? sanitize_text_field( $iascd_options[ $args['label_for'] ] ) : '';
		?>
		<input type='text' class="<?php echo esc_attr( $args['class'] ); ?>" id="<?php echo esc_attr( $args['label_for'] ); ?>" name="iascd_general_options[<?php echo esc_attr( $args['label_for'] ); ?>]" value="<?php echo esc_attr( $iascd_options[ $args['label_for'] ] ) ?>">
		<p class="description" id="tagline-description">Enter logo width (px)</p>
		<?php
	}

	/**
	 * Login header url callback function.
	 *
	 * @param array $args
	 */
	public function iascd_general_login_header_url_cb( $args ) {
		// Get the value of the setting we've registered with register_setting()
		$iascd_options = get_option( 'iascd_general_options' );
		$iascd_options[ $args['label_for'] ] = ( ! empty( $iascd_options[ $args['label_for'] ] ) ) ? sanitize_url( $iascd_options[ $args['label_for'] ] ) : '';
		?>
		<input type='text' class="<?php echo esc_attr( $args['class'] ); ?>" id="<?php echo esc_attr( $args['label_for'] ); ?>" name="iascd_general_options[<?php echo esc_attr( $args['label_for'] ); ?>]" value="<?php echo esc_attr( $iascd_options[ $args['label_for'] ] ) ?>">
		<p class="description" id="tagline-description">Enter logo URL</p>
		<?php
	}

	/**
	 * Add the top level menu page.
	 */
	public function iascd_general_options_page() {
		add_submenu_page(
			'options-general.php',
			'Custom Dash Settings',
			'Custom Dash',
			'manage_options',
			'iascd_general',
			array( $this, 'iascd_general_options_page_html' )
		);
	}

	/**
	 * Top level menu callback function
	 */
	public function iascd_general_options_page_html() {
		// check user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		
		// show error/update messages
		settings_errors( 'iascd_general_messages' );
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post">
				<?php
				// output security fields for the registered setting "iascd_general"
				settings_fields( 'iascd_general' );
				// output setting sections and their fields
				// (sections are registered for "iascd_general", each field is registered to a specific section)
				do_settings_sections( 'iascd_general' );
				// output save settings button
				submit_button( 'Save Settings' );
				?>
			</form>
		</div>
		<?php
	}

	public function iascd_settings_link( $links ) {
		$settings_link = '<a href="options-general.php?page=iascd_general">Settings</a>';
		array_unshift( $links, $settings_link );

		return $links;
	}

	public function iascd_sanitize_fields( $data ) {
		$old_options = get_option('iascd_general_options');
		$has_errors  = false;

		if ( empty( $data['iascd_general_text_or_logo'] ) ) {
			add_settings_error( 'iascd_sanitize_fields_error', 'iascd_general_text_or_logo_message', __( 'Please choose logo type', 'custom-dash' ), 'error' );
			$has_errors = true;
		}

		if ( ! empty( $data['iascd_general_login_logo'] ) ) {
			if ( ! wp_http_validate_url( $data['iascd_general_login_logo'] ) ) {
				add_settings_error( 'iascd_sanitize_fields_error', 'iascd_general_image_logo_message', __( 'Image logo URL is not correct', 'custom-dash' ), 'error' );
				$has_errors = true;
			}
		}

		if ( ! empty( $data['iascd_general_logo_height'] ) ) {
			if ( ! is_numeric( $data['iascd_general_logo_height'] ) ) {
				add_settings_error( 'iascd_sanitize_fields_error', 'iascd_general_logo_height_message', __( 'Image logo height in number', 'custom-dash' ), 'error' );
				$has_errors = true;
			}
		}

		if ( ! empty( $data['iascd_general_logo_width'] ) ) {
			if ( ! is_numeric( $data['iascd_general_logo_width'] ) ) {
				add_settings_error( 'iascd_sanitize_fields_error', 'iascd_general_logo_width_message', __( 'Image logo width in number', 'custom-dash' ), 'error' );
				$has_errors = true;
			}
		}

		if ( ! empty( $data['iascd_general_login_header_url'] ) ) {
			if ( ! wp_http_validate_url( $data['iascd_general_login_header_url'] ) ) {
				add_settings_error( 'iascd_sanitize_fields_error', 'iascd_general_header_url_message', __( 'Logo URL is not correct', 'custom-dash' ), 'error' );
				$has_errors = true;
			}
		}

		if ( $has_errors ) {
			$data = $old_options;
		}

		return $data;
	}
}
