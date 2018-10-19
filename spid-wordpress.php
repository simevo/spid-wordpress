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

session_start();

// Load plugin
require_once 'src/SpidWordPress.php';

SpidWordPress::getInstance();
