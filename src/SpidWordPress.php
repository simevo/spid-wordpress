<?php

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
        $settings = array(
            'base' => 'http://sp2.simevo.com',
            'keyFile' => '/srv/spid-wordpress/sp.key',
            'certFile' => '/srv/spid-wordpress/sp.crt',
            'idpMetaData' => array(
                '/srv/spid-wordpress/aruba_metadata.xml',
                '/srv/spid-wordpress/infocert_metadata.xml',
                '/srv/spid-wordpress/namirial_metadata.xml',
                '/srv/spid-wordpress/poste_metadata.xml',
                '/srv/spid-wordpress/register_metadata.xml',
                '/srv/spid-wordpress/sp2_metadata.xml', // 5
                '/srv/spid-wordpress/tim_metadata.xml',
                '/srv/spid-wordpress/intesa_metadata.xml',
                '/srv/spid-wordpress/sielte_metadata.xml'
            )
        );
        $this->auth = new SPID($settings);

        // https://codex.wordpress.org/Plugin_API/Filter_Reference/login_message
        add_filter('login_message', array( $this, 'filterLoginMessage' ));
        
        // https://codex.wordpress.org/Plugin_API/Filter_Reference/authenticate
        // after wp_authenticate_username_password runs:
        add_filter('authenticate', array( $this, 'filterAuthenticate' ), 21, 3);
    }

    public function filterLoginMessage($message)
    {
        $query_args  = array(
            'sso',
            'idp' => 5  // 0-base index in the idpMetaData array
        );
        echo '<div><a class="button" href="' .
            esc_url(add_query_arg($query_args, wp_login_url())) .
            '">Accedi con SPID usando testenv2 come IdP</a></div>';
    }

    public function filterAuthenticate($user, $username, $password)
    {

        if (isset($_GET['sso'])) {
            if (isset($_GET['idp'])) {
                $this->auth->login($_GET['idp']);
            } elseif (! empty($_POST['SAMLResponse'])) {
                $this->auth->processResponse();
                if (! $this->auth->isAuthenticated()) {
                    // TODO error handling
                }
                $attributes  = $this->auth->getAttributes();
                // TODO create WP user if missing ...
                $user = get_user_by('id', $user_id);
            }
        } else {
            // TODO
        }
        return $user;
    }

}
