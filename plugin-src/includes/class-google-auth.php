<?php
/**
 * Google Authentication handler for LiveNetTV Pro
 */

defined('ABSPATH') || exit;

class LiveNetTV_Pro_Google_Auth {

    private $client_id;
    private $client_secret;
    private $redirect_uri;

    public function __construct() {
        $this->client_id = get_option('livenettv_pro_google_client_id', '');
        $this->client_secret = get_option('livenettv_pro_google_client_secret', '');

        add_action('init', array($this, 'init'));
    }

    public function init() {
        $this->redirect_uri = site_url('/?livenettv_auth=google');

        add_action('init', array($this, 'handle_oauth_callback'));
        add_action('wp_ajax_livenettv_google_login', array($this, 'ajax_login'));
        add_shortcode('livenettv_login_button', array($this, 'render_login_button'));
    }

    public function get_auth_url() {
        $params = http_build_query(array(
            'client_id' => $this->client_id,
            'redirect_uri' => $this->redirect_uri,
            'response_type' => 'code',
            'scope' => 'email profile',
            'access_type' => 'online',
            'include_granted_scopes' => 'true',
            'state' => $this->generate_state(),
        ));

        return 'https://accounts.google.com/o/oauth2/v2/auth?' . $params;
    }

    private function generate_state() {
        $state = wp_generate_password(32, false);
        set_transient('livenettv_pro_oauth_state_' . $state, $state, 300);
        return $state;
    }

    private function verify_state($state) {
        $stored = get_transient('livenettv_pro_oauth_state_' . $state);
        if ($stored && $stored === $state) {
            delete_transient('livenettv_pro_oauth_state_' . $state);
            return true;
        }
        return false;
    }

    public function handle_oauth_callback() {
        if (!isset($_GET['livenettv_auth']) || $_GET['livenettv_auth'] !== 'google') {
            return;
        }

        if (!isset($_GET['code'])) {
            wp_die(__('Authorization failed: No authorization code received.', 'livenettv-pro'));
        }

        $state = isset($_GET['state']) ? sanitize_text_field($_GET['state']) : '';
        if (!$this->verify_state($state)) {
            wp_die(__('Authorization failed: Invalid state token.', 'livenettv-pro'));
        }

        $code = sanitize_text_field($_GET['code']);

        $tokens = $this->exchange_code_for_tokens($code);
        if (is_wp_error($tokens)) {
            wp_die($tokens->get_error_message());
        }

        $user_info = $this->get_user_info($tokens['access_token']);
        if (is_wp_error($user_info)) {
            wp_die($user_info->get_error_message());
        }

        $user_id = $this->create_or_update_user($user_info);
        if (is_wp_error($user_id)) {
            wp_die($user_id->get_error_message());
        }

        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id, true);

        $redirect_to = get_option('livenettv_pro_redirect_after_login', get_permalink(get_option('livenettv_pro_pro_page_id', '')));

        if (!$redirect_to) {
            $redirect_to = home_url('/');
        }

