<?php
class SpidWordPress
{

    private static $instance;

    private $provider = null;

    public static function getInstance()
    {
        if (! isset(self::$instance)) {
            self::$instance = new WP_SAML_Auth;
            add_action('init', array( self::$instance, 'actionInit' ));
        }
        return self::$instance;
    }

    public function actionInit()
    {

        $connection_type = self::get_option('connection_type');
        $this->provider = new OneLogin_Saml2_Auth($auth_config);
        add_filter('login_message', array( $this, 'filterLoginMessage' ));
        // after wp_authenticate_username_password runs:
        add_filter('authenticate', array( $this, 'filterAuthenticate' ), 21, 3);
    }

    public function filterLoginMessage($message)
    {
        echo '<div><a class="button" href="' .
            esc_url(add_query_arg($query_args, wp_login_url())) .
            '">Accedi con SPID</a></div>';
    }

    public function filterAuthenticate($user, $username, $password)
    {

        if (! empty($_POST['SAMLResponse'])) {
            $user = $this->do_saml_authentication();
        } elseif (( empty($_GET['loggedout']) ) || ( ! empty($_GET['action']) && 'wp-saml-auth' === $_GET['action'] )) {
            $user = $this->do_saml_authentication();
        }
        return $user;
    }

    public function doSpidAuthentication()
    {

        if (! empty($_POST['SAMLResponse'])) {
            $this->provider->processResponse();
            if (! $this->provider->isAuthenticated()) {
            // Translators: Includes error reason from OneLogin.
                return new WP_Error(
                    'wp_saml_auth_unauthenticated',
                    sprintf(
                        __('User is not authenticated with SAML IdP. Reason: %s', 'wp-saml-auth'),
                        $this->provider->getLastErrorReason()
                    )
                );
            }
            $attributes  = $this->provider->getAttributes();
        } else {
            $this->provider->login();
        }

        $user = get_user_by('id', $user_id);

        return $user;
    }
}
