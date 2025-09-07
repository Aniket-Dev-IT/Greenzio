<?php
/**
 * Inventory Report View
 * Display detailed inventory analysis and reporting
 */
?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <div>
            <h1 class="h2 text-primary">
                <i class="fas fa-chart-line me-2"></i>
                Inventory Reports
            </h1>
            <p class="text-muted mb-0">Comprehensive inventory analysis and reporting dashboard</p>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-download me-1"></i>
                    Export PDF
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-file-excel me-1"></i>
                    Export Excel
                </button>
            </div>
            <button type="button" class="btn btn-sm btn-primary">
                <i class="fas fa-sync-alt me-1"></i>
                Refresh Data
            </button>
        </div>
    </div>

    <!-- Report Type Selection -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="card-title text-primary mb-2">
                                <i class="fas fa-filter me-2"></i>
                                Report Filters
                            </h5>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="reportType" class="form-label fw-semibold">Report Type</label>
                                    <select class="form-select" id="reportType">
                                        <option value="summary" <?= ($report_type ?? 'summary') == 'summary' ? 'selected' : '' ?>>Inventory Summary</option>
                                        <option value="low_stock" <?= ($report_type ?? '') == 'low_stock' ? 'selected' : '' ?>>Low Stock Alert</option>
                                        <option value="expiry" <?= ($report_type ?? '') == 'expiry' ? 'selected' : '' ?>>Expiry Report</option>
                                        <option value="category_wise" <?= ($report_type ?? '') == 'category_wise' ? 'selected' : '' ?>>Category Analysis</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="dateRange" class="form-label fw-semibold">Date Range</label>
                                    <select class="form-select" id="dateRange">
                                        <option value="today">Today</option>
                                        <option value="week" selected>This Week</option>
                                        <option value="month">This Month</option>
                                        <option value="quarter">This Quarter</option>
                                        <option value="custom">Custom Range</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="category" class="form-label fw-semibold">Category</label>
                                    <select class="form-select" id="category">
                                        <option value="all">All Categories</option>
                                        <option value="fruits">Fruits & Vegetables</option>
                                        <option value="dairy">Dairy & Bakery</option>
                                        <option value="grains">Grains & Rice</option>
                                        <option value="beverages">Beverages</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-primary">
                                    <i class="fas fa-search me-1"></i>
                                    Generate Report
                                </button>
                                <button type="button" class="btn btn-outline-secondary">
                                    <i class="fas fa-undo me-1"></i>
                                    Reset Filters
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50 mb-1">Total Products</h6>
                            <h3 class="mb-0 text-white">
                                <?= isset($report_data['total_products']) ? number_format($report_data['total_products']) : '0' ?>
                            </h3>
                        </div>
                        <div class="text-white-50">
                            <i class="fas fa-boxes fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-gradient-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50 mb-1">Low Stock Items</h6>
                            <h3 class="mb-0 text-white">15</h3>
                        </div>
                        <div class="text-white-50">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-gradient-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50 mb-1">Expiring Soon</h6>
                            <h3 class="mb-0 text-white">8</h3>
                        </div>
                        <div class="text-white-50">
                            <i class="fas fa-calendar-times fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-gradient-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50 mb-1">Total Value</h6>
                            <h3 class="mb-0 text-white">₹2,45,680</h3>
                        </div>
                        <div class="text-white-50">
                            <i class="fas fa-rupee-sign fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Stock Levels Chart -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title text-primary mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        Stock Levels by Category
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="stockLevelsChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
        <!-- Top Categories -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title text-primary mb-0">
                        <i class="fas fa-trophy me-2"></i>
                        Top Categories
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-semibold">Fruits & Vegetables</span>
                            <span class="badge bg-success">45 items</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: 85%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-semibold">Dairy & Bakery</span>
                            <span class="badge bg-info">23 items</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-info" style="width: 65%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-semibold">Grains & Rice</span>
                            <span class="badge bg-warning">18 items</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-warning" style="width: 45%"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-semibold">Beverages</span>
                            <span class="badge bg-primary">12 items</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary" style="width: 35%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Report Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title text-primary mb-0">
                        <i class="fas fa-table me-2"></i>
                        Detailed Inventory Report
                        <?php if (isset($report_type) && $report_type == 'low_stock'): ?>
                            - Low Stock Items
                        <?php elseif (isset($report_type) && $report_type == 'expiry'): ?>
                            - Expiring Items
                        <?php elseif (isset($report_type) && $report_type == 'category_wise'): ?>
                            - Category Analysis
                        <?php else: ?>
                            - Summary
                        <?php endif; ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Product Name</th>
                                    <th>Category</th>
                                    <th>Current Stock</th>
                                    <th>Unit Price</th>
                                    <th>Total Value</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Sample data rows -->
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-light rounded me-2">
                                                <i class="fas fa-apple-alt text-success"></i>
                                            </div>
                                            <span class="fw-semibold">Fresh Red Apples</span>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-success-soft text-success">Fruits</span></td>
                                    <td>
                                        <span class="fw-bold text-warning">15 kg</span>
                                        <small class="text-muted d-block">Min: 20 kg</small>
                                    </td>
                                    <td>₹120/kg</td>
                                    <td>₹1,800</td>
                                    <td><span class="badge bg-warning">Low Stock</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-light rounded me-2">
                                                <i class="fas fa-cheese text-warning"></i>
                                            </div>
                                            <span class="fw-semibold">Premium Cheddar Cheese</span>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-info-soft text-info">Dairy</span></td>
                                    <td>
                                        <span class="fw-bold text-danger">3 pcs</span>
                                        <small class="text-muted d-block">Expires: 2 days</small>
                                    </td>
                                    <td>₹450/pc</td>
                                    <td>₹1,350</td>
                                    <td><span class="badge bg-danger">Expiring Soon</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-light rounded me-2">
                                                <i class="fas fa-seedling text-primary"></i>
                                            </div>
                                            <span class="fw-semibold">Organic Basmati Rice</span>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-warning-soft text-warning">Grains</span></td>
                                    <td>
                                        <span class="fw-bold text-success">50 kg</span>
                                        <small class="text-muted d-block">Min: 30 kg</small>
                                    </td>
                                    <td>₹180/kg</td>
                                    <td>₹9,000</td>
                                    <td><span class="badge bg-success">In Stock</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-chart-line"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Showing 1-3 of 98 entries
                        </div>
                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item disabled">
                                    <span class="page-link">Previous</span>
                                </li>
                                <li class="page-item active">
                                    <span class="page-link">1</span>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#" onclick="event.preventDefault()">2</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#" onclick="event.preventDefault()">3</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="#" onclick="event.preventDefault()">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Stock Levels Chart
    const ctx = document.getElementById('stockLevelsChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Fruits & Vegetables', 'Dairy & Bakery', 'Grains & Rice', 'Beverages', 'Frozen Foods'],
            datasets: [{
                label: 'Items in Stock',
                data: [45, 23, 18, 12, 8],
                backgroundColor: [
                    'rgba(40, 167, 69, 0.8)',
                    'rgba(23, 162, 184, 0.8)',
                    'rgba(255, 193, 7, 0.8)',
                    'rgba(0, 123, 255, 0.8)',
                    'rgba(108, 117, 125, 0.8)'
                ],
                borderColor: [
                    'rgba(40, 167, 69, 1)',
                    'rgba(23, 162, 184, 1)',
                    'rgba(255, 193, 7, 1)',
                    'rgba(0, 123, 255, 1)',
                    'rgba(108, 117, 125, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
    
    // Event listeners for filters
    document.getElementById('reportType').addEventListener('change', function() {
        // In a real implementation, this would reload the report
        console.log('Report type changed to:', this.value);
    });
    
    document.getElementById('dateRange').addEventListener('change', function() {
        console.log('Date range changed to:', this.value);
    });
    
    document.getElementById('category').addEventListener('change', function() {
        console.log('Category changed to:', this.value);
    });
});
</script>

<style>
.bg-gradient-primary {
    background: linear-gradient(45deg, #007bff, #0056b3);
}

.bg-gradient-warning {
    background: linear-gradient(45deg, #ffc107, #e0a800);
}

.bg-gradient-danger {
    background: linear-gradient(45deg, #dc3545, #b02a37);
}

.bg-gradient-success {
    background: linear-gradient(45deg, #28a745, #1e7e34);
}

.bg-success-soft {
    background-color: rgba(40, 167, 69, 0.1);
}

.bg-info-soft {
    background-color: rgba(23, 162, 184, 0.1);
}

.bg-warning-soft {
    background-color: rgba(255, 193, 7, 0.1);
}

.avatar-sm {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.table > :not(caption) > * > * {
    padding: 0.75rem 0.5rem;
}
</style>
