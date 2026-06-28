<?php
/**
 * The template for displaying comments
 */

if ( post_password_required() ) {
	return;
}
?>

<div class="ct-comments is-width-constrained" id="comments">

	<?php if ( have_comments() ) : ?>
		<h3 class="ct-comments-title">
			<?php
			$singlo_comment_count = get_comments_number();
			if ( '1' === $singlo_comment_count ) {
				printf(
					esc_html__( 'One Comment', 'singlo' )
				);
			} else {
				printf(
					esc_html( _n( '%s Comment', '%s Comments', $singlo_comment_count, 'singlo' ) ),
					number_format_i18n( $singlo_comment_count )
				);
			}
			?>
		</h3>

		<ol class="ct-comment-list">
			<?php
			wp_list_comments( array(
				'style'      => 'ol',
				'short_ping' => true,
				'avatar_size' => 100,
                'callback' => 'singlo_comment_callback'
			) );
			?>
		</ol>

		<?php
		the_comments_navigation();

		if ( ! comments_open() ) :
			?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'singlo' ); ?></p>
			<?php
		endif;

	endif;

    $commenter = wp_get_current_commenter();
    $req = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );

    $fields = array(
        'author' => '<p class="comment-form-field-input-author">
                        <label for="author">' . __( 'Name', 'singlo' ) . ( $req ? ' <b class="required">&nbsp;*</b>' : '' ) . '</label>
                        <input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' required="required">
                    </p>',
        'email'  => '<p class="comment-form-field-input-email">
                        <label for="email">' . __( 'Email', 'singlo' ) . ( $req ? ' <b class="required">&nbsp;*</b>' : '' ) . '</label>
                        <input id="email" name="email" type="text" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' required="required">
                    </p>',
        'cookies' => '<p class="comment-form-cookies-consent"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"' . ( empty( $commenter['comment_author_email'] ) ? '' : ' checked="checked"' ) . '><label for="wp-comment-cookies-consent">' . __( 'Save my name and email in this browser for the next time I comment.', 'singlo' ) . '</label></p>',
    );

	comment_form( array(
		'class_form' => 'comment-form has-labels-inside',
		'title_reply' => __( 'Leave a Reply', 'singlo' ),
        'title_reply_before' => '<h2 id="reply-title" class="comment-reply-title">',
        'title_reply_after' => '<span class="ct-cancel-reply">' . get_cancel_comment_reply_link( __( 'Cancel Reply', 'singlo' ) ) . '</span></h2>',
        'comment_field' => '<p class="comment-form-field-textarea">
                                <label for="comment">' . _x( 'Add Comment', 'noun', 'singlo' ) . '<b class="required">&nbsp;*</b></label>
                                <textarea id="comment" name="comment" cols="45" rows="8" required="required"></textarea>
                            </p>',
        'fields' => $fields,
        'submit_button' => '<button type="submit" name="submit" id="submit" class="submit" value="Post Comment">' . __( 'Post Comment', 'singlo' ) . '</button>',
        'format' => 'html5',
	) );
	?>

</div>
