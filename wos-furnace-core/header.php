<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

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
            background: rgba(27, 38, 59, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(0, 255, 255, 0.1);
        }
    </style>

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site min-h-screen flex flex-col">
	<header id="masthead" class="site-header fixed w-full top-0 z-50 glass shadow-lg border-b border-accent/20">
		<div class="container mx-auto px-4 h-16 flex items-center justify-between">
			<div class="site-branding flex items-center gap-2">
                <div class="w-8 h-8 bg-accent/20 rounded-full flex items-center justify-center border border-accent">
                    <span class="text-accent text-xs font-bold">WOS</span>
                </div>
				<?php
				if ( is_front_page() && is_home() ) :
					?>
					<h1 class="site-title text-xl font-bold tracking-tighter text-white m-0"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="hover:text-accent transition-colors"><?php bloginfo( 'name' ); ?></a></h1>
					<?php
				else :
					?>
					<p class="site-title text-xl font-bold tracking-tighter text-white m-0"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="hover:text-accent transition-colors"><?php bloginfo( 'name' ); ?></a></p>
					<?php
				endif;
				?>
			</div><!-- .site-branding -->

			<nav id="site-navigation" class="main-navigation hidden md:block">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'menu-1',
						'menu_id'        => 'primary-menu',
                        'container'      => false,
                        'menu_class'     => 'flex gap-6 text-sm font-medium',
                        'fallback_cb'    => false,
					)
				);
				?>
			</nav><!-- #site-navigation -->

            <!-- Mobile Menu Button (Placeholder) -->
            <button class="md:hidden text-accent p-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
		</div>
	</header>
    
    <!-- Spacer for fixed header -->
    <div class="h-16"></div>

    <main id="content" class="site-content flex-grow relative">
