<?php
/**
 * Asset Optimization Script for Greenzio
 * 
 * This script minifies CSS and JavaScript files for production deployment
 * Run this script before deploying to production for optimal performance
 * 
 * Usage: php optimize-assets.php
 */

// Define paths
define('ASSET_PATH', dirname(__DIR__) . '/assets/');
define('CSS_PATH', ASSET_PATH . 'css/');
define('JS_PATH', ASSET_PATH . 'js/');
define('FONTAWESOME_PATH', ASSET_PATH . 'fontawesome/');

echo "=== Greenzio Asset Optimization ===\n\n";

// CSS Files to optimize
$css_files = [
    'bootstrap.css',
    'creative.css',
    'nouislider.css',
    'owl.carousel.css',
    'owl.theme.default.css',
    'search-enhancements.css',
    'product-detail-enhancements.css',
    'mobile-enhancements.css',
    'stock-management.css'
];

// JS Files to optimize
$js_files = [
    'jquery.js',
    'popper.js',
    'bootstrap.js',
    'creative.js',
    'nouislider.js',
    'owl.carousel.js',
    'parallax.js',
    'mobile-nav.js',
    'lazy-loading.js',
    'error-handler.js',
    'search-enhanced.js',
    'advanced-search.js'
];

/**
 * Minify CSS content
 */
function minify_css($css) {
    // Remove comments
    $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
    
    // Remove whitespace
    $css = str_replace(["\r\n", "\r", "\n", "\t", '  ', '    ', '    '], '', $css);
    
    // Remove unnecessary spaces
    $css = str_replace(['; ', ' {', '{ ', ' }', '} ', ': ', ', '], [';', '{', '{', '}', '}', ':', ','], $css);
    
    // Remove trailing semicolon before closing brace
    $css = str_replace(';}', '}', $css);
    
    return trim($css);
}

/**
 * Basic JS minification (removes comments and whitespace)
 */
function minify_js($js) {
    // Remove single line comments (but not URLs)
    $js = preg_replace('/(?<!:)\/\/.*$/m', '', $js);
    
    // Remove multi-line comments
    $js = preg_replace('/\/\*[\s\S]*?\*\//', '', $js);
    
    // Remove excess whitespace but preserve necessary spaces
    $js = preg_replace('/\s+/', ' ', $js);
    
    // Remove spaces around operators and punctuation (carefully)
    $js = str_replace([' = ', ' == ', ' === ', ' != ', ' !== ', ' + ', ' - ', ' * ', ' / ', ' % ', 
                      ' && ', ' || ', ' { ', ' } ', ' [ ', ' ] ', ' ( ', ' ) ', ' ; ', ' : ', ' , '],
                     ['=', '==', '===', '!=', '!==', '+', '-', '*', '/', '%', 
                      '&&', '||', '{', '}', '[', ']', '(', ')', ';', ':', ','], $js);
    
    return trim($js);
}

/**
 * Process CSS files
 */
echo "Processing CSS files...\n";
$css_processed = 0;
$css_saved_bytes = 0;

foreach ($css_files as $file) {
    $source_path = CSS_PATH . $file;
    $minified_path = CSS_PATH . str_replace('.css', '.min.css', $file);
    
    if (file_exists($source_path)) {
        $original_content = file_get_contents($source_path);
        $original_size = strlen($original_content);
        
        $minified_content = minify_css($original_content);
        $minified_size = strlen($minified_content);
        
        file_put_contents($minified_path, $minified_content);
        
        $saved_bytes = $original_size - $minified_size;
        $css_saved_bytes += $saved_bytes;
        $css_processed++;
        
        $reduction_percent = round(($saved_bytes / $original_size) * 100, 1);
        
        echo "  ✓ {$file} -> " . str_replace('.css', '.min.css', $file) . 
             " ({$reduction_percent}% smaller, saved " . number_format($saved_bytes) . " bytes)\n";
    } else {
        echo "  ✗ {$file} not found\n";
    }
}

echo "CSS Processing Complete: {$css_processed} files, " . number_format($css_saved_bytes) . " bytes saved\n\n";

