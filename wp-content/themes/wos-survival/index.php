<?php
/**
 * The main template file
 *
 * @package WOS_Survival
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="snow-container">
        <!-- Snow canvas will be injected here by JS -->
        <canvas id="snow-canvas"></canvas>
    </div>

    <div class="container glass-panel">
        <?php
        if ( have_posts() ) :
            while ( have_posts() ) :
                the_post();
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <header class="entry-header">
                        <?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
                    </header>

                    <div class="entry-content">
                        <?php the_excerpt(); ?>
                    </div>
                </article>
                <?php
            endwhile;

            the_posts_navigation();

        else :
            ?>
            <p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for.', 'wos-survival' ); ?></p>
            <?php
        endif;
        ?>
    </div>
</main>

<?php
get_footer();
