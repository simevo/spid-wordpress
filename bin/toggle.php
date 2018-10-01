#!/usr/bin/php
<?php
// toggles the spid-wordpress plugin on and off
//
// usage:
//   cd /usr/share/wordpress # cd to your wordpress home
//   php toggle.php
//
// Copyright (c) 2018, Paolo Greppi <paolo.greppi@simevo.com>
// License: AGPL-3.0
 
require_once('wp-load.php');
require_once('wp-admin/includes/plugin.php');

// var_dump(get_plugins());
$my_plugin = 'spid-wordpress/spid-wordpress.php';
if (is_plugin_active($my_plugin)) {
    echo 'deactivating ' . $my_plugin;
    deactivate_plugins($my_plugin);
} else {
    echo 'activating ' . $my_plugin;
    activate_plugin($my_plugin);
}
