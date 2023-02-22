<?php
/**
 * Astra Child Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Astra-child
 * @since 1.0.0
 */

/**
 * Define Constants
 */
define( 'ASTRA_CHILD_THEME_VERSION', '1.0.1' );

/**
 * Enqueue styles
 */
function child_enqueue_styles() {
    wp_enqueue_style( 'omeva-icon-css', get_stylesheet_directory_uri() . '/fontello.css', array('astra-theme-css'), ASTRA_CHILD_THEME_VERSION, 'all' );
	wp_enqueue_style( 'astra-child-theme-css', get_stylesheet_directory_uri() . '/style.css', array('astra-theme-css'), ASTRA_CHILD_THEME_VERSION, 'all' );
	wp_enqueue_script( 'omeva-js', get_stylesheet_directory_uri() . '/script.js', array('jquery'), ASTRA_CHILD_THEME_VERSION, 'all');
}

add_action( 'wp_enqueue_scripts', 'child_enqueue_styles' );

function astra_child_omeva_javascript() {
    ?>
    <!-- Matomo -->
    <script>
        var _paq = window._paq = window._paq || [];
        /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
        _paq.push(['trackPageView']);
        _paq.push(['enableLinkTracking']);
        (function() {
            var u="//matomo.omeva.fr/";
            _paq.push(['setTrackerUrl', u+'matomo.php']);
            _paq.push(['setSiteId', '2']);
            var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
            g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
        })();
    </script>
    <!-- End Matomo Code -->
    <!-- Start cookieyes banner -->
    <script id="cookieyes" type="text/javascript" src="https://cdn-cookieyes.com/client_data/1ec3b495baf85034d957c195/script.js"></script>
    <!-- End cookieyes banner -->
    <?php
}
add_action('wp_head', 'astra_child_omeva_javascript');