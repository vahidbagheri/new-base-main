<?php
the_title(
    sprintf(
        '<%s class="post-title fs-6 p-3 lh-lg mb-0" itemprop="headline"><a href="%s" class="fw-bold link-body-emphasis text-decoration-none" itemprop="url">',
        $args['title-tag'],
        esc_url( get_permalink() )
    ),
    sprintf(
        '</a></%s>',
        $args['title-tag']
    )
);