<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">
                            <i class="fas fa-chart-bar me-2" style="color: #66bb6a;"></i>
                            Reports & Analytics
                        </h4>
                        <p class="text-muted mb-0">Business insights and performance metrics</p>
                    </div>
                    <div class="card-body">
                        <!-- Summary Cards -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-boxes fa-2x me-3"></i>
                                            <div>
                                                <h4 class="mb-0"><?php echo $reports_data['total_products'] ?? 0; ?></h4>
                                                <small>Total Products</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-users fa-2x me-3"></i>
                                            <div>
                                                <h4 class="mb-0"><?php echo $reports_data['total_users'] ?? 0; ?></h4>
                                                <small>Total Users</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-shopping-cart fa-2x me-3"></i>
                                            <div>
                                                <h4 class="mb-0"><?php echo $reports_data['total_orders'] ?? 0; ?></h4>
                                                <small>Total Orders</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-rupee-sign fa-2x me-3"></i>
                                            <div>
                                                <h4 class="mb-0">₹<?php echo number_format($reports_data['monthly_revenue'] ?? 0, 2); ?></h4>
                                                <small>Monthly Revenue</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Report Filters -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <label for="reportType" class="form-label">Report Type</label>
                                <select class="form-control" id="reportType">
                                    <option value="overview">Overview</option>
                                    <option value="sales">Sales Report</option>
                                    <option value="products">Product Performance</option>
                                    <option value="users">User Analytics</option>
                                    <option value="inventory">Inventory Report</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="dateRange" class="form-label">Date Range</label>
                                <select class="form-control" id="dateRange">
                                    <option value="today">Today</option>
                                    <option value="week">This Week</option>
                                    <option value="month" selected>This Month</option>
                                    <option value="quarter">This Quarter</option>
                                    <option value="year">This Year</option>
                                    <option value="custom">Custom Range</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="dateCustom" class="form-label">Custom Date Range</label>
                                <div class="input-group">
                                    <input type="date" class="form-control" id="dateFrom" disabled>
                                    <span class="input-group-text">to</span>
                                    <input type="date" class="form-control" id="dateTo" disabled>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-grid">
                                    <button class="btn btn-success" onclick="generateReport()">
                                        <i class="fas fa-chart-line"></i> Generate
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Top Products Report -->
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-star me-2"></i>
                                    Top Products
                                </h5>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($reports_data['top_products'])): ?>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Product Name</th>
                                                    <th>Category</th>
                                                    <th>Price</th>
                                                    <th>Stock</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($reports_data['top_products'] as $product): ?>
                                                    <tr>
                                                        <td><strong><?php echo htmlspecialchars($product['pname']); ?></strong></td>
                                                        <td><span class="badge badge-secondary"><?php echo htmlspecialchars($product['category']); ?></span></td>
                                                        <td>₹<?php echo number_format($product['price'], 2); ?></td>
                                                        <td><?php echo $product['stock_quantity'] ?? 0; ?></td>
                                                        <td><span class="badge badge-success">Active</span></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-4">
                                        <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                                        <h6 class="text-muted">No product data available</h6>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-user-plus me-2"></i>
                                    Recent Signups
                                </h5>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($reports_data['recent_signups'])): ?>
                                    <div class="list-group list-group-flush">
                                        <?php foreach ($reports_data['recent_signups'] as $user): ?>
                                            <div class="list-group-item d-flex align-items-center px-0">
                                                <div class="avatar me-3">
                                                    <i class="fas fa-user-circle fa-2x text-primary"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-1"><?php echo htmlspecialchars($user['fname'] . ' ' . $user['lname']); ?></h6>
                                                    <small class="text-muted"><?php echo htmlspecialchars($user['email']); ?></small>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-4">
                                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                        <h6 class="text-muted">No recent signups</h6>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sales Chart Placeholder -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-chart-area me-2"></i>
                                    Sales Trend
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="text-center py-5">
                                    <i class="fas fa-chart-area fa-4x text-muted mb-3"></i>
                                    <h5 class="text-muted">Sales Chart</h5>
                                    <p class="text-muted">Sales trend visualization would be implemented here using Chart.js or similar library.</p>
                                    <button class="btn btn-primary" onclick="loadSalesChart()">
                                        <i class="fas fa-chart-line"></i> Load Sales Chart
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Export Options -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-download me-2"></i>
                                    Export Reports
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <p class="text-muted mb-3">Export your reports in various formats for external analysis or record keeping.</p>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="btn-group w-100" role="group">
                                            <button type="button" class="btn btn-outline-success" onclick="exportReport('excel')">
                                                <i class="fas fa-file-excel"></i> Excel
                                            </button>
                                            <button type="button" class="btn btn-outline-danger" onclick="exportReport('pdf')">
                                                <i class="fas fa-file-pdf"></i> PDF
                                            </button>
                                            <button type="button" class="btn btn-outline-primary" onclick="exportReport('csv')">
                                                <i class="fas fa-file-csv"></i> CSV
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Handle date range selection
document.getElementById('dateRange').addEventListener('change', function() {
    const customInputs = [document.getElementById('dateFrom'), document.getElementById('dateTo')];
    if (this.value === 'custom') {
        customInputs.forEach(input => input.disabled = false);
    } else {
        customInputs.forEach(input => input.disabled = true);
    }
});

function generateReport() {
    const reportType = document.getElementById('reportType').value;
    const dateRange = document.getElementById('dateRange').value;
    
    alert(`Generating ${reportType} report for ${dateRange} period. This functionality would be implemented to dynamically generate reports.`);
}

function loadSalesChart() {
    alert('Sales chart functionality would be implemented here using Chart.js or similar charting library.');
}

function exportReport(format) {
    alert(`Export to ${format.toUpperCase()} functionality would be implemented here.`);
}
</script>

<style>
.card {
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    border: none;
}

.card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid #dee2e6;
    border-radius: 10px 10px 0 0 !important;
}

.badge {
    padding: 0.5rem 0.75rem;
    font-size: 0.75rem;
}

.table th {
    border-top: none;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
}

.list-group-item {
    border: none;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f1f3f4;
}

.list-group-item:last-child {
    border-bottom: none;
}

.btn-group .btn {
    flex: 1;
}
</style>
