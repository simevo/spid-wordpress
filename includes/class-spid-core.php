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

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class SPID_Core {

	private static $instance;
	private $auth = null;
	public $options;
	public $idp_files_in_metadata_folder;

	public static function getInstance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new SPID_Core();
			add_action( 'init', array( self::$instance, 'actionInit' ) );
		}
		return self::$instance;
	}

	public function actionInit() {
		$base = get_home_url();

		// Default path to key and crt file and idp_metadata folder
		$home = ABSPATH . 'spid-conf';

		// The following are mandatory attributes for any WordPress install of the plugin.
		update_option( 'sp_spidCode', 'on' );
		update_option( 'sp_email', 'on' );

		$this->options = $this->getOptions();

		$sp_attributeconsumingservice = $this->findActiveAttributes( $this->options );

		$settings   = [
			'sp_entityid'                  => $base,
			'sp_key_file'                  => "$home/wp.key",
			'sp_cert_file'                 => "$home/wp.crt",
			'sp_assertionconsumerservice'  => [
				$base . '/wp-login.php?sso=spid',
			],
			'sp_singlelogoutservice'       => [ [ $base . '/wp-login.php?sso=spid&amp;slo', '' ] ],
			'sp_org_name'                  => $this->options['sp_org_name'] ?: "myservice",
			'sp_org_display_name'          => $this->options['sp_org_display_name'] ?: "My Service",
			'idp_metadata_folder'          => "$home/idp_metadata/",
			'sp_attributeconsumingservice' => [ $sp_attributeconsumingservice ],
			'sp_key_cert_values'           => [
				'countryName'         => 'IT',
				'stateOrProvinceName' => $this->options['sp_stateName'] ?: "Roma",
				'localityName'        => $this->options['sp_localityName'] ?: 'Ostia',
				'commonName'          => parse_url( get_home_url(), PHP_URL_HOST ),
				'emailAddress'        => $this->options['sp_emailAddress'] ?: 'test@example.com',
			],
		];
		$this->auth = new Italia\Spid\Sp( $settings );

		// https://codex.wordpress.org/Plugin_API/Filter_Reference/login_message
		add_filter( 'login_message', array( $this, 'filterLoginMessage' ) );

		// https://codex.wordpress.org/Plugin_API/Filter_Reference/authenticate
		// After wp_authenticate_username_password runs.
		add_filter( 'authenticate', array( $this, 'filterAuthenticate' ), 21, 3 );

		$this->define_admin_hooks();

		$this->spid_enqueue_scripts();

	}

	public function filterLoginMessage( $message ) {
		$options = $this->options;

		$query_args = array(
			'sso' => $options['sp_sso'],
			'idp' => $options['sp_idp'],
		);
		$mapping = $this->auth->getIdpList();
		include_once SPID_WORDPRESS_PATH . 'templates/spid-button.php';
	}

	public function filterAuthenticate( $user, $username, $password ) {
		if ( isset( $_GET['sso'] ) && ( $_GET['sso'] == 'spid' ) ) {
			if ( isset( $_GET['metadata'] ) ) {
				// Metadata endpoint.
				header( 'Content-type: text/xml' );
				$smpmetadata = $this->auth->getSPMetadata();
				echo esc_attr( $smpmetadata );
				die;
			} elseif ( isset( $_GET['idp'] ) ) {
				// SSO endpoint
				// shortname of IdP, same as the name of corresponding IdP metadata file, without .xml
				$idpName = $_GET['idp'];
				// index of assertion consumer service as per the SP metadata.
				$assertId = 0;
				// index of attribute consuming service as per the SP metadata.
				$attrId = 0;
				// SPID level (1, 2 or 3).
				$spidLevel = get_option( 'sp_livello', '1' );
				// return url, optional.
				$returnTo = null;
				$this->auth->login( $idpName, $assertId, $attrId, $spidLevel, $returnTo );
			} elseif ( isset( $_GET['slo'] ) ) {
				// SLO endpoint
				// TODO
			} elseif ( ! empty( $_POST['SAMLResponse'] ) ) {
				// assertion consuming service endpoint
				if ( ! $this->auth->isAuthenticated() ) {
					return new WP_Error( 'spid_wordpress_not_authenticated', 'Qualcosa non ha funzionato nel login SPID.' );
				}
				$attributes = $this->auth->getAttributes();

				if ( empty( $attributes ) ) {
					return new WP_Error( 'spid_wordpress_no_attributes', 'Non ho ricevuto i dati necessari da SPID.' );
				}
				$existing_user = get_user_by( 'user_login', $attributes['spidCode'] );
				if ( $existing_user ) {
					do_action( 'spid_wordpress_existing_user_authenticated', $existing_user, $attributes );
					return $existing_user;
				}
				$user_args = array();
				// assumes that name and familyName attributes are requested to IdP !
				$user_args['user_login'] = ! empty( $attributes['spidCode'] ) ? $attributes['spidCode'] : '';
				$user_args['first_name'] = ! empty( $attributes['name'] ) ? $attributes['name'] : '';
				$user_args['last_name']  = ! empty( $attributes['familyName'] ) ? $attributes['familyName'] : '';
				$user_args['user_email'] = ! empty( $attributes['email'] ) ? $attributes['email'] : '';
				$user_args['role']       = get_option( 'sp_role', get_option( 'default_role' ) );
				$user_args['user_pass']  = wp_generate_password(); // ??

				$user_args = apply_filters( 'spid_wordpress_insert_user', $user_args, $attributes );
				// https://developer.wordpress.org/reference/functions/wp_insert_user/
				$user_id = wp_insert_user( $user_args );
				if ( is_wp_error( $user_id ) ) {
					return $user_id;
				}
				$user = get_user_by( 'id', $user_id );

			} else {
				return new WP_Error( 'spid_wrong_endpoint', 'Wrong endpoint' );
			}
		} else {
			// Ignore.
		}
		do_action( 'spid_wordress_new_user_authenticated', $user, $attributes );
		return $user;
	}

	private function define_admin_hooks() {
		$plugin_admin = new SPID_Admin();
	}

	public function getOptions() {
		$options['sp_org_name']                          = get_option( 'sp_org_name' );
		$options['sp_org_display_name']                  = get_option( 'sp_org_display_name' );
		$options['sp_sso']                               = get_option( 'sp_sso', 'spid' );
		$options['sp_idp']                               = get_option( 'sp_idp', 'testenv2' );
		$options['sp_livello']                           = get_option( 'sp_livello' );
		$options['sp_attributes']['sp_spidCode']         = get_option( 'sp_spidCode' );
		$options['sp_attributes']['sp_name']             = get_option( 'sp_name' );
		$options['sp_attributes']['sp_familyName']       = get_option( 'sp_familyName' );
		$options['sp_attributes']['sp_placeOfBirth']     = get_option( 'sp_placeOfBirth' );
		$options['sp_attributes']['sp_countyOfBirth']    = get_option( 'sp_countyOfBirth' );
		$options['sp_attributes']['sp_dateOfBirth']      = get_option( 'sp_dateOfBirth' );
		$options['sp_attributes']['sp_gender']           = get_option( 'sp_gender' );
		$options['sp_attributes']['sp_companyName']      = get_option( 'sp_companyName' );
		$options['sp_attributes']['sp_registeredOffice'] = get_option( 'sp_registeredOffice' );
		$options['sp_attributes']['sp_fiscalNumber']     = get_option( 'sp_fiscalNumber' );
		$options['sp_attributes']['sp_ivaCode']          = get_option( 'sp_ivaCode' );
		$options['sp_attributes']['sp_idCard']           = get_option( 'sp_idCard' );
		$options['sp_attributes']['sp_mobilePhone']      = get_option( 'sp_mobilePhone' );
		$options['sp_attributes']['sp_email']            = get_option( 'sp_email' );
		$options['sp_attributes']['sp_address']          = get_option( 'sp_address' );
		$options['sp_attributes']['sp_expirationDate']   = get_option( 'sp_expirationDate' );
		$options['sp_attributes']['sp_digitalAddress']   = get_option( 'sp_digitalAddress' );

		return $options;
	}

	public function findActiveAttributes( $options ) {
		$active_attributes = [];
		foreach ( $options['sp_attributes'] as $key => $value ) {
			if ( $value == 'on' ) {
				$active_attributes[] = substr( $key, 3 );
			}
		}
		return $active_attributes;
	}

	/**
	 * Enqueue scripts and CSS. Loads in login and wp-admin only.
	 *
	 * @return void
	 */
	public function spid_enqueue_scripts() {

		function enqueue_login_script() {
			wp_enqueue_script( 'spid-smart-button-script', 'https://italia.github.io/spid-smart-button/spid-button.min.js', true, '1.0.0' );
		}
		add_action( 'login_enqueue_scripts', 'enqueue_login_script', 10 );

		function load_spid_css() {
			wp_register_style( 'custom_wp_admin_css', SPID_WORDPRESS_URL . 'public/css/style.css', true, '1.0.0' );
			wp_enqueue_style( 'custom_wp_admin_css' );
			wp_enqueue_style( 'spid_smart_button', 'https://italia.github.io/spid-smart-button/spid-button.min.css', true, '1.0.0' );
		}
		add_action( 'admin_enqueue_scripts', 'load_spid_css', 10 );
		add_action( 'login_enqueue_scripts', 'load_spid_css', 10 );
	}

}
