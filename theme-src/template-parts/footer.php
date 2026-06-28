<?php
/**
 * Template part for displaying the footer
 */

$telegram_url = get_theme_mod( 'singlo_telegram_url', '' );
$youtube_url  = get_theme_mod( 'singlo_youtube_url', '' );
$contact_url  = get_theme_mod( 'singlo_contact_url', '' );
$copyright    = get_theme_mod( 'singlo_footer_copyright', '' );
?>
<footer id="footer" class="ct-footer" data-id="type-1" itemscope="" itemtype="https://schema.org/WPFooter">
    <div data-row="top">
        <div class="ct-container" data-columns-divider="md">
            <div data-column="menu">
                <nav id="footer-menu" class="footer-menu-inline menu-container " data-id="menu" itemscope="" itemtype="https://schema.org/SiteNavigationElement" aria-label="Footer Menu">
                    <?php
                    wp_nav_menu( array(
                        'theme_location' => 'footer',
                        'menu_id'        => 'menu-mt2',
                        'container'      => false,
                        'menu_class'     => 'menu',
                        'fallback_cb'    => '__return_false',
                        'walker'         => new Singlo_Desktop_Walker_Nav_Menu(),
                    ) );
                    ?>
                </nav>
            </div>
            <div data-column="socials">
                <div class="singlo-language-switcher notranslate" translate="no">
    <label class="screen-reader-text" for="singlo-language-select">
        <?php esc_html_e( 'Select language', 'singlo' ); ?>
    </label>

    <select id="singlo-language-select" aria-label="Select language">
        <option value="">English</option>
        <option value="ar">العربية</option>
        <option value="bn">বাংলা</option>
        <option value="bs">Bosanski</option>
        <option value="bg">Български</option>
        <option value="ca">Català</option>
        <option value="ceb">Cebuano</option>
        <option value="ny">Chichewa</option>
        <option value="zh-CN">简体中文</option>
        <option value="zh-TW">繁體中文</option>
        <option value="co">Corsu</option>
        <option value="hr">Hrvatski</option>
        <option value="cs">Čeština</option>
        <option value="da">Dansk</option>
        <option value="nl">Nederlands</option>
        <option value="fr">Français</option>
        <option value="de">Deutsch</option>
        <option value="hi">हिन्दी</option>
        <option value="id">Bahasa Indonesia</option>
        <option value="it">Italiano</option>
        <option value="ja">日本語</option>
        <option value="ko">한국어</option>
        <option value="fa">فارسی</option>
        <option value="pl">Polski</option>
        <option value="pt">Português</option>
        <option value="ro">Română</option>
        <option value="ru">Русский</option>
        <option value="es">Español</option>
        <option value="sv">Svenska</option>
        <option value="tr">Türkçe</option>
        <option value="uk">Українська</option>
        <option value="ur">اردو</option>
        <option value="vi">Tiếng Việt</option>
    </select>
</div>

