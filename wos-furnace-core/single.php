<?php
/**
 * The template for displaying all single posts
 *
 * @package wos-furnace-core
 */

get_header();
?>

<div class="container mx-auto px-4 py-12">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <main id="primary" class="md:col-span-3">
            <?php
            while ( have_posts() ) :
                the_post();
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('bg-secondary/20 p-8 rounded-lg border border-accent/10'); ?>>
                    <header class="entry-header mb-8 border-b border-accent/20 pb-8">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="aspect-video w-full rounded-lg overflow-hidden mb-6 border border-accent/20 shadow-[0_0_15px_rgba(0,255,255,0.1)]">
                                <?php the_post_thumbnail( 'large', array( 'class' => 'w-full h-full object-cover' ) ); ?>
                            </div>
                        <?php endif; ?>

                        <div class="entry-meta text-accent/60 font-mono text-sm mb-2">
                             <?php echo get_the_date(); ?> / SYSTEM_LOG: <?php the_ID(); ?>
                        </div>

                        <?php the_title( '<h1 class="entry-title text-3xl md:text-4xl font-bold text-white mb-4 leading-tight drop-shadow-[0_0_5px_rgba(0,255,255,0.3)]">', '</h1>' ); ?>
                        
                        <div class="flex flex-wrap gap-2 mt-4">
                            <?php
                            $tags = get_the_tags();
                            if ( $tags ) {
                                foreach ( $tags as $tag ) {
                                    echo '<span class="px-2 py-1 text-xs font-mono border border-accent/40 text-accent rounded bg-accent/5">#' . esc_html( $tag->name ) . '</span>';
                                }
                            }
                            ?>
                        </div>
                    </header><!-- .entry-header -->

                    <div class="entry-content text-gray-300 leading-relaxed space-y-6">
                        <style>
                            /* Content Typography */
                            .entry-content h2 {
                                font-size: 1.5rem;
                                font-weight: 700;
                                color: #00FFFF;
                                border-left: 4px solid #00FFFF;
                                padding-left: 1rem;
                                margin-top: 2.5rem;
                                margin-bottom: 1.5rem;
                                background: linear-gradient(90deg, rgba(0,255,255,0.1) 0%, transparent 100%);
                                padding-top: 0.5rem;
                                padding-bottom: 0.5rem;
                            }
                            .entry-content h3 {
                                font-size: 1.25rem;
                                font-weight: 700;
                                color: #E0E1DD;
                                margin-top: 2rem;
                                margin-bottom: 1rem;
                                padding-bottom: 0.5rem;
                                border-bottom: 1px solid rgba(224, 225, 221, 0.2);
                            }
                            .entry-content p {
                                margin-bottom: 1.5rem;
                            }
                            .entry-content ul, .entry-content ol {
                                margin-bottom: 1.5rem;
                                padding-left: 1.5rem;
                                list-style-type: disc;
                            }
                            .entry-content ul li::marker {
                                color: #00FFFF;
                            }
                            .entry-content a {
                                color: #00FFFF;
                                text-decoration: underline;
                                text-underline-offset: 4px;
                            }
                            .entry-content a:hover {
                                text-decoration: none;
                                text-shadow: 0 0 5px #00FFFF;
                            }
                            
                            /* Responsive Table */
                            .entry-content table {
                                width: 100%;
                                border-collapse: collapse;
                                margin-bottom: 1.5rem;
                                font-size: 0.9rem;
                                white-space: nowrap; /* Prevent wrapping for horizontal scroll */
                            }
                            .entry-content th {
                                background-color: rgba(0, 255, 255, 0.1);
                                color: #00FFFF;
                                font-weight: bold;
                                text-align: left;
                                padding: 0.75rem;
                                border: 1px solid rgba(0, 255, 255, 0.2);
                            }
                            .entry-content td {
                                padding: 0.75rem;
                                border: 1px solid rgba(255, 255, 255, 0.1);
                            }
                            .entry-content tr:nth-child(even) {
                                background-color: rgba(255, 255, 255, 0.03);
                            }
                            /* Wrap table in scrollable container via JS or just CSS display block */
                            .table-wrapper {
                                overflow-x: auto;
                                -webkit-overflow-scrolling: touch;
                                margin-bottom: 1.5rem;
                                border: 1px solid rgba(0, 255, 255, 0.1);
                                border-radius: 4px;
                            }
                        </style>

                        <?php the_content(); ?>

                        <!-- Script to wrap tables for responsiveness -->
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const tables = document.querySelectorAll('.entry-content table');
                                tables.forEach(table => {
                                    const wrapper = document.createElement('div');
                                    wrapper.className = 'table-wrapper';
                                    table.parentNode.insertBefore(wrapper, table);
                                    wrapper.appendChild(table);
                                });
                            });
                        </script>
                    </div><!-- .entry-content -->

                    <footer class="entry-footer mt-12 pt-8 border-t border-accent/20">
                        <div class="flex justify-between items-center text-sm text-gray-400">
                             <?php previous_post_link( '<div class="nav-previous uppercase tracking-wider hover:text-accent font-bold">%link</div>', '&larr; Previous Log' ); ?>
                             <?php next_post_link( '<div class="nav-next uppercase tracking-wider hover:text-accent font-bold">%link</div>', 'Next Log &rarr;' ); ?>
                        </div>
                    </footer><!-- .entry-footer -->

                </article><!-- #post-<?php the_ID(); ?> -->
                <?php
            endwhile; // End of the loop.
            ?>
        </main><!-- #primary -->

        <aside class="md:col-span-1 space-y-8">
             <!-- Sidebar Widget Area -->
             <div class="p-6 bg-secondary/20 rounded-lg border border-accent/5">
                 <h3 class="text-lg font-bold text-accent mb-4 border-b border-accent/20 pb-2">RELATED PROTOCOLS</h3>
                 <ul class="space-y-3 text-sm">
                    <?php
                        $related_args = array(
                            'posts_per_page' => 5,
                            'post__not_in'   => array( get_the_ID() ),
                            'orderby'        => 'rand',
                        );
                        $related_query = new WP_Query( $related_args );
                        if( $related_query->have_posts() ) {
                            while( $related_query->have_posts() ) {
                                $related_query->the_post();
                                echo '<li><a href="' . get_permalink() . '" class="text-gray-300 hover:text-accent transition-colors block py-1 border-b border-white/5">' . get_the_title() . '</a></li>';
                            }
                            wp_reset_postdata();
                        }
                    ?>
                 </ul>
             </div>
        </aside>
    </div>
</div>

<?php
get_footer();
