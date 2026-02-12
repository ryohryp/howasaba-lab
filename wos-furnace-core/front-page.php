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
    <section class="relative w-full h-[85vh] flex items-center justify-center overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1545156526-905446bb32d3?q=80&w=2670&auto=format&fit=crop')] bg-cover bg-center opacity-30 mix-blend-overlay"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-[#0D1B2A] via-[#0D1B2A]/60 to-[#0D1B2A]"></div>
        
        <!-- Content -->
        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto">
            <h1 class="text-5xl md:text-7xl lg:text-8xl font-bold tracking-tighter text-white mb-6">
                SERVER AGE
            </h1>
            
            <p class="text-xl md:text-2xl text-gray-400 font-light mb-10 tracking-widest uppercase opacity-80">
                Current Generation
            </p>

            <div class="flex flex-col md:flex-row items-center justify-center gap-6">
                <a href="<?php echo esc_url( home_url( '/tools/' ) ); ?>" class="px-10 py-4 bg-white text-[#0D1B2A] font-bold tracking-wide rounded hover:bg-gray-100 transition-colors duration-300">
                    ACCESS TOOLS
                </a>
                
                <a href="#latest-intel" class="px-10 py-4 text-white/80 hover:text-white font-medium tracking-wide transition-colors duration-300 flex items-center gap-2">
                    VIEW INTEL <span class="text-lg opacity-70">&darr;</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Latest Intel (Recent Posts) -->
    <section id="latest-intel" class="py-24 relative">
        <div class="container mx-auto px-6">
            <div class="flex items-end justify-between mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-white tracking-tight">Latest Intel</h2>
                <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="text-white/60 hover:text-white transition-colors text-sm font-medium hidden md:block">
                    View Archives &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
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
                        <article class="group flex flex-col h-full">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="aspect-[16/9] w-full overflow-hidden rounded-sm bg-white/5 mb-6">
                                     <?php the_post_thumbnail( 'medium_large', array( 'class' => 'w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 opacity-90 group-hover:opacity-100' ) ); ?>
                                </div>
                            <?php else: ?>
                                <div class="aspect-[16/9] w-full bg-white/5 flex items-center justify-center rounded-sm mb-6">
                                    <span class="text-white/10 font-mono text-4xl">///</span>
                                </div>
                            <?php endif; ?>
                            
                            <div class="flex-grow flex flex-col">
                                <div class="text-accent/80 text-xs font-medium tracking-wider uppercase mb-3"><?php echo get_the_date(); ?></div>
                                <h3 class="text-2xl font-bold text-white mb-3 group-hover:text-white/80 transition-colors leading-tight">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>
                                <div class="text-gray-400 text-sm leading-relaxed line-clamp-2 mb-6 flex-grow">
                                    <?php the_excerpt(); ?>
                                </div>
                                <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-sm font-bold text-white/90 hover:text-white tracking-wide mt-auto">
                                    Read More <span class="ml-2 transition-transform group-hover:translate-x-1">&rarr;</span>
                                </a>
                            </div>
                        </article>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    ?>
                    <div class="col-span-full text-center py-20 text-gray-500">
                        <p>No intelligence reports found.</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="mt-12 text-center md:hidden">
                 <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="text-white/60 hover:text-white transition-colors text-sm font-medium">
                    View Archives &rarr;
                </a>
            </div>
        </div>
    </section>
</div>

<?php
get_footer();
