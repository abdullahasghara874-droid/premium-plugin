<?php
/**
 * Theme Settings Page
 * Custom dashboard menu for Telegram, YouTube, Contact, Copyright, and FAQs.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add Admin Menu Page
 */
function singlo_add_theme_settings_menu() {
    add_menu_page(
        __( 'Singlo Settings', 'singlo' ),
        __( 'Singlo Settings', 'singlo' ),
        'manage_options',
        'singlo-settings',
        'singlo_theme_settings_callback',
        'dashicons-admin-generic',
        60
    );
}
add_action( 'admin_menu', 'singlo_add_theme_settings_menu' );

/**
 * Settings Page Callback
 */
function singlo_theme_settings_callback() {
    // Save settings if form is submitted
    if ( isset( $_POST['singlo_save_settings'] ) && check_admin_referer( 'singlo_settings_nonce_action', 'singlo_settings_nonce_field' ) ) {
        
        set_theme_mod( 'singlo_telegram_url', esc_url_raw( $_POST['singlo_telegram_url'] ) );
        set_theme_mod( 'singlo_youtube_url', esc_url_raw( $_POST['singlo_youtube_url'] ) );
        set_theme_mod( 'singlo_request_update_url', esc_url_raw( $_POST['singlo_request_update_url'] ) );
        set_theme_mod( 'singlo_contact_url', esc_url_raw( $_POST['singlo_contact_url'] ) );
        set_theme_mod( 'singlo_footer_copyright', wp_kses_post( $_POST['singlo_footer_copyright'] ) );
        set_theme_mod( 'singlo_site_name', sanitize_text_field( $_POST['singlo_site_name'] ) );
        set_theme_mod( 'singlo_home_redirect_id', intval( $_POST['singlo_home_redirect_id'] ) );
        
        // Handle FAQs
        $faqs = array();
        if ( isset( $_POST['singlo_faq_questions'] ) && isset( $_POST['singlo_faq_answers'] ) ) {
            $questions = $_POST['singlo_faq_questions'];
            $answers = $_POST['singlo_faq_answers'];
            
            foreach ( $questions as $index => $question ) {
                if ( ! empty( $question ) ) {
                    $faqs[] = array(
                        'question' => sanitize_text_field( $question ),
                        'answer'   => wp_kses_post( $answers[$index] )
                    );
                }
            }
        }
        set_theme_mod( 'singlo_faqs', $faqs );

        echo '<div class="updated settings-error notice is-dismissible"><p><strong>' . __( 'Settings saved successfully!', 'singlo' ) . '</strong></p></div>';
    }

    $telegram = get_theme_mod( 'singlo_telegram_url', '' );
    $youtube = get_theme_mod( 'singlo_youtube_url', '' );
    $request_update = get_theme_mod( 'singlo_request_update_url', '' );
    $contact = get_theme_mod( 'singlo_contact_url', '' );
    $copyright = get_theme_mod( 'singlo_footer_copyright', '' );
    $site_name = get_theme_mod( 'singlo_site_name', '' );
    $home_redirect_id = get_theme_mod( 'singlo_home_redirect_id', 0 );
    $home_redirect_title = $home_redirect_id ? get_the_title( $home_redirect_id ) : '';
    $faqs = get_theme_mod( 'singlo_faqs', array() );
    ?>
    <style>
        :root {
            --singlo-primary: #4f46e5;
            --singlo-primary-hover: #4338ca;
            --singlo-bg: #f8fafc;
            --singlo-card-bg: #ffffff;
            --singlo-border: #e2e8f0;
            --singlo-text-main: #1e293b;
            --singlo-text-muted: #64748b;
            --singlo-success: #10b981;
        }

        .singlo-admin-container {
            max-width: 1100px;
            margin: 30px 20px 40px 0;
            font-family: 'Inter', -apple-system, sans-serif;
        }

        .singlo-admin-header {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            padding: 40px;
            border-radius: 16px;
            color: white;
            margin-bottom: 30px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .singlo-admin-header h1 {
            color: white;
            font-size: 32px;
            font-weight: 800;
            margin: 0;
            letter-spacing: -0.025em;
        }

        .singlo-admin-header p {
            margin: 10px 0 0 0;
            opacity: 0.8;
            font-size: 16px;
        }

        .singlo-admin-card {
            background: var(--singlo-card-bg);
            border: 1px solid var(--singlo-border);
            border-radius: 16px;
            padding: 35px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .singlo-card-title {
            font-size: 20px;
            font-weight: 700;
            margin: 0 0 25px 0;
            color: var(--singlo-text-main);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .singlo-card-title .dashicons {
            font-size: 24px;
            width: 24px;
            height: 24px;
            color: var(--singlo-primary);
        }

        .singlo-form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .singlo-form-group {
            margin-bottom: 25px;
        }

        .singlo-form-group label {
            display: block;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 8px;
            color: var(--singlo-text-main);
        }

        .singlo-inline-input {
            width: 100%;
            background: #f1f5f9;
            border: 1px solid var(--singlo-border);
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.2s;
            box-sizing: border-box;
        }

        .singlo-inline-input:focus {
            background: white;
            border-color: var(--singlo-primary);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
            outline: none;
        }

        .singlo-faq-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .singlo-faq-card {
            background: #f8fafc;
            border: 1px solid var(--singlo-border);
            border-radius: 12px;
            padding: 20px;
            position: relative;
        }

        .singlo-faq-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .singlo-remove-btn {
            color: #ef4444;
            background: #fee2e2;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
        }

        .singlo-remove-btn:hover {
            background: #fecaca;
        }

        .singlo-add-btn {
            background: var(--singlo-primary);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: 0.2s;
            margin-top: 10px;
        }

        .singlo-add-btn:hover {
            background: var(--singlo-primary-hover);
            transform: translateY(-1px);
        }

        .singlo-save-bar {
            position: sticky;
            bottom: 20px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            padding: 20px;
            border-radius: 16px;
            border: 1px solid var(--singlo-border);
            display: flex;
            justify-content: flex-end;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            z-index: 100;
        }

        .singlo-save-btn {
            background: var(--singlo-success);
            color: white;
            border: none;
            padding: 14px 40px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 16px;
            cursor: pointer;
            transition: 0.2s;
        }

        .singlo-save-btn:hover {
            background: #059669;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
        }
    </style>

    <div class="singlo-admin-container">
        <div class="singlo-admin-header">
            <h1><?php _e( 'Theme Control Center', 'singlo' ); ?></h1>
            <p><?php _e( 'Configure your global theme settings, social links, and Knowledge Base.', 'singlo' ); ?></p>
        </div>

        <form method="post" action="">
            <?php wp_nonce_field( 'singlo_settings_nonce_action', 'singlo_settings_nonce_field' ); ?>

            <div class="singlo-admin-card">
                <h2 class="singlo-card-title"><span class="dashicons dashicons-admin-home"></span> <?php _e( 'Homepage Redirect', 'singlo' ); ?></h2>
                <div class="singlo-form-group">
                    <label for="singlo_home_search"><?php _e( 'Select Redirect Post', 'singlo' ); ?></label>
                    <div style="position: relative;">
                        <input type="text" id="singlo_home_search" class="singlo-inline-input" placeholder="Search for a post title..." autocomplete="off" value="<?php echo esc_attr($home_redirect_title); ?>">
                        <input type="hidden" name="singlo_home_redirect_id" id="singlo_home_redirect_id" value="<?php echo esc_attr($home_redirect_id); ?>">
                        <div id="singlo-search-results" style="position: absolute; top: 100%; left: 0; right: 0; background: white; border: 1px solid #ddd; border-top: none; z-index: 1000; display: none; border-radius: 0 0 10px 10px; max-height: 250px; overflow-y: auto; box-shadow: 0 4px 6px rgba(0,0,0,0.1);"></div>
                    </div>
                    <p class="description"><?php _e( 'Searching for a post will show 10 results. Select one to automatically redirect the home URL to it.', 'singlo' ); ?></p>
                </div>
            </div>

            <div class="singlo-admin-card">
                <h2 class="singlo-card-title"><span class="dashicons dashicons-admin-settings"></span> <?php _e( 'App Identity', 'singlo' ); ?></h2>
                <div class="singlo-form-group">
                    <label for="singlo_site_name"><?php _e( 'Custom Site Name', 'singlo' ); ?></label>
                    <input name="singlo_site_name" type="text" id="singlo_site_name" value="<?php echo esc_attr( $site_name ); ?>" class="singlo-inline-input" placeholder="Enter custom name (fallback to blog name)">
                    <p class="description"><?php _e( 'This name will appear in the header. Leave empty to use default WordPress site name.', 'singlo' ); ?></p>
                </div>
            </div>

            <div class="singlo-admin-card">
                <h2 class="singlo-card-title"><span class="dashicons dashicons-share"></span> <?php _e( 'Social Connect', 'singlo' ); ?></h2>
                <div class="singlo-form-grid">
                    <div class="singlo-form-group">
                        <label for="singlo_telegram_url"><?php _e( 'Telegram Channel', 'singlo' ); ?></label>
                        <input name="singlo_telegram_url" type="url" id="singlo_telegram_url" value="<?php echo esc_url( $telegram ); ?>" class="singlo-inline-input" placeholder="https://t.me/...">
                    </div>
                    <div class="singlo-form-group">
                        <label for="singlo_youtube_url"><?php _e( 'YouTube Channel', 'singlo' ); ?></label>
                        <input name="singlo_youtube_url" type="url" id="singlo_youtube_url" value="<?php echo esc_url( $youtube ); ?>" class="singlo-inline-input" placeholder="https://youtube.com/...">
                    </div>
                </div>
            </div>

            <div class="singlo-admin-card">
                <h2 class="singlo-card-title"><span class="dashicons dashicons-email"></span> <?php _e( 'Support & Legal', 'singlo' ); ?></h2>
                <div class="singlo-form-group">
                    <label for="singlo_request_update_url"><?php _e( 'Request Update URL', 'singlo' ); ?></label>
                    <input name="singlo_request_update_url" type="url" id="singlo_request_update_url" value="<?php echo esc_url( $request_update ); ?>" class="singlo-inline-input" placeholder="URL for requesting updates (e.g. Telegram group link)">
                </div>
                <div class="singlo-form-group">
                    <label for="singlo_contact_url"><?php _e( 'Contact Us Page', 'singlo' ); ?></label>
                    <input name="singlo_contact_url" type="url" id="singlo_contact_url" value="<?php echo esc_url( $contact ); ?>" class="singlo-inline-input" placeholder="URL to your contact page">
                </div>
                <div class="singlo-form-group">
                    <label for="singlo_footer_copyright"><?php _e( 'Footer Copyright Text', 'singlo' ); ?></label>
                    <textarea name="singlo_footer_copyright" id="singlo_footer_copyright" rows="3" class="singlo-inline-input"><?php echo esc_textarea( $copyright ); ?></textarea>
                </div>
            </div>

            <div class="singlo-admin-card">
                <h2 class="singlo-card-title"><span class="dashicons dashicons-editor-help"></span> <?php _e( 'Knowledge Base (FAQs)', 'singlo' ); ?></h2>
                <div id="singlo-faq-list" class="singlo-faq-container">
                    <?php if ( ! empty( $faqs ) ) : ?>
                        <?php foreach ( $faqs as $index => $faq ) : ?>
                            <div class="singlo-faq-card">
                                <div class="singlo-faq-card-header">
                                    <span class="dashicons dashicons-menu" style="color: #cbd5e1; cursor: grab;"></span>
                                    <button type="button" class="singlo-remove-btn remove-faq"><?php _e( 'Delete', 'singlo' ); ?></button>
                                </div>
                                <div class="singlo-form-group">
                                    <label><?php _e( 'Question', 'singlo' ); ?></label>
                                    <input type="text" name="singlo_faq_questions[]" value="<?php echo esc_attr( $faq['question'] ); ?>" class="singlo-inline-input">
                                </div>
                                <div class="singlo-form-group" style="margin-bottom: 0;">
                                    <label><?php _e( 'Detailed Answer', 'singlo' ); ?></label>
                                    <?php 
                                    wp_editor( $faq['answer'], 'singlo_faq_answer_' . $index, array(
                                        'textarea_name' => 'singlo_faq_answers[]',
                                        'media_buttons' => false,
                                        'textarea_rows' => 5,
                                        'tinymce'       => true,
                                        'quicktags'     => true
                                    ) ); 
                                    ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <button type="button" class="singlo-add-btn add-faq">
                    <span class="dashicons dashicons-plus"></span> <?php _e( 'Add New FAQ Item', 'singlo' ); ?>
                </button>
            </div>

            <div class="singlo-save-bar">
                <input type="submit" name="singlo_save_settings" id="submit" class="singlo-save-btn" value="<?php _e( 'Update Settings', 'singlo' ); ?>">
            </div>
        </form>
    </div>

    <script>
    jQuery(document).ready(function($) {
        let faqIndex = <?php echo count($faqs); ?>;

        $('.add-faq').on('click', function() {
            const id = 'singlo_faq_answer_new_' + faqIndex;
            const html = `
                <div class="singlo-faq-card" style="opacity: 0; transform: translateY(10px); transition: 0.3s ease forwards;">
                    <div class="singlo-faq-card-header">
                        <span class="dashicons dashicons-menu" style="color: #cbd5e1;"></span>
                        <button type="button" class="singlo-remove-btn remove-faq">Delete</button>
                    </div>
                    <div class="singlo-form-group">
                        <label>Question</label>
                        <input type="text" name="singlo_faq_questions[]" value="" class="singlo-inline-input" placeholder="Enter question...">
                    </div>
                    <div class="singlo-form-group" style="margin-bottom: 0;">
                        <label>Detailed Answer</label>
                        <textarea name="singlo_faq_answers[]" id="${id}" rows="5" class="singlo-inline-input"></textarea>
                    </div>
                </div>
            `;
            const $newCard = $(html);
            $('#singlo-faq-list').append($newCard);
            setTimeout(() => $newCard.css({opacity: 1, transform: 'translateY(0)'}), 10);
            
            if (typeof wp !== 'undefined' && wp.editor) {
                wp.editor.initialize(id, {
                    tinymce: {
                        wpautop: true,
                        plugins: 'charmap,hr,media,paste,tabfocus,textcolor,fullscreen,wordpress,wpeditimage,wpgallery,wplink,wpdialogs,wpview',
                        toolbar1: 'bold,italic,bullist,numlist,link,unlink,blockquote,fullscreen'
                    },
                    quicktags: true,
                    mediaButtons: false
                });
            }
            faqIndex++;
        });

        // Post Search for Home Redirect
        let searchTimer;
        $('#singlo_home_search').on('input', function() {
            clearTimeout(searchTimer);
            const query = $(this).val();
            const resultsBox = $('#singlo-search-results');

            if (query.length < 2) {
                resultsBox.hide().empty();
                return;
            }

            searchTimer = setTimeout(() => {
                $.ajax({
                    url: ajaxurl,
                    data: {
                        action: 'singlo_search_posts_ajax',
                        q: query,
                        nonce: '<?php echo wp_create_nonce("singlo_search_nonce"); ?>'
                    },
                    success: function(response) {
                        if (response.success && response.data.length > 0) {
                            let html = '';
                            response.data.forEach(post => {
                                html += `<div class="search-result-item" data-id="${post.id}" data-title="${post.title}" style="padding: 10px 15px; cursor: pointer; border-bottom: 1px solid #eee;">${post.title}</div>`;
                            });
                            resultsBox.html(html).show();
                        } else {
                            resultsBox.html('<div style="padding: 10px 15px;">No posts found</div>').show();
                        }
                    }
                });
            }, 300);
        });

        $(document).on('click', '.search-result-item', function() {
            const id = $(this).data('id');
            const title = $(this).data('title');
            $('#singlo_home_redirect_id').val(id);
            $('#singlo_home_search').val(title);
            $('#singlo-search-results').hide().empty();
        });

        $(document).on('click', function(e) {
            if (!$(e.target).closest('#singlo_home_search, #singlo-search-results').length) {
                $('#singlo-search-results').hide();
            }
        });

        $(document).on('click', '.remove-faq', function() {
            if (confirm('Permanently delete this FAQ item?')) {
                const item = $(this).closest('.singlo-faq-card');
                const editorId = item.find('textarea').attr('id');
                if (typeof wp !== 'undefined' && wp.editor && editorId) {
                    wp.editor.remove(editorId);
                }
                item.css({opacity: 0, transform: 'scale(0.95)'});
                setTimeout(() => item.remove(), 300);
            }
        });
    });
    </script>
    <?php
}
