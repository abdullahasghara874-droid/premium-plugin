<?php
defined( 'ABSPATH' ) || exit;

class LiveNetTV_Pro_Google_Auth {

    private $client_id;
    private $client_secret;
    private $redirect_uri;

    public function __construct() {
        $this->client_id     = get_option( 'livenettv_pro_google_client_id',     '' );
        $this->client_secret = get_option( 'livenettv_pro_google_client_secret', '' );
        $this->redirect_uri  = site_url( '/?livenettv_auth=google' );

        // Register shortcode and AJAX during init.
        add_action( 'init', array( $this, 'init' ) );
    }

    public function init() {
        // Handle OAuth callback directly here (we are already inside the 'init' hook).
        $this->maybe_handle_oauth_callback();

        add_action( 'wp_ajax_livenettv_get_google_auth_url',        array( $this, 'ajax_get_auth_url' ) );
        add_action( 'wp_ajax_nopriv_livenettv_get_google_auth_url', array( $this, 'ajax_get_auth_url' ) );
        add_shortcode( 'livenettv_login_button', array( $this, 'render_login_button' ) );
    }

    private function maybe_handle_oauth_callback() {
        if ( ! isset( $_GET['livenettv_auth'] ) || 'google' !== $_GET['livenettv_auth'] ) {
            return;
        }

        if ( empty( $_GET['code'] ) ) {
            wp_die( esc_html__( 'Authorization failed: no authorization code received.', 'livenettv-pro' ) );
        }

        $state = isset( $_GET['state'] ) ? sanitize_text_field( wp_unslash( $_GET['state'] ) ) : '';

        if ( ! $this->verify_state( $state ) ) {
            wp_die( esc_html__( 'Authorization failed: invalid state token.', 'livenettv-pro' ) );
        }

        $code   = sanitize_text_field( wp_unslash( $_GET['code'] ) );
        $tokens = $this->exchange_code( $code );

        if ( is_wp_error( $tokens ) ) {
            wp_die( esc_html( $tokens->get_error_message() ) );
        }

        $user_info = $this->fetch_user_info( $tokens['access_token'] );

        if ( is_wp_error( $user_info ) ) {
            wp_die( esc_html( $user_info->get_error_message() ) );
        }

        $user_id = $this->create_or_update_user( $user_info );

        if ( is_wp_error( $user_id ) ) {
            wp_die( esc_html( $user_id->get_error_message() ) );
        }

        wp_set_current_user( $user_id );
        wp_set_auth_cookie( $user_id, true );

        // Redirect to the destination preserved in state (or default).
        $destination = get_transient( 'livenettv_google_redirect_' . $state );
        delete_transient( 'livenettv_google_redirect_' . $state );

        if ( empty( $destination ) ) {
            $destination = get_option( 'livenettv_pro_redirect_after_login', '' );
        }

        if ( empty( $destination ) ) {
            $pro_page_id = get_option( 'livenettv_pro_pro_page_id', 0 );
            $destination = $pro_page_id ? get_permalink( $pro_page_id ) : home_url( '/' );
        }

        wp_safe_redirect( esc_url_raw( $destination ) );
        exit;
    }

    public function get_auth_url( $redirect_after = '' ) {
        $state = $this->generate_state( $redirect_after );

        $params = http_build_query( array(
            'client_id'             => $this->client_id,
            'redirect_uri'          => $this->redirect_uri,
            'response_type'         => 'code',
            'scope'                 => 'email profile',
            'access_type'           => 'online',
            'include_granted_scopes'=> 'true',
            'state'                 => $state,
        ) );

        return 'https://accounts.google.com/o/oauth2/v2/auth?' . $params;
    }

    private function generate_state( $redirect_after = '' ) {
        $state = wp_generate_password( 32, false );
        set_transient( 'livenettv_pro_oauth_state_' . $state, $state, 300 );

        if ( ! empty( $redirect_after ) ) {
            set_transient( 'livenettv_google_redirect_' . $state, esc_url_raw( $redirect_after ), 300 );
        }

        return $state;
    }

