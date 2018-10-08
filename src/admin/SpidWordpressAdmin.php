<?php
if (!class_exists('SpidWordpressAdmin')) {
    class SpidWordpressAdmin
    {
        public function __construct()
        {
            add_action('admin_menu', 'spid_settings_menu');

            function spid_settings_menu()
            {
                add_options_page('SPID Opzioni', 'SPID opzioni', 'manage_options', 'spid_opzioni', 'build_admin_options');
            }

            function build_admin_options()
            {
                if (!current_user_can('manage_options')) {
                    wp_die(__('Accesso limitato agli amministratori.'));
                }
                require_once dirname(__FILE__) . '/views/settings_form.php';
            }
        }
    }
}
