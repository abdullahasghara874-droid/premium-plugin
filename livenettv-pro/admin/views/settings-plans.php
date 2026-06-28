<?php
defined( 'ABSPATH' ) || exit;

$membership_obj = new LiveNetTV_Pro_Membership();
$plans = $membership_obj->get_plans();
?>
<div class="lntv-settings-section">
    <h2><?php esc_html_e( 'Membership Plans', 'livenettv-pro' ); ?></h2>
    <p class="description">
        <?php esc_html_e( 'Configure the subscription plans available to users.', 'livenettv-pro' ); ?>
    </p>
</div>

<form method="post" action="">
    <?php wp_nonce_field( 'livenettv_pro_save_plans' ); ?>

    <div id="lntv-plans-container">
        <?php foreach ( $plans as $slug => $plan ) : ?>
            <div class="lntv-plan-card" data-slug="<?php echo esc_attr( $slug ); ?>">
                <h3 class="lntv-plan-header">
                    <input
                        type="text"
                        name="plans[<?php echo esc_attr( $slug ); ?>][name]"
                        value="<?php echo esc_attr( $plan['name'] ); ?>"
                        class="lntv-plan-name-input"
                    >
                </h3>
                <div class="lntv-plan-fields">
                    <table class="form-table">
                        <tr>
                            <th><?php esc_html_e( 'Duration (days)', 'livenettv-pro' ); ?></th>
                            <td>
                                <input
                                    type="number"
                                    name="plans[<?php echo esc_attr( $slug ); ?>][duration]"
                                    value="<?php echo esc_attr( $plan['duration'] ); ?>"
                                    min="1"
                                    class="small-text"
                                >
                                <input
                                    type="text"
                                    name="plans[<?php echo esc_attr( $slug ); ?>][duration_text]"
                                    value="<?php echo esc_attr( $plan['duration_text'] ?? '' ); ?>"
                                    class="regular-text"
                                    placeholder="<?php esc_attr_e( 'e.g., 30 days', 'livenettv-pro' ); ?>"
                                    style="margin-left: 10px;"
                                >
                            </td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e( 'Price', 'livenettv-pro' ); ?></th>
                            <td>
                                <input
                                    type="number"
                                    name="plans[<?php echo esc_attr( $slug ); ?>][price]"
                                    value="<?php echo esc_attr( $plan['price'] ); ?>"
                                    step="0.01"
                                    min="0"
                                    class="small-text"
                                >
                                <select name="plans[<?php echo esc_attr( $slug ); ?>][currency]" class="small-text">
                                    <option value="USD" <?php selected( $plan['currency'] ?? 'USD', 'USD' ); ?>>USD</option>
                                    <option value="EUR" <?php selected( $plan['currency'] ?? 'USD', 'EUR' ); ?>>EUR</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e( 'Recommended', 'livenettv-pro' ); ?></th>
                            <td>
                                <label>
                                    <input
                                        type="checkbox"
                                        name="plans[<?php echo esc_attr( $slug ); ?>][recommended]"
                                        value="1"
                                        <?php checked( ! empty( $plan['recommended'] ) ); ?>
                                    >
                                    <?php esc_html_e( 'Mark as recommended', 'livenettv-pro' ); ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e( 'Badge Text', 'livenettv-pro' ); ?></th>
                            <td>
                                <input
                                    type="text"
                                    name="plans[<?php echo esc_attr( $slug ); ?>][badge]"
                                    value="<?php echo esc_attr( $plan['badge'] ?? '' ); ?>"
                                    class="regular-text"
                                    placeholder="<?php esc_attr_e( 'e.g., Best Value', 'livenettv-pro' ); ?>"
                                >
                            </td>
                        </tr>
                        <tr>
                            <th><?php esc_html_e( 'Save Percentage', 'livenettv-pro' ); ?></th>
                            <td>
                                <input
                                    type="number"
                                    name="plans[<?php echo esc_attr( $slug ); ?>][save_percent]"
                                    value="<?php echo esc_attr( $plan['save_percent'] ?? 0 ); ?>"
                                    min="0"
                                    max="100"
                                    class="small-text"
                                >%
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <p class="submit">
        <button type="submit" name="save_plans" class="button button-primary"><?php esc_html_e( 'Save Plans', 'livenettv-pro' ); ?></button>
    </p>
</form>
