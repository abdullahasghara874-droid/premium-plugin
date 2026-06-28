<?php
defined( 'ABSPATH' ) || exit;
settings_errors();
?>
<div class="wrap lntv-admin">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

    <nav class="nav-tab-wrapper">
        <?php foreach ( $tabs as $tab_key => $tab_label ) : ?>
            <a href="<?php echo esc_url( add_query_arg( 'tab', $tab_key, admin_url( 'admin.php?page=livenettv-pro-settings' ) ) ); ?>" class="nav-tab <?php echo $active_tab === $tab_key ? 'nav-tab-active' : ''; ?>">
                <?php echo esc_html( $tab_label ); ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <div class="lntv-settings-content">
        <?php switch ( $active_tab ) {
            case 'general':
                include LIVENETTV_PRO_PATH . 'admin/views/settings-general.php';
                break;
            case 'google':
                include LIVENETTV_PRO_PATH . 'admin/views/settings-google.php';
                break;
            case 'wallets':
                include LIVENETTV_PRO_PATH . 'admin/views/settings-wallets.php';
                break;
            case 'plans':
                include LIVENETTV_PRO_PATH . 'admin/views/settings-plans.php';
                break;
            case 'ads':
                include LIVENETTV_PRO_PATH . 'admin/views/settings-ads.php';
                break;
            case 'emails':
                include LIVENETTV_PRO_PATH . 'admin/views/settings-emails.php';
                break;
        } ?>
    </div>
</div>
