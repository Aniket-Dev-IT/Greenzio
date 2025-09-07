<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="header">
                        <h4 class="title">Add Product</h4>
                    </div>
                    <div class="content">
                        <form enctype="multipart/form-data" method="post" action="<?php echo base_url('admin/insertProduct'); ?>">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Product Name</label>
                                        <input type="text" class="form-control" placeholder="Enter Product Name" required name="productName">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="category">Category</label>
                                        <select class="form-control" name="category" required>
                                            <option value="">Select Category</option>
                                            <option value="Fruits & Vegetables">Fruits & Vegetables</option>
                                            <option value="Grains & Rice">Grains & Rice</option>
                                            <option value="Dairy & Bakery">Dairy & Bakery</option>
                                            <option value="Spices & Seasonings">Spices & Seasonings</option>
                                            <option value="Cooking Oils">Cooking Oils</option>
                                            <option value="Beverages">Beverages</option>
                                            <option value="Snacks & Instant Food">Snacks & Instant Food</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="subcategory">Subcategory</label>
                                        <input type="text" class="form-control" placeholder="e.g., Fruits, Rice, Spices" required name="subcategory">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="brand">Brand</label>
                                        <input type="text" class="form-control" placeholder="e.g., Tata, Fortune" required name="brand">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Price</label>
                                        <input type="number" class="form-control" placeholder="Product Price" required name="price">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Discount</label>
                                        <input type="number" class="form-control" placeholder="Discount of Product" value="0" name="discount">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Weight/Quantity</label>
                                        <input type="text" class="form-control" placeholder="e.g., 1, 500, 250" required name="weight">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Unit</label>
                                        <select class="form-control" name="unit" required>
                                            <option value="">Select Unit</option>
                                            <option value="kg">Kilogram (kg)</option>
                                            <option value="grams">Grams</option>
                                            <option value="litre">Litre</option>
                                            <option value="ml">Millilitre (ml)</option>
                                            <option value="piece">Piece</option>
                                            <option value="dozen">Dozen</option>
                                            <option value="pack">Pack</option>
                                            <option value="box">Box</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Stock Quantity</label>
                                        <input type="number" class="form-control" placeholder="Available Stock" required name="stock_quantity" min="0">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Reorder Level</label>
                                        <input type="number" class="form-control" placeholder="Minimum stock level" name="reorder_level" min="1" value="10">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Expiry Date</label>
                                        <input type="date" class="form-control" name="expiry_date" min="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Product Type</label>
                                        <select class="form-control" name="product_type">
                                            <option value="regular">Regular</option>
                                            <option value="perishable">Perishable</option>
                                            <option value="organic">Organic</option>
                                            <option value="premium">Premium</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Storage Temperature</label>
                                        <select class="form-control" name="storage_temp">
                                            <option value="room_temperature">Room Temperature</option>
                                            <option value="refrigerated">Refrigerated (2-8°C)</option>
                                            <option value="frozen">Frozen (-18°C)</option>
                                            <option value="cool_dry">Cool & Dry</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Country of Origin</label>
                                        <input type="text" class="form-control" placeholder="e.g., India, USA" name="country_origin" value="India">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nutritional Category</label>
                                        <select class="form-control" name="nutrition_category">
                                            <option value="">Select Category (Optional)</option>
                                            <option value="high_protein">High Protein</option>
                                            <option value="high_fiber">High Fiber</option>
                                            <option value="low_fat">Low Fat</option>
                                            <option value="sugar_free">Sugar Free</option>
                                            <option value="gluten_free">Gluten Free</option>
                                            <option value="vitamin_rich">Vitamin Rich</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Product Description</label>
                                        <textarea class="form-control" rows="3" placeholder="Brief description of the product" name="description"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Ingredients (Optional)</label>
                                        <textarea class="form-control" rows="3" placeholder="List main ingredients separated by commas" name="ingredients"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Storage Instructions</label>
                                        <textarea class="form-control" rows="3" placeholder="How to store this product" name="storage_instructions"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Image Upload</label>
                                        <input type="file" class="form-control" required name="image">
                                    </div>
                                </div>
                            </div>
                        
                            <button type="submit" class="btn btn-info btn-fill pull-right">Add Product</button>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div>
            </div>
    
        </div>
    </div>
</div>