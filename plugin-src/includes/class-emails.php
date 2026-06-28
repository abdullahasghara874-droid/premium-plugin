<?php
/**
 * Email notifications for LiveNetTV Pro
 */

defined('ABSPATH') || exit;

class LiveNetTV_Pro_Emails {

    public function __construct() {
        add_filter('wp_mail_from', array($this, 'set_mail_from'));
        add_filter('wp_mail_from_name', array($this, 'set_mail_from_name'));
    }

    public function set_mail_from($from_email) {
        $custom_email = get_option('livenettv_pro_notification_email', get_option('admin_email'));
        return $custom_email ?: $from_email;
    }

    public function set_mail_from_name($from_name) {
        $custom_name = get_option('livenettv_pro_email_from_name', get_bloginfo('name'));
        return $custom_name ?: $from_name;
    }

    public function send_approval_notification($user_id, $payment_data) {
        $user = get_user_by('ID', $user_id);

        if (!$user) {
            return false;
        }

        $plan_name = $payment_data->plan_name;
        $expiry_date = date_i18n(get_option('date_format'), strtotime($payment_data->membership_end));

        $subject = sprintf(
            __('[%s] Your Pro Membership is Now Active!', 'livenettv-pro'),
            get_bloginfo('name')
        );

        $message = $this->get_email_template('approval', array(
            'user_name' => $user->display_name,
            'user_email' => $user->user_email,
            'plan_name' => $plan_name,
            'expiry_date' => $expiry_date,
            'site_name' => get_bloginfo('name'),
            'site_url' => home_url('/'),
        ));

        return $this->send_email($user->user_email, $subject, $message);
    }

    public function send_rejection_notification($user_id, $payment_data) {
        $user = get_user_by('ID', $user_id);

        if (!$user) {
            return false;
        }

        $subject = sprintf(
            __('[%s] Payment Request Update', 'livenettv-pro'),
            get_bloginfo('name')
        );

        $message = $this->get_email_template('rejection', array(
            'user_name' => $user->display_name,
            'user_email' => $user->user_email,
            'plan_name' => $payment_data->plan_name,
            'rejection_notes' => $payment_data->notes ?? '',
            'site_name' => get_bloginfo('name'),
            'site_url' => home_url('/'),
            'support_email' => get_option('livenettv_pro_support_email', get_option('admin_email')),
        ));

        return $this->send_email($user->user_email, $subject, $message);
    }

    public function send_expiry_notification($user_id) {
        $user = get_user_by('ID', $user_id);

        if (!$user) {
            return false;
        }

        $subject = sprintf(
            __('[%s] Your Pro Membership Has Expired', 'livenettv-pro'),
            get_bloginfo('name')
        );

        $pro_page_id = get_option('livenettv_pro_pro_page_id');
        $renewal_url = $pro_page_id ? get_permalink($pro_page_id) : home_url('/');

        $message = $this->get_email_template('expiry', array(
            'user_name' => $user->display_name,
            'user_email' => $user->user_email,
            'site_name' => get_bloginfo('name'),
            'site_url' => home_url('/'),
            'renewal_url' => $renewal_url,
        ));

        return $this->send_email($user->user_email, $subject, $message);
    }

    public function send_expiry_warning($user_id, $days_remaining) {
        $user = get_user_by('ID', $user_id);

        if (!$user) {
            return false;
        }

        $subject = sprintf(
            _n(
                '[%s] Your Pro Membership Expires in %d Day',
                '[%s] Your Pro Membership Expires in %d Days',
                $days_remaining,
                'livenettv-pro'
            ),
            get_bloginfo('name'),
            $days_remaining
        );

        $expiry_date = get_user_meta($user_id, 'livenettv_pro_expiry_date', true);

        $pro_page_id = get_option('livenettv_pro_pro_page_id');
        $renewal_url = $pro_page_id ? get_permalink($pro_page_id) : home_url('/');

        $message = $this->get_email_template('expiry_warning', array(
            'user_name' => $user->display_name,
            'user_email' => $user->user_email,
            'days_remaining' => $days_remaining,
            'expiry_date' => date_i18n(get_option('date_format'), strtotime($expiry_date)),
            'site_name' => get_bloginfo('name'),
            'site_url' => home_url('/'),
            'renewal_url' => $renewal_url,
        ));

        return $this->send_email($user->user_email, $subject, $message);
    }

    public function send_new_payment_admin_notification($payment_id, $user_id) {
        $user = get_user_by('ID', $user_id);
        $admin_email = get_option('livenettv_pro_admin_notification_email', get_option('admin_email'));

        if (!$user) {
            return false;
        }

        $admin_url = admin_url('admin.php?page=livenettv-pro-payments&action=view&id=' . $payment_id);

        $subject = sprintf(
            __('[%s] New Pro Membership Payment Request', 'livenettv-pro'),
            get_bloginfo('name')
        );

        $message = $this->get_email_template('admin_notification', array(
            'user_name' => $user->display_name,
            'user_email' => $user->user_email,
            'admin_url' => $admin_url,
            'site_name' => get_bloginfo('name'),
        ));

        return $this->send_email($admin_email, $subject, $message);
    }

