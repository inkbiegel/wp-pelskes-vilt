<?php
/**
 * Template part for displaying a guestbook entry in guestbook.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Pelske
 */

	$post_id = get_the_ID();
	$gb_name = get_post_meta( $post_id, 'gb_author', true );
	$gb_location = get_post_meta( $post_id, 'gb_location', true );
	$comment = get_comments( array(
		'number' => -1,
		'post_id' => $post_id
	) );
?>
	<li class="gb-entry-list-item">
		<article class="gb-entry">
			<header class="gb-entry-header">
				<p>
					<strong class="gb-entry-name"><?php echo esc_html( $gb_name ); ?></strong>
					<span class="gb-entry-location"><?php echo esc_html( $gb_location ); ?>,</span>
					<span class="gb-entry-date"><?php echo esc_html( human_time_diff( get_the_time('U'), current_time('timestamp') ) ) . ' ' . __( 'ago', 'pelske' ); ?></span>
				</p>
			</header>
			<div class="gb-entry-msg">
				<?php esc_textarea( the_content() ); ?>
			</div>
			<?php if( ! empty( $comment ) ) : ?>
			<div class="gb-entry-comment">
				<div class="bubble">
					<?php echo $comment[0]->comment_content; ?>
				</div>
				<div class="avatar">
					<?php echo wp_get_attachment_image( 1072, 'thumbnail', false, array( 'class' => 'avatar-img' )); ?>
				</div>
			</div>
			<?php endif; ?>
		</article>
	</li>