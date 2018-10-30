<?php
/**
 * Summary goes here.
 *
 * @package  SPID_WordPress
 * @author   Paolo Greppi simevo s.r.l. <email@emailgoeshere.com>
 * @license  GNU Affero General Public License v3.0
 * @link     https://github.com/simevo/spid-wordpress
 * @since    1.0.0
 * 
 */

if ( ! class_exists( 'SPID_Admin' ) ) {

	/**	
	 * Admin screen
	 */
	class SPID_Admin {

		private $spid_options_general;
		private $spid_options_metadata;
		private $spid_options_button;
		private $spid_options_certificati;
		private $spid_options_import;
		private $spid_options_aiuto;

		public function __construct() {
			add_action( 'admin_menu', array( $this, 'spid_settings_menu' ) );
			add_action( 'admin_init', array( $this, 'spid_options_init' ) );
		}

		public function spid_settings_menu() {
			add_menu_page( 'SPID Opzioni', 'SPID Opzioni', 'manage_options', 'spid_opzioni', array( $this, 'spid_admin_options' ) );
		}

		function spid_admin_options() {
			$this->spid_options_general     = get_option( 'spid_general' );
			$this->spid_options_metadata    = get_option( 'spid_metadata' );
			$this->spid_options_button      = get_option( 'spid_button' );
			$this->spid_options_certificati = get_option( 'spid_certificati' );
			$this->spid_options_import      = get_option( 'spid_import' );
			$this->spid_options_aiuto       = get_option( 'spid_aiuto' );

			$metadata_screen    = ( isset( $_GET['action'] ) && 'metadata' == $_GET['action'] ) ? true : false;
			$button_screen      = ( isset( $_GET['action'] ) && 'button' == $_GET['action'] ) ? true : false;
			$certificati_screen = ( isset( $_GET['action'] ) && 'certificati' == $_GET['action'] ) ? true : false;
			$import_screen      = ( isset( $_GET['action'] ) && 'import' == $_GET['action'] ) ? true : false;
			$aiuto_screen       = ( isset( $_GET['action'] ) && 'aiuto' == $_GET['action'] ) ? true : false;

			?>
			<div class="wrap">
				<div class="spid-logo">
					<img src="<?php echo esc_url( SPID_WORDPRESS_URL . 'public/images/spid.png' ); ?>" style="width: 400px; margin: 3% 0 0;">
				</div>
				<h1><?php esc_attr_e( 'SPID WordPress Plug-in Opzioni', 'spid-wordpress' ); ?></h1>
				<h2 class="nav-tab-wrapper">
					<a 
						href="<?php echo esc_url( admin_url( 'admin.php?page=spid_opzioni' ) ); ?>"
						class="nav-tab
						<?php
						if ( ! isset( $_GET['action'] ) || isset( $_GET['action'] )
							&& 'metadata' != $_GET['action']
							&& 'button' != $_GET['action']
							&& 'certificati' != $_GET['action']
							&& 'import' != $_GET['action']
							&& 'aiuto' != $_GET['action'] ) {
							echo ' nav-tab-active';
						}
						?>
					">
					<?php esc_html_e( 'Configurazione del Plugin' ); ?>
					</a>
					<a 
						href="<?php echo esc_url( add_query_arg( array( 'action' => 'metadata' ), admin_url( 'admin.php?page=spid_opzioni' ) ) ); ?>" 
						class="nav-tab
						<?php
						if ( $metadata_screen ) {
							echo ' nav-tab-active';}
						?>
					">
					<?php esc_html_e( 'Metadata' ); ?></a> 
					<a 
						href="<?php echo esc_url( add_query_arg( array( 'action' => 'button' ), admin_url( 'admin.php?page=spid_opzioni' ) ) ); ?>" 
						class="nav-tab
						<?php
						if ( $button_screen ) {
							echo ' nav-tab-active';}
						?>
					">
					<?php esc_html_e( 'SPID Button' ); ?>
					</a> 
					<a 
						href="<?php echo esc_url( add_query_arg( array( 'action' => 'certificati' ), admin_url( 'admin.php?page=spid_opzioni' ) ) ); ?>" 
						class="nav-tab
						<?php
						if ( $certificati_screen ) {
							echo ' nav-tab-active';}
						?>
					">
					<?php esc_html_e( 'Certificati' ); ?>
					</a>
					<a 
						href="<?php echo esc_url( add_query_arg( array( 'action' => 'import' ), admin_url( 'admin.php?page=spid_opzioni' ) ) ); ?>" 
						class="nav-tab
						<?php
						if ( $import_screen ) {
							echo ' nav-tab-active';}
						?>
					">
					<?php esc_html_e( 'Import' ); ?>
					</a> 
					<a 
						href="<?php echo esc_url( add_query_arg( array( 'action' => 'aiuto' ), admin_url( 'admin.php?page=spid_opzioni' ) ) ); ?>" 
						class="nav-tab
						<?php
						if ( $aiuto_screen ) {
							echo ' nav-tab-active';}
						?>
					">
					<?php esc_html_e( 'Aiuto' ); ?>
					</a> 								  			       
				</h2>
	
				<form method="post" action="options.php">
					<?php
					if ( $metadata_screen ) {
						settings_fields( 'spid_metadata' );
						do_settings_sections( 'spid-setting-metadata' );
						submit_button();
					} elseif ( $button_screen ) {
						settings_fields( 'spid_button' );
						do_settings_sections( 'spid-setting-button' );
						submit_button();
					} elseif ( $certificati_screen ) {
						settings_fields( 'spid_certificati' );
						do_settings_sections( 'spid-setting-certificati' );
						submit_button();	
					} elseif ( $import_screen ) {
						settings_fields( 'spid_import' );
						do_settings_sections( 'spid-setting-import' );
						submit_button();
					} elseif ( $aiuto_screen ) {
						settings_fields( 'spid_aiuto' );
						do_settings_sections( 'spid-setting-aiuto' );
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
				'Opzioni di configurazione', // Title.
				array( $this, 'general_section_info' ), // Callback.
				'spid-setting-admin' // Page.
			);
			add_settings_field(
				'idp_list', // ID.
				'SPID Available IdP', // Title.
				array( $this, 'idp_list_callback' ), // Callback.
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
			add_settings_field(
				'sp_role', // ID.
				'Ruolo nuovi registrati con SPID', // Title.
				array( $this, 'sp_role_callback' ), // Callback.
				'spid-setting-admin', // Page.
				'spid_settings_section' // Section.
			);
			add_settings_field(
				'sp_idp', // ID.
				'Inserisci l\'ID del IdP di test', // Title.
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
				'user_attributes', // ID.
				'Seleziona gli attributi utente', // Title.
				array( $this, 'user_attributes_callback' ), // Callback.
				'spid-setting-metadata', // Page.
				'spid_metadata_setting' // Section.
			);
			add_settings_field(
				'sp_org_name', // ID.
				'Nome dell\'organizzazione', // Title.
				array( $this, 'sp_org_name_callback' ), // Callback.
				'spid-setting-metadata', // Page.
				'spid_metadata_setting' // Section.
			);
			add_settings_field(
				'sp_org_display_name', // ID.
				'Nome visualizzato organizzazione', // Title.
				array( $this, 'sp_org_display_name_callback' ), // Callback.
				'spid-setting-metadata', // Page.
				'spid_metadata_setting' // Section.
			);
			add_settings_field(
				'sp_sso', // ID.
				'SSO', // Title.
				array( $this, 'sp_sso_callback' ), // Callback.
				'spid-setting-metadata', // Page.
				'spid_metadata_setting' // Section.
			);
			add_settings_field(
				'sp_metadata_url', // ID.
				'Percorso al metadata', // Title.
				array( $this, 'sp_metadata_url_callback' ), // Callback.
				'spid-setting-metadata', // Page.
				'spid_metadata_setting' // Section.
			);

			/**
			 * SPID Button
			 */
			register_setting(
				'sp_spid_button', // Option Group.
				'sp_spid_button', // Option Name.
				array( $this, 'sanitize' ) // Sanitize.
			);
			add_settings_section(
				'sp_spid_button', // ID.
				'Configurazione SPID Button', // Title.
				array( $this, 'button_section_info' ), // Callback.
				'spid-setting-button' // Page.
			);
			add_settings_field(
				'sp_spid_button', // ID.
				'Attiva SPID Button', // Title.
				array( $this, 'sp_spid_button_callback' ), // Callback.
				'spid-setting-button', // Page.
				'sp_spid_button' // Section.
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
				'spid_certificati_setting', // ID.
				'Configurazione chiave e certificati', // Title.
				array( $this, 'certificati_section_info' ), // Callback.
				'spid-setting-certificati' // Page.
			);
			add_settings_field(
				'sp_statename', // ID.
				'Provincia (nome per esteso)', // Title.
				array( $this, 'sp_statename_callback' ), // Callback.
				'spid-setting-certificati', // Page.
				'spid_certificati_setting' // Section.
			);
			add_settings_field(
				'sp_localitnName', // ID.
				'Città (nome per esteso)', // Title.
				array( $this, 'sp_localityname_callback' ), // Callback.
				'spid-setting-certificati', // Page.
				'spid_certificati_setting' // Section.
			);
			add_settings_field(
				'sp_emailaddress', // ID.
				'Email dell\'ente o del referente tecnico', // Title.
				array( $this, 'sp_emailaddress_callback' ), // Callback.
				'spid-setting-certificati', // Page.
				'spid_certificati_setting' // Section.
			);

			/**
			 * SPID Import
			 */
			register_setting(
				'spid_import', // Option Group.
				'spid_import', // Option Name.
				array( $this, 'sanitize' ) // Sanitize.
			);

			add_settings_section(
				'setting_section_id', // ID.
				'Importazione e esportazione della configurazione', // Title.
				array( $this, 'import_section_info' ), // Callback.
				'spid-setting-import' // Page.
			);

			add_settings_field(
				'spid_import', // ID.
				array( $this, 'spid_import_callback' ), // Callback.
				'spid-setting-import', // Page.
				'setting_section_id' // Section.
			);

			/**
			 * SPID Aiuto
			 */
			register_setting(
				'spid_aiuto', // Option Group.
				'spid_aiuto', // Option Name.
				array( $this, 'sanitize' ) // Sanitize.
			);
			add_settings_section(
				'setting_section_id', // ID.
				'Aiuto', // Title.
				array( $this, 'aiuto_section_info' ), // Callback.
				'spid-setting-aiuto' // Page.
			);
			add_settings_field(
				'spid_aiuto', // ID.
				array( $this, 'spid_aiuto_callback' ), // Callback.
				'spid-setting-aiuto', // Page.
				'setting_section_id' // Section.
			);									
		}

		/**
		 * Description of each tab.
		 */
		public function general_section_info() {
			echo '<p>';
			esc_attr_e( 'Configura il plug-in e salva le impostazioni.' );
			echo '</br>';
			esc_attr_e( 'Questi parametri non modificano in nessun modo il metadata (link) quindi puoi configurare e salvare le impostazioni liberamente e non sarà necessaro ridistribuire il metadata ai vari Identity Provider.' );
		}

		public function metadata_section_info() {
			echo '<p>';
			esc_attr_e( 'Attenzione: una volta cambiati uno di questi settaggi si dovrà ridistribuire il metadata del SP a tutti gli IdP, dal _metadata link' );
			echo '</p>';
		}

		public function button_section_info() {
			echo '<p>';
			esc_attr_e( 'Configurazione dello SPID Button' );
			echo '</p>';
		}

		public function certificati_section_info() {
			echo '<p>';
			esc_attr_e( 'In questa tab puoi modificare i dati dei certificati.' );
			echo '</p>';
		}

		public function import_section_info() {
			echo '<p>';
			esc_attr_e( 'In questa sezione potrai importare o esportare l\'intera configurazione, salvare o ripristinare precedenti configurazioni. In quali casi ti puo\' essere utile questo tool:' );
			echo '</p>';
			echo '<ul>';
				echo '<li>';
					esc_attr_e( 'Se vuoi passare da un ambiente di test a uno in produzione' );
				echo '</li>';
				echo '<li>';
					esc_attr_e( 'Se necessiti di cambiare dei dati e quindi devi avviare una nuova procedura di registrazione con gli Entity Provider senza dover necessariamente disattivare l\'attuale configurazione' );
				echo '</li>';
				echo '<li>';
					esc_attr_e( 'Se il tuo è il sito di un piccolo ente e non è registrato come Service Provider. La verifica dei metadata può essere gestita da un\'ente di livello superiore (come ad esempio la regione o l\'area metropolitana).' );
				echo '</li>';
			echo '</ul>';
		}
		public function aiuto_section_info() {
			esc_attr_e( '' );
		}	

		/**
		 * General Settings
		 */
		public function idp_list_callback() {
			printf(
				'<p class="description">Seleziona quali Identity Provider utilizzare tra quelli disponibili.</p>
				<label><input type="checkbox" name="idp_list[]" value="Idp 1"> Idp 1</label><br>
				<label><input type="checkbox" name="idp_list[]" value="Idp 2"> Idp 2</label><br>
				<label><input type="checkbox" name="idp_list[]" value="Idp 3"> Idp 3</label><br>
				<label><input type="checkbox" name="idp_list[]" value="Idp 4"> Idp 4</label>',
				isset( $this->spid_options_general['idp_list'] ) ? esc_attr( $this->spid_options_general['idp_list'] ) : ''
			);
		}

		public function sp_livello_callback() {
			printf(
				'<p class="description">Seleziona quale livello di sicurezza intendi utilizzare per il login SPID<br>
				- Il primo livello permette di accedere ai servizi online attraverso un nome utente e una password scelti dall’utente.</br>
				- Il secondo livello permette l’accesso attraverso un nome utente e una password scelti dall’utente più la geerazione di un codice temporaneo di accesso (autenticazione a due fattori).</p>
				<select name="spid_general[sp_livello]" id="sp_livello" value="%s"/>
                <option selected disabled>Scegli...</option>
                <option value="1">1</option>
				<option value="2">2</option>
                </select>',
				isset( $this->spid_options_general['sp_livello'] ) ? esc_attr( $this->spid_options_general['sp_livello'] ) : ''
			);
		}

		public function sp_role_callback() {
			printf(
				'<p class="description">Scegli che ruolo assegnare di default agli utenti che si registrano sul tuo sito WordPress mediante autenticazione SPID. Una volta creato l\'utente sarà possibile per gli amministratori modificare questo dato nella sezione "utenti".</p>
				<select name="spid_general[sp_role]" id="sp_role" value="%s"/>
                <option selected disabled>Scegli...</option>
                <option value="admin">Admin</option>
				<option value="registered">Registered</option>
                </select>',
				isset( $this->spid_options_general['sp_role'] ) ? esc_attr( $this->spid_options_general['sp_role'] ) : ''
			);
		}

		public function sp_idp_callback() {
			printf(
				'<p class="description">Inserisci l\'ID del IdP di test.</p>
				<input type="text" id="sp_idp" name="spid_general[sp_idp]" value="%s" />',
				isset( $this->spid_options_general['sp_idp'] ) ? esc_attr( $this->spid_options_general['sp_idp'] ) : ''
			);
		}

		/**
		 * Metadata Settings
		 */
		public function metadata_callback() {
			esc_attr_e( 'Attenzione: una volta cambiati uno di questi settaggi si dovrà ridistribuire il metadata del SP a tutti gli IdP, dal _metadata link_.' );
		}

		public function user_attributes_callback() {
			printf(
				'<p class="description">Seleziona quali dati relativi all\'identità digitale dell\'utente ti interessa acquisire tra quelli disponibili.</p>
				<label><input type="checkbox" name="sp_name" value=""> Nome</label><br> 
				<label><input type="checkbox" name="sp_familyname" value=""> Cognome</label><br>
				<label><input type="checkbox" name="sp_placeofbirth" value=""> Luogo di nascita</label><br>
				<label><input type="checkbox" name="sp_countyofbirth" value=""> Paese di nascita</label><br>
				<label><input type="checkbox" name="sp_dateofbirth" value=""> Data di nascita</label><br>
				<label><input type="checkbox" name="sp_gender" value=""> Sesso</label><br>
				<label><input type="checkbox" name="sp_companyname" value=""> Ragione o denominazione Sociale</label><br>
				<label><input type="checkbox" name="sp_registeredOffice" value=""> Sede legale</label><br>
				<label><input type="checkbox" name="sp_fiscalnumber" value=""> Codice fiscale</label><br>
				<label><input type="checkbox" name="sp_ivacode" value=""> Partita IVA Sociale</label><br>
				<label><input type="checkbox" name="sp_idcard" value=""> Documento di identità	</label><br>
				<label><input type="checkbox" name="sp_mobilephone" value=""> Ragione o denominazione Sociale</label><br>
				<label><input type="checkbox" name="sp_address" value=""> Domicilio fisico</label><br>
				<label><input type="checkbox" name="sp_expirationdate" value=""> Data di scadenza identità</label><br>
				<label><input type="checkbox" name="sp_digitaladdress" value=""> Domicilio digitale</label><br>'
			);
		}

		public function sp_org_name_callback() {
			printf(
				'<p class="description">Inserisci il nome per esteso del proprio istituto (es. "Istituto Superiore Leonardo da Vinci" o "Comune di Torino").</p>
				<input type="text" id="sp_org_name" name="spid_metadata[sp_org_name]" value="%s" />',
				isset( $this->spid_options_metadata['sp_org_name'] ) ? esc_attr( $this->spid_options_metadata['sp_org_name'] ) : ''
			);
		}

		public function sp_org_display_name_callback() {
			printf(
				'<p class="description">Inserisci il nome del proprio ente che si vuole mostare sulla maschera di Login (es. "DaVinci PA").</p>
				<input type="text" id="sp_org_display_name" name="spid_metadata[sp_org_display_name]" value="%s" />',
				isset( $this->spid_options_metadata['sp_org_display_name'] ) ? esc_attr( $this->spid_options_metadata['sp_org_display_name'] ) : ''
			);
		}
		public function sp_sso_callback() {
			printf(
				'<p class="description">Se il tuo sito offre più sistemi di Single Sign On puoi specificare quale utilizzare (di default questo campo è "spid").</p>
				<input type="text" id="sp_sso" name="spid_metadata[sp_sso]" value="%s" />',
				isset( $this->spid_options_metadata['sp_sso'] ) ? esc_attr( $this->spid_options_metadata['sp_sso'] ) : ''
			);
		}
		public function sp_metadata_url_callback() {
			printf(
				'<p class="description">Specifa l\'url all\'endpoint</p>
				<input type="text" id="sp_metadata_url" name="spid_metadata[sp_metadata_url]" value="%s" disabled/>',
				isset( $this->spid_options_metadata['sp_metadata_url'] ) ? esc_attr( $this->spid_options_metadata['sp_metadata_url'] ) : ''
			);
		}				
		/**
		 * Button Settings
		 */
		public function sp_spid_button_callback() {
			printf(
				'<input type="checkbox" id="sp_spid_button" name="activebutton" value="%s">',
				isset( $this->spid_options_button['sp_spid_button'] ) ? esc_attr( $this->spid_options_button['sp_spid_button'] ) : ''
			);
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

			if ( isset( $input['idp_list'] ) ) {
				$new_input['idp_list'] = sanitize_text_field( $input['idp_list'] );
			}

			/**	
			 * Sanitize Metadata fields.
			 */
			if ( isset( $input['sp_org_name'] ) ) {
				$new_input['sp_org_name'] = sanitize_text_field( $input['sp_org_name'] );
			}

			if ( isset( $input['sp_sso'] ) ) {
				$new_input['sp_sso'] = sanitize_text_field( $input['sp_sso'] );
			}

			if ( isset( $input['sp_org_display_name'] ) ) {
				$new_input['sp_org_display_name'] = sanitize_text_field( $input['sp_org_display_name'] );
			}

			/**	
			 * Sanitize Button fields
			 */
			if ( isset( $input['sp_spid_button'] ) ) {
				$new_input['sp_spid_button'] = sanitize_text_field( $input['sp_spid_button'] );
			}

			/**
			 * Sanitize Certificati fields
			 */
			if ( isset( $input['sp_statename'] ) ) {
				$new_input['sp_statename'] = sanitize_text_field( $input['sp_statename'] );
			}

			if ( isset( $input['sp_localityname'] ) ) {
				$new_input['sp_localityname'] = sanitize_text_field( $input['sp_localityname'] );
			}

			if ( isset( $input['sp_emailaddress'] ) ) {
				$new_input['sp_emailaddress'] = sanitize_text_field( $input['sp_emailaddress'] );
			}

			return $new_input;
		}

		/**
		 * Certificati Settings
		 */
		public function certificati_callback() {
			esc_attr_e( 'Attenzione: una volta cambiati uno di questi settaggi si dovrà ridistribuire il metadata del SP a tutti gli IdP, dal _metadata link_.' );
		}

		public function sp_statename_callback() {
			echo '<p class="description">';
			esc_attr_e( 'Es. "Bologna"' );
			echo '</p>';
			printf(
				'<input type="text" id="sp_statename" name="spid_certificati[sp_statename]" value="%s" />',
				isset( $this->spid_options_certificati['sp_statename'] ) ? esc_attr( $this->spid_options_certificati['sp_statename'] ) : ''
			);
		}
		public function sp_localityname_callback() {
			echo '<p class="description">';
			esc_attr_e( 'Es. "Imola"' );
			echo '</p>';
			printf(
				'<input type="text" id="sp_localityname" name="spid_certificati[sp_localityname]"  value="%s" />',
				isset( $this->spid_options_certificati['sp_localityname'] ) ? esc_attr( $this->spid_options_certificati['sp_localityname'] ) : ''
			);
		}
		public function sp_emailaddress_callback() {
			echo '<p class="description">';
			esc_attr_e( 'Es. "email@comune.imola.bo.it"' );
			echo '</p>';
			printf(
				'<input type="text" id="sp_emailaddress" name="spid_certificati[sp_emailaddress]" value="%s" />',
				isset( $this->spid_options_certificati['sp_emailaddress'] ) ? esc_attr( $this->spid_options_certificati['sp_emailaddress'] ) : ''
			);
		}						
	}
}
