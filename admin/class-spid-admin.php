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
		private $spid_options_metadata;
		private $spid_options_button;

		public function __construct() {
			add_action( 'admin_menu', array( $this, 'spid_settings_menu' ) );
			add_action( 'admin_init', array( $this, 'spid_options_init' ) );
		}

		public function spid_settings_menu() {
			add_menu_page( 'SPID Opzioni', 'SPID Opzioni', 'manage_options', 'spid_opzioni', array( $this, 'spid_admin_options' ) );
		}

		function spid_admin_options() {
			$this->spid_options_general = get_option( 'spid_general' );
			$this->spid_options_metadata  = get_option( 'spid_metadata' );
			$this->spid_options_button    = get_option( 'spid_button' );

			$metadata_Screen = ( isset( $_GET['action'] ) && 'metadata' == $_GET['action'] ) ? true : false;
			$button_Screen   = ( isset( $_GET['action'] ) && 'button' == $_GET['action'] ) ? true : false;

			?>
			<div class="wrap">
			<div class="spid-logo" style="text-align:center;">
				<img src="<?php echo  SPID_WORDPRESS_URL . 'public/images/spid.png'; ?>" style="width: 600px; margin: 3% 0 0;">
			</div>
			<h1><?php esc_attr_e( 'SPID Wordpress Plug-in Options', 'spid-wordpress' ); ?></h1>
			<h2 class="nav-tab-wrapper">
				<a 
					href="<?php echo esc_url( admin_url( 'admin.php?page=spid_opzioni' ) ); ?>"
					class="nav-tab
					<?php
					if ( ! isset( $_GET['action'] ) || isset( $_GET['action'] ) && 'metadata' != $_GET['action'] && 'button' != $_GET['action'] ) {
						echo ' nav-tab-active';}
					?>
					">
					<?php esc_html_e( 'Plugin Configuration' ); ?>
				</a>
				<a 
					href="<?php echo esc_url( add_query_arg( array( 'action' => 'metadata' ), admin_url( 'admin.php?page=spid_opzioni' ) ) ); ?>" 
					class="nav-tab
					<?php
					if ( $metadata_Screen ) {
						echo ' nav-tab-active';}
					?>
					">
					<?php esc_html_e( 'Metadata' ); ?></a> 
				<a 
					href="<?php echo esc_url( add_query_arg( array( 'action' => 'button' ), admin_url( 'admin.php?page=spid_opzioni' ) ) ); ?>" 
					class="nav-tab
					<?php
					if ( $button_Screen ) {
						echo ' nav-tab-active';}
					?>
					">
					<?php esc_html_e( 'SPID Button' ); ?>
				</a> 
				<a 
					href="<?php echo esc_url( add_query_arg( array( 'action' => 'certificati' ), admin_url( 'admin.php?page=spid_opzioni' ) ) ); ?>" 
					class="nav-tab
					<?php
					if ( $button_Screen ) {
						echo ' nav-tab-active';}
					?>
					">
					<?php esc_html_e( 'Certificati' ); ?>
				</a>  			       
			</h2>
	
			<form method="post" action="options.php">
			<?php
			if ( $metadata_Screen ) {
				settings_fields( 'spid_metadata' );
				do_settings_sections( 'spid-setting-metadata' );
				submit_button();
			} elseif ( $button_Screen ) {
				settings_fields( 'spid_button' );
				do_settings_sections( 'spid-setting-button' );
				submit_button();
			} elseif ( $cert_Screen ) {
				settings_fields( 'spid_certificati' );
				do_settings_sections( 'spid-setting-certificati' );
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
				array( $this, 'general_section_info' ), // Callback.
				'spid-setting-admin' // Page.
			);

			add_settings_field(
				'sp_livello', // ID.
				'SPID Level', // Title.
				array( $this, 'sp_livello_callback' ), // Callback.
				'spid-setting-admin', // Page.
				'spid_settings_section' // Section.
			);

			add_settings_field(
				'sp_role', // ID.
				'Role on registration with SPID', // Title.
				array( $this, 'sp_role_callback' ), // Callback.
				'spid-setting-admin', // Page.
				'spid_settings_section' // Section.
			);

			add_settings_field(
				'sp_idp', // ID.
				'Name of the test Identity Provider', // Title.
				array( $this, 'sp_idp_callback' ), // Callback.
				'spid-setting-admin', // Page.
				'spid_settings_section' // Section.
			);

			/**
			 * Metadata Settings
			 */
			register_setting(
				'spid_metadata', // Option Group.
				'spid_metadata', // Option Name.
				array( $this, 'sanitize' ) // Sanitize.
			);

			add_settings_section(
				'spid_metadata_setting', // ID.
				'Metadata', // Title.
				array( $this, 'metadata_section_info' ), // Callback.
				'spid-setting-metadata' // Page.
			);

			add_settings_field(
				'sp_org_name', // ID.
				'Organization Name', // Title.
				array( $this, 'spid_metadata_callback' ), // Callback.
				'spid-setting-metadata', // Page.
				'spid_button_metadata' // Section.
			);

			/**
			 * SPID Button
			 */
			register_setting(
				'spid_button', // Option Group.
				'spid_button', // Option Name.
				array( $this, 'sanitize' ) // Sanitize.
			);

			add_settings_section(
				'setting_section_id', // ID.
				'Configurazione di SPID Button', // Title.
				array( $this, 'button_section_info' ), // Callback.
				'spid-setting-button' // Page.
			);

			add_settings_field(
				'spid_button', // ID.
				array( $this, 'spid_button_callback' ), // Callback.
				'spid-setting-button', // Page.
				'setting_section_id' // Section.
			);
			/**
			 * SPID Certificati
			 */
			register_setting(
				'spid_certificati', // Option Group.
				'spid_certificati', // Option Name.
				array( $this, 'sanitize' ) // Sanitize.
			);

			add_settings_section(
				'setting_section_id', // ID.
				'Configurazione certificati', // Title.
				array( $this, 'button_section_info' ), // Callback.
				'spid-setting-certificati' // Page.
			);

			add_settings_field(
				'spid_certificati', // ID.
				array( $this, 'spid_button_callback' ), // Callback.
				'spid-setting-button', // Page.
				'setting_section_id' // Section.
			);			
		}

		/**
		 * Description of each tab.
		 *
		 * @return print description
		 */
		public function general_section_info() {
			echo 'Configura il plug-in e salva le impostazioni.<br>
Questi parametri non modificano in nessun modo il metadata (link) quindi puoi configurare e salvare le impostazioni liberamente e non sarà necessaro ridistribuire il metadata ai vari Identity Provider.';
		}

		public function metadata_section_info() {
			echo 'Attenzione: una volta cambiati uno di questi settaggi si dovrà ridistribuire il metadata del SP a tutti gli IdP, dal _metadata link_';
		}

		public function button_section_info() {
			echo '';
		}
		public function certificati_section_info() {
			echo 'In questa tab puoi modificare i dati dei certificati...';
		}

		/**
		 * General Settings
		 */

		public function sp_livello_callback() {
			printf(
				'<select name="spid_general[sp_livello]" id="sp_livello" value="%s"/>
                <option selected disabled>Scelgi...</option>
                <option value="1">1</option>
				<option value="2">2</option>
                </select>',
				isset( $this->spid_options_general['sp_livello'] ) ? esc_attr( $this->spid_options_general['sp_livello'] ) : ''
			);
		}

		public function sp_role_callback() {
			printf(
				'<select name="spid_general[sp_role]" id="sp_role" value="%s"/>
                <option selected disabled>Scelgi...</option>
                <option value="admin">Admin</option>
				<option value="registered">Registered</option>
                </select>',
				isset( $this->spid_options_general['sp_role'] ) ? esc_attr( $this->spid_options_general['sp_role'] ) : ''
			);
		}

		public function sp_idp_callback() {
			printf(
				'<input type="text" id="sp_idp" name="spid_general[sp_idp]" value="%s" />',
				isset( $this->spid_options_general['sp_idp'] ) ? esc_attr( $this->spid_options_general['sp_idp'] ) : ''
			);
		}


		/**
		 * Metadata Settings
		 */
		public function metadata_callback() {
			echo 'Attenzione: una volta cambiati uno di questi settaggi si dovrà ridistribuire il metadata del SP a tutti gli IdP, dal _metadata link_.';
		}

		public function sp_org_name_callback() {
			printf(
				'<input type="text" id="sp_org_name" name="spid_metadata[sp_org_name]" value="%s" />',
				isset( $this->spid_options_general['sp_org_name'] ) ? esc_attr( $this->spid_options_general['sp_org_name'] ) : ''
			);
		}

		/**
		 * Button Settings
		 */
		public function spid_button_callback() {
			echo '';
		}

		public function sanitize( $input ) {
			$new_input = array();

			if ( isset( $input['sp_livello'] ) ) {
				$new_input['sp_livello'] = sanitize_text_field( $input['sp_livello'] );
			}
			
			if ( isset( $input['sp_role'] ) ) {
				$new_input['sp_role'] = sanitize_text_field( $input['sp_role'] );
			}

			if ( isset( $input['sp_idp'] ) ) {
				$new_input['sp_idp'] = sanitize_text_field( $input['sp_idp'] );
			}

			return $new_input;
		}
	}
}
