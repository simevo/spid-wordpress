<?php
if (!class_exists('SPID_Admin')) {
    class SPID_Admin
    {
        private $spid_options_general;
        private $spid_options_button;
        private $spid_options_help;

        public function __construct()
        {
            add_action('admin_menu', array($this, 'spid_settings_menu'));
            add_action('admin_init', array($this, 'spid_options_init'));
        }

        public function spid_settings_menu()
        {
            add_menu_page('SPID Opzioni', 'SPID Opzioni', 'manage_options', 'spid_opzioni', array($this, 'spid_admin_options'));
        }

        function spid_admin_options()
        {
            $this->spid_options_general = get_option('spid_general');
            $this->spid_options_button = get_option('spid_button');
            $this->spid_options_help = get_option('spid_help');

            $button_Screen = (isset($_GET['action']) && 'button' == $_GET['action']) ? true : false;
            $help_Screen = (isset($_GET['action']) && 'help' == $_GET['action']) ? true : false;

            ?>
            <div class="wrap">
            <h1>SPID Options</h1>
            <h2 class="nav-tab-wrapper">
                <a 
                    href="<?php echo admin_url('admin.php?page=spid_opzioni'); ?>"
                    class="nav-tab<?php if (!isset($_GET['action']) || isset($_GET['action']) && 'button' != $_GET['action'] && 'help' != $_GET['action']) echo ' nav-tab-active'; ?>">
                    <?php esc_html_e('General'); ?>
                </a>
                <a 
                    href="<?php echo esc_url(add_query_arg(array('action' => 'button'), admin_url('admin.php?page=spid_opzioni'))); ?>" 
                    class="nav-tab<?php if ($button_Screen) echo ' nav-tab-active'; ?>">
                    <?php esc_html_e('SPID Button'); ?></a> 
                <a 
                    href="<?php echo esc_url(add_query_arg(array('action' => 'help'), admin_url('admin.php?page=spid_opzioni'))); ?>" 
                    class="nav-tab<?php if ($help_Screen) echo ' nav-tab-active'; ?>">
                    <?php esc_html_e('Help'); ?>
                </a>        
			</h2>
    
        	<form method="post" action="options.php">
            <?php
            if ($button_Screen) {
                settings_fields('spid_button');
                do_settings_sections('spid-setting-button');
                submit_button();
            } elseif ($help_Screen) {
                settings_fields('spid_help');
                do_settings_sections('spid-setting-help');
                submit_button();
            } else {
                settings_fields('spid_general');
                do_settings_sections('spid-setting-admin');
                submit_button();
            } ?>
            </form>
            </div>
            <?php

        }

        public function spid_options_init()
        {
            /**
             * General Settings
             */
            register_setting(
                'spid_general', // Option group
                'spid_general', // Option name
                array($this, 'sanitize') // Sanitize
            );

            add_settings_section(
                'spid_settings_section', // ID
                'Options', // Title
                array($this, 'print_section_info'), // Callback
                'spid-setting-admin' // Page
            );

            add_settings_field(
                'sp_org_name', // ID
                'Name of Service Provider', // Title
                array($this, 'sp_org_name_callback'), // Callback
                'spid-setting-admin', // Page
                'spid_settings_section' // Section
            );

            add_settings_field(
                'sp_sso', // ID
                'SSO', // Title
                array($this, 'sp_sso_callback'), // Callback
                'spid-setting-admin', // Page
                'spid_settings_section' // Section
            );

            /**
             * SPID Button Settings
             */
            register_setting(
                'spid_button', // Option group
                'spid_button', // Option name
                array($this, 'sanitize') // Sanitize
            );

            add_settings_section(
                'spid_button_setting', // ID
                'Button Settings', // Title
                array($this, 'print_section_info'), // Callback
                'spid-setting-button' // Page
            );

            add_settings_field(
                'spid_button', // ID
                'SPID Button Layout', // Title 
                array($this, 'spid_button_callback'), // Callback
                'spid-setting-button', // Page
                'spid_button_setting' // Section           
            );

            /**
             * Help Screen
             */
            register_setting(
                'spid_help', // Option group
                'spid_help', // Option name
                array($this, 'sanitize') // Sanitize
            );

            add_settings_section(
                'setting_section_id', // ID
                'Help', // Title
                array($this, 'print_section_info'), // Callback
                'spid-setting-help' // Page
            );

            add_settings_field(
                'spid_help',
                'Help',
                array($this, 'spid_help_callback'),
                'spid-setting-help',
                'setting_section_id'
            );
        }


        public function print_section_info()
        {
            echo 'Info here.';
        }

        public function sp_org_name_callback()
        {
            printf(
                '<input type="text" id="sp_org_name" name="spid_general[sp_org_name]" value="%s" />',
                isset($this->spid_options_general['sp_org_name']) ? esc_attr($this->spid_options_general['sp_org_name']) : ''
            );
        }

        public function sp_sso_callback()
        {
            printf(
                '<input type="text" id="sp_sso" name="spid_general[sp_sso]" value="%s" />',
                isset($this->spid_options_general['sp_sso']) ? esc_attr($this->spid_options_general['sp_sso']) : ''
            );
        }

        public function spid_help_callback()
        {
            echo 'Info here.';
        }

        public function spid_button_callback()
        {
            echo 'Info here.';
        }

        public function sanitize($input)
        {
            $new_input = array();
            if (isset($input['sp_org_name']))
                $new_input['sp_org_name'] = sanitize_text_field($input['sp_org_name']);

            if (isset($input['sp_sso']))
                $new_input['sp_sso'] = sanitize_text_field($input['sp_sso']);

            return $new_input;
        }
    }
}