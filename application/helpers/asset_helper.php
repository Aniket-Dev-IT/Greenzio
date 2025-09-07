<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Asset Helper for Greenzio
 * 
 * Provides optimized asset loading with versioning, minification support,
 * and CDN integration for better performance.
 */

if (!function_exists('load_css')) {
    /**
     * Load CSS files with optimization
     * 
     * @param mixed $files - string or array of CSS files
     * @param array $options - loading options
     * @return string HTML link tags
     */
    function load_css($files, $options = []) {
        $CI =& get_instance();
        
        // Default options
        $defaults = [
            'minify' => ENVIRONMENT === 'production',
            'version' => true,
            'preload' => false,
            'critical' => false,
            'media' => 'all'
        ];
        
        $options = array_merge($defaults, $options);
        
        if (!is_array($files)) {
            $files = [$files];
        }
        
        $output = '';
        
        foreach ($files as $file) {
            $file_path = _get_asset_path($file, 'css', $options['minify']);
            $file_url = base_url($file_path);
            
            // Add version for cache busting
            if ($options['version']) {
                $file_url .= '?v=' . _get_asset_version($file_path);
            }
            
            // Preload for critical CSS
            if ($options['preload']) {
                $output .= '<link rel="preload" href="' . $file_url . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">' . PHP_EOL;
                $output .= '<noscript><link rel="stylesheet" href="' . $file_url . '"></noscript>' . PHP_EOL;
            } else {
                $output .= '<link rel="stylesheet" href="' . $file_url . '" media="' . $options['media'] . '">' . PHP_EOL;
            }
        }
        
        return $output;
    }
}

if (!function_exists('load_js')) {
    /**
     * Load JavaScript files with optimization
     * 
     * @param mixed $files - string or array of JS files
     * @param array $options - loading options
     * @return string HTML script tags
     */
    function load_js($files, $options = []) {
        $CI =& get_instance();
        
        // Default options
        $defaults = [
            'minify' => ENVIRONMENT === 'production',
            'version' => true,
            'defer' => false,
            'async' => false,
            'preload' => false,
            'position' => 'head' // head or footer
        ];
        
        $options = array_merge($defaults, $options);
        
        if (!is_array($files)) {
            $files = [$files];
        }
        
        $output = '';
        
        foreach ($files as $file) {
            $file_path = _get_asset_path($file, 'js', $options['minify']);
            $file_url = base_url($file_path);
            
            // Add version for cache busting
            if ($options['version']) {
                $file_url .= '?v=' . _get_asset_version($file_path);
            }
            
            // Attributes
            $attributes = '';
            if ($options['defer']) $attributes .= ' defer';
            if ($options['async']) $attributes .= ' async';
            
            // Preload for important scripts
            if ($options['preload']) {
                $output .= '<link rel="preload" href="' . $file_url . '" as="script">' . PHP_EOL;
            }
            
            $output .= '<script src="' . $file_url . '"' . $attributes . '></script>' . PHP_EOL;
        }
        
        return $output;
    }
}

if (!function_exists('load_cdn_css')) {
    /**
     * Load CSS from CDN with fallback
     * 
     * @param string $cdn_url - CDN URL
     * @param string $fallback_file - Local fallback file
     * @param array $options - loading options
     * @return string HTML
     */
    function load_cdn_css($cdn_url, $fallback_file = null, $options = []) {
        $output = '<link rel="stylesheet" href="' . $cdn_url . '">' . PHP_EOL;
        
        // Add fallback if provided
        if ($fallback_file) {
            $fallback_url = base_url(_get_asset_path($fallback_file, 'css'));
            $output .= '<script>';
            $output .= 'if(!document.querySelector(\'link[href*="' . $cdn_url . '"]\')) {';
            $output .= 'document.write(\'<link rel="stylesheet" href="' . $fallback_url . '">\');';
            $output .= '}';
            $output .= '</script>' . PHP_EOL;
        }
        
        return $output;
    }
}

if (!function_exists('load_cdn_js')) {
    /**
     * Load JavaScript from CDN with fallback
     * 
     * @param string $cdn_url - CDN URL
     * @param string $fallback_file - Local fallback file
     * @param string $test_object - JavaScript object to test for CDN load
     * @param array $options - loading options
     * @return string HTML
     */
    function load_cdn_js($cdn_url, $fallback_file = null, $test_object = null, $options = []) {
        $attributes = '';
        if (isset($options['defer']) && $options['defer']) $attributes .= ' defer';
        if (isset($options['async']) && $options['async']) $attributes .= ' async';
        
        $output = '<script src="' . $cdn_url . '"' . $attributes . '></script>' . PHP_EOL;
        
        // Add fallback if provided
        if ($fallback_file && $test_object) {
            $fallback_url = base_url(_get_asset_path($fallback_file, 'js'));
            $output .= '<script>';
            $output .= 'if(typeof ' . $test_object . ' === "undefined") {';
            $output .= 'document.write(\'<script src="' . $fallback_url . '"><\/script>\');';
            $output .= '}';
            $output .= '</script>' . PHP_EOL;
        }
        
        return $output;
    }
}

if (!function_exists('inline_css')) {
    /**
     * Inline critical CSS
     * 
     * @param string $file - CSS file path
     * @return string Inline CSS
     */
    function inline_css($file) {
        $file_path = _get_asset_path($file, 'css');
        $full_path = FCPATH . $file_path;
        
        if (file_exists($full_path)) {
            $css = file_get_contents($full_path);
            
            // Basic minification for inline CSS
            if (ENVIRONMENT === 'production') {
                $css = _minify_css($css);
            }
            
            return '<style>' . $css . '</style>' . PHP_EOL;
        }
        
        return '';
    }
}

