<header id="header" class="ct-header" data-id="type-1" itemscope="" itemtype="https://schema.org/WPHeader">
    <div data-device="desktop">
        <div data-row="bottom" data-column-set="2">
            <div class="ct-container-fluid">
                <div data-column="start" data-placements="1">
                    <div data-items="primary">
                        <div class="site-branding" data-id="logo" itemscope="itemscope" itemtype="https://schema.org/Organization">
                            <div class="site-title-container">
                                <?php 
                                $custom_site_name = get_theme_mod( 'singlo_site_name', '' );
                                $display_site_name = ! empty( $custom_site_name ) ? $custom_site_name : get_bloginfo( 'name' );
                                ?>
                                <span class="site-title " itemprop="name"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" itemprop="url"><?php echo esc_html( $display_site_name ); ?></a></span>
                            </div>
                        </div>
                        <nav id="header-menu-2" class="header-menu-2 menu-container" data-id="menu-secondary" data-menu="type-2:left" data-dropdown="type-1:padded" data-responsive="yes" itemscope="" itemtype="https://schema.org/SiteNavigationElement" aria-label="menu 1">
                            <?php
                            wp_nav_menu( array(
                                'theme_location' => 'header',
                                'menu_id'        => 'menu-menu-1',
                                'container'      => false,
                                'menu_class'     => 'menu',
                                'fallback_cb'    => '__return_false',
                                'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                                'depth'          => 1,
                                'walker'         => new Singlo_Desktop_Walker_Nav_Menu(),
                            ) );
                            ?>
                        </nav>
                    </div>
                </div>
                <div data-column="end" data-placements="1">
                    <div data-items="primary">
                        <div class="ct-search-box " data-id="search-input">
                            <form role="search" method="get" class="ct-search-form" data-form-controls="inside" data-taxonomy-filter="false" data-submit-button="icon" action="<?php echo esc_url( home_url( '/' ) ); ?>" aria-haspopup="listbox" data-live-results="thumbs">
                                <input type="search" placeholder="<?php echo esc_attr_x( 'Search for apps', 'placeholder', 'singlo' ); ?>" value="<?php echo get_search_query(); ?>" name="s" autocomplete="off" title="Search for..." aria-label="Search for...">
                                <div class="ct-search-form-controls">
                                    <button type="submit" class="wp-element-button" data-button="inside:icon" aria-label="Search button">
                                        <svg class="ct-icon ct-search-button-content" aria-hidden="true" width="15" height="15" viewBox="0 0 15 15">
                                            <path d="M14.8,13.7L12,11c0.9-1.2,1.5-2.6,1.5-4.2c0-3.7-3-6.8-6.8-6.8S0,3,0,6.8s3,6.8,6.8,6.8c1.6,0,3.1-0.6,4.2-1.5l2.8,2.8c0.1,0.1,0.3,0.2,0.5,0.2s0.4-0.1,0.5-0.2C15.1,14.5,15.1,14,14.8,13.7z M1.5,6.8c0-2.9,2.4-5.2,5.2-5.2S12,3.9,12,6.8S9.6,12,6.8,12S1.5,9.6,1.5,6.8z"></path>
                                        </svg>
                                        <span class="ct-ajax-loader">
                                            <svg viewBox="0 0 24 24">
                                                <circle cx="12" cy="12" r="10" opacity="0.2" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="2"></circle>
                                                <path d="m12,2c5.52,0,10,4.48,10,10" fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2">
                                                    <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="0.6s" from="0 12 12" to="360 12 12" repeatCount="indefinite"></animateTransform>
                                                </path>
                                            </svg>
                                        </span>
                                    </button>
                                    <input type="hidden" name="post_type" value="post">
                                    <input type="hidden" value="<?php echo wp_create_nonce('ct-live-results'); ?>" class="ct-live-results-nonce">
                                </div>
                                <div class="screen-reader-text" aria-live="polite" role="status">No results</div>
                            </form>
                        </div>
                        <div class="ct-contact-info" data-id="contacts">
                            <ul data-icons-type="rounded:solid">
                                    <?php $contact_url = get_theme_mod( 'singlo_contact_url', home_url( '/contact-us/' ) ); ?>
                                    <a href="<?php echo esc_url( $contact_url ); ?>" class="ct-icon-container" aria-label=" " target="_blank" rel="noopener noreferrer">
                                        <svg aria-hidden="true" width="20" height="20" viewBox="0,0,512,512">
                                            <path d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 448c-110.532 0-200-89.431-200-200 0-110.495 89.472-200 200-200 110.491 0 200 89.471 200 200 0 110.53-89.431 200-200 200zm107.244-255.2c0 67.052-72.421 68.084-72.421 92.863V300c0 6.627-5.373 12-12 12h-45.647c-6.627 0-12-5.373-12-12v-8.659c0-35.745 27.1-50.034 47.579-61.516 17.561-9.845 28.324-16.541 28.324-29.579 0-17.246-21.999-28.693-39.784-28.693-23.189 0-33.894 10.977-48.942 29.969-4.057 5.12-11.46 6.071-16.666 2.124l-27.824-21.098c-5.107-3.872-6.251-11.066-2.644-16.363C184.846 131.491 214.94 112 261.794 112c49.071 0 101.45 38.304 101.45 88.8zM298 368c0 23.159-18.841 42-42 42s-42-18.841-42-42 18.841-42 42-42 42 18.841 42 42z"></path>
                                        </svg>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div data-device="mobile">
        <div data-row="top" data-column-set="2">
            <div class="ct-container-fluid">
                <div data-column="start" data-placements="1">
                    <div data-items="primary">
                        <div class="site-branding" data-id="logo">
                            <div class="site-title-container">
                                <span class="site-title "><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php echo esc_html( $display_site_name ); ?></a></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div data-column="end" data-placements="1">
                    <div data-items="primary">
                        <button class="ct-header-search ct-toggle " data-toggle-panel="#search-modal" aria-controls="search-modal" aria-label="Search" data-label="left" data-id="search">
                            <span class="ct-label ct-hidden-sm ct-hidden-md ct-hidden-lg" aria-hidden="true">Search</span>
                            <svg class="ct-icon" aria-hidden="true" width="15" height="15" viewBox="0 0 15 15">
                                <path d="M14.8,13.7L12,11c0.9-1.2,1.5-2.6,1.5-4.2c0-3.7-3-6.8-6.8-6.8S0,3,0,6.8s3,6.8,6.8,6.8c1.6,0,3.1-0.6,4.2-1.5l2.8,2.8c0.1,0.1,0.3,0.2,0.5,0.2s0.4-0.1,0.5-0.2C15.1,14.5,15.1,14,14.8,13.7z M1.5,6.8c0-2.9,2.4-5.2,5.2-5.2S12,3.9,12,6.8S9.6,12,6.8,12S1.5,9.6,1.5,6.8z"></path>
                            </svg></button>
                        <div class="ct-contact-info" data-id="contacts">
                            <ul data-icons-type="rounded:solid">
                                <li class="">
                                    <?php $contact_url = get_theme_mod( 'singlo_contact_url', home_url( '/contact-us/' ) ); ?>
                                    <a href="<?php echo esc_url( $contact_url ); ?>" class="ct-icon-container" aria-label=" " target="_blank" rel="noopener noreferrer">
                                        <svg aria-hidden="true" width="20" height="20" viewBox="0,0,512,512">
                                            <path d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 448c-110.532 0-200-89.431-200-200 0-110.495 89.472-200 200-200 110.491 0 200 89.471 200 200 0 110.53-89.431 200-200 200zm107.244-255.2c0 67.052-72.421 68.084-72.421 92.863V300c0 6.627-5.373 12-12 12h-45.647c-6.627 0-12-5.373-12-12v-8.659c0-35.745 27.1-50.034 47.579-61.516 17.561-9.845 28.324-16.541 28.324-29.579 0-17.246-21.999-28.693-39.784-28.693-23.189 0-33.894 10.977-48.942 29.969-4.057 5.12-11.46 6.071-16.666 2.124l-27.824-21.098c-5.107-3.872-6.251-11.066-2.644-16.363C184.846 131.491 214.94 112 261.794 112c49.071 0 101.45 38.304 101.45 88.8zM298 368c0 23.159-18.841 42-42 42s-42-18.841-42-42 18.841-42 42-42 42 18.841 42 42z"></path>
                                        </svg>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <button class="ct-header-trigger ct-toggle " data-toggle-panel="#offcanvas" aria-controls="offcanvas" data-design="simple" data-label="bottom" aria-label="Menu" data-id="trigger">
                            <span class="ct-label " aria-hidden="true">Menu</span>
                            <svg class="ct-icon" width="18" height="14" viewBox="0 0 18 14" data-type="type-3" aria-hidden="true">
                                <rect y="0.00" width="18" height="1.7" rx="1"></rect>
                                <rect y="6.15" width="18" height="1.7" rx="1"></rect>
                                <rect y="12.3" width="18" height="1.7" rx="1"></rect>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div data-row="middle" data-column-set="1">
            <div class="ct-container-fluid">
                <div data-column="middle">
                    <div data-items="">
                        <nav class="mobile-menu-inline menu-container " data-id="mobile-menu-secondary" itemscope="" itemtype="https://schema.org/SiteNavigationElement" aria-label="menu 2">
                            <?php
                            wp_nav_menu( array(
                                'theme_location' => 'sub_header',
                                'menu_id'        => 'menu-menu-2',
                                'container'      => false,
                                'menu_class'     => 'menu',
                                'fallback_cb'    => '__return_false',
                                'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                                'depth'          => 1,
                                'walker'         => new Singlo_Desktop_Walker_Nav_Menu(),
                            ) );
                            ?>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="ct-drawer-canvas" data-location="start">
    <div id="search-modal" class="ct-panel" data-behaviour="modal" role="dialog" aria-label="Search modal" inert="">
        <div class="ct-panel-actions">
            <button class="ct-toggle-close" data-type="type-2" aria-label="Close search modal">
                <svg class="ct-icon" width="12" height="12" viewBox="0 0 15 15">
                    <path d="M1 15a1 1 0 01-.71-.29 1 1 0 010-1.41l5.8-5.8-5.8-5.8A1 1 0 011.7.29l5.8 5.8 5.8-5.8a1 1 0 011.41 1.41l-5.8 5.8 5.8 5.8a1 1 0 01-1.41 1.41l-5.8-5.8-5.8 5.8A1 1 0 011 15z"></path>
                </svg>
            </button>
        </div>
        <div class="ct-panel-content">
            <form role="search" method="get" class="ct-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>" aria-haspopup="listbox" data-live-results="thumbs">
                <input type="search" class="modal-field" placeholder="<?php echo esc_attr_x( 'Search for apps', 'placeholder', 'singlo' ); ?>" value="<?php echo get_search_query(); ?>" name="s" autocomplete="off" title="Search for..." aria-label="Search for...">
                <div class="ct-search-form-controls">
                    <button type="submit" class="wp-element-button" data-button="icon" aria-label="Search button">
                        <svg class="ct-icon ct-search-button-content" aria-hidden="true" width="15" height="15" viewBox="0 0 15 15">
                            <path d="M14.8,13.7L12,11c0.9-1.2,1.5-2.6,1.5-4.2c0-3.7-3-6.8-6.8-6.8S0,3,0,6.8s3,6.8,6.8,6.8c1.6,0,3.1-0.6,4.2-1.5l2.8,2.8c0.1,0.1,0.3,0.2,0.5,0.2s0.4-0.1,0.5-0.2C15.1,14.5,15.1,14,14.8,13.7z M1.5,6.8c0-2.9,2.4-5.2,5.2-5.2S12,3.9,12,6.8S9.6,12,6.8,12S1.5,9.6,1.5,6.8z"></path>
                        </svg>
                        <span class="ct-ajax-loader">
                            <svg viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" opacity="0.2" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="2"></circle>
                                <path d="m12,2c5.52,0,10,4.48,10,10" fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2">
                                    <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="0.6s" from="0 12 12" to="360 12 12" repeatCount="indefinite"></animateTransform>
                                </path>
                            </svg>
                        </span>
                    </button>
                    <input type="hidden" name="post_type" value="post">
                    <input type="hidden" value="<?php echo wp_create_nonce('ct-live-results'); ?>" class="ct-live-results-nonce">
                </div>
                <div class="screen-reader-text" aria-live="polite" role="status">No results</div>
            </form>
        </div>
    </div>

    <div id="offcanvas" class="ct-panel ct-header" data-behaviour="left-side" role="dialog" aria-label="Offcanvas modal" inert="">
        <div class="ct-panel-inner">
            <div class="ct-panel-actions">
                <button class="ct-toggle-close" data-type="type-2" aria-label="Close drawer">
                    <svg class="ct-icon" width="12" height="12" viewBox="0 0 15 15">
                        <path d="M1 15a1 1 0 01-.71-.29 1 1 0 010-1.41l5.8-5.8-5.8-5.8A1 1 0 011.7.29l5.8 5.8 5.8-5.8a1 1 0 011.41 1.41l-5.8 5.8 5.8 5.8a1 1 0 01-1.41 1.41l-5.8-5.8-5.8 5.8A1 1 0 011 15z"></path>
                    </svg>
                </button>
            </div>
            <div class="ct-panel-content" data-device="desktop">
                <div class="ct-panel-content-inner"></div>
            </div>
            <div class="ct-panel-content" data-device="mobile">
                <div class="ct-panel-content-inner">
                    <div class="ct-search-box" data-id="search-input">
                        <form role="search" method="get" class="ct-search-form" data-form-controls="inside" data-taxonomy-filter="false" data-submit-button="icon" action="<?php echo esc_url( home_url( '/' ) ); ?>" aria-haspopup="listbox" data-live-results="thumbs">
                            <input type="search" placeholder="<?php echo esc_attr_x( 'Search for apps', 'placeholder', 'singlo' ); ?>" value="<?php echo get_search_query(); ?>" name="s" autocomplete="off" title="Search for..." aria-label="Search for...">
                            <div class="ct-search-form-controls">
                                <button type="submit" class="wp-element-button" data-button="inside:icon" aria-label="Search button">
                                    <svg class="ct-icon ct-search-button-content" aria-hidden="true" width="15" height="15" viewBox="0 0 15 15">
                                        <path d="M14.8,13.7L12,11c0.9-1.2,1.5-2.6,1.5-4.2c0-3.7-3-6.8-6.8-6.8S0,3,0,6.8s3,6.8,6.8,6.8c1.6,0,3.1-0.6,4.2-1.5l2.8,2.8c0.1,0.1,0.3,0.2,0.5,0.2s0.4-0.1,0.5-0.2C15.1,14.5,15.1,14,14.8,13.7z M1.5,6.8c0-2.9,2.4-5.2,5.2-5.2S12,3.9,12,6.8S9.6,12,6.8,12S1.5,9.6,1.5,6.8z"></path>
                                    </svg>
                                    <span class="ct-ajax-loader">
                                        <svg viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="10" opacity="0.2" fill="none" stroke="currentColor" stroke-miterlimit="10" stroke-width="2"></circle>
                                            <path d="m12,2c5.52,0,10,4.48,10,10" fill="none" stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2">
                                                <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="0.6s" from="0 12 12" to="360 12 12" repeatCount="indefinite"></animateTransform>
                                            </path>
                                        </svg>
                                    </span>
                                </button>
                                <input type="hidden" name="post_type" value="post">
                                <input type="hidden" value="<?php echo wp_create_nonce('ct-live-results'); ?>" class="ct-live-results-nonce">
                            </div>
                            <div class="screen-reader-text" aria-live="polite" role="status">No results</div>
                        </form>
                    </div>

                    <nav class="mobile-menu menu-container has-submenu" data-id="mobile-menu" data-interaction="click" data-toggle-type="type-1" data-submenu-dots="yes" aria-label="SD mobile">
                        <?php
                        wp_nav_menu( array(
                            'theme_location' => 'mobile_header',
                            'container'      => false,
                            'menu_class'     => '',
                            'items_wrap'     => '<ul id="menu-sd-mobile" class="%2$s">%3$s</ul>',
                            'fallback_cb'    => '__return_false',
                            'walker'         => new Singlo_Mobile_Walker_Nav_Menu(),
                        ) );
                        ?>
                    </nav>

                    <div class="ct-header-divider" data-id="divider"></div>
                    <div class="ct-header-socials " data-id="socials">
                        <div class="ct-social-box" data-color="custom" data-icon-size="custom" data-icons-type="rounded:solid">
                            <?php 
                            $telegram_url = get_theme_mod( 'singlo_telegram_url', '' );
                            $youtube_url  = get_theme_mod( 'singlo_youtube_url', '' );
                            
                            if ( ! empty( $telegram_url ) ) :
                            ?>
                            <a href="<?php echo esc_url( $telegram_url ); ?>" data-network="telegram" aria-label="Telegram" target="_blank" rel="noopener noreferrer nofollow">
                                <span class="ct-icon-container">
                                    <svg width="20px" height="20px" viewBox="0 0 20 20" aria-hidden="true">
                                        <path d="M19.9,3.1l-3,14.2c-0.2,1-0.8,1.3-1.7,0.8l-4.6-3.4l-2.2,2.1c-0.2,0.2-0.5,0.5-0.9,0.5l0.3-4.7L16.4,5c0.4-0.3-0.1-0.5-0.6-0.2L5.3,11.4L0.7,10c-1-0.3-1-1,0.2-1.5l17.7-6.8C19.5,1.4,20.2,1.9,19.9,3.1z"></path>
                                    </svg>
                                </span>
                            </a>
                            <?php endif; ?>

                            <?php $contact_url = get_theme_mod( 'singlo_contact_url', home_url( '/contact-us/' ) ); ?>
                            <a href="<?php echo esc_url( $contact_url ); ?>" data-network="email" aria-label="Email" target="_blank" rel="noopener noreferrer nofollow">
                                <span class="ct-icon-container">
                                    <svg width="20" height="20" viewBox="0 0 20 20" aria-hidden="true">
                                        <path d="M10,10.1L0,4.7C0.1,3.2,1.4,2,3,2h14c1.6,0,2.9,1.2,3,2.8L10,10.1z M10,11.8c-0.1,0-0.2,0-0.4-0.1L0,6.4V15c0,1.7,1.3,3,3,3h4.9h4.3H17c1.7,0,3-1.3,3-3V6.4l-9.6,5.2C10.2,11.7,10.1,11.7,10,11.8z"></path>
                                    </svg>
                                </span>
                            </a>

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
    </div>
</div>