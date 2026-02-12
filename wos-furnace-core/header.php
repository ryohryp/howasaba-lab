<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

    <?php
    // OGP Settings
    $og_title = is_front_page() ? get_bloginfo('name') : get_the_title() . ' | ' . get_bloginfo('name');
    $og_url   = get_permalink();
    $og_desc  = is_single() ? get_the_excerpt() : get_bloginfo('description');
    $og_image = get_template_directory_uri() . '/screenshot.png'; // Default image

    if ( has_post_thumbnail() ) {
        $og_image = get_the_post_thumbnail_url(null, 'large');
    }
    ?>
    <meta property="og:title" content="<?php echo esc_attr($og_title); ?>" />
    <meta property="og:type" content="<?php echo is_single() ? 'article' : 'website'; ?>" />
    <meta property="og:url" content="<?php echo esc_url($og_url); ?>" />
    <meta property="og:image" content="<?php echo esc_url($og_image); ?>" />
    <meta property="og:description" content="<?php echo esc_attr($og_desc); ?>" />
    <meta property="og:site_name" content="<?php bloginfo('name'); ?>" />
    
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="<?php echo esc_attr($og_title); ?>" />
    <meta name="twitter:description" content="<?php echo esc_attr($og_desc); ?>" />
    <meta name="twitter:image" content="<?php echo esc_url($og_image); ?>" />

	<!-- Tailwind CSS CDN -->
	<script src="https://cdn.tailwindcss.com"></script>
	<script>
		tailwind.config = {
			theme: {
				extend: {
					colors: {
						primary: '#0D1B2A',
						accent: '#00FFFF',
						secondary: '#1B263B',
						text: '#E0E1DD',
					},
					fontFamily: {
						sans: ['Inter', 'sans-serif'],
						mono: ['Fira Code', 'monospace'],
					}
				}
			}
		}
	</script>
    <style>
        body {
            background-color: #0D1B2A;
            color: #E0E1DD;
            font-family: 'Inter', sans-serif;
        }
        /* Glassmorphism utility */
        .glass {
            background: rgba(13, 27, 42, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site min-h-screen flex flex-col">
	<header id="masthead" class="site-header fixed w-full top-0 z-50 glass transition-all duration-300">
		<div class="container mx-auto px-6 h-20 flex items-center justify-between">
			<div class="site-branding flex items-center gap-3">
                <div class="flex items-center justify-center">
                    <span class="text-white text-lg font-bold tracking-tight">WOS</span>
                </div>
				<?php
				if ( is_front_page() && is_home() ) :
					?>
					<h1 class="site-title text-lg font-medium tracking-tight text-white/90 m-0"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="hover:text-white transition-colors"><?php bloginfo( 'name' ); ?></a></h1>
					<?php
				else :
					?>
					<p class="site-title text-lg font-medium tracking-tight text-white/90 m-0"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="hover:text-white transition-colors"><?php bloginfo( 'name' ); ?></a></p>
					<?php
				endif;
				?>
			</div><!-- .site-branding -->

			<nav id="site-navigation" class="main-navigation hidden md:block">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'global-nav',
						'menu_id'        => 'global-nav',
                        'container'      => false,
                        'menu_class'     => 'flex gap-8 text-sm font-medium text-white/70 hover:text-white transition-colors',
                        'fallback_cb'    => false,
					)
				);
				?>
			</nav><!-- #site-navigation -->

            <!-- Mobile Menu Button (Placeholder) -->
            <button class="md:hidden text-white/80 hover:text-white p-2 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
		</div>
	</header>
    
    <!-- Spacer for fixed header -->
    <div class="h-20"></div>

    <main id="content" class="site-content flex-grow relative">
