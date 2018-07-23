<?php
// {{ ansible_managed }}
define('DB_NAME', 'wp');
define('DB_USER', 'wp');
define('DB_PASSWORD', '{{ lookup('password', 'credentials/wp_mysql length=32') }}');
define('DB_HOST', 'localhost');
define('WP_CONTENT_DIR', '/var/lib/wordpress/wp-content');
{{ salt.content }}
?>