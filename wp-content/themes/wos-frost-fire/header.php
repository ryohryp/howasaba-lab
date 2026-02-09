<!doctype html>
<html <?php language_attributes(); ?> class="scroll-smooth">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Iceberg&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
	<?php wp_head(); ?>
</head>

<body <?php body_class( 'antialiased bg-deep-freeze text-slate-100' ); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site relative min-h-screen flex flex-col">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'wos-frost-fire' ); ?></a>

	<header id="masthead" class="site-header fixed w-full z-50 transition-all duration-300 bg-deep-freeze/80 backdrop-blur-md border-b border-white/10" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">
		<div class="container mx-auto px-4 h-16 flex items-center justify-between">
            <!-- Branding -->
			<div class="site-branding flex items-center">
				<?php
				if ( has_custom_logo() ) {
                    the_custom_logo();
                } else {
                    ?>
                    <h1 class="site-title text-2xl font-display font-bold text-ice-blue tracking-wider hover:text-white transition-colors">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
                    </h1>
                    <?php
                }
				?>
			</div><!-- .site-branding -->

            <!-- Navigation -->
			<nav id="site-navigation" class="main-navigation hidden md:block">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'menu-1',
						'menu_id'        => 'primary-menu',
                        'container_class'=> 'flex gap-6',
                        'menu_class'     => 'flex gap-6 text-sm font-medium uppercase tracking-widest text-blue-100',
                        'walker'         => null, // Can add custom walker for glassmorphism dropdowns later
					)
				);
				?>
			</nav><!-- #site-navigation -->

            <!-- Mobile Menu Button -->
            <button class="md:hidden text-white p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
            </button>
		</div>
	</header><!-- #masthead -->
    
    <!-- Spacer for fixed header -->
    <div class="h-16"></div>
