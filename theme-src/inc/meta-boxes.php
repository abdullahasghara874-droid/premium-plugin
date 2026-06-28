<?php
/**
 * Singlo Meta Boxes for App Details
 * UPDATED: Added "Standard WordPress Page" layout option
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function singlo_add_app_meta_boxes() {
    add_meta_box(
        'singlo_app_details',
        __( 'App Details', 'singlo' ),
        'singlo_app_details_callback',
        array( 'post', 'page' ),
        'normal',
        'high'
    );

    add_meta_box(
        'singlo_app_whats_new',
        __( 'What\'s New (Changelog)', 'singlo' ),
        'singlo_app_whats_new_callback',
        array( 'post', 'page' ),
        'normal',
        'high'
    );

    add_meta_box(
        'singlo_app_screenshots',
        __( 'App Screenshots', 'singlo' ),
        'singlo_app_screenshots_callback',
        array( 'post', 'page' ),
        'normal',
        'high'
    );

}
add_action( 'add_meta_boxes', 'singlo_add_app_meta_boxes' );

function singlo_app_details_callback( $post ) {
    wp_nonce_field( 'singlo_app_details_save', 'singlo_app_details_nonce' );

    $rating_value = get_post_meta( $post->ID, '_singlo_app_rating_value', true );
    $rating_count = get_post_meta( $post->ID, '_singlo_app_rating_count', true );
    $version      = get_post_meta( $post->ID, '_singlo_app_version', true );
    $size         = get_post_meta( $post->ID, '_singlo_app_size', true );
    $downloads    = get_post_meta( $post->ID, '_singlo_app_downloads', true );
    $developer    = get_post_meta( $post->ID, '_singlo_app_developer', true );
    $download_url = get_post_meta( $post->ID, '_singlo_app_download_url', true );
    $package_name = get_post_meta( $post->ID, '_singlo_app_package_name', true );
    $category     = get_post_meta( $post->ID, '_singlo_app_category', true );
    $min_os       = get_post_meta( $post->ID, '_singlo_app_min_os', true );
    $supported_device = get_post_meta( $post->ID, '_singlo_app_supported_devices', true );
    $post_layout      = get_post_meta( $post->ID, '_singlo_post_layout', true );
    $download_counts  = get_post_meta( $post->ID, '_singlo_download_counts', true );

    // Defaults
    if ( empty( $rating_value ) ) $rating_value = '4.5';
    if ( empty( $rating_count ) ) $rating_count = '100';
    if ( empty( $supported_device ) ) $supported_device = '';
    if ( ! isset( $post_layout ) ) $post_layout = '';
    if ( empty( $download_counts ) ) $download_counts = '0';
    ?>
    <style>
        .singlo-meta-row { margin-bottom: 15px; display: flex; align-items: center; }
        .singlo-meta-row label { width: 150px; font-weight: 600; }
        .singlo-meta-row input, .singlo-meta-row select { flex: 1; padding: 8px; }
    </style>

    <div class="singlo-meta-row" style="background: #f0f0f0; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
        <label for="singlo_post_layout" style="color: #d63638;"><?php _e( 'Page Layout', 'singlo' ); ?></label>
        <select name="singlo_post_layout" id="singlo_post_layout" style="border: 2px solid #d63638;">
            <option value="" <?php selected( $post_layout, '' ); ?>><?php _e( 'Standard WordPress Page', 'singlo' ); ?></option>
            <option value="app" <?php selected( $post_layout, 'app' ); ?>><?php _e( 'Application Review', 'singlo' ); ?></option>
            <option value="guide" <?php selected( $post_layout, 'guide' ); ?>><?php _e( 'Guide/Article', 'singlo' ); ?></option>
        </select>
    </div>

    <div class="singlo-meta-row">
        <label for="singlo_app_rating_value"><?php _e( 'Average Rating (1-5)', 'singlo' ); ?></label>
        <input type="number" step="0.01" min="0" max="5" name="singlo_app_rating_value" id="singlo_app_rating_value" value="<?php echo esc_attr( $rating_value ); ?>">
    </div>

    <div class="singlo-meta-row">
        <label for="singlo_app_rating_count"><?php _e( 'Rating Count', 'singlo' ); ?></label>
        <input type="number" name="singlo_app_rating_count" id="singlo_app_rating_count" value="<?php echo esc_attr( $rating_count ); ?>">
    </div>

    <div class="singlo-meta-row">
        <label for="singlo_app_version"><?php _e( 'Version', 'singlo' ); ?></label>
        <input type="text" name="singlo_app_version" id="singlo_app_version" value="<?php echo esc_attr( $version ); ?>" placeholder="e.g. 1.0.2a">
    </div>

    <div class="singlo-meta-row">
        <label for="singlo_app_size"><?php _e( 'Size', 'singlo' ); ?></label>
        <input type="text" name="singlo_app_size" id="singlo_app_size" value="<?php echo esc_attr( $size ); ?>" placeholder="e.g. 48.24 MB">
    </div>

    <div class="singlo-meta-row">
        <label for="singlo_app_downloads"><?php _e( 'Downloads', 'singlo' ); ?></label>
        <input type="text" name="singlo_app_downloads" id="singlo_app_downloads" value="<?php echo esc_attr( $downloads ); ?>" placeholder="e.g. 6k+">
    </div>

    <div class="singlo-meta-row">
        <label for="singlo_app_developer"><?php _e( 'Developer', 'singlo' ); ?></label>
        <input type="text" name="singlo_app_developer" id="singlo_app_developer" value="<?php echo esc_attr( $developer ); ?>" placeholder="e.g. Meta TV Team">
    </div>

    <div class="singlo-meta-row">
        <label for="singlo_app_download_url"><?php _e( 'Download URL', 'singlo' ); ?></label>
        <input type="url" name="singlo_app_download_url" id="singlo_app_download_url" value="<?php echo esc_url( $download_url ); ?>" placeholder="https://example.com/file.apk">
    </div>

    <div class="singlo-meta-row">
        <label for="singlo_app_package_name"><?php _e( 'Package Name', 'singlo' ); ?></label>
        <input type="text" name="singlo_app_package_name" id="singlo_app_package_name" value="<?php echo esc_attr( $package_name ); ?>" placeholder="e.g. com.metatv.apk">
    </div>

    <div class="singlo-meta-row">
        <label for="singlo_app_category"><?php _e( 'Category', 'singlo' ); ?></label>
        <input type="text" name="singlo_app_category" id="singlo_app_category" value="<?php echo esc_attr( $category ); ?>" placeholder="e.g. Movies & TV">
    </div>

    <div class="singlo-meta-row">
        <label for="singlo_app_min_os"><?php _e( 'Minimum OS', 'singlo' ); ?></label>
        <input type="text" name="singlo_app_min_os" id="singlo_app_min_os" value="<?php echo esc_attr( $min_os ); ?>" placeholder="e.g. Android 5.0+">
    </div>

    <div class="singlo-meta-row">
        <label for="singlo_app_supported_devices"><?php _e( 'Supported Devices', 'singlo' ); ?></label>
        <select name="singlo_app_supported_devices" id="singlo_app_supported_devices" style="flex: 1; padding: 8px;">
            <option value="" <?php selected( $supported_device, '' ); ?>><?php _e( 'Select Devices...', 'singlo' ); ?></option>
            <option value="MOBILE" <?php selected( $supported_device, 'MOBILE' ); ?>><?php _e( 'Mobile', 'singlo' ); ?></option>
            <option value="TV" <?php selected( $supported_device, 'TV' ); ?>><?php _e( 'Tv', 'singlo' ); ?></option>
            <option value="MOBILE_AND_TV" <?php selected( $supported_device, 'MOBILE_AND_TV' ); ?>><?php _e( 'Mobile and TV', 'singlo' ); ?></option>
        </select>
    </div>

    <div class="singlo-meta-row" style="background: #fff8e1; padding: 10px; border-radius: 5px; border: 1px solid #ffe082;">
        <label for="singlo_download_counts"><?php _e( 'Download Counts (Actual)', 'singlo' ); ?></label>
        <input type="number" name="singlo_download_counts" id="singlo_download_counts" value="<?php echo esc_attr( $download_counts ); ?>" style="font-weight: bold; color: #ff8f00;">
    </div>
<?php
}

function singlo_app_whats_new_callback( $post ) {
    $changelogs = get_post_meta( $post->ID, '_singlo_app_changelogs', true );
    if ( ! is_array( $changelogs ) ) {
        $changelogs = array();
    }
    ?>
    <style>
        .singlo-changelog-item { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; margin-bottom: 15px; position: relative; }
        .singlo-changelog-header { display: flex; gap: 15px; margin-bottom: 15px; align-items: flex-end; }
        .singlo-changelog-field { display: flex; flex-direction: column; gap: 5px; flex: 1; }
        .singlo-changelog-field label { font-weight: 600; font-size: 13px; color: #1e293b; }
        .singlo-changelog-field input { padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; }
        .singlo-remove-changelog { background: #fee2e2; color: #ef4444; border: 1px solid #fecaca; padding: 8px 15px; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 12px; }
        .singlo-remove-changelog:hover { background: #fecaca; }
        .singlo-add-changelog { background: #4f46e5; color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; font-weight: 600; margin-top: 10px; display: inline-flex; align-items: center; gap: 8px; }
        .singlo-add-changelog:hover { background: #4338ca; }
    </style>

    <div id="singlo-changelog-wrapper">
        <div class="changelog-list">
            <?php foreach ( $changelogs as $index => $log ) :
                $version = isset($log['version']) ? $log['version'] : '';
                $date = isset($log['date']) ? $log['date'] : '';
                $note = isset($log['note']) ? $log['note'] : '';
            ?>
                <div class="singlo-changelog-item">
                    <div class="singlo-changelog-header">
                        <div class="singlo-changelog-field">
                            <label><?php _e( 'Version', 'singlo' ); ?></label>
                            <input type="text" name="singlo_changelog_versions[]" value="<?php echo esc_attr( $version ); ?>" placeholder="e.g. 2.1.0">
                        </div>
                        <div class="singlo-changelog-field">
                            <label><?php _e( 'Release Date', 'singlo' ); ?></label>
                            <input type="date" name="singlo_changelog_dates[]" value="<?php echo esc_attr( $date ); ?>">
                        </div>
                        <button type="button" class="singlo-remove-changelog"><?php _e( 'Remove', 'singlo' ); ?></button>
                    </div>
                    <div class="singlo-changelog-field">
                        <label><?php _e( 'Changes / Notes', 'singlo' ); ?></label>
                        <?php
                        wp_editor( $note, 'singlo_changelog_note_' . $index, array(
                            'textarea_name' => 'singlo_changelog_notes[]',
                            'media_buttons' => false,
                            'textarea_rows' => 4,
                            'teeny'         => true,
                            'tinymce'       => true,
                            'quicktags'     => true
                        ) );
                        ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="singlo-add-changelog">
            <span class="dashicons dashicons-plus-alt"></span> <?php _e( 'Add Version Entry', 'singlo' ); ?>
        </button>
    </div>

    <script>
    jQuery(document).ready(function($) {
        let logIndex = <?php echo count($changelogs); ?>;

        $('.singlo-add-changelog').on('click', function() {
            const id = 'singlo_changelog_note_new_' + logIndex;
            const html = `
                <div class="singlo-changelog-item">
                    <div class="singlo-changelog-header">
                        <div class="singlo-changelog-field">
                            <label>Version</label>
                            <input type="text" name="singlo_changelog_versions[]" placeholder="e.g. 1.0.0">
                        </div>
                        <div class="singlo-changelog-field">
                            <label>Release Date</label>
                            <input type="date" name="singlo_changelog_dates[]">
                        </div>
                        <button type="button" class="singlo-remove-changelog">Remove</button>
                    </div>
                    <div class="singlo-changelog-field">
                        <label>Changes / Notes</label>
                        <textarea id="${id}" name="singlo_changelog_notes[]" rows="4"></textarea>
                    </div>
                </div>
            `;
            $('.changelog-list').append(html);

            if (typeof wp !== 'undefined' && wp.editor) {
                wp.editor.initialize(id, {
                    tinymce: {
                        wpautop: true,
                        plugins: 'charmap,hr,media,paste,tabfocus,textcolor,fullscreen,wordpress,wpeditimage,wplink,wpdialogs,wpview',
                        toolbar1: 'bold,italic,bullist,numlist,link,unlink,undo,redo'
                    },
                    quicktags: true,
                    mediaButtons: false
                });
            }
            logIndex++;
        });

        $(document).on('click', '.singlo-remove-changelog', function() {
            if (confirm('Delete this version entry?')) {
                const item = $(this).closest('.singlo-changelog-item');
                const editorId = item.find('textarea').attr('id');
                if (typeof wp !== 'undefined' && wp.editor && editorId) {
                    wp.editor.remove(editorId);
                }
                item.remove();
            }
        });
    });
    </script>
    <?php
}

function singlo_app_screenshots_callback( $post ) {
    $screenshots = get_post_meta( $post->ID, '_singlo_app_screenshots', true );
    if ( ! is_array( $screenshots ) ) {
        $screenshots = array();
    }
    ?>
    <div id="singlo-screenshots-wrapper">
        <div class="screenshots-list">
            <?php foreach ( $screenshots as $index => $url ) : ?>
                <div class="screenshot-row" style="margin-bottom: 10px; display: flex; gap: 10px;">
                    <input type="url" name="singlo_app_screenshots[]" value="<?php echo esc_url( $url ); ?>" placeholder="Screenshot URL" style="flex: 1;">
                    <button type="button" class="button remove-screenshot">Remove</button>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="button add-screenshot" style="margin-top: 10px;">Add New Screenshot</button>
    </div>

    <script>
    jQuery(document).ready(function($) {
        $('.add-screenshot').on('click', function() {
            var row = '<div class="screenshot-row" style="margin-bottom: 10px; display: flex; gap: 10px;">' +
                      '<input type="url" name="singlo_app_screenshots[]" value="" placeholder="Screenshot URL" style="flex: 1;">' +
                      '<button type="button" class="button remove-screenshot">Remove</button>' +
                      '</div>';
            $('.screenshots-list').append(row);
        });

        $(document).on('click', '.remove-screenshot', function() {
            $(this).closest('.screenshot-row').remove();
        });
    });
    </script>
    <?php
}

function singlo_save_app_meta( $post_id ) {
    if ( ! isset( $_POST['singlo_app_details_nonce'] ) || ! wp_verify_nonce( $_POST['singlo_app_details_nonce'], 'singlo_app_details_save' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    $fields = [
        'singlo_app_rating_value' => '_singlo_app_rating_value',
        'singlo_app_rating_count' => '_singlo_app_rating_count',
        'singlo_app_version'      => '_singlo_app_version',
        'singlo_app_size'         => '_singlo_app_size',
        'singlo_app_downloads'    => '_singlo_app_downloads',
        'singlo_app_developer'    => '_singlo_app_developer',
        'singlo_app_download_url' => '_singlo_app_download_url',
        'singlo_app_whats_new'    => '_singlo_app_whats_new',
        'singlo_app_package_name' => '_singlo_app_package_name',
        'singlo_app_category'     => '_singlo_app_category',
        'singlo_app_min_os'       => '_singlo_app_min_os',
        'singlo_app_supported_devices' => '_singlo_app_supported_devices',
        'singlo_post_layout'         => '_singlo_post_layout',
        'singlo_download_counts'     => '_singlo_download_counts',
    ];

    foreach ( $fields as $key => $meta_key ) {
        if ( isset( $_POST[$key] ) ) {
            update_post_meta( $post_id, $meta_key, sanitize_text_field( $_POST[$key] ) );
        }
    }

    if ( isset( $_POST['singlo_changelog_versions'] ) && is_array( $_POST['singlo_changelog_versions'] ) ) {
        $versions = $_POST['singlo_changelog_versions'];
        $dates    = $_POST['singlo_changelog_dates'];
        $notes    = $_POST['singlo_changelog_notes'];
        $changelogs = array();

        foreach ( $versions as $index => $version ) {
            if ( ! empty( $version ) ) {
                $changelogs[] = array(
                    'version' => sanitize_text_field( $version ),
                    'date'    => sanitize_text_field( $dates[$index] ),
                    'note'    => wp_kses_post( $notes[$index] )
                );
            }
        }
        update_post_meta( $post_id, '_singlo_app_changelogs', $changelogs );
    } else {
        delete_post_meta( $post_id, '_singlo_app_changelogs' );
    }

    if ( isset( $_POST['singlo_app_screenshots'] ) && is_array( $_POST['singlo_app_screenshots'] ) ) {
        $screenshots = array_map( 'esc_url_raw', $_POST['singlo_app_screenshots'] );
        $screenshots = array_filter( $screenshots ); // Remove empty values
        update_post_meta( $post_id, '_singlo_app_screenshots', $screenshots );
    } else {
        delete_post_meta( $post_id, '_singlo_app_screenshots' );
    }
}
add_action( 'save_post', 'singlo_save_app_meta' );