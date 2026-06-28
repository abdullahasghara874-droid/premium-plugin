<?php
defined( 'ABSPATH' ) || exit;

class LiveNetTV_Pro_Emails {

    private $from_name;
    private $from_email;

    public function __construct() {
        $this->from_name  = get_option( 'livenettv_pro_email_from_name', get_bloginfo( 'name' ) );
        $this->from_email = get_option( 'livenettv_pro_email_from_address', get_option( 'admin_email' ) );
    }

    public function send_new_payment_admin_notification( $payment_id, $user_id ) {
        $db      = livenettv_pro()->get_db();
        $payment = $db->get_payment( $payment_id );

        if ( ! $payment ) {
            return false;
        }

        $user = get_user_by( 'id', $user_id );

        if ( ! $user ) {
            return false;
        }

        $admin_email = get_option( 'livenettv_pro_admin_notification_email', get_option( 'admin_email' ) );

        if ( ! is_email( $admin_email ) ) {
            return false;
        }

        $subject = sprintf(
            /* translators: %s: site name */
            __( '[%s] New Payment Submission Received', 'livenettv-pro' ),
            get_bloginfo( 'name' )
        );

        $message = $this->get_template( 'admin-new-payment', array(
            'payment'    => $payment,
            'user'       => $user,
            'admin_url'  => admin_url( 'admin.php?page=livenettv-pro-payments&view=' . $payment_id ),
        ) );

        return $this->send( $admin_email, $subject, $message );
    }

    public function send_approval_notification( $user_id, $payment ) {
        $user = get_user_by( 'id', $user_id );

        if ( ! $user ) {
            return false;
        }

        $subject = sprintf(
            /* translators: %s: site name */
            __( '[%s] Your Payment Has Been Approved!', 'livenettv-pro' ),
            get_bloginfo( 'name' )
        );

        $message = $this->get_template( 'user-approval', array(
            'user'       => $user,
            'payment'    => $payment,
            'site_url'   => home_url(),
            'pro_page'   => $this->get_pro_page_url(),
        ) );

        return $this->send( $user->user_email, $subject, $message );
    }

    public function send_rejection_notification( $user_id, $payment ) {
        $user = get_user_by( 'id', $user_id );

        if ( ! $user ) {
            return false;
        }

        $subject = sprintf(
            /* translators: %s: site name */
            __( '[%s] Payment Submission Update', 'livenettv-pro' ),
            get_bloginfo( 'name' )
        );

        $message = $this->get_template( 'user-rejection', array(
            'user'       => $user,
            'payment'    => $payment,
            'reason'     => $payment->notes ?? '',
            'pro_page'   => $this->get_pro_page_url(),
        ) );

        return $this->send( $user->user_email, $subject, $message );
    }

    public function send_expiry_warning( $user_id, $days_remaining ) {
        $user = get_user_by( 'id', $user_id );

        if ( ! $user ) {
            return false;
        }

        $subject = sprintf(
            /* translators: 1: site name, 2: days remaining */
            __( '[%1$s] Your Pro Membership Expires in %2$d Days', 'livenettv-pro' ),
            get_bloginfo( 'name' ),
            $days_remaining
        );

        $message = $this->get_template( 'user-expiry-warning', array(
            'user'           => $user,
            'days_remaining' => $days_remaining,
            'pro_page'       => $this->get_pro_page_url(),
        ) );

        return $this->send( $user->user_email, $subject, $message );
    }

    public function send_expired_notification( $user_id ) {
        $user = get_user_by( 'id', $user_id );

        if ( ! $user ) {
            return false;
        }

        $subject = sprintf(
            /* translators: %s: site name */
            __( '[%s] Your Pro Membership Has Expired', 'livenettv-pro' ),
            get_bloginfo( 'name' )
        );

        $message = $this->get_template( 'user-expired', array(
            'user'     => $user,
            'pro_page' => $this->get_pro_page_url(),
        ) );

        return $this->send( $user->user_email, $subject, $message );
    }

    public function send_welcome_email( $user_id, $plan_name ) {
        $user = get_user_by( 'id', $user_id );

        if ( ! $user ) {
            return false;
        }

        $subject = sprintf(
            /* translators: %s: site name */
            __( '[%s] Welcome to Pro!', 'livenettv-pro' ),
            get_bloginfo( 'name' )
        );

        $message = $this->get_template( 'user-welcome', array(
            'user'      => $user,
            'plan_name' => $plan_name,
            'pro_page'  => $this->get_pro_page_url(),
        ) );

        return $this->send( $user->user_email, $subject, $message );
    }

    private function send( $to, $subject, $message ) {
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            sprintf( 'From: %s <%s>', $this->from_name, $this->from_email ),
        );

