<?php $__env->startSection('title', 'Checkout — ' . $tenant->business_name); ?>
<?php $__env->startSection('meta_description', 'Complete your order securely with our checkout process'); ?>
<?php $__env->startSection('meta_keywords', 'checkout, order, payment, ' . $tenant->business_name); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(\App\Helpers\StorefrontHelper::route('storefront.preview', [$tenant->subdomain])); ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(\App\Helpers\StorefrontHelper::route('storefront.cart', [$tenant->subdomain])); ?>">Cart</a></li>
            <li class="breadcrumb-item active" aria-current="page">Checkout</li>
        </ol>
    </nav>

    <h1 class="h3 mb-4">Checkout</h1>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h5><i class="fas fa-exclamation-triangle"></i> Please fix the following errors:</h5>
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if(count($cart) > 0): ?>
    <form id="checkoutForm" action="<?php echo e(\App\Helpers\StorefrontHelper::route('storefront.checkout.process', [$tenant->subdomain])); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <div class="row">
            <!-- Checkout Form -->
            <div class="col-lg-8">
                <!-- Billing Information -->
                <div class="checkout-section">
                    <div class="section-header">
                        <h5><i class="fas fa-user"></i> Billing Information</h5>
                    </div>
                    <div class="section-content">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="billing_first_name" class="form-label">First Name *</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['billing_first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="billing_first_name" name="billing_first_name" 
                                       value="<?php echo e(old('billing_first_name', auth()->check() ? auth()->user()->first_name : '')); ?>" required>
                                <?php $__errorArgs = ['billing_first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="billing_last_name" class="form-label">Last Name *</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['billing_last_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="billing_last_name" name="billing_last_name" 
                                       value="<?php echo e(old('billing_last_name', auth()->check() ? auth()->user()->last_name : '')); ?>" required>
                                <?php $__errorArgs = ['billing_last_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="billing_email" class="form-label">Email *</label>
                                <input type="email" class="form-control <?php $__errorArgs = ['billing_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="billing_email" name="billing_email" 
                                       value="<?php echo e(old('billing_email', auth()->check() ? auth()->user()->email : '')); ?>" required>
                                <?php $__errorArgs = ['billing_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="billing_phone" class="form-label">Phone *</label>
                                <input type="tel" class="form-control <?php $__errorArgs = ['billing_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="billing_phone" name="billing_phone" 
                                       value="<?php echo e(old('billing_phone')); ?>" required>
                                <?php $__errorArgs = ['billing_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="billing_address" class="form-label">Address *</label>
                            <textarea class="form-control <?php $__errorArgs = ['billing_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      id="billing_address" name="billing_address" rows="3" required><?php echo e(old('billing_address')); ?></textarea>
                            <?php $__errorArgs = ['billing_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="billing_city" class="form-label">City *</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['billing_city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="billing_city" name="billing_city" 
                                       value="<?php echo e(old('billing_city')); ?>" required>
                                <?php $__errorArgs = ['billing_city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="billing_state" class="form-label">State *</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['billing_state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="billing_state" name="billing_state" 
                                       value="<?php echo e(old('billing_state')); ?>" required>
                                <?php $__errorArgs = ['billing_state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="billing_postal_code" class="form-label">Postal Code *</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['billing_postal_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="billing_postal_code" name="billing_postal_code" 
                                       value="<?php echo e(old('billing_postal_code')); ?>" required>
                                <?php $__errorArgs = ['billing_postal_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="billing_country" class="form-label">Country *</label>
                            <select class="form-select <?php $__errorArgs = ['billing_country'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    id="billing_country" name="billing_country" required>
                                <option value="">Select Country</option>
                                <option value="Nepal" <?php echo e(old('billing_country', 'Nepal') == 'Nepal' ? 'selected' : ''); ?>>Nepal</option>
                                <option value="India" <?php echo e(old('billing_country') == 'India' ? 'selected' : ''); ?>>India</option>
                                <option value="China" <?php echo e(old('billing_country') == 'China' ? 'selected' : ''); ?>>China</option>
                                <option value="Bangladesh" <?php echo e(old('billing_country') == 'Bangladesh' ? 'selected' : ''); ?>>Bangladesh</option>
                                <option value="Other" <?php echo e(old('billing_country') == 'Other' ? 'selected' : ''); ?>>Other</option>
                            </select>
                            <?php $__errorArgs = ['billing_country'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>

                <!-- Shipping Information -->
                <div class="checkout-section">
                    <div class="section-header">
                        <h5><i class="fas fa-truck"></i> Shipping Information</h5>
                    </div>
                    <div class="section-content">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="same_as_billing" name="same_as_billing" value="1" checked>
                            <label class="form-check-label" for="same_as_billing">
                                Same as billing address
                            </label>
                        </div>

                        <div id="shipping_address_fields" style="display: none;">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="shipping_first_name" class="form-label">First Name *</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['shipping_first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="shipping_first_name" name="shipping_first_name" 
                                           value="<?php echo e(old('shipping_first_name')); ?>">
                                    <?php $__errorArgs = ['shipping_first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="shipping_last_name" class="form-label">Last Name *</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['shipping_last_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="shipping_last_name" name="shipping_last_name" 
                                           value="<?php echo e(old('shipping_last_name')); ?>">
                                    <?php $__errorArgs = ['shipping_last_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="shipping_email" class="form-label">Email *</label>
                                    <input type="email" class="form-control <?php $__errorArgs = ['shipping_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="shipping_email" name="shipping_email" 
                                           value="<?php echo e(old('shipping_email')); ?>">
                                    <?php $__errorArgs = ['shipping_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="shipping_phone" class="form-label">Phone *</label>
                                    <input type="tel" class="form-control <?php $__errorArgs = ['shipping_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="shipping_phone" name="shipping_phone" 
                                           value="<?php echo e(old('shipping_phone')); ?>">
                                    <?php $__errorArgs = ['shipping_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="shipping_address" class="form-label">Address *</label>
                                <textarea class="form-control <?php $__errorArgs = ['shipping_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                          id="shipping_address" name="shipping_address" rows="3"><?php echo e(old('shipping_address')); ?></textarea>
                                <?php $__errorArgs = ['shipping_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="shipping_city" class="form-label">City *</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['shipping_city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="shipping_city" name="shipping_city" 
                                           value="<?php echo e(old('shipping_city')); ?>">
                                    <?php $__errorArgs = ['shipping_city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="shipping_state" class="form-label">State *</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['shipping_state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="shipping_state" name="shipping_state" 
                                           value="<?php echo e(old('shipping_state')); ?>">
                                    <?php $__errorArgs = ['shipping_state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="shipping_postal_code" class="form-label">Postal Code *</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['shipping_postal_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="shipping_postal_code" name="shipping_postal_code" 
                                           value="<?php echo e(old('shipping_postal_code')); ?>">
                                    <?php $__errorArgs = ['shipping_postal_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="shipping_country" class="form-label">Country *</label>
                                <select class="form-select <?php $__errorArgs = ['shipping_country'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        id="shipping_country" name="shipping_country">
                                    <option value="">Select Country</option>
                                    <option value="Nepal" <?php echo e(old('shipping_country', 'Nepal') == 'Nepal' ? 'selected' : ''); ?>>Nepal</option>
                                    <option value="India" <?php echo e(old('shipping_country') == 'India' ? 'selected' : ''); ?>>India</option>
                                    <option value="China" <?php echo e(old('shipping_country') == 'China' ? 'selected' : ''); ?>>China</option>
                                    <option value="Bangladesh" <?php echo e(old('shipping_country') == 'Bangladesh' ? 'selected' : ''); ?>>Bangladesh</option>
                                    <option value="Other" <?php echo e(old('shipping_country') == 'Other' ? 'selected' : ''); ?>>Other</option>
                                </select>
                                <?php $__errorArgs = ['shipping_country'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping Method -->
                <div class="checkout-section">
                    <div class="section-header">
                        <h5><i class="fas fa-shipping-fast"></i> Shipping Method</h5>
                    </div>
                    <div class="section-content">
                        <?php $__currentLoopData = $shippingMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="shipping_method_id" 
                                   id="ship_<?php echo e($method->id); ?>" value="<?php echo e($method->id); ?>" 
                                   <?php echo e($i === 0 ? 'checked' : ''); ?> required>
                            <label class="form-check-label w-100" for="ship_<?php echo e($method->id); ?>">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?php echo e($method->name); ?></strong>
                                        <?php if($method->min_days || $method->max_days): ?>
                                            <span class="text-muted small d-block">
                                                <?php echo e($method->min_days); ?>-<?php echo e($method->max_days); ?> days delivery
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="shipping-price">
                                        <?php if($method->base_rate > 0): ?>
                                            <strong>Rs. <?php echo e(number_format($method->base_rate, 2)); ?></strong>
                                        <?php else: ?>
                                            <strong class="text-success">Free</strong>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="checkout-section">
                    <div class="section-header">
                        <h5><i class="fas fa-credit-card"></i> Payment Method</h5>
                    </div>
                    <div class="section-content">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="form-check payment-method">
                                    <input class="form-check-input" type="radio" name="payment_method" 
                                           id="payment_cod" value="cod" checked>
                                    <label class="form-check-label" for="payment_cod">
                                        <div class="payment-option">
                                            <i class="fas fa-money-bill-wave"></i>
                                            <span>Cash on Delivery</span>
                                            <small>Pay when you receive</small>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-check payment-method">
                                    <input class="form-check-input" type="radio" name="payment_method" 
                                           id="payment_esewa" value="esewa">
                                    <label class="form-check-label" for="payment_esewa">
                                        <div class="payment-option">
                                            <i class="fas fa-mobile-alt"></i>
                                            <span>eSewa</span>
                                            <small>Mobile payment</small>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-check payment-method">
                                    <input class="form-check-input" type="radio" name="payment_method" 
                                           id="payment_khalti" value="khalti">
                                    <label class="form-check-label" for="payment_khalti">
                                        <div class="payment-option">
                                            <i class="fas fa-wallet"></i>
                                            <span>Khalti</span>
                                            <small>Digital wallet</small>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="order-summary">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Order Summary</h5>
                        </div>
                        <div class="card-body">
                            <!-- Order Items -->
                            <div class="order-items">
                                <?php $__currentLoopData = $cart; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="order-item">
                                    <div class="d-flex align-items-center">
                                        <img src="<?php echo e($item['image']); ?>" class="me-3" 
                                             style="width: 60px; height: 60px; object-fit: cover;" 
                                             alt="<?php echo e($item['name']); ?>">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1"><?php echo e($item['name']); ?></h6>
                                            <small class="text-muted">Qty: <?php echo e($item['quantity']); ?></small>
                                        </div>
                                        <div class="order-item-price">
                                            <div class="fw-bold">Rs. <?php echo e(number_format($item['quantity'] * $item['price'], 2)); ?></div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                            <hr>

                            <!-- Order Totals -->
                            <div class="order-totals">
                                <div class="total-row">
                                    <span>Subtotal</span>
                                    <span id="subtotal">Rs. <?php echo e(number_format(array_sum(array_map(function($item) { return $item['quantity'] * $item['price']; }, $cart)), 2)); ?></span>
                                </div>

                                <?php if(!empty($coupon)): ?>
                                    <?php
                                        $subtotal = array_sum(array_map(function($item) { return $item['quantity'] * $item['price']; }, $cart));
                                        $discount = 0;
                                        if ($coupon['type'] === 'percentage') {
                                            $discount = round($subtotal * ($coupon['value'] / 100), 2);
                                        } else {
                                            $discount = min($coupon['value'], $subtotal);
                                        }
                                    ?>
                                    <div class="total-row">
                                        <span>Coupon (<?php echo e($coupon['code']); ?>)</span>
                                        <span class="text-success">- Rs. <?php echo e(number_format($discount, 2)); ?></span>
                                    </div>
                                <?php endif; ?>

                                <div class="total-row">
                                    <span>Shipping</span>
                                    <span id="shipping-cost">Rs. 0.00</span>
                                </div>

                                <div class="total-row">
                                    <span>Tax</span>
                                    <span id="tax-amount">Rs. 0.00</span>
                                </div>

                                <hr>

                                <div class="total-row total-final">
                                    <span><strong>Total</strong></span>
                                    <span id="total-amount"><strong>Rs. <?php echo e(number_format(array_sum(array_map(function($item) { return $item['quantity'] * $item['price']; }, $cart)), 2)); ?></strong></span>
                                </div>
                            </div>

                            <!-- Place Order Button -->
                            <div class="place-order-section mt-4">
                                <button type="submit" class="btn btn-success w-100 btn-lg" id="placeOrderBtn">
                                    <i class="fas fa-lock"></i> Place Order Securely
                                </button>
                                <p class="text-center small text-muted mt-2">
                                    <i class="fas fa-shield-alt"></i> Your payment information is secure and encrypted
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-shopping-cart"></i>
            <h3>Your cart is empty</h3>
            <p>Add some items to your cart before proceeding to checkout.</p>
            <a href="<?php echo e(\App\Helpers\StorefrontHelper::route('storefront.preview', [$tenant->subdomain])); ?>" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Continue Shopping
            </a>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.checkout-section {
    background: white;
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-sm);
    margin-bottom: 2rem;
    overflow: hidden;
}

.section-header {
    background: var(--surface-color);
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--border-color);
}

.section-header h5 {
    margin: 0;
    color: var(--text-color);
    font-weight: 600;
}

.section-header i {
    color: var(--primary-color);
    margin-right: 0.5rem;
}

.section-content {
    padding: 1.5rem;
}

.form-label {
    font-weight: 600;
    color: var(--text-color);
    margin-bottom: 0.5rem;
}

.form-control, .form-select {
    border: 2px solid var(--border-color);
    border-radius: var(--border-radius-sm);
    padding: 0.75rem;
    transition: var(--transition);
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.payment-method {
    margin-bottom: 0;
}

.payment-option {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 1rem;
    border: 2px solid var(--border-color);
    border-radius: var(--border-radius-sm);
    transition: var(--transition);
    cursor: pointer;
    height: 100%;
}

.payment-option i {
    font-size: 2rem;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
}

.payment-option span {
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.payment-option small {
    color: var(--text-muted);
    font-size: 0.75rem;
}

.payment-method input[type="radio"]:checked + label .payment-option {
    border-color: var(--primary-color);
    background: rgba(102, 126, 234, 0.05);
}

.order-summary .card {
    border: none;
    box-shadow: var(--shadow-lg);
    position: sticky;
    top: 2rem;
}

.order-item {
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--border-color);
}

.order-item:last-child {
    border-bottom: none;
}

.order-item-price {
    text-align: right;
}

.total-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
}

.total-final {
    font-size: 1.1rem;
    padding-top: 1rem;
    margin-top: 1rem;
    border-top: 2px solid var(--border-color);
}

.shipping-price {
    color: var(--primary-color);
    font-weight: 600;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: var(--surface-color);
    border-radius: var(--border-radius-lg);
    border: 2px dashed var(--border-color);
}

.empty-state i {
    font-size: 4rem;
    color: var(--text-muted);
    margin-bottom: 1rem;
}

.empty-state h3 {
    color: var(--text-color);
    margin-bottom: 1rem;
}

.empty-state p {
    color: var(--text-muted);
    margin-bottom: 2rem;
}

@media (max-width: 768px) {
    .order-summary .card {
        position: static;
        margin-top: 2rem;
    }
    
    .payment-option {
        padding: 0.75rem;
    }
    
    .payment-option i {
        font-size: 1.5rem;
    }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Same as billing address toggle
document.getElementById('same_as_billing').addEventListener('change', function() {
    const shippingFields = document.getElementById('shipping_address_fields');
    const shippingInputs = shippingFields.querySelectorAll('input, textarea, select');
    
    if (this.checked) {
        shippingFields.style.display = 'none';
        shippingInputs.forEach(input => {
            input.removeAttribute('required');
        });
    } else {
        shippingFields.style.display = 'block';
        shippingInputs.forEach(input => {
            input.setAttribute('required', 'required');
        });
    }
});

// Copy billing to shipping when checkbox is checked
document.getElementById('same_as_billing').addEventListener('change', function() {
    if (this.checked) {
        copyBillingToShipping();
    }
});

function copyBillingToShipping() {
    const billingFields = ['first_name', 'last_name', 'email', 'phone', 'address', 'city', 'state', 'postal_code', 'country'];
    
    billingFields.forEach(field => {
        const billingInput = document.getElementById(`billing_${field}`);
        const shippingInput = document.getElementById(`shipping_${field}`);
        
        if (billingInput && shippingInput) {
            if (field === 'country') {
                shippingInput.value = billingInput.value;
            } else {
                shippingInput.value = billingInput.value;
            }
        }
    });
}

// Shipping method change handler
document.querySelectorAll('input[name="shipping_method_id"]').forEach(radio => {
    radio.addEventListener('change', function() {
        updateOrderTotals();
    });
});

// Update order totals
function updateOrderTotals() {
    const subtotal = <?php echo e(array_sum(array_map(function($item) { return $item['quantity'] * $item['price']; }, $cart))); ?>;
    const shippingMethods = {
        <?php $__currentLoopData = $shippingMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php echo e($method->id); ?>: <?php echo e((float)$method->base_rate); ?>,
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    };
    
    const selectedShipping = document.querySelector('input[name="shipping_method_id"]:checked');
    const shippingCost = selectedShipping ? shippingMethods[selectedShipping.value] || 0 : 0;
    
    <?php if(!empty($coupon)): ?>
        const coupon = <?php echo json_encode($coupon, 15, 512) ?>;
        let discount = 0;
        if (coupon.type === 'percentage') {
            discount = subtotal * (coupon.value / 100);
        } else {
            discount = Math.min(coupon.value, subtotal);
        }
    <?php else: ?>
        const discount = 0;
    <?php endif; ?>
    
    const taxRate = <?php echo e($taxRate ?? 13.0); ?>; // %
    const taxableAmount = subtotal - discount;
    const tax = taxableAmount * (taxRate / 100);
    const total = Math.max(0, taxableAmount) + shippingCost + tax;
    
    // Update display
    document.getElementById('shipping-cost').textContent = `Rs. ${shippingCost.toFixed(2)}`;
    document.getElementById('tax-amount').textContent = `Rs. ${tax.toFixed(2)}`;
    document.getElementById('total-amount').innerHTML = `<strong>Rs. ${total.toFixed(2)}</strong>`;
}

// Form validation
document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('placeOrderBtn');
    const originalText = submitBtn.innerHTML;
    
    // Show loading state
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    submitBtn.disabled = true;
    
    // Validate form
    if (!validateCheckoutForm()) {
        e.preventDefault();
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        return false;
    }
    
    // Form is valid, let it submit
});

function validateCheckoutForm() {
    let isValid = true;
    const requiredFields = document.querySelectorAll('input[required], textarea[required], select[required]');
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
        } else {
            field.classList.remove('is-invalid');
        }
    });
    
    // Email validation
    const emailField = document.getElementById('billing_email');
    if (emailField && emailField.value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(emailField.value)) {
            emailField.classList.add('is-invalid');
            isValid = false;
        }
    }
    
    // Phone validation
    const phoneField = document.getElementById('billing_phone');
    if (phoneField && phoneField.value) {
        const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;
        if (!phoneRegex.test(phoneField.value.replace(/\s/g, ''))) {
            phoneField.classList.add('is-invalid');
            isValid = false;
        }
    }
    
    return isValid;
}

// Initialize totals on page load
document.addEventListener('DOMContentLoaded', function() {
    updateOrderTotals();
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.storefront', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/storefront/checkout.blade.php ENDPATH**/ ?>