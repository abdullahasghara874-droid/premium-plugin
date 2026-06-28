<?php
defined( 'ABSPATH' ) || exit;

class LiveNetTV_Pro_DB {

    private $table_payments;

    public function __construct() {
        global $wpdb;
        $this->table_payments = $wpdb->prefix . 'livenettv_pro_payments';
    }

    public function create_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

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
            transaction_id varchar(255) NOT NULL DEFAULT '',
            screenshot_path varchar(500) NOT NULL DEFAULT '',
            amount_sent decimal(20,8) DEFAULT NULL,
            status enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
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
        dbDelta( $sql );

        update_option( 'livenettv_pro_db_version', LIVENETTV_PRO_VERSION );
    }

    public function insert_payment( array $data ) {
        global $wpdb;

        $data = wp_parse_args( $data, array(
            'status'       => 'pending',
            'submitted_at' => current_time( 'mysql' ),
        ) );

        $result = $wpdb->insert( $this->table_payments, $data );

        if ( false === $result ) {
            return new WP_Error( 'db_error', __( 'Failed to insert payment record.', 'livenettv-pro' ) );
        }

        return $wpdb->insert_id;
    }

    public function update_payment( $id, array $data ) {
        global $wpdb;
        return $wpdb->update( $this->table_payments, $data, array( 'id' => absint( $id ) ) );
    }

    public function get_payment( $id ) {
        global $wpdb;
        return $wpdb->get_row(
            $wpdb->prepare( "SELECT * FROM {$this->table_payments} WHERE id = %d", absint( $id ) )
        );
    }

    public function get_payments( array $args = array() ) {
        global $wpdb;

        $args = wp_parse_args( $args, array(
            'status'  => '',
            'user_id' => 0,
            'orderby' => 'submitted_at',
            'order'   => 'DESC',
            'limit'   => 20,
            'offset'  => 0,
        ) );

        $where  = 'WHERE 1=1';
        $values = array();

        if ( ! empty( $args['status'] ) ) {
            $where   .= ' AND status = %s';
            $values[] = sanitize_key( $args['status'] );
        }

        if ( ! empty( $args['user_id'] ) ) {
            $where   .= ' AND user_id = %d';
            $values[] = absint( $args['user_id'] );
        }

        $allowed_orderby = array( 'submitted_at', 'processed_at', 'plan_price', 'id' );
        $orderby  = in_array( $args['orderby'], $allowed_orderby, true ) ? $args['orderby'] : 'submitted_at';
        $order    = 'ASC' === strtoupper( $args['order'] ) ? 'ASC' : 'DESC';

        $values[] = absint( $args['limit'] );
        $values[] = absint( $args['offset'] );

        $sql = "SELECT * FROM {$this->table_payments} {$where} ORDER BY {$orderby} {$order} LIMIT %d OFFSET %d";

        return $wpdb->get_results( $wpdb->prepare( $sql, $values ) );
    }

    public function get_payments_count( $status = '' ) {
        global $wpdb;

        if ( ! empty( $status ) ) {
            return (int) $wpdb->get_var(
                $wpdb->prepare( "SELECT COUNT(*) FROM {$this->table_payments} WHERE status = %s", sanitize_key( $status ) )
            );
        }

        return (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$this->table_payments}" );
    }

    public function get_user_pending_payment( $user_id ) {
        global $wpdb;
        return $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM {$this->table_payments} WHERE user_id = %d AND status = 'pending' ORDER BY submitted_at DESC LIMIT 1",
                absint( $user_id )
            )
        );
    }

    public function get_user_last_approved_payment( $user_id ) {
        global $wpdb;
        return $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM {$this->table_payments} WHERE user_id = %d AND status = 'approved' ORDER BY processed_at DESC LIMIT 1",
                absint( $user_id )
            )
        );
    }

    public function get_table_payments() {
        return $this->table_payments;
    }
}
