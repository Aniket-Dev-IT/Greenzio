<?php 
// Category management view for admin
?>

<div class="content">
    <div class="container-fluid">
        
        <?php if ($this->session->flashdata('item')) { ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <strong><?php echo $this->session->flashdata('item')['message']; ?></strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php } ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="header">
                        <h4 class="title">Category Management</h4>
                        <p class="category">Manage product categories and organize your inventory</p>
                    </div>
                    <div class="content">
                        
                        <!-- Current Categories -->
                        <div class="table-responsive table-full-width">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Category Name</th>
                                        <th>Products Count</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($categories)) { 
                                        foreach ($categories as $category) { 
                                    ?>
                                        <tr>
                                            <td>
                                                <strong><i class="fas fa-tag"></i> <?php echo ucfirst($category['category']); ?></strong>
                                            </td>
                                            <td>
                                                <span class="badge badge-info"><?php echo $category['product_count']; ?> Products</span>
                                            </td>
                                            <td>
                                                <a href="<?php echo base_url('admin/deleteCategory?id=' . urlencode($category['category'])); ?>" 
                                                   class="btn btn-danger btn-sm" 
                                                   title="Delete Category (WARNING: This will delete all products in this category!)"
                                                   onclick="return confirm('WARNING: This will delete ALL PRODUCTS in the <?php echo $category['category']; ?> category! This action cannot be undone. Are you sure?')">
                                                    <i class="fa fa-trash"></i> Delete Category
                                                </a>
                                            </td>
                                        </tr>
                                    <?php } 
                                    } else { ?>
                                        <tr>
                                            <td colspan="3" class="text-center">
                                                <i class="fa fa-folder-open fa-3x text-muted"></i><br>
                                                <strong>No categories found</strong><br>
                                                <small>Add some products to see categories here.</small>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                        <?php if (!empty($categories)) { ?>
                            <div class="footer">
                                <hr>
                                <div class="stats">
                                    <i class="fa fa-folder"></i> Total Categories: <strong><?php echo count($categories); ?></strong>
                                    | Total Products: <strong><?php 
                                        $total_products = 0;
                                        foreach ($categories as $cat) {
                                            $total_products += $cat['product_count'];
                                        }
                                        echo $total_products;
                                    ?></strong>
                                </div>
                            </div>
                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>

        <!-- Instructions -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="header">
                        <h4 class="title">Category Management Instructions</h4>
                        <p class="category">How to manage categories in your store</p>
                    </div>
                    <div class="content">
                        <div class="alert alert-info">
                            <strong><i class="fa fa-info-circle"></i> How Categories Work:</strong>
                            <ul class="mb-0">
                                <li><strong>Automatic Creation:</strong> Categories are created automatically when you add products</li>
                                <li><strong>Product Organization:</strong> Categories help organize products by type (Fruits & Vegetables, Dairy & Bakery, etc.)</li>
                                <li><strong>Grocery-Based:</strong> Categories are organized for grocery items with proper food categorization</li>
                                <li><strong>Dynamic Management:</strong> Categories appear and disappear based on available products</li>
                            </ul>
                        </div>
                        
                        <div class="alert alert-warning">
                            <strong><i class="fa fa-exclamation-triangle"></i> Important Notes:</strong>
                            <ul class="mb-0">
                                <li><strong>Deleting Categories:</strong> This will delete ALL products in that category permanently</li>
                                <li><strong>Adding Categories:</strong> Create new categories by adding products with new category names</li>
                                <li><strong>Category Names:</strong> Use consistent naming (Fruits & Vegetables, Dairy & Bakery, Grains & Rice, etc.)</li>
                            </ul>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <h5><i class="fa fa-plus"></i> To Add New Categories:</h5>
                                <p>Go to <a href="<?php echo base_url('admin/productinsert'); ?>"><strong>Insert Products</strong></a> and create products with new category names.</p>
                            </div>
                            <div class="col-md-6">
                                <h5><i class="fa fa-cog"></i> Manage Products:</h5>
                                <p>Go to <a href="<?php echo base_url('admin/productlist'); ?>"><strong>Manage Products</strong></a> to edit or delete individual products.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