        wp_safe_redirect(esc_url_raw($redirect_to));
        exit;
    }

    private function exchange_code_for_tokens($code) {
        $response = wp_remote_post('https://oauth2.googleapis.com/token', array(
            'body' => array(
                'code' => $code,
                'client_id' => $this->client_id,
                'client_secret' => $this->client_secret,
                'redirect_uri' => $this->redirect_uri,
                'grant_type' => 'authorization_code',
            ),
            'timeout' => 30,
        ));

        if (is_wp_error($response)) {
            return new WP_Error('token_error', __('Failed to connect to Google.', 'livenettv-pro'));
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);

        if (isset($body['error'])) {
            return new WP_Error('token_error', $body['error_description'] ?? __('Token exchange failed.', 'livenettv-pro'));
        }

        return $body;
    }

    private function get_user_info($access_token) {
        $response = wp_remote_get('https://www.googleapis.com/oauth2/v3/userinfo', array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $access_token,
            ),
            'timeout' => 30,
        ));

        if (is_wp_error($response)) {
            return new WP_Error('user_info_error', __('Failed to retrieve user info from Google.', 'livenettv-pro'));
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);

        if (isset($body['error'])) {
            return new WP_Error('user_info_error', $body['error_description'] ?? __('Failed to get user info.', 'livenettv-pro'));
        }

        return $body;
    }

    private function create_or_update_user($user_info) {
        $email = sanitize_email($user_info['email']);
        $google_id = sanitize_text_field($user_info['sub']);

        if (!is_email($email)) {
            return new WP_Error('invalid_email', __('Invalid email address.', 'livenettv-pro'));
        }

        $existing_user = get_user_by('email', $email);

        if ($existing_user) {
            update_user_meta($existing_user->ID, 'livenettv_google_id', $google_id);
            update_user_meta($existing_user->ID, 'livenettv_google_picture', esc_url_raw($user_info['picture'] ?? ''));
            update_user_meta($existing_user->ID, 'livenettv_google_name', sanitize_text_field($user_info['name'] ?? ''));

            return $existing_user->ID;
        }

        $username = $this->generate_username($email, $user_info['name'] ?? '');

        $user_id = wp_create_user(
            $username,
            wp_generate_password(32, true, true),
            $email
        );

        if (is_wp_error($user_id)) {
            return new WP_Error('user_creation_error', __('Failed to create user account.', 'livenettv-pro'));
        }

        update_user_meta($user_id, 'livenettv_google_id', $google_id);
        update_user_meta($user_id, 'livenettv_google_picture', esc_url_raw($user_info['picture'] ?? ''));
        update_user_meta($user_id, 'livenettv_google_name', sanitize_text_field($user_info['name'] ?? ''));
        update_user_meta($user_id, 'first_name', sanitize_text_field($user_info['given_name'] ?? ''));
        update_user_meta($user_id, 'last_name', sanitize_text_field($user_info['family_name'] ?? ''));

        wp_update_user(array(
            'ID' => $user_id,
            'display_name' => sanitize_text_field($user_info['name'] ?? $username),
            'nickname' => sanitize_text_field($user_info['name'] ?? $username),
        ));

        return $user_id;
    }

    private function generate_username($email, $name) {
        $username_from_email = sanitize_user(strstr($email, '@', true));
        $username_from_name = sanitize_user(str_replace(' ', '.', strtolower($name)));

        $preferred_username = !empty($username_from_name) ? $username_from_name : $username_from_email;

        if (!username_exists($preferred_username)) {
            return $preferred_username;
        }

        $counter = 2;
        while (username_exists($preferred_username . $counter)) {
            $counter++;
        }

        return $preferred_username . $counter;
    }

    public function ajax_login() {
        check_ajax_referer('livenettv_pro_nonce', 'nonce');

        wp_send_json_success(array(
            'auth_url' => $this->get_auth_url(),
        ));
    }

    public function render_login_button($atts = array()) {
        if (is_user_logged_in()) {
            return '';
        }

        $defaults = array(
            'text' => __('Sign in with Google', 'livenettv-pro'),
            'class' => 'livenettv-google-btn',
            'redirect' => '',
        );

        $atts = shortcode_atts($defaults, $atts);

        if (!$this->is_configured()) {
            return '<p class="livenettv-error">' . __('Google Sign-In is not configured.', 'livenettv-pro') . '</p>';
        }

        ob_start();
        ?>
        <a href="<?php echo esc_url($this->get_auth_url()); ?>" class="<?php echo esc_attr($atts['class']); ?>">
            <svg viewBox="0 0 24 24" width="24" height="24" xmlns="http://www.w3.org/2000/svg">
                <g transform="matrix(1, 0, 0, 1, 27.009155, -39.238177)">
                    <path d="M -3.264 51.509 C -3.264 50.719 -3.334 49.969 -3.454 49.239 L -14.754 49.239 L -14.754 53.749 L -8.284 53.749 C -8.514 54.989 -9.224 56.049 -10.264 56.759 L -10.264 59.779 L -6.644 59.779 C -4.524 57.819 -3.264 54.949 -3.264 51.509 Z" fill="#4285F4"/>
                    <path d="M -14.754 62.769 C -11.514 62.769 -8.794 61.789 -6.644 59.779 L -10.264 56.759 C -11.414 57.549 -12.914 58.019 -14.754 58.019 C -17.894 58.019 -20.514 56.0579 -21.454 53.379 L -25.174 53.379 L -25.174 56.489 C -23.024 60.679 -18.594 63.769 -14.754 63.769 Z" fill="#34A853"/>
                    <path d="M -21.454 53.379 C -21.694 52.649 -21.824 51.869 -21.824 51.069 C -21.824 50.269 -21.694 49.489 -21.454 48.759 L -21.454 45.649 L -25.174 45.649 C -26.024 47.429 -26.504 49.429 -26.504 51.509 C -26.504 53.589 -26.024 55.589 -25.174 57.369 L -21.454 54.259 Z" fill="#FBBC05"/>
                    <path d="M -14.754 44.099 C -12.844 44.099 -11.114 44.759 -9.754 45.999 L -6.544 42.789 C -8.794 40.689 -11.514 39.349 -14.754 39.349 C -18.594 39.349 -22.024 41.439 -23.954 44.649 L -20.334 47.759 C -19.404 45.079 -16.784 43.099 -13.634 43.099 Z" fill="#EA4335"/>
                </g>
            </svg>
            <span><?php echo esc_html($atts['text']); ?></span>
        </a>
        <?php
        return ob_get_clean();
    }

    public function is_configured() {
        return !empty($this->client_id) && !empty($this->client_secret);
    }
}
