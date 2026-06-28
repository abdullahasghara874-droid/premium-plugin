<?php
defined( 'ABSPATH' ) || exit;

$wallets = array(
    'usdt' => get_option( 'livenettv_pro_wallet_usdt', '' ),
    'btc'  => get_option( 'livenettv_pro_wallet_btc', '' ),
    'eth'  => get_option( 'livenettv_pro_wallet_eth', '' ),
    'bnb'  => get_option( 'livenettv_pro_wallet_bnb', '' ),
);

$crypto_info = array(
    'usdt' => array( 'name' => 'USDT (TRC20/ERC20)', 'icon' => '💵' ),
    'btc'  => array( 'name' => 'Bitcoin (BTC)', 'icon' => '₿' ),
    'eth'  => array( 'name' => 'Ethereum (ETH)', 'icon' => 'Ξ' ),
    'bnb'  => array( 'name' => 'BNB (BSC)', 'icon' => '🔶' ),
);
?>
<div class="lntv-settings-section">
    <h2><?php esc_html_e( 'Cryptocurrency Wallet Addresses', 'livenettv-pro' ); ?></h2>
    <p class="description">
        <?php esc_html_e( 'Enter your wallet addresses for receiving cryptocurrency payments. Leave empty to disable that payment method.', 'livenettv-pro' ); ?>
    </p>
</div>

<form method="post" action="options.php">
    <?php
    settings_fields( 'livenettv_pro_wallets' );
    ?>
    <table class="form-table">
        <?php foreach ( $wallets as $crypto => $address ) : ?>
            <tr>
                <th scope="row">
                    <label for="<?php echo esc_attr( 'livenettv_pro_wallet_' . $crypto ); ?>">
                        <?php echo esc_html( $crypto_info[ $crypto ]['icon'] ); ?>
                        <?php echo esc_html( $crypto_info[ $crypto ]['name'] ); ?>
                    </label>
                </th>
                <td>
                    <input
                        type="text"
                        name="<?php echo esc_attr( 'livenettv_pro_wallet_' . $crypto ); ?>"
                        id="<?php echo esc_attr( 'livenettv_pro_wallet_' . $crypto ); ?>"
                        value="<?php echo esc_attr( $address ); ?>"
                        class="regular-text lntv-wallet-input"
                        placeholder="<?php esc_attr_e( 'Enter wallet address...', 'livenettv-pro' ); ?>"
                    >
                    <p class="description">
                        <?php esc_html_e( 'Only active if filled.', 'livenettv-pro' ); ?>
                    </p>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php submit_button( __( 'Save Wallet Addresses', 'livenettv-pro' ) ); ?>
</form>