<div id="google_translate_element" aria-hidden="true"></div>
                <div class="ct-footer-socials" data-id="socials">
                    <div class="ct-social-box" data-color="custom" data-icon-size="custom" data-icons-type="rounded:outline">
                        <?php if ( ! empty( $telegram_url ) ) : ?>
                            <a href="<?php echo esc_url( $telegram_url ); ?>" data-network="telegram" aria-label="Telegram" target="_blank" rel="noopener noreferrer nofollow">
                                <span class="ct-icon-container">
                                    <svg width="20px" height="20px" viewBox="0 0 20 20" aria-hidden="true">
                                        <path d="M19.9,3.1l-3,14.2c-0.2,1-0.8,1.3-1.7,0.8l-4.6-3.4l-2.2,2.1c-0.2,0.2-0.5,0.5-0.9,0.5l0.3-4.7L16.4,5c0.4-0.3-0.1-0.5-0.6-0.2L5.3,11.4L0.7,10c-1-0.3-1-1,0.2-1.5l17.7-6.8C19.5,1.4,20.2,1.9,19.9,3.1z"></path>
                                    </svg>
                                </span>
                            </a>
                        <?php endif; ?>

                        <?php if ( ! empty( $contact_url ) ) : ?>
                            <a href="<?php echo esc_url( $contact_url ); ?>" data-network="email" aria-label="Email" target="_blank" rel="noopener noreferrer nofollow">
                                <span class="ct-icon-container">
                                    <svg width="20" height="20" viewBox="0 0 20 20" aria-hidden="true">
                                        <path d="M10,10.1L0,4.7C0.1,3.2,1.4,2,3,2h14c1.6,0,2.9,1.2,3,2.8L10,10.1z M10,11.8c-0.1,0-0.2,0-0.4-0.1L0,6.4V15c0,1.7,1.3,3,3,3h4.9h4.3H17c1.7,0,3-1.3,3-3V6.4l-9.6,5.2C10.2,11.7,10.1,11.7,10,11.8z"></path>
                                    </svg>
                                </span>
                            </a>
                        <?php endif; ?>

                        <?php if ( ! empty( $youtube_url ) ) : ?>
                            <a href="<?php echo esc_url( $youtube_url ); ?>" data-network="youtube" aria-label="YouTube" target="_blank" rel="noopener noreferrer nofollow">
                                <span class="ct-icon-container">
                                    <svg width="20" height="20" viewBox="0 0 20 20" aria-hidden="true">
                                        <path d="M15,0H5C2.2,0,0,2.2,0,5v10c0,2.8,2.2,5,5,5h10c2.8,0,5-2.2,5-5V5C20,2.2,17.8,0,15,0z M14.5,10.9l-6.8,3.8c-0.1,0.1-0.3,0.1-0.5,0.1c-0.5,0-1-0.4-1-1l0,0V6.2c0-0.5,0.4-1,1-1c0.2,0,0.3,0,0.5,0.1l6.8,3.8c0.5,0.3,0.7,0.8,0.4,1.3C14.8,10.6,14.6,10.8,14.5,10.9z"></path>
                                    </svg>
                                </span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div data-row="bottom">
        <div class="ct-container">
            <div data-column="copyright">
                <div class="ct-footer-copyright" data-id="copyright">
                    <?php if ( ! empty( $copyright ) ) : ?>
                        <?php echo wp_kses_post( $copyright ); ?>
                    <?php else : ?>
                        <p>© <?php echo date('Y'); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?> | <a title="Sitemap" href="<?php echo esc_url( home_url( '/sitemap_index.xml' ) ); ?>" target="_blank" rel="noopener">Sitemap</a></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <style>
.singlo-language-switcher {
    width: 157px;
    margin-bottom: 12px;
}

.singlo-language-switcher select {
    width: 100%;
    height: 38px;
    padding: 0 28px 0 0;
    color: var(--theme-text-color, #333);
    background: transparent;
    border: 0;
    border-bottom: 1px solid #3154ad;
    border-radius: 0;
    font: inherit;
    cursor: pointer;
    outline: none;
}

.singlo-language-switcher select:focus {
    border-bottom-color: #2871fa;
}

#google_translate_element {
    position: absolute !important;
    width: 1px;
    height: 1px;
    overflow: hidden;
    clip-path: inset(50%);
}

@media (max-width: 689px) {
    .singlo-language-switcher {
        align-self: center;
    }
}
</style>

<script>
function singloGoogleTranslateInit() {
    new google.translate.TranslateElement({
        pageLanguage: 'en',
        includedLanguages: 'ar,bn,bs,bg,ca,ceb,ny,zh-CN,zh-TW,co,hr,cs,da,nl,fr,de,hi,id,it,ja,ko,fa,pl,pt,ro,ru,es,sv,tr,uk,ur,vi',
        autoDisplay: false
    }, 'google_translate_element');
}

(function () {
    var selector = document.getElementById('singlo-language-select');

    if (!selector) {
        return;
    }

    var savedLanguage = document.cookie.match(
        /(?:^|;\s*)googtrans=\/en\/([^;]+)/
    );

    if (savedLanguage && savedLanguage[1] !== 'en') {
        selector.value = decodeURIComponent(savedLanguage[1]);
    }

    selector.addEventListener('change', function () {
        var language = this.value;
        var hostname = window.location.hostname.replace(/^www\./, '');
        var cookieValue = language ? '/en/' + language : '';
        var maxAge = language ? 31536000 : 0;

        document.cookie =
            'googtrans=' + cookieValue +
            ';path=/;max-age=' + maxAge +
            ';SameSite=Lax';

        if (hostname.indexOf('.') !== -1) {
            document.cookie =
                'googtrans=' + cookieValue +
                ';path=/;domain=.' + hostname +
                ';max-age=' + maxAge +
                ';SameSite=Lax';
        }

        window.location.reload();
    });
}());
</script>

<script
    src="https://translate.google.com/translate_a/element.js?cb=singloGoogleTranslateInit"
    async>
</script>
</footer>