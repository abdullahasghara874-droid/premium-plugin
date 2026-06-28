<?php
/**
 * Database handler for LiveNetTV Pro
 */

defined('ABSPATH') || exit;

class LiveNetTV_Pro_DB {

    private $table_payments;
    private $wpdb;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_payments = $wpdb->prefix . 'livenettv_pro_payments';
    }

    public function create_tables() {
        $charset_collate = $this->wpdb->get_charset_collate();

        $sql = "CREATE TABLE {$this->table_payments} (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            user_id bigint(20) unsigned NOT NULL,
            plan_slug varchar(50) NOT NULL,
            plan_name varchar(100) NOT NULL,
            plan_duration int(11) NOT NULL,
            plan_price decimal(10,2) NOT NULL,
            currency varchar(10) NOT NULL DEFAULT 'USD',
            crypto_type varchar(20) NOT NULL,
            wallet_address varchar(255) NOT NULL,
            transaction_id varchar(255) DEFAULT '',
            screenshot_path varchar(255) NOT NULL,
            amount_sent decimal(20,8) DEFAULT NULL,
            status enum('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
            submitted_at datetime NOT NULL,
            processed_at datetime DEFAULT NULL,
            processed_by bigint(20) unsigned DEFAULT NULL,
            membership_start date DEFAULT NULL,
            membership_end date DEFAULT NULL,
            notes text DEFAULT NULL,
            PRIMARY KEY (id),
            KEY user_id (user_id),
            KEY status (status),
            KEY submitted_at (submitted_at),
            KEY transaction_id (transaction_id(191))
        ) {$charset_collate};";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);

        update_option('livenettv_pro_db_version', LIVENETTV_PRO_VERSION);
    }

    public function insert_payment($data) {
        $defaults = array(
            'status' => 'pending',
            'submitted_at' => current_time('mysql'),
        );

        $data = wp_parse_args($data, $defaults);

        $result = $this->wpdb->insert(
            $this->table_payments,
            $data,
            array(
                '%d', '%s', '%s', '%d', '%f', '%s', '%s', '%s', '%s', '%s', '%f',
                '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s'
            )
        );

        if (false === $result) {
            return new WP_Error('db_error', __('Failed to insert payment record.', 'livenettv-pro'));
        }

        return $this->wpdb->insert_id;
    }

    public function update_payment($id, $data) {
        return $this->wpdb->update(
            $this->table_payments,
            $data,
            array('id' => $id),
            null,
            array('%d')
        );
    }

    public function get_payment($id) {
        return $this->wpdb->get_row(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->table_payments} WHERE id = %d",
                $id
            )
        );
    }

    public function get_payments($args = array()) {
        $defaults = array(
            'status' => '',
            'user_id' => 0,
            'orderby' => 'submitted_at',
            'order' => 'DESC',
            'limit' => 20,
            'offset' => 0,
        );

        $args = wp_parse_args($args, $defaults);

        $where = 'WHERE 1=1';
        $values = array();

        if (!empty($args['status'])) {
            $where .= ' AND status = %s';
            $values[] = $args['status'];
        }

        if (!empty($args['user_id'])) {
            $where .= ' AND user_id = %d';
            $values[] = $args['user_id'];
        }

        $orderby = sanitize_sql_orderby("{$args['orderby']} {$args['order']}");
        if (!$orderby) {
            $orderby = 'submitted_at DESC';
        }

        $sql = "SELECT * FROM {$this->table_payments} {$where} ORDER BY {$orderby} LIMIT %d OFFSET %d";
        $values[] = $args['limit'];
        $values[] = $args['offset'];

        if (!empty($values)) {
            $sql = $this->wpdb->prepare($sql, $values);
        }

        return $this->wpdb->get_results($sql);
    }

    public function get_payments_count($status = '') {
        $where = 'WHERE 1=1';
        $values = array();

        if (!empty($status)) {
            $where .= ' AND status = %s';
            $values[] = $status;
        }

        $sql = "SELECT COUNT(*) FROM {$this->table_payments} {$where}";

        if (!empty($values)) {
            $sql = $this->wpdb->prepare($sql, $values);
        }

        return (int) $this->wpdb->get_var($sql);
    }

    public function get_user_pending_payment($user_id) {
        return $this->wpdb->get_row(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->table_payments} WHERE user_id = %d AND status = %s ORDER BY submitted_at DESC LIMIT 1",
                $user_id,
                'pending'
            )
        );
    }

    public function get_user_last_approved_payment($user_id) {
        return $this->wpdb->get_row(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->table_payments} WHERE user_id = %d AND status = %s ORDER BY processed_at DESC LIMIT 1",
                $user_id,
                'approved'
            )
        );
    }

    public function get_table_payments() {
        return $this->table_payments;
    }
}
