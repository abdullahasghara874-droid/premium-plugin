<?php
/**
 * Ad removal functionality for LiveNetTV Pro
 */

defined('ABSPATH') || exit;

class LiveNetTV_Pro_Ad_Remover {

    private $ad_selectors = array();
    private $ad_shortcodes = array();

    public function __construct() {
        add_action('init', array($this, 'init'));
    }

    public function init() {
        $this->ad_selectors = get_option('livenettv_pro_ad_selectors', $this->get_default_ad_selectors());
        $this->ad_shortcodes = get_option('livenettv_pro_ad_shortcodes', array());

        if (!is_admin()) {
            add_action('wp_head', array($this, 'inject_ad_remover_css'), 1);
            add_action('wp_footer', array($this, 'inject_ad_remover_js'), 99);
            add_filter('the_content', array($this, 'remove_ads_from_content'), PHP_INT_MAX);
        }
    }

    private function get_default_ad_selectors() {
        return array(
            '.popup-ad',
            '.banner-ad',
            '.sticky-ad',
            '.sidebar-ad',
            '.header-ad',
            '.footer-ad',
            '.in-content-ad',
            '.download-page-ad',
            '.ad-container',
            '.advertisement',
            '[id*="google_ads_"]',
            '[id*="ads-"]',
            '[id*="ad-"]',
            'ins.adsbygoogle',
            '.adsbygoogle',
        );
    }

    public function inject_ad_remover_css() {
        $user_id = get_current_user_id();
        $is_pro = livenettv_pro()->get_membership()->is_pro_user($user_id);

        if (!$is_pro) {
            return;
        }
        ?>
        <style id="livenettv-pro-ad-remover" type="text/css">
        <?php foreach ($this->ad_selectors as $selector) : ?>
        <?php echo esc_attr(trim($selector)); ?> { display: none !important; visibility: hidden !important; }
        <?php endforeach; ?>

        /* Additional ad removal CSS for known ad networks */
        .adsbygoogle,
        .ad-banner,
        .ad-wrapper,
        .ad-block,
        .ad-unit,
        .ad-slot,
        [data-ad-slot],
        [data-og-ad-slot-id],
        ins.adsbygoogle[data-ad-slot],
        .taboola-ad,
        .outbrain-ad,
        .taboola-placeholder,
        .OUTBRAIN,
        .ob-ad { display: none !important; }

        body.livenettv-pro-user .livenettv-pro-show-only-free { display: none !important; }
        body:not(.livenettv-pro-user) .livenettv-pro-show-only-pro { display: none !important; }
        </style>
        <?php
        add_filter('body_class', array($this, 'add_pro_user_body_class'));
    }

    public function add_pro_user_body_class($classes) {
        $user_id = get_current_user_id();
        $is_pro = livenettv_pro()->get_membership()->is_pro_user($user_id);

        if ($is_pro) {
            $classes[] = 'livenettv-pro-user';
        } else {
            $classes[] = 'livenettv-free-user';
        }

        return $classes;
    }

    public function inject_ad_remover_js() {
        $user_id = get_current_user_id();
        $is_pro = livenettv_pro()->get_membership()->is_pro_user($user_id);

        if (!$is_pro) {
            return;
        }

        $js_selectors = json_encode($this->ad_selectors);
        ?>
        <script id="livenettv-pro-ad-remover-js" type="text/javascript">
        (function() {
            var adSelectors = <?php echo $js_selectors; ?>;

            document.querySelectorAll('.livenettv-pro-show-only-free').forEach(function(el) {
                el.style.display = 'none';
            });

            function removeAds() {
                adSelectors.forEach(function(selector) {
                    try {
                        var elements = document.querySelectorAll(selector);
                        elements.forEach(function(el) {
                            el.style.display = 'none';
                            el.style.visibility = 'hidden';
                        });
                    } catch(e) {}
                });

                document.querySelectorAll('.adsbygoogle, [data-ad-slot]').forEach(function(el) {
                    el.style.display = 'none';
                    el.style.visibility = 'hidden';
                });

                if (typeof window.adsbygoogle !== 'undefined') {
                    try {
                        delete window.adsbygoogle;
                    } catch(e) {}
                }
            }

            removeAds();

            document.addEventListener('DOMContentLoaded', removeAds);
            window.addEventListener('load', removeAds);

            setTimeout(removeAds, 1000);
            setTimeout(removeAds, 3000);
            setTimeout(removeAds, 5000);
        })();
        </script>
        <?php
    }

    public function remove_ads_from_content($content) {
        $user_id = get_current_user_id();
        $is_pro = livenettv_pro()->get_membership()->is_pro_user($user_id);

        if (!$is_pro) {
            return $content;
        }

        foreach ($this->ad_shortcodes as $shortcode) {
            $pattern = '/\[' . preg_quote($shortcode, '/') . '[^\]]*\][\s\S]*?\[\/' . preg_quote($shortcode, '/') . '\]|\[' . preg_quote($shortcode, '/') . '[^\]]*\]/i';
            $content = preg_replace($pattern, '', $content);
        }

        foreach ($this->ad_selectors as $selector) {
            $pattern = '/(<(div|li|aside|section|figure|img|iframe|span)[^>]*(class|id)[^>]*="' . preg_quote(current(explode(' ', trim($selector))), '/') . '"[^>]*>[\s\S]*?<\/\2>)/i';
            $content = preg_replace($pattern, '', $content);
        }

        $ad_patterns = array(
            '/<!--adsense-->[\s\S]*?<!--\/adsense-->/i',
            '/<!--google_ad-->\s*<!--\/google_ad-->/i',
            '/\[adsense[^\]]*\][\s\S]*?\[\/adsense\]/i',
            '/\[ad[^\]]*\][\s\S]*?\[\/ad\]/i',
            '/<script[^>]*(adsbygoogle|google_ad)[^>]*>[\s\S]*?<\/script>/i',
        );

        foreach ($ad_patterns as $pattern) {
            $content = preg_replace($pattern, '', $content);
        }

        return $content;
    }

    public function remove_specific_ad($content, $selector) {
        if (!is_user_logged_in()) {
            return $content;
        }

        $user_id = get_current_user_id();
        $is_pro = livenettv_pro()->get_membership()->is_pro_user($user_id);

        if (!$is_pro) {
            return $content;
        }

        $pattern = '/<' . preg_quote($selector, '/') . '[\s\S]*?<' . preg_quote($selector, '/') . '>/i';
        return preg_replace($pattern, '', $content);
    }

    public function is_pro_user() {
        $user_id = get_current_user_id();
        return livenettv_pro()->get_membership()->is_pro_user($user_id);
    }

    public function show_pro_cta() {
        $user_id = get_current_user_id();
        $is_pro = livenettv_pro()->get_membership()->is_pro_user($user_id);

        if ($is_pro) {
            return false;
        }

        echo do_shortcode('[livenettv_pro_cta]');
        return true;
    }
}
