<?php
/**
 * Summary goes here.
 *
 * @package  SPID_WordPress
 * @author   Paolo Greppi simevo s.r.l. <email@emailgoeshere.com>
 * @license  GNU Affero General Public License v3.0
 * @link     https://github.com/simevo/spid-wordpress
 * @since    1.0.0
 */

if ( ! class_exists( 'SPID_Admin' ) ) {
	class SPID_Admin {

		private $spid_options_general;
		private $spid_options_button;
		private $spid_options_help;

		public function __construct() {
			add_action( 'admin_menu', array( $this, 'spid_settings_menu' ) );
			add_action( 'admin_init', array( $this, 'spid_options_init' ) );
		}

		public function spid_settings_menu() {
			add_menu_page( 'SPID Opzioni', 'SPID Opzioni', 'manage_options', 'spid_opzioni', array( $this, 'spid_admin_options' ) );
		}

		function spid_admin_options() {
			$this->spid_options_general = get_option( 'spid_general' );
			$this->spid_options_button  = get_option( 'spid_button' );
			$this->spid_options_help    = get_option( 'spid_help' );

			$button_Screen = ( isset( $_GET['action'] ) && 'button' == $_GET['action'] ) ? true : false;
			$help_Screen   = ( isset( $_GET['action'] ) && 'help' == $_GET['action'] ) ? true : false;

			?>
			<div class="wrap">
			<h1><?php _esc_attr_e( 'SPID Options', 'spid-wordpress' ); ?></h1>
			<h2 class="nav-tab-wrapper">
				<a 
					href="<?php echo esc_url( admin_url( 'admin.php?page=spid_opzioni' ) ); ?>"
					class="nav-tab
					<?php
					if ( ! isset( $_GET['action'] ) || isset( $_GET['action'] ) && 'button' != $_GET['action'] && 'help' != $_GET['action'] ) {
						echo ' nav-tab-active';}
					?>
					">
					<?php esc_html_e( 'General' ); ?>
				</a>
				<a 
					href="<?php echo esc_url( add_query_arg( array( 'action' => 'button' ), admin_url( 'admin.php?page=spid_opzioni' ) ) ); ?>" 
					class="nav-tab
					<?php
					if ( $button_Screen ) {
						echo ' nav-tab-active';}
					?>
					">
					<?php esc_html_e( 'SPID Button' ); ?></a> 
				<a 
					href="<?php echo esc_url( add_query_arg( array( 'action' => 'help' ), admin_url( 'admin.php?page=spid_opzioni' ) ) ); ?>" 
					class="nav-tab
					<?php
					if ( $help_Screen ) {
						echo ' nav-tab-active';}
					?>
					">
					<?php esc_html_e( 'Help' ); ?>
				</a>        
			</h2>
	
			<form method="post" action="options.php">
			<?php
			if ( $button_Screen ) {
				settings_fields( 'spid_button' );
				do_settings_sections( 'spid-setting-button' );
				submit_button();
			} elseif ( $help_Screen ) {
				settings_fields( 'spid_help' );
				do_settings_sections( 'spid-setting-help' );
				submit_button();
			} else {
				settings_fields( 'spid_general' );
				do_settings_sections( 'spid-setting-admin' );
				submit_button();
			}
			?>
			</form>
			</div>
			<?php

		}

		public function spid_options_init() {
			/**
			 * General Settings
			 */
			register_setting(
				'spid_general', // Option Group.
				'spid_general', // Option Name.
				array( $this, 'sanitize' ) // Sanitize.
			);

			add_settings_section(
				'spid_settings_section', // ID.
				'Options', // Title.
				array( $this, 'print_section_info' ), // Callback.
				'spid-setting-admin' // Page.
			);

			add_settings_field(
				'sp_org_name', // ID.
				'Name of Service Provider', // Title.
				array( $this, 'sp_org_name_callback' ), // Callback.
				'spid-setting-admin', // Page.
				'spid_settings_section' // Section.
			);

			add_settings_field(
				'sp_sso', // ID.
				'SSO', // Title.
				array( $this, 'sp_sso_callback' ), // Callback.
				'spid-setting-admin', // Page.
				'spid_settings_section' // Section.
			);

			add_settings_field(
				'sp_idp', // ID.
				'Identity Provider', // Title.
				array( $this, 'sp_idp_callback' ), // Callback.
				'spid-setting-admin', // Page.
				'spid_settings_section' // Section.
			);

			add_settings_field(
				'sp_livello', // ID.
				'SPID Level', // Title.
				array( $this, 'sp_livello_callback' ), // Callback.
				'spid-setting-admin', // Page.
				'spid_settings_section' // Section.
			);

			/**
			 * SPID Button Settings
			 */
			register_setting(
				'spid_button', // Option Group.
				'spid_button', // Option Name.
				array( $this, 'sanitize' ) // Sanitize.
			);

			add_settings_section(
				'spid_button_setting', // ID.
				'Button Settings', // Title.
				array( $this, 'print_section_info' ), // Callback.
				'spid-setting-button' // Page.
			);

			add_settings_field(
				'spid_button', // ID.
				'SPID Button Layout', // Title.
				array( $this, 'spid_button_callback' ), // Callback.
				'spid-setting-button', // Page.
				'spid_button_setting' // Section.
			);

			/**
			 * Help Screen
			 */
			register_setting(
				'spid_help', // Option Group.
				'spid_help', // Option Name.
				array( $this, 'sanitize' ) // Sanitize.
			);

			add_settings_section(
				'setting_section_id', // ID.
				'Help', // Title.
				array( $this, 'print_section_info' ), // Callback.
				'spid-setting-help' // Page.
			);

			add_settings_field(
				'spid_help', // ID.
				'Help', // Title.
				array( $this, 'spid_help_callback' ), // Callback.
				'spid-setting-help', // Page.
				'setting_section_id' // Section.
			);
		}

		public function print_section_info() {
			echo 'Info here.';
		}

		/**
		 * General Settings
		 */
		public function sp_org_name_callback() {
			printf(
				'<input type="text" id="sp_org_name" name="spid_general[sp_org_name]" value="%s" />',
				isset( $this->spid_options_general['sp_org_name'] ) ? esc_attr( $this->spid_options_general['sp_org_name'] ) : ''
			);
		}

		public function sp_sso_callback() {
			printf(
				'<input type="text" id="sp_sso" name="spid_general[sp_sso]" value="%s" />',
				isset( $this->spid_options_general['sp_sso'] ) ? esc_attr( $this->spid_options_general['sp_sso'] ) : ''
			);
		}

		public function sp_idp_callback() {
			printf(
				'<input type="text" id="sp_idp" name="spid_general[sp_idp]" value="%s" />',
				isset( $this->spid_options_general['sp_idp'] ) ? esc_attr( $this->spid_options_general['sp_idp'] ) : ''
			);
		}

		public function sp_livello_callback() {
			printf(
				'<select name="spid_general[sp_livello]" id="sp_livello" value="%s"/>
                <option selected disabled>Choose here</option>
                <option value="1">1</option>
				<option value="2">2</option>
                </select>',
				isset( $this->spid_options_general['sp_livello'] ) ? esc_attr( $this->spid_options_general['sp_livello'] ) : ''
			);
		}

		/**
		 * SPID Button Settings
		 */
		public function spid_button_callback() {
			echo 'Info here.';
		}

		/**
		 * Help Page
		 */
		public function spid_help_callback() {
			echo 'Info here.';
		}

		public function sanitize( $input ) {
			$new_input = array();

			if ( isset( $input['sp_org_name'] ) ) {
				$new_input['sp_org_name'] = sanitize_text_field( $input['sp_org_name'] );
			}

			if ( isset( $input['sp_sso'] ) ) {
				$new_input['sp_sso'] = sanitize_text_field( $input['sp_sso'] );
			}

			if ( isset( $input['sp_idp'] ) ) {
				$new_input['sp_idp'] = sanitize_text_field( $input['sp_idp'] );
			}

			if ( isset( $input['sp_livello'] ) ) {
				$new_input['sp_livello'] = sanitize_text_field( $input['sp_livello'] );
			}

			return $new_input;
		}
	}
}