        return wp_mail( $to, $subject, $message, $headers );
    }

    private function get_template( $template_name, $args = array() ) {
        $template_path = LIVENETTV_PRO_PATH . 'templates/emails/' . $template_name . '.php';

        if ( ! file_exists( $template_path ) ) {
            return $this->get_default_template( $template_name, $args );
        }

        ob_start();
        extract( $args, EXTR_SKIP );
        include $template_path;
        return ob_get_clean();
    }

    private function get_default_template( $template_name, $args ) {
        $site_name = get_bloginfo( 'name' );
        $site_url  = home_url();

        switch ( $template_name ) {
            case 'admin-new-payment':
                $payment = $args['payment'] ?? null;
                $user    = $args['user'] ?? null;
                $admin_url = $args['admin_url'] ?? admin_url();

                return "
                <html>
                <body style='font-family: Arial, sans-serif;'>
                <h2>New Payment Submission</h2>
                <p>A new payment has been submitted and requires your review.</p>
                <table style='border-collapse: collapse; width: 100%; max-width: 500px;'>
                <tr><td style='padding: 8px; border: 1px solid #ddd;'><strong>User:</strong></td><td style='padding: 8px; border: 1px solid #ddd;'>{$user->user_login} ({$user->user_email})</td></tr>
                <tr><td style='padding: 8px; border: 1px solid #ddd;'><strong>Plan:</strong></td><td style='padding: 8px; border: 1px solid #ddd;'>{$payment->plan_name}</td></tr>
                <tr><td style='padding: 8px; border: 1px solid #ddd;'><strong>Price:</strong></td><td style='padding: 8px; border: 1px solid #ddd;'>{$payment->plan_price} {$payment->currency}</td></tr>
                <tr><td style='padding: 8px; border: 1px solid #ddd;'><strong>Crypto:</strong></td><td style='padding: 8px; border: 1px solid #ddd;'>{$payment->crypto_type}</td></tr>
                <tr><td style='padding: 8px; border: 1px solid #ddd;'><strong>Transaction ID:</strong></td><td style='padding: 8px; border: 1px solid #ddd;'>{$payment->transaction_id}</td></tr>
                </table>
                <p style='margin-top: 20px;'><a href='{$admin_url}' style='background: #0073aa; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 3px;'>Review Payment</a></p>
                </body>
                </html>
                ";

            case 'user-approval':
                $payment = $args['payment'] ?? null;
                $user    = $args['user'] ?? null;
                $pro_page = $args['pro_page'] ?? home_url();

                return "
                <html>
                <body style='font-family: Arial, sans-serif;'>
                <h2>Payment Approved - Welcome to Pro!</h2>
                <p>Great news! Your payment for <strong>{$payment->plan_name}</strong> has been approved.</p>
                <p>Your Pro membership is now active" . ( ! empty( $payment->membership_end ) ? " until {$payment->membership_end}" : '' ) . ".</p>
                <p>You now have access to all Pro features including ad-free viewing.</p>
                <p style='margin-top: 20px;'><a href='{$pro_page}' style='background: #28a745; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 3px;'>Go to Pro Page</a></p>
                <p>Thank you for your support!<br>{$site_name}</p>
                </body>
                </html>
                ";

            case 'user-rejection':
                $payment = $args['payment'] ?? null;
                $reason  = $args['reason'] ?? '';
                $pro_page = $args['pro_page'] ?? home_url();

                return "
                <html>
                <body style='font-family: Arial, sans-serif;'>
                <h2>Payment Submission Update</h2>
                <p>Unfortunately, your payment for <strong>{$payment->plan_name}</strong> could not be verified.</p>
                " . ( ! empty( $reason ) ? "<p><strong>Reason:</strong> {$reason}</p>" : '' ) . "
                <p>You can submit a new payment at any time. Please ensure your transaction ID is correct and the screenshot clearly shows the payment.</p>
                <p style='margin-top: 20px;'><a href='{$pro_page}' style='background: #0073aa; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 3px;'>Try Again</a></p>
                <p>If you believe this is an error, please contact our support team.</p>
                <p>{$site_name}</p>
                </body>
                </html>
                ";

            case 'user-expiry-warning':
                $days = $args['days_remaining'] ?? 0;
                $pro_page = $args['pro_page'] ?? home_url();

                return "
                <html>
                <body style='font-family: Arial, sans-serif;'>
                <h2>Your Pro Membership Expires Soon</h2>
                <p>Your Pro membership will expire in <strong>{$days} days</strong>.</p>
                <p>Renew now to continue enjoying ad-free viewing and all Pro features.</p>
                <p style='margin-top: 20px;'><a href='{$pro_page}' style='background: #ffc107; color: #333; padding: 10px 20px; text-decoration: none; border-radius: 3px;'>Renew Now</a></p>
                <p>Thank you for being a Pro member!<br>{$site_name}</p>
                </body>
                </html>
                ";

            case 'user-expired':
                $pro_page = $args['pro_page'] ?? home_url();

                return "
                <html>
                <body style='font-family: Arial, sans-serif;'>
                <h2>Your Pro Membership Has Expired</h2>
                <p>Your Pro membership has expired. You no longer have access to Pro features.</p>
                <p>Renew your membership today to get back to ad-free viewing.</p>
                <p style='margin-top: 20px;'><a href='{$pro_page}' style='background: #0073aa; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 3px;'>Renew Now</a></p>
                <p>We hope to see you back soon!<br>{$site_name}</p>
                </body>
                </html>
                ";

            case 'user-welcome':
                $plan_name = $args['plan_name'] ?? 'Pro';
                $pro_page  = $args['pro_page'] ?? home_url();

                return "
                <html>
                <body style='font-family: Arial, sans-serif;'>
                <h2>Welcome to {$site_name} Pro!</h2>
                <p>Thank you for subscribing to <strong>{$plan_name}</strong>!</p>
                <p>You now have access to all Pro features including ad-free viewing.</p>
                <p style='margin-top: 20px;'><a href='{$pro_page}' style='background: #28a745; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 3px;'>Explore Pro Features</a></p>
                <p>Enjoy!<br>{$site_name}</p>
                </body>
                </html>
                ";

            default:
                return '';
        }
    }

    private function get_pro_page_url() {
        $pro_page_id = (int) get_option( 'livenettv_pro_pro_page_id', 0 );
        return $pro_page_id ? get_permalink( $pro_page_id ) : home_url();
    }
}
