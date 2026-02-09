=== WOS Frost & Fire ===
Contributors: antigravity
Tags: custom-background, custom-logo, custom-menu, featured-images, threaded-comments, translation-ready, glassmorphism, whiteout-survival
Requires at least: 6.0
Tested up to: 6.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A WordPress theme inspired by Whiteout Survival, featuring glassmorphism UI and dynamic particle effects.

== Description ==

WOS Frost & Fire is a highly immersive WordPress theme designed for fan sites, gaming communities, or wikis related to the survival strategy game "Whiteout Survival". 

**Key Features:**
*   **Frost & Fire Design:** A custom color palette representing the harsh cold and the warmth of the furnace.
*   **Glassmorphism UI:** Modern, translucent components that blur the background, creating a depth effect.
*   **Particle Effects:** Integrated snow particle system for an atmospheric experience.
*   **Custom Post Types:** Pre-configured support for "Heroes" and "Events" to easily manage game data.
*   **Modern Stack:** Built with Vite, Tailwind CSS, and Alpine.js for performance and developer experience.

== Installation ==

1.  Upload the theme files to the `/wp-content/themes/wos-frost-fire` directory.
2.  Run `npm install` and `npm run build` to generate the assets.
3.  Activate the theme through the 'Appearance' menu in WordPress.

== Frequently Asked Questions ==

= How do I change the hero stats? =
The theme uses custom fields for stats. You can verify the field names in `inc/cpt.php` or use a plugin like ACF to create a UI for them (though the theme registers the keys natively).

= How do I deploy to Xserver? =
This theme includes a GitHub Actions workflow in `.github/workflows/deploy.yml`. configure your FTP secrets in GitHub to enable auto-deployment.

== Changelog ==

= 1.0.0 =
*   Initial release.
