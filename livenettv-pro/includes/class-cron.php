<?php
defined( 'ABSPATH' ) || exit;

class LiveNetTV_Pro_Cron {

    public function __construct() {
        add_action( 'init', array( $this, 'init' ) );
    }

    public function init() {
        // Schedule cron jobs.
        add_action( 'wp', array( $this, 'schedule_events' ) );

        // Hook our callbacks.
        add_action( 'livenettv_pro_daily_check', array( $this, 'check_expired_memberships' ) );
        add_action( 'livenettv_pro_hourly_check', array( $this, 'send_expiry_warnings' ) );

        // Deactivation cleanup is handled in main plugin file.
    }

    public function schedule_events() {
        if ( ! wp_next_scheduled( 'livenettv_pro_daily_check' ) ) {
            wp_schedule_event( time(), 'daily', 'livenettv_pro_daily_check' );
        }

        if ( ! wp_next_scheduled( 'livenettv_pro_hourly_check' ) ) {
            wp_schedule_event( time(), 'hourly', 'livenettv_pro_hourly_check' );
        }
    }

    public function check_expired_memberships() {
        $membership = livenettv_pro()->get_membership();
        $emails     = livenettv_pro()->get_emails();

        // Get all users with expired but still marked as active membership.
        $expired_users = get_users( array(
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key'     => 'livenettv_pro_status',
                    'value'   => 'active',
                    'compare' => '=',
                ),
                array(
                    'key'     => 'livenettv_pro_plan',
                    'value'   => 'lifetime',
                    'compare' => '!=',
                ),
                array(
                    'key'     => 'livenettv_pro_expiry_date',
                    'value'   => current_time( 'Y-m-d' ),
                    'compare' => '<',
                    'type'    => 'DATE',
                ),
            ),
            'number' => 100,
            'fields' => 'ID',
        ) );

        foreach ( $expired_users as $user_id ) {
            // Double-check expiry.
            if ( ! $membership->is_pro_user( $user_id ) ) {
                // Status was just expired by the check, send notification.
                $emails->send_expired_notification( $user_id );
            }
        }
    }

    public function send_expiry_warnings() {
        $membership = livenettv_pro()->get_membership();
        $emails     = livenettv_pro()->get_emails();

        // Warn 7 days before expiry.
        $users_7_days = get_users( array(
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key'     => 'livenettv_pro_status',
                    'value'   => 'active',
                    'compare' => '=',
                ),
                array(
                    'key'     => 'livenettv_pro_plan',
                    'value'   => 'lifetime',
                    'compare' => '!=',
                ),
                array(
                    'key'     => 'livenettv_pro_expiry_date',
                    'value'   => date( 'Y-m-d', strtotime( '+7 days' ) ),
                    'compare' => '=',
                    'type'    => 'DATE',
                ),
                array(
                    'key'     => 'livenettv_pro_warning_7_sent',
                    'compare' => 'NOT EXISTS',
                ),
            ),
            'number' => 100,
            'fields' => 'ID',
        ) );

        foreach ( $users_7_days as $user_id ) {
            if ( $emails->send_expiry_warning( $user_id, 7 ) ) {
                update_user_meta( $user_id, 'livenettv_pro_warning_7_sent', current_time( 'mysql' ) );
            }
        }

        // Warn 3 days before expiry.
        $users_3_days = get_users( array(
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key'     => 'livenettv_pro_status',
                    'value'   => 'active',
                    'compare' => '=',
                ),
                array(
                    'key'     => 'livenettv_pro_plan',
                    'value'   => 'lifetime',
                    'compare' => '!=',
                ),
                array(
                    'key'     => 'livenettv_pro_expiry_date',
                    'value'   => date( 'Y-m-d', strtotime( '+3 days' ) ),
                    'compare' => '=',
                    'type'    => 'DATE',
                ),
                array(
                    'key'     => 'livenettv_pro_warning_3_sent',
                    'compare' => 'NOT EXISTS',
                ),
            ),
            'number' => 100,
            'fields' => 'ID',
        ) );

        foreach ( $users_3_days as $user_id ) {
            if ( $emails->send_expiry_warning( $user_id, 3 ) ) {
                update_user_meta( $user_id, 'livenettv_pro_warning_3_sent', current_time( 'mysql' ) );
            }
        }

        // Warn 1 day before expiry.
        $users_1_day = get_users( array(
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key'     => 'livenettv_pro_status',
                    'value'   => 'active',
                    'compare' => '=',
                ),
                array(
                    'key'     => 'livenettv_pro_plan',
                    'value'   => 'lifetime',
                    'compare' => '!=',
                ),
                array(
                    'key'     => 'livenettv_pro_expiry_date',
                    'value'   => date( 'Y-m-d', strtotime( '+1 day' ) ),
                    'compare' => '=',
                    'type'    => 'DATE',
                ),
                array(
                    'key'     => 'livenettv_pro_warning_1_sent',
                    'compare' => 'NOT EXISTS',
                ),
            ),
            'number' => 100,
            'fields' => 'ID',
        ) );

        foreach ( $users_1_day as $user_id ) {
            if ( $emails->send_expiry_warning( $user_id, 1 ) ) {
                update_user_meta( $user_id, 'livenettv_pro_warning_1_sent', current_time( 'mysql' ) );
            }
        }
    }

    public static function clear_scheduled_events() {
        wp_clear_scheduled_hook( 'livenettv_pro_daily_check' );
        wp_clear_scheduled_hook( 'livenettv_pro_hourly_check' );
    }
}
