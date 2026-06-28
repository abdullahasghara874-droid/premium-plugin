<?php
defined( 'ABSPATH' ) || exit;

class LiveNetTV_Pro_Membership {

    private static $plans = array();

    public function __construct() {
        self::$plans = $this->get_default_plans();
    }

    private function get_default_plans() {
        $custom = get_option( 'livenettv_pro_custom_plans', array() );
        if ( ! empty( $custom ) && is_array( $custom ) ) {
            return $custom;
        }

        return array(
            '1_month' => array(
                'name'          => __( '1 Month Pro', 'livenettv-pro' ),
                'duration'      => 30,
                'duration_text' => __( '30 days', 'livenettv-pro' ),
                'price'         => 9.99,
                'currency'      => 'USD',
                'recommended'   => false,
            ),
            '3_months' => array(
                'name'          => __( '3 Months Pro', 'livenettv-pro' ),
                'duration'      => 90,
                'duration_text' => __( '90 days', 'livenettv-pro' ),
                'price'         => 24.99,
                'currency'      => 'USD',
                'recommended'   => false,
            ),
            '6_months' => array(
                'name'          => __( '6 Months Pro', 'livenettv-pro' ),
                'duration'      => 180,
                'duration_text' => __( '6 months', 'livenettv-pro' ),
                'price'         => 44.99,
                'currency'      => 'USD',
                'recommended'   => false,
            ),
            '1_year' => array(
                'name'          => __( '1 Year Pro', 'livenettv-pro' ),
                'duration'      => 365,
                'duration_text' => __( '1 year', 'livenettv-pro' ),
                'price'         => 74.99,
                'currency'      => 'USD',
                'recommended'   => true,
                'badge'         => __( 'Best Value', 'livenettv-pro' ),
                'save_percent'  => 37,
            ),
            'lifetime' => array(
                'name'          => __( 'Lifetime Pro', 'livenettv-pro' ),
                'duration'      => 36500,
                'duration_text' => __( 'Lifetime', 'livenettv-pro' ),
                'price'         => 149.99,
                'currency'      => 'USD',
                'recommended'   => false,
            ),
        );
    }

    public function get_plans() {
        return apply_filters( 'livenettv_pro_plans', self::$plans );
    }

    public function get_plan( $slug ) {
        $plans = $this->get_plans();
        return isset( $plans[ $slug ] ) ? $plans[ $slug ] : null;
    }

    public function is_pro_user( $user_id ) {
        $user_id = absint( $user_id );
        if ( ! $user_id ) {
            return false;
        }

        $status = get_user_meta( $user_id, 'livenettv_pro_status', true );

        if ( 'active' !== $status ) {
            return false;
        }

        if ( $this->is_lifetime( $user_id ) ) {
            return true;
        }

        $expiry = get_user_meta( $user_id, 'livenettv_pro_expiry_date', true );

        if ( empty( $expiry ) ) {
            return false;
        }

        if ( $expiry >= current_time( 'Y-m-d' ) ) {
            return true;
        }

        $this->expire_membership( $user_id );
        return false;
    }

    public function activate_membership( $user_id, $plan_slug, $payment_id = 0 ) {
        $user_id = absint( $user_id );
        $plan    = $this->get_plan( $plan_slug );

        if ( ! $plan ) {
            return new WP_Error( 'invalid_plan', __( 'Invalid plan.', 'livenettv-pro' ) );
        }

        $start  = current_time( 'Y-m-d' );
        $expiry = ( 'lifetime' === $plan_slug )
            ? date( 'Y-m-d', strtotime( '+100 years' ) )
            : date( 'Y-m-d', strtotime( "+{$plan['duration']} days", strtotime( $start ) ) );

        update_user_meta( $user_id, 'livenettv_pro_status',       'active' );
        update_user_meta( $user_id, 'livenettv_pro_plan',         $plan_slug );
        update_user_meta( $user_id, 'livenettv_pro_start_date',   $start );
        update_user_meta( $user_id, 'livenettv_pro_expiry_date',  $expiry );
        update_user_meta( $user_id, 'livenettv_pro_activated_at', current_time( 'mysql' ) );
        update_user_meta( $user_id, 'livenettv_pro_payment_id',   absint( $payment_id ) );

        do_action( 'livenettv_pro_membership_activated', $user_id, $plan_slug, $payment_id );

        return true;
    }

    public function renew_membership( $user_id, $plan_slug, $payment_id = 0 ) {
        $user_id = absint( $user_id );
        $plan    = $this->get_plan( $plan_slug );

        if ( ! $plan ) {
            return new WP_Error( 'invalid_plan', __( 'Invalid plan.', 'livenettv-pro' ) );
        }

        $current_expiry = get_user_meta( $user_id, 'livenettv_pro_expiry_date', true );
        $today          = current_time( 'Y-m-d' );
        $start          = ( $current_expiry && $current_expiry > $today ) ? $current_expiry : $today;
        $expiry         = date( 'Y-m-d', strtotime( "+{$plan['duration']} days", strtotime( $start ) ) );

        update_user_meta( $user_id, 'livenettv_pro_status',              'active' );
        update_user_meta( $user_id, 'livenettv_pro_plan',                $plan_slug );
        update_user_meta( $user_id, 'livenettv_pro_expiry_date',         $expiry );
        update_user_meta( $user_id, 'livenettv_pro_renewed_at',          current_time( 'mysql' ) );
        update_user_meta( $user_id, 'livenettv_pro_renewal_payment_id',  absint( $payment_id ) );

        do_action( 'livenettv_pro_membership_renewed', $user_id, $plan_slug, $payment_id );

        return true;
    }

    public function expire_membership( $user_id ) {
        $user_id = absint( $user_id );
        update_user_meta( $user_id, 'livenettv_pro_status', 'expired' );
        do_action( 'livenettv_pro_membership_expired', $user_id );
        return true;
    }

    public function get_user_membership_data( $user_id ) {
        $user_id = absint( $user_id );
        return array(
            'status'       => get_user_meta( $user_id, 'livenettv_pro_status',       true ),
            'plan'         => get_user_meta( $user_id, 'livenettv_pro_plan',         true ),
            'start_date'   => get_user_meta( $user_id, 'livenettv_pro_start_date',   true ),
            'expiry_date'  => get_user_meta( $user_id, 'livenettv_pro_expiry_date',  true ),
            'activated_at' => get_user_meta( $user_id, 'livenettv_pro_activated_at', true ),
            'payment_id'   => get_user_meta( $user_id, 'livenettv_pro_payment_id',   true ),
        );
    }

    public function get_days_remaining( $user_id ) {
        $expiry = get_user_meta( absint( $user_id ), 'livenettv_pro_expiry_date', true );
        if ( empty( $expiry ) ) {
            return 0;
        }
        $today = current_time( 'Y-m-d' );
        if ( $expiry < $today ) {
            return 0;
        }
        return (int) floor( ( strtotime( $expiry ) - strtotime( $today ) ) / DAY_IN_SECONDS );
    }

    public function is_lifetime( $user_id ) {
        return 'lifetime' === get_user_meta( absint( $user_id ), 'livenettv_pro_plan', true );
    }
}
