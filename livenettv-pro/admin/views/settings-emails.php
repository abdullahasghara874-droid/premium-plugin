<?php
defined( 'ABSPATH' ) || exit;

$from_name  = get_option( 'livenettv_pro_email_from_name', get_bloginfo( 'name' ) );
$from_email = get_option( 'livenettv_pro_email_from_address', get_option( 'admin_email' ) );
$admin_email = get_option( 'livenettv_pro_admin_notification_email', get_option( 'admin_email' ) );
?>
<div class="lntv-settings-section">
    <h2><?php esc_html_e( 'Email Settings', 'livenettv-pro' ); ?></h2>
    <p class="description">
        <?php esc_html_e( 'Configure how notification emails are sent.', 'livenettv-pro' ); ?>
    </p>
</div>

<form method="post" action="options.php">
    <?php
    settings_fields( 'livenettv_pro_emails' );
    ?>
    <table class="form-table">
        <tr>
            <th scope="row">
                <label for="livenettv_pro_email_from_name"><?php esc_html_e( 'From Name', 'livenettv-pro' ); ?></label>
            </th>
            <td>
                <input type="text" name="livenettv_pro_email_from_name" id="livenettv_pro_email_from_name" value="<?php echo esc_attr( $from_name ); ?>" class="regular-text">
                <p class="description">
                    <?php esc_html_e( 'Name displayed in email "From" field.', 'livenettv-pro' ); ?>
                </p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="livenettv_pro_email_from_address"><?php esc_html_e( 'From Email', 'livenettv-pro' ); ?></label>
            </th>
            <td>
                <input type="email" name="livenettv_pro_email_from_address" id="livenettv_pro_email_from_address" value="<?php echo esc_attr( $from_email ); ?>" class="regular-text">
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="livenettv_pro_admin_notification_email"><?php esc_html_e( 'Admin Notification Email', 'livenettv-pro' ); ?></label>
            </th>
            <td>
                <input type="email" name="livenettv_pro_admin_notification_email" id="livenettv_pro_admin_notification_email" value="<?php echo esc_attr( $admin_email ); ?>" class="regular-text">
                <p class="description">
                    <?php esc_html_e( 'Email address for new payment notifications.', 'livenettv-pro' ); ?>
                </p>
            </td>
        </tr>
    </table>

    <?php submit_button( __( 'Save Email Settings', 'livenettv-pro' ) ); ?>
</form>

<div class="lntv-email-templates-info">
    <h3><?php esc_html_e( 'Email Templates', 'livenettv-pro' ); ?></h3>
    <p class="description">
        <?php esc_html_e( 'Email templates are located in:', 'livenettv-pro' ); ?>
        <code><?php echo esc_html( LIVENETTV_PRO_PATH . 'templates/emails/' ); ?></code>
    </p>
    <p class="description">
        <?php esc_html_e( 'Copy the default templates to your theme to customize them.', 'livenettv-pro' ); ?>
    </p>
</div>
