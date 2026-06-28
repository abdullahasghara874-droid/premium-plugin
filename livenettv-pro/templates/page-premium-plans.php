<?php
/**
 * Template Name: LiveNetTV Pro: Premium Plans
 *
 * Assigned to a page to show the full premium plans layout within the theme.
 * The [livenettv_premium_plans] shortcode can also be used on any page.
 *
 * @package LiveNetTV_Pro
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <?php echo do_shortcode( '[livenettv_premium_plans]' ); ?>
    </main>
</div>

<?php
get_footer();
