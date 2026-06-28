<?php
defined( 'ABSPATH' ) || exit;

class LiveNetTV_Pro_Ad_Remover {

    private $ad_selectors;
    private $remove_enabled;

    public function __construct() {
        $this->ad_selectors = get_option( 'livenettv_pro_ad_selectors', $this->get_default_selectors() );
        $this->remove_enabled = (bool) get_option( 'livenettv_pro_remove_ads', true );

        add_action( 'init', array( $this, 'init' ) );
    }

    public function init() {
        // Only inject ad removal for pro users on frontend.
        if ( ! is_admin() && livenettv_pro()->get_membership()->is_pro_user( get_current_user_id() ) ) {
            if ( $this->remove_enabled ) {
                add_action( 'wp_head', array( $this, 'inject_css' ), 99 );
                add_action( 'wp_footer', array( $this, 'inject_js' ), 99 );
            }
        }
    }

    public function inject_css() {
        $selectors = $this->parse_selectors();

        if ( empty( $selectors ) ) {
            return;
        }

        $css_rules = array();
        foreach ( $selectors as $selector ) {
            $selector = trim( $selector );
            if ( ! empty( $selector ) ) {
                $css_rules[] = sprintf( '%s { display: none !important; visibility: hidden !important; opacity: 0 !important; height: 0 !important; overflow: hidden !important; }', esc_attr( $selector ) );
            }
        }

        if ( empty( $css_rules ) ) {
            return;
        }

        printf(
            '<style id="livenettv-pro-ad-remover">%s</style>',
            implode( "\n", $css_rules )
        );
    }

    public function inject_js() {
        $selectors = $this->parse_selectors();

        if ( empty( $selectors ) ) {
            return;
        }

        $selectors_json = wp_json_encode( $selectors );

        ?>
        <script id="livenettv-pro-ad-remover-js">
        (function() {
            var selectors = <?php echo $selectors_json; ?>;
            var style = document.createElement('style');
            var cssRules = selectors.map(function(s) {
                return s + ' { display: none !important; visibility: hidden !important; }';
            }).join(' ');
            style.textContent = cssRules;
            document.head.appendChild(style);

            // Also remove elements directly for stubborn ads.
            function removeAds() {
                selectors.forEach(function(selector) {
                    var elements = document.querySelectorAll(selector);
                    elements.forEach(function(el) {
                        if (el.parentNode) {
                            el.parentNode.removeChild(el);
                        }
                    });
                });
            }

            // Run immediately.
            removeAds();

            // Run again after DOM is fully loaded.
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', removeAds);
            }

            // Run periodically for dynamically loaded ads.
            setInterval(removeAds, 5000);
        })();
        </script>
        <?php
    }

    private function parse_selectors() {
        if ( empty( $this->ad_selectors ) ) {
            return array();
        }

        if ( is_array( $this->ad_selectors ) ) {
            return array_filter( array_map( 'sanitize_text_field', $this->ad_selectors ) );
        }

        // Handle textarea input (one selector per line).
        $lines = explode( "\n", $this->ad_selectors );
        return array_filter( array_map( 'sanitize_text_field', $lines ) );
    }

    private function get_default_selectors() {
        return array(
            // Common ad class names.
            '.adsbygoogle',
            '.ad-container',
            '.ad-wrapper',
            '.ad-banner',
            '.advertisement',
            '.ad-slot',
            '.ad-block',
            '.ad-unit',
            '[class*="ad-"]',
            '[id*="ad-"]',
            '[class*="ads-"]',
            '[id*="ads-"]',
            '[id*="google_ads_"]',
            'div[data-ad]',
            'ins.adsbygoogle',
            // Common ad network elements.
            'div[id^="div-gpt-ad"]',
            'iframe[src*="googlesyndication.com"]',
            'iframe[src*="doubleclick.net"]',
            'iframe[src*="googleadservices"]',
            // Specific common ad placements.
            '.sidebar-ads',
            '.header-ads',
            '.footer-ads',
            '.content-ads',
            '.post-ads',
            '.widget_ad',
            // Common ad placeholder text.
            '.ad-label',
            '.sponsored',
            '.sponsored-content',
        );
    }

    public static function get_selectors_list() {
        $instance = new self();
        return $instance->ad_selectors;
    }
}
