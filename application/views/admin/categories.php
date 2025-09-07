<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">
                            <i class="fas fa-tags me-2" style="color: #27ae60;"></i>
                            Categories Management
                        </h4>
                        <p class="text-muted mb-0">Organize and manage product categories</p>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                    <i class="fas fa-plus"></i> Add New Category
                                </button>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search categories...">
                                    <button class="btn btn-outline-secondary" type="button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <?php if (!empty($categories)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Category Name</th>
                                            <th>Total Products</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($categories as $category): ?>
                                            <tr>
                                                <td><?php echo $category['id']; ?></td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-tag text-primary me-2"></i>
                                                        <strong><?php echo htmlspecialchars($category['name']); ?></strong>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-info"><?php echo $category['products']; ?> products</span>
                                                </td>
                                                <td>
                                                    <?php if ($category['status'] === 'active'): ?>
                                                        <span class="badge badge-success">Active</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-secondary">Inactive</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button class="btn btn-sm btn-primary" onclick="editCategory(<?php echo $category['id']; ?>)">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </button>
                                                        <button class="btn btn-sm btn-info" onclick="viewProducts(<?php echo $category['id']; ?>)">
                                                            <i class="fas fa-eye"></i> View Products
                                                        </button>
                                                        <button class="btn btn-sm btn-danger" onclick="deleteCategory(<?php echo $category['id']; ?>)">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="fas fa-tags fa-4x text-muted mb-3"></i>
                                <h5 class="text-muted">No categories found</h5>
                                <p class="text-muted">Create your first product category to get started.</p>
                                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                                    <i class="fas fa-plus"></i> Add Category
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Category Statistics -->
                <div class="row mt-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-tags fa-2x me-3"></i>
                                    <div>
                                        <h5 class="mb-0"><?php echo count($categories ?? []); ?></h5>
                                        <small>Total Categories</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle fa-2x me-3"></i>
                                    <div>
                                        <h5 class="mb-0"><?php echo count(array_filter($categories ?? [], function($c) { return $c['status'] === 'active'; })); ?></h5>
                                        <small>Active Categories</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-boxes fa-2x me-3"></i>
                                    <div>
                                        <h5 class="mb-0"><?php echo array_sum(array_column($categories ?? [], 'products')); ?></h5>
                                        <small>Total Products</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-star fa-2x me-3"></i>
                                    <div>
                                        <h5 class="mb-0"><?php 
                                        $mostProducts = !empty($categories) ? max(array_column($categories, 'products')) : 0;
                                        echo $mostProducts;
                                        ?></h5>
                                        <small>Most Products</small>
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

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addCategoryForm">
                    <div class="mb-3">
                        <label for="categoryName" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="categoryName" required>
                    </div>
                    <div class="mb-3">
                        <label for="categoryDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="categoryDescription" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="categoryStatus" class="form-label">Status</label>
                        <select class="form-control" id="categoryStatus">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" onclick="saveCategory()">Save Category</button>
            </div>
        </div>
    </div>
</div>

<script>
function editCategory(categoryId) {
    alert('Edit category functionality for ID: ' + categoryId + ' would be implemented here.');
}

function viewProducts(categoryId) {
    window.location.href = '<?php echo base_url("admin/productlist"); ?>?category=' + categoryId;
}

function deleteCategory(categoryId) {
    if (confirm('Are you sure you want to delete this category?')) {
        alert('Delete category functionality for ID: ' + categoryId + ' would be implemented here.');
    }
}

function saveCategory() {
    const name = document.getElementById('categoryName').value;
    const description = document.getElementById('categoryDescription').value;
    const status = document.getElementById('categoryStatus').value;
    
    if (!name) {
        alert('Please enter a category name.');
        return;
    }
    
    // Here you would implement the actual save functionality
    alert('Save category functionality would be implemented here.');
    
    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('addCategoryModal'));
    modal.hide();
    
    // Reset form
    document.getElementById('addCategoryForm').reset();
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

.btn-group .btn {
    margin-right: 0.25rem;
}

.btn-group .btn:last-child {
    margin-right: 0;
}
</style>