    private function verify_state( $state ) {
        if ( empty( $state ) ) {
            return false;
        }
        $stored = get_transient( 'livenettv_pro_oauth_state_' . $state );
        if ( $stored && $stored === $state ) {
            delete_transient( 'livenettv_pro_oauth_state_' . $state );
            return true;
        }
        return false;
    }

    private function exchange_code( $code ) {
        $response = wp_remote_post( 'https://oauth2.googleapis.com/token', array(
            'body'    => array(
                'code'          => $code,
                'client_id'     => $this->client_id,
                'client_secret' => $this->client_secret,
                'redirect_uri'  => $this->redirect_uri,
                'grant_type'    => 'authorization_code',
            ),
            'timeout' => 20,
        ) );

        if ( is_wp_error( $response ) ) {
            return new WP_Error( 'token_error', __( 'Failed to connect to Google.', 'livenettv-pro' ) );
        }

        $body = json_decode( wp_remote_retrieve_body( $response ), true );

        if ( isset( $body['error'] ) ) {
            return new WP_Error( 'token_error', $body['error_description'] ?? __( 'Token exchange failed.', 'livenettv-pro' ) );
        }

        return $body;
    }

    private function fetch_user_info( $access_token ) {
        $response = wp_remote_get( 'https://www.googleapis.com/oauth2/v3/userinfo', array(
            'headers' => array( 'Authorization' => 'Bearer ' . $access_token ),
            'timeout' => 20,
        ) );

        if ( is_wp_error( $response ) ) {
            return new WP_Error( 'user_info_error', __( 'Failed to retrieve user info from Google.', 'livenettv-pro' ) );
        }

        $body = json_decode( wp_remote_retrieve_body( $response ), true );

        if ( isset( $body['error'] ) ) {
            return new WP_Error( 'user_info_error', __( 'Failed to get user info.', 'livenettv-pro' ) );
        }

        return $body;
    }

    private function create_or_update_user( $info ) {
        $email     = sanitize_email( $info['email'] ?? '' );
        $google_id = sanitize_text_field( $info['sub'] ?? '' );

        if ( ! is_email( $email ) ) {
            return new WP_Error( 'invalid_email', __( 'Invalid email address from Google.', 'livenettv-pro' ) );
        }

        $existing = get_user_by( 'email', $email );

        if ( $existing ) {
            update_user_meta( $existing->ID, 'livenettv_google_id',      $google_id );
            update_user_meta( $existing->ID, 'livenettv_google_picture', esc_url_raw( $info['picture'] ?? '' ) );
            update_user_meta( $existing->ID, 'livenettv_google_name',    sanitize_text_field( $info['name'] ?? '' ) );
            return $existing->ID;
        }

        $username = $this->unique_username( $email, $info['name'] ?? '' );

        $user_id = wp_create_user( $username, wp_generate_password( 32, true, true ), $email );

        if ( is_wp_error( $user_id ) ) {
            return new WP_Error( 'user_creation', __( 'Failed to create user account.', 'livenettv-pro' ) );
        }

        wp_update_user( array(
            'ID'           => $user_id,
            'display_name' => sanitize_text_field( $info['name'] ?? $username ),
            'nickname'     => sanitize_text_field( $info['name'] ?? $username ),
            'first_name'   => sanitize_text_field( $info['given_name'] ?? '' ),
            'last_name'    => sanitize_text_field( $info['family_name'] ?? '' ),
        ) );

        update_user_meta( $user_id, 'livenettv_google_id',      $google_id );
        update_user_meta( $user_id, 'livenettv_google_picture', esc_url_raw( $info['picture'] ?? '' ) );
        update_user_meta( $user_id, 'livenettv_google_name',    sanitize_text_field( $info['name'] ?? '' ) );

        return $user_id;
    }