/**
 * Process JS files
 */
echo "Processing JavaScript files...\n";
$js_processed = 0;
$js_saved_bytes = 0;

foreach ($js_files as $file) {
    $source_path = JS_PATH . $file;
    $minified_path = JS_PATH . str_replace('.js', '.min.js', $file);
    
    if (file_exists($source_path)) {
        $original_content = file_get_contents($source_path);
        $original_size = strlen($original_content);
        
        $minified_content = minify_js($original_content);
        $minified_size = strlen($minified_content);
        
        file_put_contents($minified_path, $minified_content);
        
        $saved_bytes = $original_size - $minified_size;
        $js_saved_bytes += $saved_bytes;
        $js_processed++;
        
        $reduction_percent = round(($saved_bytes / $original_size) * 100, 1);
        
        echo "  ✓ {$file} -> " . str_replace('.js', '.min.js', $file) . 
             " ({$reduction_percent}% smaller, saved " . number_format($saved_bytes) . " bytes)\n";
    } else {
        echo "  ✗ {$file} not found\n";
    }
}

echo "JS Processing Complete: {$js_processed} files, " . number_format($js_saved_bytes) . " bytes saved\n\n";

/**
 * Create combined bundles for common assets
 */
echo "Creating asset bundles...\n";

// Core CSS Bundle
$core_css_files = ['bootstrap.css', 'creative.css'];
$core_css_content = '';

foreach ($core_css_files as $file) {
    $file_path = CSS_PATH . str_replace('.css', '.min.css', $file);
    if (file_exists($file_path)) {
        $core_css_content .= file_get_contents($file_path) . "\n";
    }
}

if ($core_css_content) {
    file_put_contents(CSS_PATH . 'core.bundle.min.css', $core_css_content);
    echo "  ✓ Created core.bundle.min.css (" . number_format(strlen($core_css_content)) . " bytes)\n";
}

// Core JS Bundle
$core_js_files = ['jquery.js', 'popper.js', 'bootstrap.js'];
$core_js_content = '';

foreach ($core_js_files as $file) {
    $file_path = JS_PATH . str_replace('.js', '.min.js', $file);
    if (file_exists($file_path)) {
        $core_js_content .= file_get_contents($file_path) . ";\n";
    }
}

if ($core_js_content) {
    file_put_contents(JS_PATH . 'core.bundle.min.js', $core_js_content);
    echo "  ✓ Created core.bundle.min.js (" . number_format(strlen($core_js_content)) . " bytes)\n";
}

// Enhanced JS Bundle
$enhanced_js_files = ['nouislider.js', 'owl.carousel.js', 'parallax.js'];
$enhanced_js_content = '';

foreach ($enhanced_js_files as $file) {
    $file_path = JS_PATH . str_replace('.js', '.min.js', $file);
    if (file_exists($file_path)) {
        $enhanced_js_content .= file_get_contents($file_path) . ";\n";
    }
}

if ($enhanced_js_content) {
    file_put_contents(JS_PATH . 'enhanced.bundle.min.js', $enhanced_js_content);
    echo "  ✓ Created enhanced.bundle.min.js (" . number_format(strlen($enhanced_js_content)) . " bytes)\n";
}

/**
 * Generate critical CSS
 */
echo "\nGenerating critical CSS...\n";

