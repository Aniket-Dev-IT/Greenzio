<?php
/**
 * Breadcrumbs Partial
 * Reusable breadcrumb navigation component
 * 
 * Required variables:
 * - $breadcrumbs: Array of breadcrumb items
 * Optional variables:
 * - $show_home: Boolean to show home link (default: true)
 * - $separator: Custom separator (default: uses CSS)
 */

// Set defaults
$show_home = isset($show_home) ? $show_home : true;
$separator = isset($separator) ? $separator : '';

// Helper functions
$this->load->helper(['common']);

// Ensure breadcrumbs array exists
if (!isset($breadcrumbs)) {
    $breadcrumbs = generate_breadcrumbs();
}

// Add home if not present and show_home is true
if ($show_home && (empty($breadcrumbs) || $breadcrumbs[0]['title'] !== 'Home')) {
    array_unshift($breadcrumbs, [
        'title' => 'Home',
        'url' => base_url(),
        'active' => false
    ]);
}
?>

<?php if (!empty($breadcrumbs)): ?>
<nav aria-label="breadcrumb" class="breadcrumb-nav">
    <div class="container">
        <ol class="breadcrumb bg-transparent mb-0 py-3">
            <?php foreach ($breadcrumbs as $index => $crumb): ?>
                <?php 
                $is_last = ($index === count($breadcrumbs) - 1);
                $is_active = isset($crumb['active']) ? $crumb['active'] : $is_last;
                ?>
                
                <li class="breadcrumb-item <?php echo $is_active ? 'active' : ''; ?>" 
                    <?php echo $is_active ? 'aria-current="page"' : ''; ?>>
                    
                    <?php if (!$is_active && isset($crumb['url']) && !empty($crumb['url'])): ?>
                        <a href="<?php echo $crumb['url']; ?>" class="breadcrumb-link">
                            <?php if (isset($crumb['icon']) && !empty($crumb['icon'])): ?>
                                <i class="<?php echo $crumb['icon']; ?> me-1"></i>
                            <?php endif; ?>
                            <?php echo htmlspecialchars($crumb['title']); ?>
                        </a>
                    <?php else: ?>
                        <?php if (isset($crumb['icon']) && !empty($crumb['icon'])): ?>
                            <i class="<?php echo $crumb['icon']; ?> me-1"></i>
                        <?php endif; ?>
                        <?php echo htmlspecialchars($crumb['title']); ?>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ol>
    </div>
</nav>
<?php endif; ?>

<style>
.breadcrumb-nav {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.breadcrumb {
    padding: 0.75rem 0;
    margin-bottom: 0;
    list-style: none;
    background-color: transparent;
    border-radius: 0;
}

.breadcrumb-item {
    display: inline-flex;
    align-items: center;
}

.breadcrumb-item + .breadcrumb-item {
    padding-left: 0.5rem;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: ">";
    display: inline-block;
    padding-right: 0.5rem;
    color: #6c757d;
    font-weight: 600;
}

.breadcrumb-link {
    color: #007bff;
    text-decoration: none;
    transition: color 0.15s ease-in-out;
}

.breadcrumb-link:hover {
    color: #0056b3;
    text-decoration: underline;
}

.breadcrumb-item.active {
    color: #6c757d;
    font-weight: 500;
}

/* Custom separator if provided */
<?php if (!empty($separator)): ?>
.breadcrumb-item + .breadcrumb-item::before {
    content: "<?php echo htmlspecialchars($separator); ?>";
}
<?php endif; ?>

/* Responsive design */
@media (max-width: 768px) {
    .breadcrumb {
        font-size: 0.875rem;
        padding: 0.5rem 0;
    }
    
    .breadcrumb-item {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    /* Hide middle items on small screens if there are many breadcrumbs */
    .breadcrumb-item:not(:first-child):not(:last-child):not(:nth-last-child(2)) {
        display: none;
    }
    
    /* Show ellipsis for hidden items */
    .breadcrumb-item:first-child:not(:last-child):not(:nth-last-child(2))::after {
        content: " ... ";
        color: #6c757d;
        padding: 0 0.25rem;
    }
}

@media (max-width: 576px) {
    .breadcrumb {
        flex-wrap: nowrap;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    .breadcrumb-item {
        flex: 0 0 auto;
        white-space: nowrap;
    }
}

/* Accessibility improvements */
.breadcrumb-link:focus {
    outline: 2px solid #007bff;
    outline-offset: 2px;
    border-radius: 2px;
}

/* Icon spacing */
.breadcrumb-item i {
    margin-right: 0.25rem;
}

/* Print styles */
@media print {
    .breadcrumb-nav {
        display: none;
    }
}
</style>

<script>
// Optional: Add click tracking for breadcrumbs
document.addEventListener('DOMContentLoaded', function() {
    const breadcrumbLinks = document.querySelectorAll('.breadcrumb-link');
    
    breadcrumbLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            // Optional: Add analytics tracking here
            // ga('send', 'event', 'Breadcrumb', 'click', this.textContent.trim());
        });
    });
});
</script>
