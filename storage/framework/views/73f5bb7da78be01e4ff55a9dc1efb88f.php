<?php $__env->startSection('title', 'Create Pending Order'); ?>
<?php $__env->startSection('page-title', 'Create Pending Order'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Create Order Page Specific Styles */
    .create-order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .page-title-section h1 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-color);
        margin: 0;
        background: linear-gradient(135deg, #10B981, #34D399);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .page-subtitle {
        color: var(--text-secondary);
        font-size: 1rem;
        margin-top: 0.5rem;
        font-weight: 400;
    }

    .back-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: rgba(107, 114, 128, 0.1);
        color: #6B7280;
        text-decoration: none;
        border-radius: 0.75rem;
        font-weight: 500;
        transition: all 0.3s ease;
        border: 1px solid rgba(107, 114, 128, 0.2);
    }

    .back-btn:hover {
        background: rgba(107, 114, 128, 0.2);
        color: #6B7280;
        transform: translateY(-2px);
    }

    .create-order-form {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 1rem;
        padding: 2rem;
        border: 1px solid rgba(16, 185, 129, 0.1);
        backdrop-filter: blur(10px);
        margin-bottom: 2rem;
    }

    .form-section {
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: rgba(16, 185, 129, 0.02);
        border-radius: 0.75rem;
        border: 1px solid rgba(16, 185, 129, 0.1);
    }

    .section-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-title i {
        color: #10B981;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-control {
        border-radius: 0.75rem;
        border: 1px solid rgba(16, 185, 129, 0.2);
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
        padding: 0.875rem 1rem;
    }

    .form-control:focus {
        border-color: #10B981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        background: white;
        outline: none;
    }

    .form-select {
        border-radius: 0.75rem;
        border: 1px solid rgba(16, 185, 129, 0.2);
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
        padding: 0.875rem 1rem;
    }

    .form-select:focus {
        border-color: #10B981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        background: white;
        outline: none;
    }

    .form-textarea {
        min-height: 100px;
        resize: vertical;
    }

    .products-section {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 1rem;
        padding: 2rem;
        border: 1px solid rgba(16, 185, 129, 0.1);
        backdrop-filter: blur(10px);
        margin-bottom: 2rem;
    }

    .product-row {
        display: flex;
        gap: 1rem;
        align-items: end;
        margin-bottom: 1rem;
        padding: 1rem;
        background: rgba(16, 185, 129, 0.02);
        border-radius: 0.75rem;
        border: 1px solid rgba(16, 185, 129, 0.1);
    }

    .product-select {
        flex: 2;
    }

    .quantity-input {
        flex: 1;
        max-width: 120px;
    }

    .price-display {
        flex: 1;
        max-width: 120px;
        padding: 0.875rem 1rem;
        background: rgba(16, 185, 129, 0.1);
        border-radius: 0.75rem;
        border: 1px solid rgba(16, 185, 129, 0.2);
        font-weight: 600;
        color: #10B981;
        text-align: center;
    }

    .remove-product-btn {
        background: rgba(239, 68, 68, 0.1);
        color: #EF4444;
        border: 1px solid rgba(239, 68, 68, 0.2);
        border-radius: 0.5rem;
        padding: 0.875rem;
        transition: all 0.3s ease;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 50px;
        height: 50px;
    }

    .remove-product-btn:hover {
        background: rgba(239, 68, 68, 0.2);
        color: #EF4444;
    }

    .add-product-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, #10B981, #34D399);
        color: white;
        text-decoration: none;
        border-radius: 0.75rem;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        margin-bottom: 1rem;
    }

    .add-product-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
        color: white;
    }

    .order-summary {
        background: rgba(16, 185, 129, 0.05);
        border-radius: 1rem;
        padding: 1.5rem;
        border: 1px solid rgba(16, 185, 129, 0.1);
        margin-bottom: 2rem;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(16, 185, 129, 0.1);
    }

    .summary-row:last-child {
        border-bottom: none;
        font-weight: 700;
        font-size: 1.125rem;
        color: var(--text-primary);
    }

    .summary-label {
        color: var(--text-secondary);
        font-weight: 500;
    }

    .summary-value {
        font-weight: 600;
        color: var(--text-primary);
    }

    .submit-section {
        background: rgba(16, 185, 129, 0.05);
        border-radius: 1rem;
        padding: 2rem;
        border: 1px solid rgba(16, 185, 129, 0.1);
        text-align: center;
    }

    .submit-btn {
        background: linear-gradient(135deg, #10B981, #34D399);
        color: white;
        border: none;
        border-radius: 0.75rem;
        padding: 1rem 2rem;
        font-weight: 600;
        font-size: 1.125rem;
        transition: all 0.3s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
    }

    .submit-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .empty-products {
        text-align: center;
        padding: 2rem;
        color: var(--text-secondary);
    }

    .empty-products i {
        font-size: 2rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .create-order-form {
            padding: 1rem;
        }
        
        .form-section {
            padding: 1rem;
        }
        
        .product-row {
            flex-direction: column;
            align-items: stretch;
            gap: 0.75rem;
        }
        
        .product-select,
        .quantity-input,
        .price-display {
            max-width: none;
        }
        
        .remove-product-btn {
            width: 100%;
            height: auto;
            padding: 0.75rem;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="create-order-header">
    <div class="page-title-section">
        <h1>Create Pending Order</h1>
        <p class="page-subtitle">Add a new pending order manually</p>
    </div>
    <a href="<?php echo e(route('admin.pending-orders.index')); ?>" class="back-btn">
        <i class="fas fa-arrow-left"></i> Back to Orders
    </a>
</div>

<form action="<?php echo e(route('admin.pending-orders.store')); ?>" method="POST" id="createOrderForm">
    <?php echo csrf_field(); ?>
    
    <div class="create-order-form">
        <div class="form-section">
            <h3 class="section-title">
                <i class="fas fa-user"></i>
                Customer/Receiver Information
            </h3>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="customer_name" class="form-label">Customer Name *</label>
                        <input type="text" class="form-control" id="customer_name" name="customer_name" 
                               value="<?php echo e(old('customer_name')); ?>" required 
                               placeholder="Full name of customer">
                        <?php $__errorArgs = ['customer_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="customer_phone" class="form-label">Customer Phone *</label>
                        <input type="tel" class="form-control" id="customer_phone" name="customer_phone" 
                               value="<?php echo e(old('customer_phone')); ?>" required 
                               placeholder="+92XXXXXXXXXX">
                        <?php $__errorArgs = ['customer_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="receiver_city" class="form-label">City *</label>
                        <input type="text" class="form-control" id="receiver_city" name="receiver_city" 
                               value="<?php echo e(old('receiver_city')); ?>" required 
                               placeholder="e.g., Islamabad, Lahore">
                        <?php $__errorArgs = ['receiver_city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="receiver_area" class="form-label">Area/Locality *</label>
                        <input type="text" class="form-control" id="receiver_area" name="receiver_area" 
                               value="<?php echo e(old('receiver_area')); ?>" required 
                               placeholder="e.g., F-7, Gulberg">
                        <?php $__errorArgs = ['receiver_area'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="shipping_address" class="form-label">Complete Shipping Address *</label>
                <textarea class="form-control form-textarea" id="shipping_address" name="shipping_address" 
                          rows="3" required placeholder="House/Flat number, Street, Landmark, City"><?php echo e(old('shipping_address')); ?></textarea>
                <small class="text-muted">This will be used as receiver_address for Gaaubesi Logistics</small>
                <?php $__errorArgs = ['shipping_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger mt-1"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>
        
        <div class="form-section">
            <h3 class="section-title">
                <i class="fas fa-shipping-fast"></i>
                Delivery Preferences (For Gaaubesi Logistics)
            </h3>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="delivery_branch" class="form-label">Destination Branch</label>
                        <input type="text" class="form-control" id="delivery_branch" name="delivery_branch" 
                               value="<?php echo e(old('delivery_branch', 'HEAD OFFICE')); ?>" 
                               placeholder="HEAD OFFICE">
                        <small class="text-muted">Gaaubesi branch for delivery</small>
                        <?php $__errorArgs = ['delivery_branch'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="package_type" class="form-label">Package Type</label>
                        <input type="text" class="form-control" id="package_type" name="package_type" 
                               value="<?php echo e(old('package_type')); ?>" 
                               placeholder="e.g., Electronics, Clothing, Documents">
                        <small class="text-muted">Type of items being shipped</small>
                        <?php $__errorArgs = ['package_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="package_access" class="form-label">Package Access</label>
                        <select class="form-select" id="package_access" name="package_access">
                            <option value="Can't Open" <?php echo e(old('package_access') == "Can't Open" ? 'selected' : ''); ?>>Can't Open</option>
                            <option value="Can Open" <?php echo e(old('package_access') == "Can Open" ? 'selected' : ''); ?>>Can Open</option>
                        </select>
                        <small class="text-muted">Can the package be opened during delivery?</small>
                        <?php $__errorArgs = ['package_access'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="delivery_type" class="form-label">Delivery Type</label>
                        <select class="form-select" id="delivery_type" name="delivery_type">
                            <option value="Drop Off" <?php echo e(old('delivery_type') == "Drop Off" ? 'selected' : ''); ?>>Drop Off</option>
                            <option value="Pickup" <?php echo e(old('delivery_type') == "Pickup" ? 'selected' : ''); ?>>Pickup</option>
                        </select>
                        <small class="text-muted">Delivery method preference</small>
                        <?php $__errorArgs = ['delivery_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
                
                <div class="col-12">
                    <div class="form-group">
                        <label for="delivery_instructions" class="form-label">Delivery Instructions (Optional)</label>
                        <textarea class="form-control" id="delivery_instructions" name="delivery_instructions" 
                                  rows="2" placeholder="Special delivery instructions, landmarks, etc."><?php echo e(old('delivery_instructions')); ?></textarea>
                        <small class="text-muted">Will be used as remarks for Gaaubesi</small>
                        <?php $__errorArgs = ['delivery_instructions'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-section">
            <h3 class="section-title">
                <i class="fas fa-building"></i>
                Sender Information (Your Business)
            </h3>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="sender_name" class="form-label">Sender/Business Name</label>
                        <input type="text" class="form-control" id="sender_name" name="sender_name" 
                               value="<?php echo e(old('sender_name', 'E-Manager Store')); ?>" 
                               placeholder="Your business name">
                        <small class="text-muted">For Gaaubesi order contact</small>
                        <?php $__errorArgs = ['sender_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="sender_phone" class="form-label">Sender/Business Phone</label>
                        <input type="tel" class="form-control" id="sender_phone" name="sender_phone" 
                               value="<?php echo e(old('sender_phone', '+92XXXXXXXXXX')); ?>" 
                               placeholder="+92XXXXXXXXXX">
                        <small class="text-muted">For Gaaubesi order contact</small>
                        <?php $__errorArgs = ['sender_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-section">
            <h3 class="section-title">
                <i class="fas fa-credit-card"></i>
                Payment Information
            </h3>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="payment_method" class="form-label">Payment Method</label>
                        <select class="form-select" id="payment_method" name="payment_method" required>
                            <option value="">Select Payment Method</option>
                            <option value="cash_on_delivery" <?php echo e(old('payment_method') == 'cash_on_delivery' ? 'selected' : ''); ?>>Cash on Delivery</option>
                            <option value="bank_transfer" <?php echo e(old('payment_method') == 'bank_transfer' ? 'selected' : ''); ?>>Bank Transfer</option>
                            <option value="khalti" <?php echo e(old('payment_method') == 'khalti' ? 'selected' : ''); ?>>Khalti</option>
                            <option value="esewa" <?php echo e(old('payment_method') == 'esewa' ? 'selected' : ''); ?>>eSewa</option>
                        </select>
                        <?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="notes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" 
                                  placeholder="Add any special instructions..."><?php echo e(old('notes')); ?></textarea>
                        <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="text-danger mt-1"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="products-section">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="section-title mb-0">
                <i class="fas fa-box"></i>
                Products
            </h3>
            <button type="button" class="add-product-btn" onclick="addProductRow()">
                <i class="fas fa-plus"></i> Add Product
            </button>
        </div>
        
        <div id="productsContainer">
            <?php if(old('product_ids')): ?>
                <?php $__currentLoopData = old('product_ids'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $productId): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="product-row">
                        <div class="product-select">
                            <label class="form-label">Product</label>
                            <select class="form-select" name="product_ids[]" required onchange="updatePrice(this)">
                                <option value="">Select Product</option>
                                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($product->id); ?>" 
                                            data-price="<?php echo e($product->sale_price ?? $product->price); ?>"
                                            <?php echo e($productId == $product->id ? 'selected' : ''); ?>>
                                        <?php echo e($product->name); ?> - ₹<?php echo e(number_format($product->sale_price ?? $product->price, 2)); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="quantity-input">
                            <label class="form-label">Quantity</label>
                            <input type="number" class="form-control" name="quantities[]" 
                                   value="<?php echo e(old('quantities.'.$index, 1)); ?>" min="1" required onchange="calculateTotal()">
                        </div>
                        <div class="price-display">
                            <label class="form-label">Price</label>
                            <div id="price-<?php echo e($index); ?>">₹0.00</div>
                        </div>
                        <div>
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="remove-product-btn" onclick="removeProductRow(this)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <div class="product-row">
                    <div class="product-select">
                        <label class="form-label">Product</label>
                        <select class="form-select" name="product_ids[]" required onchange="updatePrice(this)">
                            <option value="">Select Product</option>
                            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($product->id); ?>" data-price="<?php echo e($product->sale_price ?? $product->price); ?>">
                                    <?php echo e($product->name); ?> - ₹<?php echo e(number_format($product->sale_price ?? $product->price, 2)); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="quantity-input">
                        <label class="form-label">Quantity</label>
                        <input type="number" class="form-control" name="quantities[]" value="1" min="1" required onchange="calculateTotal()">
                    </div>
                    <div class="price-display">
                        <label class="form-label">Price</label>
                        <div id="price-0">₹0.00</div>
                    </div>
                    <div>
                        <label class="form-label">&nbsp;</label>
                        <button type="button" class="remove-product-btn" onclick="removeProductRow(this)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if(count($products) == 0): ?>
            <div class="empty-products">
                <i class="fas fa-box"></i>
                <h5>No products available</h5>
                <p>Please add products first before creating orders.</p>
                <a href="<?php echo e(route('admin.products.create')); ?>" class="add-product-btn">
                    <i class="fas fa-plus"></i> Add Product
                </a>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="order-summary">
        <h4 class="section-title mb-3">
            <i class="fas fa-calculator"></i>
            Order Summary
        </h4>
        
        <div class="summary-row">
            <span class="summary-label">Subtotal:</span>
            <span class="summary-value" id="subtotal">₹0.00</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Tax:</span>
            <span class="summary-value" id="tax">₹0.00</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Shipping:</span>
            <span class="summary-value" id="shipping">₹0.00</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Total:</span>
            <span class="summary-value" id="total">₹0.00</span>
        </div>
    </div>
    
    <div class="submit-section">
        <button type="submit" class="submit-btn" id="submitBtn">
            <i class="fas fa-save"></i> Create Order
        </button>
        <p class="mt-3 text-muted">Review all information before creating the order</p>
    </div>
</form>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    let productCounter = 1;

    document.addEventListener('DOMContentLoaded', function() {
        calculateTotal();
        updateAllPrices();
    });

    function addProductRow() {
        const container = document.getElementById('productsContainer');
        const productRow = document.createElement('div');
        productRow.className = 'product-row';
        productRow.innerHTML = `
            <div class="product-select">
                <label class="form-label">Product</label>
                <select class="form-select" name="product_ids[]" required onchange="updatePrice(this)">
                    <option value="">Select Product</option>
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($product->id); ?>" data-price="<?php echo e($product->sale_price ?? $product->price); ?>">
                            <?php echo e($product->name); ?> - ₹<?php echo e(number_format($product->sale_price ?? $product->price, 2)); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="quantity-input">
                <label class="form-label">Quantity</label>
                <input type="number" class="form-control" name="quantities[]" value="1" min="1" required onchange="calculateTotal()">
            </div>
            <div class="price-display">
                <label class="form-label">Price</label>
                <div id="price-${productCounter}">₹0.00</div>
            </div>
            <div>
                <label class="form-label">&nbsp;</label>
                <button type="button" class="remove-product-btn" onclick="removeProductRow(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        container.appendChild(productRow);
        productCounter++;
    }

    function removeProductRow(button) {
        const productRows = document.querySelectorAll('.product-row');
        if (productRows.length > 1) {
            button.closest('.product-row').remove();
            calculateTotal();
        } else {
            showNotification('At least one product is required.', 'error');
        }
    }

    function updatePrice(select) {
        const price = select.options[select.selectedIndex].dataset.price || 0;
        const row = select.closest('.product-row');
        const priceDisplay = row.querySelector('[id^="price-"]');
        const quantity = row.querySelector('input[name="quantities[]"]').value || 1;
        const total = parseFloat(price) * parseInt(quantity);
        
        priceDisplay.textContent = `₹${total.toFixed(2)}`;
        calculateTotal();
    }

    function updateAllPrices() {
        document.querySelectorAll('select[name="product_ids[]"]').forEach(select => {
            updatePrice(select);
        });
    }

    function calculateTotal() {
        let subtotal = 0;
        
        document.querySelectorAll('.product-row').forEach(row => {
            const select = row.querySelector('select[name="product_ids[]"]');
            const quantity = row.querySelector('input[name="quantities[]"]').value || 1;
            const price = select.options[select.selectedIndex]?.dataset.price || 0;
            
            subtotal += parseFloat(price) * parseInt(quantity);
        });
        
        const tax = 0; // No tax for now
        const shipping = 0; // No shipping for now
        const total = subtotal + tax + shipping;
        
        document.getElementById('subtotal').textContent = `₨${subtotal.toFixed(2)}`;
        document.getElementById('tax').textContent = `₨${tax.toFixed(2)}`;
        document.getElementById('shipping').textContent = `₨${shipping.toFixed(2)}`;
        document.getElementById('total').textContent = `₨${total.toFixed(2)}`;
        
        // Update hidden input for form submission
        let totalInput = document.getElementById('total_amount_hidden');
        if (!totalInput) {
            totalInput = document.createElement('input');
            totalInput.type = 'hidden';
            totalInput.name = 'total_amount';
            totalInput.id = 'total_amount_hidden';
            document.getElementById('createOrderForm').appendChild(totalInput);
        }
        totalInput.value = total.toFixed(2);
    }

    document.getElementById('createOrderForm').addEventListener('submit', function(e) {
        const productRows = document.querySelectorAll('.product-row');
        const submitBtn = document.getElementById('submitBtn');
        
        if (productRows.length === 0) {
            e.preventDefault();
            showNotification('Please add at least one product.', 'error');
            return;
        }
        
        let hasValidProduct = false;
        productRows.forEach(row => {
            const select = row.querySelector('select[name="product_ids[]"]');
            if (select.value) {
                hasValidProduct = true;
            }
        });
        
        if (!hasValidProduct) {
            e.preventDefault();
            showNotification('Please select at least one product.', 'error');
            return;
        }
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating Order...';
    });
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/admin/pending-orders/create.blade.php ENDPATH**/ ?>