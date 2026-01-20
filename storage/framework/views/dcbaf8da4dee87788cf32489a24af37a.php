<?php $__env->startSection('title', 'View Product'); ?>
<?php $__env->startSection('page-title', 'View Product'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Product Details</h4>
    <div>
        <a href="<?php echo e(route('admin.products.edit', $product)); ?>" class="btn btn-primary">
            <i class="fas fa-edit"></i> Edit Product
        </a>
        <a href="<?php echo e(route('admin.products.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Products
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Product Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Product Name</label>
                            <p class="form-control-plaintext"><?php echo e($product->name); ?></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">SKU</label>
                            <p class="form-control-plaintext"><?php echo e($product->sku); ?></p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Category</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-primary"><?php echo e($product->category->name); ?></span>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Status</label>
                            <p class="form-control-plaintext">
                                <?php if($product->is_active): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Inactive</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Price</label>
                            <p class="form-control-plaintext">Rs. <?php echo e(number_format($product->price, 2)); ?></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Sale Price</label>
                            <p class="form-control-plaintext">
                                <?php if($product->sale_price): ?>
                                    Rs. <?php echo e(number_format($product->sale_price, 2)); ?>

                                <?php else: ?>
                                    <span class="text-muted">Not set</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Stock</label>
                            <p class="form-control-plaintext">
                                <?php if($product->stock > 0): ?>
                                    <span class="badge bg-success"><?php echo e($product->stock); ?> units</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Out of Stock</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Featured</label>
                            <p class="form-control-plaintext">
                                <?php if($product->is_featured): ?>
                                    <span class="badge bg-warning">Featured</span>
                                <?php else: ?>
                                    <span class="text-muted">Not featured</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Description</label>
                    <div class="form-control-plaintext">
                        <?php if($product->description): ?>
                            <?php echo e($product->description); ?>

                        <?php else: ?>
                            <span class="text-muted">No description provided</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Created At</label>
                            <p class="form-control-plaintext"><?php echo e($product->created_at->format('M d, Y H:i')); ?></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Updated At</label>
                            <p class="form-control-plaintext"><?php echo e($product->updated_at->format('M d, Y H:i')); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Product Image</h5>
            </div>
            <div class="card-body text-center">
                <?php if($product->image): ?>
                    <img src="<?php echo e(asset('storage/' . $product->image)); ?>" 
                         alt="<?php echo e($product->name); ?>" 
                         class="img-fluid rounded"
                         style="max-height: 300px;">
                <?php else: ?>
                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 200px;">
                        <div class="text-muted">
                            <i class="fas fa-image fa-3x mb-2"></i>
                            <p>No image available</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if($product->images && count($product->images) > 0): ?>
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Additional Images</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-6 mb-2">
                        <img src="<?php echo e(asset('storage/' . $image)); ?>" 
                             alt="<?php echo e($product->name); ?>" 
                             class="img-fluid rounded">
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>








<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/admin/products/show.blade.php ENDPATH**/ ?>