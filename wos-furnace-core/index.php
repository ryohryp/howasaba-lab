<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 *
 * @package wos-furnace-core
 */

get_header();
?>

<div class="container mx-auto px-4 py-12">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="md:col-span-2 space-y-8">
            <?php
            if ( have_posts() ) :
                /* Start the Loop */
                while ( have_posts() ) :
                    the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('bg-secondary/30 p-6 rounded-lg border border-accent/10 hover:border-accent/40 transition-all'); ?>>
                        <header class="entry-header mb-4">
                            <?php
                            if ( is_singular() ) :
                                the_title( '<h1 class="entry-title text-3xl font-bold text-white mb-2">', '</h1>' );
                            else :
                                the_title( '<h2 class="entry-title text-2xl font-bold text-white mb-2"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark" class="hover:text-accent transition-colors">', '</a></h2>' );
                            endif;

                            if ( 'post' === get_post_type() ) :
                                ?>
                                <div class="entry-meta text-sm text-text/60 font-mono">
                                    <?php echo get_the_date(); ?> by <?php the_author(); ?>
                                </div><!-- .entry-meta -->
                            <?php endif; ?>
                        </header><!-- .entry-header -->

                        <div class="entry-content text-text/80 leading-relaxed">
                            <?php
                            the_content(
                                sprintf(
                                    wp_kses(
                                        /* translators: %s: Name of current post. Only visible to screen readers */
                                        __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'wos-furnace-core' ),
                                        array(
                                            'span' => array(
                                                'class' => array(),
                                            ),
                                        )
                                    ),
                                    wp_kses_post( get_the_title() )
                                )
                            );

                            wp_link_pages(
                                array(
                                    'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'wos-furnace-core' ),
                                    'after'  => '</div>',
                                )
                            );
                            ?>
                        </div><!-- .entry-content -->
                    </article><!-- #post-<?php the_ID(); ?> -->
                    <?php
                endwhile;

                the_posts_navigation();

            else :
                ?>
                <section class="no-results not-found bg-secondary/30 p-8 rounded-lg border border-red-500/20">
                    <header class="page-header">
                        <h1 class="page-title text-2xl font-bold text-white mb-4"><?php esc_html_e( 'Nothing Found', 'wos-furnace-core' ); ?></h1>
                    </header><!-- .page-header -->

                    <div class="page-content text-text/80">
                        <?php
                        if ( is_home() && current_user_can( 'publish_posts' ) ) :
                            printf(
                                '<p>' . wp_kses(
                                    /* translators: 1: link to WP admin new post page. */
                                    __( 'Ready to publish your first post? <a href="%1$s" class="text-accent hover:underline">Get started here</a>.', 'wos-furnace-core' ),
                                    array(
                                        'a' => array(
                                            'href' => array(),
                                        ),
                                    )
                                ) . '</p>',
                                esc_url( admin_url( 'post-new.php' ) )
                            );
                        elseif ( is_search() ) :
                            ?>
                            <p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'wos-furnace-core' ); ?></p>
                            <?php
                            get_search_form();
                        else :
                            ?>
                            <p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'wos-furnace-core' ); ?></p>
                            <?php
                            get_search_form();
                        endif;
                        ?>
                    </div><!-- .page-content -->
                </section><!-- .no-results -->
                <?php
            endif;
            ?>
        </div>
        
        <aside class="md:col-span-1 space-y-8">
            <div class="widget-area p-6 bg-secondary/20 rounded-lg border border-accent/5">
                 <h3 class="text-lg font-bold text-accent mb-4 border-b border-accent/20 pb-2">Secondary System</h3>
                 <p class="text-sm text-text/60">Widgets will appear here.</p>
            </div>
        </aside>
    </div>
</div>

<?php
get_footer();