    private function get_email_template($template, $args) {
        $template_mode = get_option('livenettv_pro_email_template_mode', 'html');
        $template_path = LIVENETTV_PRO_PLUGIN_DIR . "templates/emails/{$template}.php";

        if (file_exists($template_path)) {
            extract($args);
            ob_start();
            include $template_path;
            return ob_get_clean();
        }

        return $this->get_fallback_template($template, $args, $template_mode);
    }

    private function get_fallback_template($template, $args, $mode = 'html') {
        $is_html = $mode === 'html';

        $templates = array(
            'approval' => $is_html ?
                "<h2>Pro Membership Activated!</h2>
                <p>Hi {user_name},</p>
                <p>Congratulations! Your <strong>{plan_name}</strong> membership is now active.</p>
                <p>Your membership will expire on <strong>{expiry_date}</strong>.</p>
                <p>Enjoy an ad-free experience on {site_name}!</p>
                <p>Visit us at <a href=\"{site_url}\">{site_url}</a></p>" :
                "Pro Membership Activated!\n\nHi {user_name},\n\nCongratulations! Your {plan_name} membership is now active.\n\nYour membership will expire on {expiry_date}.\n\nEnjoy an ad-free experience on {site_name}!\n\nVisit us at {site_url}",

            'rejection' => $is_html ?
                "<h2>Payment Request Update</h2>
                <p>Hi {user_name},</p>
                <p>Unfortunately, your payment request for <strong>{plan_name}</strong> could not be approved.</p>
                <p><strong>Reason:</strong> {rejection_notes}</p>
                <p>If you believe this is an error, please contact us at <a href=\"mailto:{support_email}\">{support_email}</a>.</p>" :
                "Payment Request Update\n\nHi {user_name},\n\nUnfortunately, your payment request for {plan_name} could not be approved.\n\nReason: {rejection_notes}\n\nIf you believe this is an error, please contact us at {support_email}.",

            'expiry' => $is_html ?
                "<h2>Pro Membership Expired</h2>
                <p>Hi {user_name},</p>
                <p>Your Pro membership has expired. Ads will now be visible on the website.</p>
                <p>Renew your membership to enjoy an ad-free experience:</p>
                <p><a href=\"{renewal_url}\">Renew Now</a></p>" :
                "Pro Membership Expired\n\nHi {user_name},\n\nYour Pro membership has expired. Ads will now be visible on the website.\n\nRenew your membership at {renewal_url}",

            'expiry_warning' => $is_html ?
                "<h2>Pro Membership Expiring Soon</h2>
                <p>Hi {user_name},</p>
                <p>Your Pro membership will expire in <strong>{days_remaining} days</strong> ({expiry_date}).</p>
                <p>Renew now to keep enjoying an ad-free experience:</p>
                <p><a href=\"{renewal_url}\">Renew Now</a></p>" :
                "Pro Membership Expiring Soon\n\nHi {user_name},\n\nYour Pro membership will expire in {days_remaining} days ({expiry_date}).\n\nRenew now to keep enjoying an ad-free experience at {renewal_url}",

            'admin_notification' => $is_html ?
                "<h2>New Payment Request</h2>
                <p>A new payment request has been submitted:</p>
                <p><strong>User:</strong> {user_name} ({user_email})</p>
                <p>Review it here: <a href=\"{admin_url}\">View Payment</a></p>" :
                "New Payment Request\n\nA new payment request has been submitted by {user_name} ({user_email}).\n\nReview it at {admin_url}",
        );

        if (!isset($templates[$template])) {
            return '';
        }

        $content = $templates[$template];

        if ($is_html) {
            $content = $this->wrap_html_email($content);
        }

        foreach ($args as $key => $value) {
            $content = str_replace('{' . $key . '}', $value, $content);
        }

        return $content;
    }

    private function wrap_html_email($content) {
        return '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td style="padding: 40px 20px;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 4px;">
                    <tr>
                        <td style="padding: 30px; font-size: 14px; line-height: 1.6; color: #333;">
                            ' . $content . '
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>';
    }

    public function send_email($to, $subject, $message, $headers = array()) {
        $html_mode = get_option('livenettv_pro_email_template_mode', 'html') === 'html';

        $headers[] = 'Content-Type: ' . ($html_mode ? 'text/html; charset=UTF-8' : 'text/plain; charset=UTF-8');

        return wp_mail($to, $subject, $message, $headers);
    }
}
