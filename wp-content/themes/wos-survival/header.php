<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

    <!-- Fonts: Plus Jakarta Sans & Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

	<?php wp_head(); ?>
</head>

<body <?php body_class('text-on-surface selection:bg-primary-container selection:text-on-primary-container'); ?> x-data="{ mobileMenuOpen: false }">
<?php wp_body_open(); ?>

<div id="page" class="site relative z-10 flex min-h-screen flex-col">
	<a class="skip-link screen-reader-text focus:top-5 focus:left-5 focus:z-[100] focus:bg-white focus:text-black focus:p-4 absolute top-[-100px]" href="#primary"><?php esc_html_e( 'Skip to content', 'wos-survival' ); ?></a>

	<header id="masthead" class="site-header fixed top-0 w-full z-50 nav-glass">
		<div class="flex justify-between items-center px-6 py-4 max-w-full lg:px-12">
            <div class="site-branding">
                <?php
                if ( has_custom_logo() ) {
                    the_custom_logo();
                } else {
                    ?>
                    <h1 class="site-title text-2xl font-black text-primary tracking-tighter">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
                    </h1>
                    <?php
                }
                ?>
            </div><!-- .site-branding -->

            <nav id="site-navigation" class="main-navigation hidden md:flex items-center gap-8">
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'menu-1',
                        'menu_id'        => 'primary-menu',
                        'container'      => false,
                        'menu_class'     => 'flex gap-8 text-sm font-semibold text-on-surface-variant',
                        'fallback_cb'    => false,
                    )
                );
                ?>
                
                <!-- Desktop Language Switcher -->
                <div class="flex items-center gap-3 border-l border-outline-variant/30 pl-8">
                    <a href="<?php echo esc_url( wos_get_language_url('ja') ); ?>" class="text-xs font-bold transition-colors <?php echo get_locale() === 'ja' ? 'text-primary' : 'text-on-surface-variant hover:text-primary'; ?>">JP</a>
                    <span class="text-outline-variant/50 text-[10px]">|</span>
                    <a href="<?php echo esc_url( wos_get_language_url('en') ); ?>" class="text-xs font-bold transition-colors <?php echo get_locale() !== 'ja' ? 'text-primary' : 'text-on-surface-variant hover:text-primary'; ?>">EN</a>
                </div>
            </nav><!-- #site-navigation -->

            <div class="flex items-center gap-4">
                <button class="hidden sm:block px-5 py-2 rounded-full font-bold text-on-surface-variant hover:bg-primary/10 transition-all scale-95 active:scale-90">Login</button>
                <button class="btn-primary scale-95 md:scale-100">Join Alliance</button>
                
                <!-- Mobile Menu Toggle -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-on-surface-variant">
                    <span class="material-symbols-outlined" x-text="mobileMenuOpen ? 'close' : 'menu'">menu</span>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Overlay -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="md:hidden bg-white/95 backdrop-blur-2xl border-b border-outline-variant/20 p-6 absolute top-full left-0 w-full shadow-2xl"
             style="display: none;">
             <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'menu-1',
                        'container'      => false,
                        'menu_class'     => 'flex flex-col gap-4 text-lg font-bold text-on-surface',
                    )
                );
            ?>
            <div class="mt-8 pt-8 border-t border-outline-variant/20 flex justify-between items-center">
                <div class="flex gap-4">
                    <a href="<?php echo esc_url( wos_get_language_url('ja') ); ?>" class="font-bold <?php echo get_locale() === 'ja' ? 'text-primary' : 'text-on-surface-variant'; ?>">Japanese</a>
                    <a href="<?php echo esc_url( wos_get_language_url('en') ); ?>" class="font-bold <?php echo get_locale() !== 'ja' ? 'text-primary' : 'text-on-surface-variant'; ?>">English</a>
                </div>
            </div>
        </div>
	</header><!-- #masthead -->

