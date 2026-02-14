<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class('bg-deep-freeze text-white font-sans antialiased selection:bg-fire-crystal selection:text-white'); ?> x-data="{ mobileMenuOpen: false }">
<?php wp_body_open(); ?>



<div id="page" class="site relative z-10 flex min-h-screen flex-col">
	<a class="skip-link screen-reader-text focus:top-5 focus:left-5 focus:z-[100] focus:bg-white focus:text-black focus:p-4 absolute top-[-100px]" href="#primary"><?php esc_html_e( 'Skip to content', 'wos-frost-fire' ); ?></a>

	<header id="masthead" class="site-header sticky top-0 z-40 border-b border-white/10 bg-deep-freeze/80 backdrop-blur-md">
		<div class="container mx-auto flex items-center justify-between px-4 py-3">
            <div class="site-branding">
                <?php
                if ( has_custom_logo() ) {
                    the_custom_logo();
                } else {
                    ?>
                    <h1 class="site-title text-2xl font-bold uppercase tracking-wider text-ice-blue">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
                    </h1>
                    <?php
                }
                ?>
            </div><!-- .site-branding -->

            <nav id="site-navigation" class="main-navigation flex items-center gap-6">
                <!-- Mobile menu button could go here with Alpine.js -->
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'menu-1',
                        'menu_id'        => 'primary-menu',
                        'container_class'=> 'hidden md:block',
                        'menu_class'     => 'flex gap-6 text-sm font-medium text-gray-300',
                        'fallback_cb'    => false, // Do not list pages if menu not assigned
                    )
                );
                ?>
                
                <!-- Desktop Language Switcher -->
                <div class="hidden md:flex items-center gap-2 border-l border-white/10 pl-6">
                    <a href="<?php echo esc_url( wos_get_language_url('ja') ); ?>" class="text-xs font-bold <?php echo get_locale() === 'ja' ? 'text-ice-blue' : 'text-gray-500 hover:text-white'; ?>">JP</a>
                    <span class="text-gray-700">/</span>
                    <a href="<?php echo esc_url( wos_get_language_url('en') ); ?>" class="text-xs font-bold <?php echo get_locale() !== 'ja' ? 'text-ice-blue' : 'text-gray-500 hover:text-white'; ?>">EN</a>
                </div>
            </nav><!-- #site-navigation -->
        </div>
	</header><!-- #masthead -->
