<?php
/**
 * Blank Theme.
 *
 * @package    WP Theme Dev
 * @subpackage Blank Theme
 * @since      0.2.0
 */

namespace Blank;

/**
 * Theme Comment Class.
 *
 * @category  Theme Comment
 */
class Comment extends Feature {
	/**
	 * Initialize class.
	 *
	 * @since 0.1.1
	 */
	protected function initialize() : void {
		add_filter( 'comment_form_defaults', [ $this, 'form_defaults' ] );
		add_filter( 'comment_form_default_fields', [ $this, 'form_default_fieds' ] );
	}

	/**
	 * Comment form default fields.
	 *
	 * @internal
	 * @since 0.1.0
	 * @param array $fields
	 * @return array
	 */
	public function form_default_fieds( $fields ) {
		$commenter = wp_get_current_commenter();

		$req      = get_option( 'require_name_email' );
		$req_atts = ( $req ? 'required="required"' : '' );
		$req_html = ( ! $req ? '<span>(opsional)</span>' : '' );

		$fields['author'] = join( '', [
			'<div class="field comment-form-author">',
			'<label class="label" for="author">' . __( 'Name', 'blank' ) . $req_html . '</label>',
			'<div class="control">',
			'<input ' . $req_atts . ' id="author" name="author" class="input" type="text" placeholder="e.g Alex Smith" value="' . esc_attr( $commenter['comment_author'] ) . '" maxlength="245"/>',
			'</div></div>',
		] );

		$fields['email'] = join( '', [
			'<div class="field comment-form-email">',
			'<label class="label" for="email">' . __( 'Email', 'blank' ) . $req_html . '</label>',
			'<div class="control">',
			'<input ' . $req_atts . ' id="email" name="email" class="input" type="email" placeholder="e.g alex@acme.com" value="' . esc_attr( $commenter['comment_author_email'] ) . '" maxlength="100" aria-describedby="email-notes"/>',
			'</div></div>',
		] );

		$fields['url'] = join( '', [
			'<div class="field comment-form-url">',
			'<label class="label" for="url">' . __( 'Website', 'blank' ) . '</label>',
			'<div class="control">',
			'<input ' . $req_atts . ' id="url" name="url" class="input" type="url" placeholder="e.g alex@acme.com" value="' . esc_attr( $commenter['comment_author_url'] ) . '" maxlength="200"/>',
			'</div></div>',
		] );

		$consent           = empty( $commenter['comment_author_email'] ) ? '' : ' checked="checked"';
		$fields['cookies'] = join( '', [
			'<div class="field comment-form-url"><div class="control">',
			'<label class="checkbox">',
			'<input ' . $consent . ' id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes">' . __( 'Save my name, email, and website in this browser for the next time I comment.', 'blank' ),
			'</label>',
			'</div></div>',
		] );

		return $fields;
	}

	/**
	 * Comment form default.
	 *
	 * @internal
	 * @since 0.1.0
	 * @param array $args
	 * @return array
	 */
	public function form_defaults( $args ) {
		$post_id = get_the_ID();

		$user          = wp_get_current_user();
		$user_identity = $user->exists() ? $user->display_name : '';

		$args['comment_field'] = join( '', [
			'<div class="field comment-form-comment">',
			'<label class="label" for="comment">' . __( 'Comment', 'blank' ) . '</label>',
			'<div class="control">',
			'<textarea id="comment" name="comment" class="textarea" maxlength="65525" required="required"></textarea>',
			'</div></div>',
		] );

		$args['must_log_in'] = join( '', [
			'<p class="must-log-in">',
			sprintf(
				/* translators: %s: login URL */
				__( 'You must be <a href="%s">logged in</a> to post a comment.', 'blank' ),
				wp_login_url( get_permalink( $post_id ) )
			),
			'</p>',
		] );

		$args['logged_in_as'] = join( '', [
			'<p class="logged-in-as">',
			sprintf(
				/* translators: 1: edit user link, 2: accessibility text, 3: user name, 4: logout URL */
				__( '<a href="%1$s" aria-label="%2$s">Logged in as %3$s</a>. <a href="%4$s">Log out?</a>', 'blank' ),
				get_edit_user_link(),
				/* translators: %s: user name */
				esc_attr( sprintf( __( 'Logged in as %s. Edit your profile.', 'blank' ), $user_identity ) ),
				$user_identity,
				wp_logout_url( get_permalink( $post_id ) )
			),
			'</p>',
		] );

		$args['submit_button'] = '<button name="%1$s" id="%2$s" class="%3$s button is-primary" type="submit">%4$s</button>';
		$args['submit_field']  = '<div class="field is-grouped"><div class="control">%1$s</div><div class="control">%2$s</div></div>';

		$args['comment_notes_before'] = join( '', [
			'<p class="comment-notes"><span id="email-notes">',
			__( 'Your email address will not be published.', 'blank' ),
			'</span></p>',
		] );

		// phpcs:disable Squiz.PHP.CommentedOutCode.Found
		// $args['comment_notes_after']  = '';

		// $args['id_form']      = '';
		// $args['class_form']   = '';
		// $args['id_submit']    = '';
		// $args['class_submit'] = '';

		// $args['title_reply_before'] = '<h3 id="reply-title" class="comment-reply-title">';
		// $args['title_reply_after']  = '</h3>';

		// $args['cancel_reply_before'] = '';
		// $args['cancel_reply_after']  = '';
		// phpcs:enable

		return $args;
	}
}