if (!function_exists('preload_assets')) {
    /**
     * Preload critical assets
     * 
     * @param array $assets - array of assets to preload
     * @return string HTML preload tags
     */
    function preload_assets($assets) {
        $output = '';
        
        foreach ($assets as $asset) {
            $type = pathinfo($asset, PATHINFO_EXTENSION);
            $as_type = ($type === 'css') ? 'style' : (($type === 'js') ? 'script' : 'fetch');
            
            $asset_url = base_url($asset) . '?v=' . _get_asset_version($asset);
            $output .= '<link rel="preload" href="' . $asset_url . '" as="' . $as_type . '">' . PHP_EOL;
        }
        
        return $output;
    }
}

if (!function_exists('_get_asset_path')) {
    /**
     * Get asset path with minification support
     * 
     * @param string $file - file name
     * @param string $type - css or js
     * @param bool $minify - use minified version
     * @return string asset path
     */
    function _get_asset_path($file, $type, $minify = false) {
        // Remove extension if provided
        $file = preg_replace('/\.(css|js)$/', '', $file);
        
        $base_path = 'assets/' . $type . '/' . $file;
        
        // Try minified version first if requested
        if ($minify) {
            $minified_path = $base_path . '.min.' . $type;
            if (file_exists(FCPATH . $minified_path)) {
                return $minified_path;
            }
        }
        
        return $base_path . '.' . $type;
    }
}

if (!function_exists('_get_asset_version')) {
    /**
     * Get asset version for cache busting
     * 
     * @param string $file_path - asset file path
     * @return string version string
     */
    function _get_asset_version($file_path) {
        $full_path = FCPATH . $file_path;
        
        if (file_exists($full_path)) {
            // Use file modification time for cache busting
            return filemtime($full_path);
        }
        
        // Fallback to application version or timestamp
        return defined('APP_VERSION') ? APP_VERSION : time();
    }
}

if (!function_exists('_minify_css')) {
    /**
     * Basic CSS minification
     * 
     * @param string $css - CSS content
     * @return string minified CSS
     */
    function _minify_css($css) {
        // Remove comments
        $css = preg_replace('/\/\*.*?\*\//', '', $css);
        
        // Remove whitespace
        $css = preg_replace('/\s+/', ' ', $css);
        
        // Remove unnecessary spaces
        $css = str_replace(['; ', ' {', '{ ', ' }', '} ', ': ', ', '], [';', '{', '{', '}', '}', ':', ','], $css);
        
        return trim($css);
    }
}

if (!function_exists('get_critical_css')) {
    /**
     * Get critical CSS for above-the-fold content
     * 
     * @return string critical CSS
     */
    function get_critical_css() {
        // Critical CSS for above-the-fold content
        return '
        body{font-family:"Work Sans",sans-serif;margin:0;padding:0}
        .header-absolute{position:absolute;top:0;left:0;right:0;z-index:1000}
        .top-bar{background:linear-gradient(135deg,#28a745,#20c997);color:white;height:35px;font-size:0.85rem}
        .navbar-brand{font-size:1.6rem;font-weight:700;color:#28a745!important;text-transform:uppercase;letter-spacing:0.8px}
        .navbar-nav .nav-link{font-weight:500;padding:0.5rem 0.9rem;font-size:0.95rem}
        .navbar-nav .nav-link:hover{color:#28a745!important}
        .search-wrapper{min-width:250px;max-width:280px}
        .search-form .input-group{border-radius:20px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.1)}
        .btn-primary{background:linear-gradient(135deg,#28a745,#20c997);border:none}
        @media(max-width:991px){.search-wrapper{min-width:100%;max-width:100%;margin-bottom:1rem}}
        ';
    }
}

/**
 * Asset Bundle Management
 */
if (!function_exists('get_asset_bundles')) {
    /**
     * Get predefined asset bundles
     * 
     * @return array asset bundles
     */
    function get_asset_bundles() {
        return [
            'core' => [
                'css' => [
                    'bootstrap',
                    'creative'
                ],
                'js' => [
                    'jquery',
                    'popper',
                    'bootstrap'
                ]
            ],
            'enhanced' => [
                'css' => [
                    'nouislider',
                    'owl.carousel.min',
                    'owl.theme.default.min'
                ],
                'js' => [
                    'nouislider',
                    'owl.carousel.min',
                    'parallax.min'
                ]
            ],
            'mobile' => [
                'css' => [
                    'mobile-enhancements'
                ],
                'js' => [
                    'mobile-nav',
                    'lazy-loading'
                ]
            ],
            'search' => [
                'css' => [
                    'search-enhancements'
                ],
                'js' => [
                    'search-enhanced',
                    'advanced-search'
                ]
            ],
            'product' => [
                'css' => [
                    'product-detail-enhancements'
                ],
                'js' => []
            ],
            'admin' => [
                'css' => [
                    'stock-management'
                ],
                'js' => []
            ]
        ];
    }
}

if (!function_exists('load_bundle')) {
    /**
     * Load a predefined asset bundle
     * 
     * @param string $bundle_name - bundle name
     * @param array $options - loading options
     * @return array [css, js] HTML strings
     */
    function load_bundle($bundle_name, $options = []) {
        $bundles = get_asset_bundles();
        
        if (!isset($bundles[$bundle_name])) {
            return ['css' => '', 'js' => ''];
        }
        
        $bundle = $bundles[$bundle_name];
        
        $css = '';
        $js = '';
        
        if (isset($bundle['css'])) {
            $css = load_css($bundle['css'], $options);
        }
        
        if (isset($bundle['js'])) {
            $js = load_js($bundle['js'], $options);
        }
        
        return ['css' => $css, 'js' => $js];
    }
}
