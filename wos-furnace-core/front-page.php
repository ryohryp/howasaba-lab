<?php
/**
 * The front page template file
 *
 * @package wos-furnace-core
 */

get_header();
?>

<div class="h-full flex flex-col">
    <!-- Hero Section -->
    <section class="relative w-full h-[80vh] flex items-center justify-center overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1545156526-905446bb32d3?q=80&w=2670&auto=format&fit=crop')] bg-cover bg-center opacity-20 mix-blend-overlay"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-primary/80 via-primary/50 to-primary"></div>
        
        <!-- Grid Overlay -->
        <div class="absolute inset-0 bg-[linear-gradient(rgba(0,255,255,0.03)_1px,transparent_1px),linear-gradient(90deg,rgba(0,255,255,0.03)_1px,transparent_1px)] bg-[size:40px_40px]"></div>

        <!-- Content -->
        <div class="relative z-10 text-center px-4">
            <div class="inline-block mb-4 px-3 py-1 border border-accent/50 rounded-full bg-accent/10 backdrop-blur-sm">
                <span class="text-accent font-mono text-sm tracking-widest">SYSTEM: ONLINE</span>
            </div>
            
            <h1 class="text-6xl md:text-8xl font-bold tracking-tighter text-white mb-2 drop-shadow-[0_0_15px_rgba(0,255,255,0.5)]">
                SERVER AGE
            </h1>
            
            <div class="flex items-center justify-center gap-4 text-2xl md:text-3xl text-gray-300 font-light mb-8">
                <span class="h-px w-12 bg-accent/50"></span>
                <span class="tracking-[0.2em] uppercase">Current Generation</span>
                <span class="h-px w-12 bg-accent/50"></span>
            </div>

            <div class="flex flex-col md:flex-row items-center justify-center gap-6 mt-8">
                <a href="<?php echo esc_url( home_url( '/tools/' ) ); ?>" class="group relative px-8 py-3 bg-accent/10 border border-accent hover:bg-accent/20 transition-all duration-300 overflow-hidden">
                    <div class="absolute inset-0 w-0 bg-accent/20 transition-all duration-[250ms] ease-out group-hover:w-full opacity-0 group-hover:opacity-100"></div>
                    <span class="relative text-accent font-bold tracking-wider group-hover:text-white transition-colors">ACCESS TOOLS</span>
                </a>
                
                <a href="#latest-intel" class="px-8 py-3 text-sm text-gray-400 hover:text-white tracking-widest border border-transparent hover:border-gray-600 transition-all">
                    VIEW INTEL
                </a>
            </div>
        </div>
        
         <!-- Decorative Elements -->
         <div class="absolute bottom-10 left-10 text-[10px] text-accent/30 font-mono hidden md:block">
            COORD: 35.6895° N, 139.6917° E<br>
            STATUS: MONITORING
         </div>
    </section>

    <!-- Latest Intel (Recent Posts) -->
    <section id="latest-intel" class="py-20 bg-secondary/20">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between mb-12 border-b border-white/10 pb-4">
                <h2 class="text-3xl font-bold text-white tracking-tight">LATEST INTEL</h2>
                <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="text-accent hover:text-white transition-colors text-sm font-mono flex items-center gap-2">
                    ARCHIVES <span class="text-lg">&rarr;</span>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php
                $args = array(
                    'posts_per_page' => 3,
                    'post_status'    => 'publish',
                );
                $latest_posts = new WP_Query( $args );

                if ( $latest_posts->have_posts() ) :
                    while ( $latest_posts->have_posts() ) :
                        $latest_posts->the_post();
                        ?>
                        <article class="group bg-primary border border-white/5 hover:border-accent/50 transition-all duration-300 rounded-sm overflow-hidden flex flex-col h-full">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="aspect-video w-full overflow-hidden">
                                     <?php the_post_thumbnail( 'medium_large', array( 'class' => 'w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 opacity-80 group-hover:opacity-100' ) ); ?>
                                </div>
                            <?php else: ?>
                                <div class="aspect-video w-full bg-secondary/50 flex items-center justify-center border-b border-white/5">
                                    <span class="text-white/20 font-mono text-4xl">///</span>
                                </div>
                            <?php endif; ?>
                            
                            <div class="p-6 flex-grow flex flex-col">
                                <div class="text-accent/60 text-xs font-mono mb-2"><?php echo get_the_date(); ?></div>
                                <h3 class="text-xl font-bold text-white mb-3 group-hover:text-accent transition-colors line-clamp-2">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>
                                <div class="text-gray-400 text-sm line-clamp-3 mb-4 flex-grow">
                                    <?php the_excerpt(); ?>
                                </div>
                                <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-xs font-bold text-white uppercase tracking-widest mt-auto group-hover:text-accent">
                                    Read Protocol <span class="ml-2 transition-transform group-hover:translate-x-1">&rarr;</span>
                                </a>
                            </div>
                        </article>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    ?>
                    <div class="col-span-full text-center py-12 text-gray-500">
                        <p>No intelligence reports found.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<?php
get_footer();
