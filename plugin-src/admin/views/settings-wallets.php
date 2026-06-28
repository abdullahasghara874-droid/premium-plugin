<?php
/**
 * Crypto wallet settings view
 */

defined('ABSPATH') || exit;

$wallets = array(
    'USDT' => get_option('livenettv_pro_wallet_usdt', ''),
    'BTC' => get_option('livenettv_pro_wallet_btc', ''),
    'ETH' => get_option('livenettv_pro_wallet_eth', ''),
    'BNB' => get_option('livenettv_pro_wallet_bnb', ''),
);

?>
<div class="wrap livenettv-pro-admin">
    <h1><span class="dashicons dashicons-vault"></span> <?php _e('Cryptocurrency Wallet Addresses', 'livenettv-pro'); ?></h1>

    <div class="notice notice-info">
        <p><?php _e('Enter your wallet addresses for receiving crypto payments. Users will see these addresses with QR codes when they select a payment method.', 'livenettv-pro'); ?></p>
    </div>

    <form method="post" action="options.php">
        <?php settings_fields('livenettv_pro_wallets'); ?>

        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="livenettv_pro_wallet_usdt"><?php _e('USDT (TRC20/ERC20) Wallet', 'livenettv-pro'); ?></label>
                </th>
                <td>
                    <input type="text" name="livenettv_pro_wallet_usdt" id="livenettv_pro_wallet_usdt" value="<?php echo esc_attr($wallets['USDT']); ?>" class="large-text" placeholder="<?php esc_attr_e('Enter your USDT wallet address', 'livenettv-pro'); ?>">
                    <p class="description"><?php _e('Your USDT wallet address for receiving payments.', 'livenettv-pro'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="livenettv_pro_wallet_btc"><?php _e('Bitcoin (BTC) Wallet', 'livenettv-pro'); ?></label>
                </th>
                <td>
                    <input type="text" name="livenettv_pro_wallet_btc" id="livenettv_pro_wallet_btc" value="<?php echo esc_attr($wallets['BTC']); ?>" class="large-text" placeholder="<?php esc_attr_e('Enter your Bitcoin wallet address', 'livenettv-pro'); ?>">
                    <p class="description"><?php _e('Your Bitcoin wallet address for receiving payments.', 'livenettv-pro'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="livenettv_pro_wallet_eth"><?php _e('Ethereum (ETH) Wallet', 'livenettv-pro'); ?></label>
                </th>
                <td>
                    <input type="text" name="livenettv_pro_wallet_eth" id="livenettv_pro_wallet_eth" value="<?php echo esc_attr($wallets['ETH']); ?>" class="large-text" placeholder="<?php esc_attr_e('Enter your Ethereum wallet address', 'livenettv-pro'); ?>">
                    <p class="description"><?php _e('Your Ethereum wallet address for receiving payments.', 'livenettv-pro'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="livenettv_pro_wallet_bnb"><?php _e('Binance Coin (BNB) Wallet', 'livenettv-pro'); ?></label>
                </th>
                <td>
                    <input type="text" name="livenettv_pro_wallet_bnb" id="livenettv_pro_wallet_bnb" value="<?php echo esc_attr($wallets['BNB']); ?>" class="large-text" placeholder="<?php esc_attr_e('Enter your BNB wallet address', 'livenettv-pro'); ?>">
                    <p class="description"><?php _e('Your BNB (BSC) wallet address for receiving payments.', 'livenettv-pro'); ?></p>
                </td>
            </tr>
        </table>

        <?php submit_button(__('Save Wallet Addresses', 'livenettv-pro')); ?>
    </form>

    <div class="livenettv-pro-preview-section">
        <h2><?php _e('Wallet Preview', 'livenettv-pro'); ?></h2>
        <div class="livenettv-pro-wallet-preview-grid">
            <?php foreach ($wallets as $crypto => $address) : ?>
                <div class="livenettv-pro-wallet-preview-card">
                    <div class="livenettv-pro-crypto-header">
                        <span class="livenettv-pro-crypto-icon <?php echo esc_attr(strtolower($crypto)); ?>"></span>
                        <h3><?php echo esc_html($crypto); ?></h3>
                    </div>
                    <?php if (!empty($address)) : ?>
                        <div class="livenettv-pro-wallet-preview-address">
                            <code><?php echo esc_html(substr($address, 0, 20)); ?>...<?php echo esc_html(substr($address, -10)); ?></code>
                            <button type="button" class="button button-small livenettv-pro-copy-btn" data-copy="<?php echo esc_attr($address); ?>">
                                <?php _e('Copy', 'livenettv-pro'); ?>
                            </button>
                        </div>
                        <div class="livenettv-pro-wallet-status active">
                            <span class="dashicons dashicons-yes-alt"></span>
                            <?php _e('Configured', 'livenettv-pro'); ?>
                        </div>
                    <?php else : ?>
                        <div class="livenettv-pro-wallet-preview-address">
                            <span class="description"><?php _e('Not configured', 'livenettv-pro'); ?></span>
                        </div>
                        <div class="livenettv-pro-wallet-status inactive">
                            <span class="dashicons dashicons-warning"></span>
                            <?php _e('Missing', 'livenettv-pro'); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="livenettv-pro-info-card">
        <h2><?php _e('Future Upgrade: Binance Pay', 'livenettv-pro'); ?></h2>
        <p><?php _e('This plugin is built to support automatic Binance Pay integration in the future. When you upgrade, the same wallet addresses and database structure will be used - no data migration needed.', 'livenettv-pro'); ?></p>
        <ul>
            <li><?php _e('Same database tables', 'livenettv-pro'); ?></li>
            <li><?php _e('Same payment records', 'livenettv-pro'); ?></li>
            <li><?php _e('Same user membership data', 'livenettv-pro'); ?></li>
            <li><?php _e('Just add your Binance Pay API credentials', 'livenettv-pro'); ?></li>
        </ul>
    </div>
</div>
