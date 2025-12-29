<?php
if ( post_password_required() ) {
    return;
}
?>

<div id="comments" class="bg-white rounded-4 shadow-sm p-4 mt-5">

    <?php if ( have_comments() ) : ?>
        <h3 class="fw-bold mb-4">
            <?php
            comments_number(
                esc_html__( 'No comments', 'citynet' ),
                esc_html__( '1 Comment', 'citynet' ),
                esc_html__( '% Comments', 'citynet' )
            );
            ?>
        </h3>

        <ul class="list-unstyled mb-4">
            <?php
            wp_list_comments([
                'style'       => 'ul',
                'short_ping'  => true,
                'avatar_size' => 60,
                'callback'    => 'modern_threaded_comment_template',
            ]);
            ?>
        </ul>

        <?php
        // Pagination for comments
        the_comments_pagination([
            'prev_text' => esc_html__( '« Previous', 'citynet' ),
            'next_text' => esc_html__( 'Next »', 'citynet' ),
        ]);
        ?>

    <?php endif; ?>

    <?php if ( comments_open() ) : ?>
        <div id="respond" class="comment-respond mt-5">
            <p id="reply-title" class="fw-bold mb-3">
                <?php comment_form_title( esc_html__( 'Leave a Comment', 'citynet' ) ); ?>
            </p>

            <?php
            $commenter = wp_get_current_commenter();
            $fields = [
                'author' => '
                    <div class="col-md-6 mb-3">
                        <input id="author" name="author" type="text" class="form-control"
                            placeholder="' . esc_attr__( 'Your Name', 'citynet' ) . '"
                            value="' . esc_attr( $commenter['comment_author'] ) . '" />
                    </div>',
                'email'  => '
                    <div class="col-md-6 mb-3">
                        <input id="email" name="email" type="email" class="form-control"
                            placeholder="' . esc_attr__( 'Your Email', 'citynet' ) . '"
                            value="' . esc_attr( $commenter['comment_author_email'] ) . '" />
                    </div>',
            ];

            $args = [
                'fields'               => $fields,
                'comment_field'        => '
                    <div class="mb-3">
                        <textarea id="comment" name="comment" class="form-control" rows="5"
                            placeholder="' . esc_attr__( 'Your Message', 'citynet' ) . '"></textarea>
                    </div>',
                'class_form'           => 'comment-form row',
                'class_submit'         => 'btn btn-primary px-4 py-2 rounded-3',
                'label_submit'         => esc_html__( 'Send', 'citynet' ),
                'title_reply'          => '',
                'comment_notes_before' => '',
                'comment_notes_after'  => '',
            ];

            comment_form( $args );
            ?>
        </div>
    <?php endif; ?>
</div>

<?php
// ✅ Modern threaded comment template (HTML accessibility improvements)
function modern_threaded_comment_template( $comment, $args, $depth ) {
    $tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
    ?>
    <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class('mb-3 position-relative list-unstyled'); ?>>

        <?php if ( $depth > 1 ) : ?>
            <span class="d-none d-md-block position-absolute top-0 bottom-0 end-0 bg-light" style="width:2px;"></span>
        <?php endif; ?>

        <div class="card border-0 rounded-4 shadow-sm mb-3 ms-<?php echo ( $depth > 1 ? min( ($depth - 1) * 2, 4 ) : 0 ); ?>">
            <div class="card-body d-flex flex-column flex-md-row align-items-md-start">
                
                <div class="me-md-3 mb-3 mb-md-0 text-center text-md-start">
                    <?php echo get_avatar( $comment, 60, '', '', [ 'class' => 'rounded-circle border border-2 border-primary shadow-sm' ] ); ?>
                </div>

                <div class="flex-grow-1">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start mb-2">
                        <p class="fw-bold text-primary mb-1 mb-sm-0"><?php comment_author(); ?></p>
                        <small class="text-secondary fst-italic"><?php echo esc_html( get_comment_date( 'Y/m/d' ) ); ?></small>
                    </div>

                    <?php if ( $comment->comment_approved == '0' ) : ?>
                        <em class="text-warning small d-block mb-2">
                            <?php echo esc_html__( '⏳ Your comment is awaiting moderation...', 'citynet' ); ?>
                        </em>
                    <?php endif; ?>

                    <div class="text-dark mb-3 lh-base">
                        <?php comment_text(); ?>
                    </div>

                    <div>
                        <?php
                        comment_reply_link( array_merge( $args, [
                            'reply_text' => '<i class="bi bi-reply"></i> ' . esc_html__( 'Reply', 'citynet' ),
                            'depth'      => $depth,
                            'max_depth'  => $args['max_depth'],
                            'class'      => 'btn btn-sm btn-outline-primary rounded-pill px-3 py-1',
                            'aria_label' => esc_html__( 'Reply to this comment', 'citynet' ),
                        ] ) );
                        ?>
                    </div>
                </div>
            </div>
        </div>

    <?php if ( 'li' === $tag ) echo '</li>'; else echo '</div>'; ?>
    <?php
}
?>
