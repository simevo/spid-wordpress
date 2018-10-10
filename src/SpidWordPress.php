<?php

require_once dirname(__FILE__) . '/admin/SpidWordpressAdmin.php';

class SpidWordPress
{

    private static $instance;

    private $auth = null;

    public static function getInstance()
    {
        if (! isset(self::$instance)) {
            self::$instance = new SpidWordPress;
            add_action('init', array( self::$instance, 'actionInit' ));
        }
        return self::$instance;
    }

    public function actionInit()
    {
        $base = get_home_url();
        $home = "wp-content/plugins/spid-wordpress/docker";

        $settings = [
            'sp_entityid' => $base,
            'sp_key_file' => "$home/wp.key",
            'sp_cert_file' => "$home/wp.crt",
            'sp_assertionconsumerservice' => [
                $base . '/wp-login.php?sso=spid'
            ],
            'sp_singlelogoutservice' => $base . '/wp-login.php?sso=spid&amp;slo',
            'sp_org_name' => 'test',
            'sp_org_display_name' => 'Test',
            'idp_metadata_folder' => "$home/idp_metadata/",
            'sp_attributeconsumingservice' => [
                ["name", "familyName", "fiscalNumber", "email"]
                ]
            ];
        $this->auth = new Italia\Spid\Sp($settings);

        // https://codex.wordpress.org/Plugin_API/Filter_Reference/login_message
        add_filter('login_message', array( $this, 'filterLoginMessage' ));
        
        // https://codex.wordpress.org/Plugin_API/Filter_Reference/authenticate
        // after wp_authenticate_username_password runs:
        add_filter('authenticate', array( $this, 'filterAuthenticate' ), 21, 3);

        $this->define_admin_hooks();
    }

    public function filterLoginMessage($message)
    {
        $sp_sso = get_option('sp_sso', 'spid');
        $sp_idp = get_option('sp_idp', 'testenv2');
        $query_args  = array(
            'sso' => $sp_sso,
            'idp' => $sp_idp
        );
        echo '<div><a class="button" href="' .
            esc_url(add_query_arg($query_args, wp_login_url())) .
            '">Accedi con SPID usando testenv2 come IdP</a></div>';
    }

    public function filterAuthenticate($user, $username, $password)
    {

        if (isset($_GET['sso']) && ($_GET['sso'] == 'spid')) {
            if (isset($_GET['metadata'])) {
                // metadata endpoint
                header('Content-type: text/xml');
                echo $this->auth->getSPMetadata();
                die;
            } elseif (isset($_GET['idp'])) {
                // SSO endpoint
                // shortname of IdP, same as the name of corresponding IdP metadata file, without .xml
                $idpName = $_GET['idp'];
                // index of assertion consumer service as per the SP metadata
                $assertId = 0;
                // index of attribute consuming service as per the SP metadata
                $attrId = 0;
                // SPID level (1, 2 or 3)
                $spidLevel = 1;
                // return url, optional
                $returnTo = null;
                $this->auth->login($idpName, $assertId, $attrId, $spidLevel, $returnTo);
            } elseif (isset($_GET['slo'])) {
                // SLO endpoint
                // TODO
            } elseif (! empty($_POST['SAMLResponse'])) {
                // assertion consuming service endpoint
                if (! $this->auth->isAuthenticated()) {
                    // TODO error handling
                    error_log('not authenticated');
                }
                $attributes  = $this->auth->getAttributes();
                foreach ($attributes as $key => $attr) {
                    echo $key . ' - ' . $attr . '<br>';
                }
                // TODO create WP user if missing ...
                $user = get_user_by('id', $user_id);
            } else {
                // TODO error handling
                error_log('wrong endpoint');
            }
        } else {
            // ignore
        }
        return $user;
    }

    private function define_admin_hooks() {
        $plugin_admin = new SpidWordpressAdmin();   
    }
}
