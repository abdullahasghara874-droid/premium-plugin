<?php
defined( 'ABSPATH' ) || exit;

$pages = get_pages();
$pro_page_id = (int) get_option( 'livenettv_pro_pro_page_id', 0 );
$redirect_url = get_option( 'livenettv_pro_redirect_after_login', '' );
?>
<form method="post" action="options.php">
    <?php
    settings_fields( 'livenettv_pro_general' );
    ?>
    <table class="form-table">
        <tr>
            <th scope="row">
                <label for="livenettv_pro_pro_page_id"><?php esc_html_e( 'Pro Page', 'livenettv-pro' ); ?></label>
            </th>
            <td>
                <select name="livenettv_pro_pro_page_id" id="livenettv_pro_pro_page_id">
                    <option value=""><?php esc_html_e( 'Select a page...', 'livenettv-pro' ); ?></option>
                    <?php foreach ( $pages as $page ) : ?>
                        <option value="<?php echo esc_attr( $page->ID ); ?>" <?php selected( $pro_page_id, $page->ID ); ?>>
                            <?php echo esc_html( $page->post_title ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="description">
                    <?php esc_html_e( 'Select the page that contains the premium plans and payment form.', 'livenettv-pro' ); ?>
                </p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="livenettv_pro_redirect_after_login"><?php esc_html_e( 'Redirect After Login', 'livenettv-pro' ); ?></label>
            </th>
            <td>
                <input type="url" name="livenettv_pro_redirect_after_login" id="livenettv_pro_redirect_after_login" value="<?php echo esc_attr( $redirect_url ); ?>" class="regular-text">
                <p class="description">
                    <?php esc_html_e( 'URL to redirect users after successful login. Leave empty to use the Pro page.', 'livenettv-pro' ); ?>
                </p>
            </td>
        </tr>
    </table>

    <?php submit_button( __( 'Save Settings', 'livenettv-pro' ) ); ?>
</form>
