<?php
/**
 * Plugin Name: LiveNetTV Pro Membership
 * Plugin URI: https://livenettv.tools
 * Description: Ad-Free Premium Membership system for LiveNetTV.tools with crypto payments
 * Version: 1.0.0
 * Author: LiveNetTV
 * Author URI: https://livenettv.tools
 * License: GPL v2 or later
 * Text Domain: livenettv-pro
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 */

defined('ABSPATH') || exit;

define('LIVENETTV_PRO_VERSION', '1.0.0');
define('LIVENETTV_PRO_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('LIVENETTV_PRO_PLUGIN_URL', plugin_dir_url(__FILE__));
define('LIVENETTV_PRO_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Main plugin class
 */
class LiveNetTV_Pro {

    private static $instance = null;

    private $db;
    private $google_auth;
    private $admin;
    private $membership;
    private $emails;
    private $cron;
    private $frontend;
    private $ad_remover;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->load_dependencies();
        $this->init_hooks();
    }

    private function load_dependencies() {
        require_once LIVENETTV_PRO_PLUGIN_DIR . 'includes/class-db.php';
        require_once LIVENETTV_PRO_PLUGIN_DIR . 'includes/class-google-auth.php';
        require_once LIVENETTV_PRO_PLUGIN_DIR . 'includes/class-membership.php';
        require_once LIVENETTV_PRO_PLUGIN_DIR . 'includes/class-emails.php';
        require_once LIVENETTV_PRO_PLUGIN_DIR . 'includes/class-cron.php';
        require_once LIVENETTV_PRO_PLUGIN_DIR . 'includes/class-ad-remover.php';
        require_once LIVENETTV_PRO_PLUGIN_DIR . 'includes/class-frontend.php';
        require_once LIVENETTV_PRO_PLUGIN_DIR . 'admin/class-admin.php';
        require_once LIVENETTV_PRO_PLUGIN_DIR . 'includes/class-payment-processor.php';
    }

    private function init_hooks() {
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));

        add_action('plugins_loaded', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        add_action('init', array($this, 'load_textdomain'));
    }

    public function init() {
        $this->db = new LiveNetTV_Pro_DB();
        $this->google_auth = new LiveNetTV_Pro_Google_Auth();
        $this->membership = new LiveNetTV_Pro_Membership();
        $this->emails = new LiveNetTV_Pro_Emails();
        $this->cron = new LiveNetTV_Pro_Cron();
        $this->ad_remover = new LiveNetTV_Pro_Ad_Remover();
        $this->frontend = new LiveNetTV_Pro_Frontend();
        $this->admin = new LiveNetTV_Pro_Admin();

        if (is_admin()) {
            $this->admin->init();
        }
    }

    public function activate() {
        require_once LIVENETTV_PRO_PLUGIN_DIR . 'includes/class-db.php';

        $db = new LiveNetTV_Pro_DB();
        $db->create_tables();

        $cron = new LiveNetTV_Pro_Cron();
        $cron->schedule_events();

        flush_rewrite_rules();
    }

    public function deactivate() {
        $cron = new LiveNetTV_Pro_Cron();
        $cron->clear_scheduled_events();

        flush_rewrite_rules();
    }

    public function enqueue_scripts() {
        wp_enqueue_style(
            'livenettv-pro-frontend',
            LIVENETTV_PRO_PLUGIN_URL . 'assets/css/frontend.css',
            array(),
            LIVENETTV_PRO_VERSION
        );

        wp_enqueue_script(
            'livenettv-pro-frontend',
            LIVENETTV_PRO_PLUGIN_URL . 'assets/js/frontend.js',
            array('jquery'),
            LIVENETTV_PRO_VERSION,
            true
        );

        wp_localize_script('livenettv-pro-frontend', 'livenettvPro', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('livenettv_pro_nonce'),
            'isLoggedIn' => is_user_logged_in(),
            'isPro' => $this->membership->is_pro_user(get_current_user_id()),
            'i18n' => array(
                'copied' => __('Copied to clipboard!', 'livenettv-pro'),
                'error' => __('An error occurred. Please try again.', 'livenettv-pro'),
            ),
        ));
    }

    public function admin_enqueue_scripts($hook) {
        if (strpos($hook, 'livenettv-pro') === false) {
            return;
        }

        wp_enqueue_style(
            'livenettv-pro-admin',
            LIVENETTV_PRO_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            LIVENETTV_PRO_VERSION
        );

        wp_enqueue_script(
            'livenettv-pro-admin',
            LIVENETTV_PRO_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery'),
            LIVENETTV_PRO_VERSION,
            true
        );

        wp_localize_script('livenettv-pro-admin', 'livenettvProAdmin', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('livenettv_pro_admin_nonce'),
            'i18n' => array(
                'confirmApprove' => __('Approve this payment and activate pro membership?', 'livenettv-pro'),
                'confirmReject' => __('Reject this payment? User will be notified.', 'livenettv-pro'),
            ),
        ));
    }

    public function load_textdomain() {
        load_plugin_textdomain(
            'livenettv-pro',
            false,
            dirname(LIVENETTV_PRO_PLUGIN_BASENAME) . '/languages'
        );
    }

    public function get_db() {
        return $this->db;
    }

    public function get_membership() {
        return $this->membership;
    }

    public function get_emails() {
        return $this->emails;
    }
}

function livenettv_pro() {
    return LiveNetTV_Pro::get_instance();
}

livenettv_pro();
