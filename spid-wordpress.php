<?php

/**
 * Plugin Name:       SPID WordPress
 * Plugin URI:        https://github.com/simevo/spid-wordpress
 * Description:       Autenticazione attraverso un Identity Provider SPID (Sistema Pubblico di Identità Digitale)
 * Version:           0.1.0
 * Author:            Paolo Greppi simevo s.r.l.
 * Author URI:        https://simevo.com
 * License:           AGPL-3.0
 * License URI:       https://www.gnu.org/licenses/agpl-3.0.en.html
 * GitHub Plugin URI: https://github.com/simevo/spid-wordpress

 * Text Domain: spid-wordpress
 * Domain Path: /languages
 *
 * @category Description
 * @package  SPID_WordPress
 * @author   Paolo Greppi simevo s.r.l. <paolo.greppi@libpf.com>
 * @license  GNU Affero General Public License v3.0
 * @link     https://github.com/simevo/spid-wordpress
 * @since    0.1.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Translations
 */
function spid_wordpress_textdomain() {
	load_plugin_textdomain( 'spid-wordpress', false, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'spid_wordpress_textdomain' );

/**
 * Global constants
 */
define( 'SPID_WORDPRESS_VERSION', '0.1.0' );
define( 'SPID_WORDPRESS_URL', plugin_dir_url( __FILE__ ) );
define( 'SPID_WORDPRESS_PATH', dirname( __FILE__ ) . '/' );
define( 'SPID_WORDPRESS_INC', SPID_WORDPRESS_PATH . 'includes/' );

/**
 * Load core files
 */
require_once SPID_WORDPRESS_PATH . 'includes/class-spid-core.php';
require_once SPID_WORDPRESS_PATH . 'admin/class-spid-admin.php';

/**
 * Load spid-php-lib Library
 */
require_once SPID_WORDPRESS_PATH . 'spid-php-lib/vendor/autoload.php';

SPID_Core::getInstance();

/**
 * Add Settings Link to Plugin Page
 */
function plugin_add_settings_link( $links ) {
	$settings_link = '<a href="admin.php?page=spid_opzioni">' . __( 'Settings' ) . '</a>';
	array_push( $links, $settings_link );
	return $links;
}
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'plugin_add_settings_link' );