    private function unique_username( $email, $name ) {
        $base = sanitize_user( $name ? str_replace( ' ', '.', strtolower( $name ) ) : strstr( $email, '@', true ) );
        $base = $base ?: 'user';

        if ( ! username_exists( $base ) ) {
            return $base;
        }

        $i = 2;
        while ( username_exists( $base . $i ) ) {
            $i++;
        }

        return $base . $i;
    }

    public function ajax_get_auth_url() {
        check_ajax_referer( 'livenettv_pro_nonce', 'nonce' );

        if ( ! $this->is_configured() ) {
            wp_send_json_error( array( 'message' => __( 'Google Sign-In is not configured.', 'livenettv-pro' ) ) );
        }

        $redirect = isset( $_POST['redirect'] ) ? esc_url_raw( wp_unslash( $_POST['redirect'] ) ) : '';

        wp_send_json_success( array( 'auth_url' => $this->get_auth_url( $redirect ) ) );
    }

    public function render_login_button( $atts = array() ) {
        if ( is_user_logged_in() ) {
            return '';
        }

        $atts = shortcode_atts( array(
            'text'     => __( 'Sign in with Google', 'livenettv-pro' ),
            'class'    => '',
            'redirect' => '',
        ), $atts );

        if ( ! $this->is_configured() ) {
            return '';
        }

        $url = $this->get_auth_url( $atts['redirect'] );
        $class = 'lntv-google-btn' . ( $atts['class'] ? ' ' . esc_attr( $atts['class'] ) : '' );

        ob_start();
        ?>
        <a href="<?php echo esc_url( $url ); ?>" class="<?php echo esc_attr( $class ); ?>">
            <svg viewBox="0 0 24 24" width="20" height="20" xmlns="http://www.w3.org/2000/svg">
                <g transform="matrix(1,0,0,1,27.009155,-39.238177)">
                    <path fill="#4285F4" d="M -3.264 51.509 C -3.264 50.719 -3.334 49.969 -3.454 49.239 L -14.754 49.239 L -14.754 53.749 L -8.284 53.749 C -8.514 54.989 -9.224 56.049 -10.264 56.759 L -10.264 59.779 L -6.644 59.779 C -4.524 57.819 -3.264 54.949 -3.264 51.509 Z"/>
                    <path fill="#34A853" d="M -14.754 62.769 C -11.514 62.769 -8.794 61.789 -6.644 59.779 L -10.264 56.759 C -11.414 57.549 -12.914 58.019 -14.754 58.019 C -17.894 58.019 -20.514 56.059 -21.454 53.379 L -25.174 53.379 L -25.174 56.489 C -23.024 60.679 -18.594 62.769 -14.754 62.769 Z"/>
                    <path fill="#FBBC05" d="M -21.454 53.379 C -21.694 52.649 -21.824 51.869 -21.824 51.069 C -21.824 50.269 -21.694 49.489 -21.454 48.759 L -21.454 45.649 L -25.174 45.649 C -26.024 47.429 -26.504 49.429 -26.504 51.509 C -26.504 53.589 -26.024 55.589 -25.174 57.369 L -21.454 54.259 Z"/>
                    <path fill="#EA4335" d="M -14.754 44.099 C -12.844 44.099 -11.114 44.759 -9.754 45.999 L -6.544 42.789 C -8.794 40.689 -11.514 39.349 -14.754 39.349 C -18.594 39.349 -22.024 41.439 -23.954 44.649 L -20.334 47.759 C -19.404 45.079 -16.784 43.099 -14.754 44.099 Z"/>
                </g>
            </svg>
            <span><?php echo esc_html( $atts['text'] ); ?></span>
        </a>
        <?php
        return ob_get_clean();
    }

    public function is_configured() {
        return ! empty( $this->client_id ) && ! empty( $this->client_secret );
    }
}
