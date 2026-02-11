<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="fixed w-full z-50 transition-all duration-300 bg-[#0f172a]/80 backdrop-blur-md border-b border-[var(--glass-border)]">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <a href="<?php echo home_url(); ?>" class="text-2xl font-black tracking-tighter text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-white">
            GLACIAL<span class="text-[var(--accent-fire-crystal)]">CORE</span>
        </a>
        <nav class="hidden md:flex gap-6 text-sm font-semibold tracking-wide">
            <a href="#events" class="hover:text-blue-300 transition-colors">EVENTS</a>
            <a href="#heroes" class="hover:text-blue-300 transition-colors">HEROES</a>
            <!-- WP Nav Menu would go here -->
        </nav>
    </div>
</header>
