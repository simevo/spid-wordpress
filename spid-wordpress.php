<?php
/**
 * Plugin Name: spid-wordpress
 * Plugin URI: https://github.com/simevo/spid-wordpress
 * Description: Autenticazione attraverso un Identity Provider SPID (Sistema Pubblico di Identità Digitale)
 * Version: 0.0.1
 * Author: Paolo Greppi simevo s.r.l.
 * Author URI: https://simevo.com
 * GitHub Plugin URI: https://github.com/simevo/spid-wordpress
 */

session_start();

require_once("vendor/autoload.php");
require_once dirname(__FILE__) . '/src/SpidWordPress.php';

SpidWordPress::getInstance();
