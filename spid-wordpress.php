<?php

/**
 * Plugin Name:       SPID WordPress
 * Plugin URI:        https://github.com/simevo/spid-wordpress
 * Description:       Autenticazione attraverso un Identity Provider SPID (Sistema Pubblico di Identità Digitale)
 * Version:           1.0.0
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
 * @author   Paolo Greppi simevo s.r.l. <email@emailgoeshere.com>
 * @license  GNU Affero General Public License v3.0
 * @link     https://github.com/simevo/spid-wordpress
 * @since    1.0.0
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Current version.
 * Use SemVer - https://semver.org
 */
define('SPID_WORDPRESS_VERSION', '1.0.0');

/**
 * Plugin Directory
 */
$spid_path = plugin_dir_path(__FILE__);

/**
 * Load core files
 */
require_once $spid_path . 'includes/class-spid-core.php';
require_once $spid_path . 'admin/class-spid-admin.php';

/**
 * Load spid-php-lib Library
 */
require_once $spid_path . 'spid-php-lib/src/Spid/Interfaces/IdpInterface.php';
require_once $spid_path . 'spid-php-lib/src/Spid/Interfaces/RequestInterface.php';
require_once $spid_path . 'spid-php-lib/src/Spid/Interfaces/ResponseInterface.php';
require_once $spid_path . 'spid-php-lib/src/Spid/Interfaces/SAMLInterface.php';

require_once $spid_path . 'spid-php-lib/src/Spid/Saml/Idp.php';
require_once $spid_path . 'spid-php-lib/src/Spid/Saml/Settings.php';
require_once $spid_path . 'spid-php-lib/src/Spid/Saml/SignatureUtils.php';

require_once $spid_path . 'spid-php-lib/src/Spid/Saml/In/BaseResponse.php';
require_once $spid_path . 'spid-php-lib/src/Spid/Saml/In/LogoutRequest.php';
require_once $spid_path . 'spid-php-lib/src/Spid/Saml/In/LogoutResponse.php';
require_once $spid_path . 'spid-php-lib/src/Spid/Saml/In/Response.php';

require_once $spid_path . 'spid-php-lib/src/Spid/Saml/Out/Base.php';
require_once $spid_path . 'spid-php-lib/src/Spid/Saml/Out/AuthnRequest.php';
require_once $spid_path . 'spid-php-lib/src/Spid/Saml/Out/LogoutRequest.php';
require_once $spid_path . 'spid-php-lib/src/Spid/Saml/Out/LogoutResponse.php';


require_once $spid_path . 'spid-php-lib/src/Spid/Saml.php';
require_once $spid_path . 'spid-php-lib/src/Spid/Session.php';

require_once $spid_path . 'spid-php-lib/src/Sp.php';

SPID_Core::getInstance();
