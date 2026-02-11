<?php
/**
 * Vite Asset Loader
 *
 * Handles loading of Vite-compiled assets (CSS/JS) in WordPress.
 * Supports both development (HMR) and production (Manifest) modes.
 *
 * @package WoS_Frost_Fire
 */

class Vite_Asset_Loader {

    /**
     * The manifest data.
     *
     * @var array
     */
    private $manifest = [];

    /**
     * Dist directory path relative to theme root.
     *
     * @var string
     */
    private $dist_path = '/dist';

    /**
     * Vite dev server URL.
     *
     * @var string
     */
    private $dev_server_url = 'http://localhost:5173';

    /**
     * Constructor.
     */
    public function __construct() {
        $this->load_manifest();
    }

    /**
     * Checks if we are in development mode.
     * 
     * Logic:
     * 1. Check if WP_ENVIRONMENT_TYPE is 'local' or 'development'.
     * 2. Check if a specific cookie is set (optional).
     * 3. Make a lightweight request to the dev server (optional, but good for robust checks).
     * 
     * For simplicity, we'll assume development if WP_DEBUG is true and not on production domain.
     * Or better, check if the dev server is reachable (timeout 1s).
     *
     * @return bool
     */
    public function is_dev_mode() {
        if ( defined( 'VITE_DEV_MODE' ) && VITE_DEV_MODE ) {
            return true;
        }

        // Check if we are on localhost/dev environment
        $is_local = in_array( wp_get_environment_type(), [ 'local', 'development' ], true );
        
        // Check if dev server is actually running
        if ( $is_local ) {
            $connection = @fsockopen( 'localhost', 5173 );
            if ( is_resource( $connection ) ) {
                fclose( $connection );
                return true;
            }
        }

        return false;
    }

    /**
     * Load the manifest file.
     */
    private function load_manifest() {
        $manifest_path = get_template_directory() . $this->dist_path . '/.vite/manifest.json';
        if ( file_exists( $manifest_path ) ) {
            $this->manifest = json_decode( file_get_contents( $manifest_path ), true );
        }
    }

    /**
     * Enqueue a script or style from Vite.
     *
     * @param string $handle    The script/style handle.
     * @param string $entry_point The entry point path (e.g., 'assets/js/app.js').
     * @param array  $deps      Dependencies.
     */
    public function enqueue( $handle, $entry_point, $deps = [] ) {
        if ( $this->is_dev_mode() ) {
            // Development: Load from Vite Dev Server
            
            // If it's a script, we need the @vite/client first
            if ( strpos( $entry_point, '.js' ) !== false ) {
                wp_enqueue_script( 'vite-client', $this->dev_server_url . '/@vite/client', [], null, true );
                
                // Add module type for Vite scripts
                add_filter( 'script_loader_tag', function( $tag, $handle, $src ) {
                    if ( 'vite-client' === $handle || strpos( $src, $this->dev_server_url ) !== false ) {
                        return '<script type="module" src="' . esc_url( $src ) . '"></script>';
                    }
                    return $tag;
                }, 10, 3 );

                wp_enqueue_script( $handle, $this->dev_server_url . '/wp-content/themes/wos-frost-fire/' . $entry_point, $deps, null, true );
            } elseif ( strpos( $entry_point, '.css' ) !== false ) {
                 // CSS in dev mode is usually injected by JS, but if standalone:
                 wp_enqueue_style( $handle, $this->dev_server_url . '/wp-content/themes/wos-frost-fire/' . $entry_point, $deps, null );
            }

        } else {
            // Production: Load from Manifest
            if ( isset( $this->manifest[ $entry_point ] ) ) {
                $file_data = $this->manifest[ $entry_point ];
                $file_url = get_template_directory_uri() . $this->dist_path . '/' . $file_data['file'];

                if ( strpos( $entry_point, '.js' ) !== false ) {
                     wp_enqueue_script( $handle, $file_url, $deps, WOS_THEME_VERSION, true );
                     
                     // Enqueue associated CSS if any
                     if ( ! empty( $file_data['css'] ) ) {
                         foreach ( $file_data['css'] as $css_file ) {
                             wp_enqueue_style( $handle . '-css', get_template_directory_uri() . $this->dist_path . '/' . $css_file, [], WOS_THEME_VERSION );
                         }
                     }
                } elseif ( strpos( $entry_point, '.css' ) !== false ) {
                    wp_enqueue_style( $handle, $file_url, $deps, WOS_THEME_VERSION );
                }
            }
        }
    }
}
