<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?> data-link="type-2" <?php if (is_search()) { echo 'data-prefix="search"'; } elseif (is_category()) { echo 'data-prefix="categories"'; } else { echo 'data-prefix="single_blog_post"'; } ?> data-header="type-1" data-footer="type-1" itemscope="itemscope" itemtype="https://schema.org/Blog">
    <?php wp_body_open(); ?>
    <div id="main-container">
        <?php get_template_part('template-parts/header'); ?>