$critical_css = '
/* Critical CSS for Greenzio - Above the fold styles */
*{box-sizing:border-box}
body{font-family:"Work Sans",sans-serif;margin:0;padding:0;color:#333}
.container-fluid{max-width:1200px;margin:0 auto;padding:0 15px}
.header-absolute{position:absolute;top:0;left:0;right:0;z-index:1000}
.top-bar{background:linear-gradient(135deg,#28a745,#20c997);color:white;height:35px;font-size:0.85rem;display:flex;align-items:center}
.navbar{background:rgba(255,255,255,0.95);backdrop-filter:blur(10px);box-shadow:0 2px 10px rgba(0,0,0,0.1)}
.navbar-brand{font-size:1.6rem;font-weight:700;color:#28a745!important;text-transform:uppercase;letter-spacing:0.8px;text-decoration:none}
.navbar-nav{display:flex;list-style:none;margin:0;padding:0}
.navbar-nav .nav-link{font-weight:500;padding:0.5rem 0.9rem;font-size:0.95rem;color:#333;text-decoration:none;transition:color 0.3s}
.navbar-nav .nav-link:hover{color:#28a745!important}
.search-wrapper{min-width:250px;max-width:280px}
.search-form .input-group{border-radius:20px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.1);display:flex}
.form-control{border:1px solid #ced4da;padding:0.375rem 0.75rem;border-radius:0.25rem}
.btn-primary{background:linear-gradient(135deg,#28a745,#20c997);border:none;color:white;padding:0.375rem 0.75rem;border-radius:0.25rem;cursor:pointer;text-decoration:none;display:inline-block}
.btn-primary:hover{background:linear-gradient(135deg,#218838,#1ea080);color:white}
.d-flex{display:flex}
.align-items-center{align-items:center}
.justify-content-between{justify-content:space-between}
.text-center{text-align:center}
.mb-0{margin-bottom:0}
.mr-2{margin-right:0.5rem}
.position-relative{position:relative}
.position-absolute{position:absolute}
.badge{display:inline-block;padding:0.25em 0.4em;font-size:75%;font-weight:700;line-height:1;text-align:center;white-space:nowrap;vertical-align:baseline;border-radius:0.25rem}
.badge-primary{color:#fff;background-color:#007bff}
.badge-pill{padding-right:0.6em;padding-left:0.6em;border-radius:10rem}
@media(max-width:991px){
.search-wrapper{min-width:100%;max-width:100%;margin-bottom:1rem}
.navbar-nav{flex-direction:column;width:100%}
.mobile-nav-toggle{display:block;background:none;border:none;color:#333;font-size:1.2rem;cursor:pointer}
}
@media(min-width:992px){
.mobile-nav-toggle{display:none}
.navbar-collapse{display:flex!important}
}
';

file_put_contents(CSS_PATH . 'critical.min.css', minify_css($critical_css));
echo "  ✓ Created critical.min.css (" . number_format(strlen($critical_css)) . " bytes)\n";

/**
 * Generate asset manifest for cache busting
 */
echo "\nGenerating asset manifest...\n";

$manifest = [];
$asset_dirs = ['css', 'js'];

foreach ($asset_dirs as $dir) {
    $dir_path = ASSET_PATH . $dir . '/';
    $files = glob($dir_path . '*.min.{css,js}', GLOB_BRACE);
    
    foreach ($files as $file) {
        $filename = basename($file);
        $manifest[$filename] = [
            'path' => 'assets/' . $dir . '/' . $filename,
            'size' => filesize($file),
            'modified' => filemtime($file),
            'hash' => md5_file($file)
        ];
    }
}

file_put_contents(ASSET_PATH . 'manifest.json', json_encode($manifest, JSON_PRETTY_PRINT));
echo "  ✓ Created asset manifest (" . count($manifest) . " files)\n";

/**
 * Performance recommendations
 */
echo "\n=== Performance Recommendations ===\n";
echo "1. Enable gzip compression on your web server\n";
echo "2. Set long cache headers for static assets (1 year)\n";
echo "3. Use the optimized header template (header_optimized.php)\n";
echo "4. Consider using a CDN for static assets\n";
echo "5. Enable browser caching with proper ETags\n\n";

/**
 * Summary
 */
$total_saved = $css_saved_bytes + $js_saved_bytes;
$total_processed = $css_processed + $js_processed;

echo "=== Optimization Summary ===\n";
echo "Files processed: {$total_processed}\n";
echo "Total bytes saved: " . number_format($total_saved) . "\n";
echo "Estimated bandwidth savings: ~" . round($total_saved / 1024, 1) . " KB per page load\n";

if ($total_saved > 0) {
    echo "\n🎉 Asset optimization completed successfully!\n";
    echo "Your Greenzio application is now production-ready with optimized assets.\n";
} else {
    echo "\n⚠️  No optimization savings achieved. Check if source files exist.\n";
}

echo "\nDone.\n";
?>
