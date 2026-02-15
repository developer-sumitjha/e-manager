<?php $__env->startSection('title', ($page->meta_title ?? $page->title) . ' — ' . $tenant->business_name); ?>
<?php $__env->startSection('meta_description', $page->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($page->description ?? $page->content), 155)); ?>
<?php $__env->startSection('meta_keywords', $page->meta_keywords ?? ''); ?>
<?php $__env->startSection('og_title', $page->title); ?>
<?php $__env->startSection('og_description', $page->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($page->description ?? $page->content), 155)); ?>
<?php $__env->startSection('og_image', $page->banner_image_url ?? $settings->logo_url ?? ''); ?>
<?php $__env->startSection('og_url', url()->current()); ?>

<?php $__env->startPush('styles'); ?>
<?php if($page->custom_css): ?>
<style>
<?php echo $page->custom_css; ?>

</style>
<?php endif; ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(\App\Helpers\StorefrontHelper::route('storefront.preview', [$tenant->subdomain])); ?>">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo e($page->title); ?></li>
        </ol>
    </nav>

    <!-- Page Banner -->
    <?php if($page->banner_image_url): ?>
    <div class="page-banner mb-4">
        <img src="<?php echo e($page->banner_image_url); ?>" alt="<?php echo e($page->title); ?>" class="img-fluid rounded">
    </div>
    <?php endif; ?>

    <!-- Page Header -->
    <div class="page-header mb-4">
        <h1 class="page-title"><?php echo e($page->title); ?></h1>
        <?php if($page->description): ?>
        <p class="page-description text-muted"><?php echo e($page->description); ?></p>
        <?php endif; ?>
    </div>

    <!-- Page Content -->
    <div class="page-content">
        <?php if($page->template === 'full-width'): ?>
        <div class="row">
            <div class="col-12">
                <div class="page-body">
                    <?php echo $page->content; ?>

                </div>
            </div>
        </div>
        <?php elseif($page->template === 'sidebar'): ?>
        <div class="row">
            <div class="col-md-8">
                <div class="page-body">
                    <?php echo $page->content; ?>

                </div>
            </div>
            <div class="col-md-4">
                <div class="page-sidebar">
                    <!-- Sidebar content can be added here -->
                    <?php if($page->page_type === 'contact'): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Contact Information</h5>
                            <?php if($page->contact_email): ?>
                            <p class="mb-2">
                                <i class="fas fa-envelope"></i> 
                                <a href="mailto:<?php echo e($page->contact_email); ?>"><?php echo e($page->contact_email); ?></a>
                            </p>
                            <?php endif; ?>
                            <?php if($page->contact_phone): ?>
                            <p class="mb-2">
                                <i class="fas fa-phone"></i> 
                                <a href="tel:<?php echo e($page->contact_phone); ?>"><?php echo e($page->contact_phone); ?></a>
                            </p>
                            <?php endif; ?>
                            <?php if($page->contact_address): ?>
                            <p class="mb-0">
                                <i class="fas fa-map-marker-alt"></i> <?php echo e($page->contact_address); ?>

                            </p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php else: ?>
        <!-- Default template -->
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="page-body">
                    <?php echo $page->content; ?>

                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Contact Page Specific Content -->
        <?php if($page->page_type === 'contact'): ?>
        <div class="row mt-4">
            <!-- Contact Details - Left Side -->
            <div class="col-lg-5 mb-4">
                <div class="contact-details-card">
                    <h3 class="contact-section-title">Get in Touch</h3>
                    <p class="contact-section-description">We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
                    
                    <div class="contact-info-list">
                        <?php if($page->contact_email): ?>
                        <div class="contact-info-item">
                            <div class="contact-info-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-info-content">
                                <h5>Email</h5>
                                <a href="mailto:<?php echo e($page->contact_email); ?>"><?php echo e($page->contact_email); ?></a>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if($page->contact_phone): ?>
                        <div class="contact-info-item">
                            <div class="contact-info-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="contact-info-content">
                                <h5>Phone</h5>
                                <a href="tel:<?php echo e($page->contact_phone); ?>"><?php echo e($page->contact_phone); ?></a>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if($page->contact_address): ?>
                        <div class="contact-info-item">
                            <div class="contact-info-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="contact-info-content">
                                <h5>Address</h5>
                                <p><?php echo e($page->contact_address); ?></p>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if($page->map_iframe): ?>
                    <div class="contact-map mt-4">
                        <div class="ratio ratio-16x9">
                            <?php echo $page->map_iframe; ?>

                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Contact Form - Right Side -->
            <div class="col-lg-7 mb-4">
                <div class="contact-form-card">
                    <h3 class="contact-section-title">Send us a Message</h3>
                    <form id="contactForm" action="<?php echo e(\App\Helpers\StorefrontHelper::route('storefront.contact.submit', [$tenant->subdomain, $page->id])); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="contact_name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="contact_name" name="name" value="<?php echo e(old('name')); ?>" required>
                                <?php $__errorArgs = ['name'];
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
                                <label for="contact_email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="contact_email" name="email" value="<?php echo e(old('email')); ?>" required>
                                <?php $__errorArgs = ['email'];
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
                                <label for="contact_phone" class="form-label">Phone</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="contact_phone" name="phone" value="<?php echo e(old('phone')); ?>">
                                <?php $__errorArgs = ['phone'];
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
                                <label for="contact_subject" class="form-label">Subject</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="contact_subject" name="subject" value="<?php echo e(old('subject')); ?>">
                                <?php $__errorArgs = ['subject'];
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
                            <label for="contact_message" class="form-label">Message <span class="text-danger">*</span></label>
                            <textarea class="form-control <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="contact_message" name="message" rows="6" required><?php echo e(old('message')); ?></textarea>
                            <?php $__errorArgs = ['message'];
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
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary btn-lg w-100" id="contactSubmitBtn">
                                <i class="fas fa-paper-plane"></i> Send Message
                            </button>
                        </div>
                        <div id="contactFormMessage" class="alert" style="display: none;"></div>
                    </form>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- About Page Specific Content - Team Members -->
        <?php if($page->page_type === 'about' && $page->team_members && count($page->team_members) > 0): ?>
        <div class="row mt-5">
            <div class="col-12">
                <div class="team-section">
                    <h2 class="section-title text-center mb-4">Our Team</h2>
                    <div class="row g-4">
                        <?php $__currentLoopData = $page->team_members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="team-member-card">
                                <?php if(isset($member['photo']) && $member['photo']): ?>
                                <div class="team-member-photo">
                                    <img src="<?php echo e(asset('storage/' . $member['photo'])); ?>" alt="<?php echo e($member['name'] ?? 'Team Member'); ?>" class="img-fluid">
                                </div>
                                <?php else: ?>
                                <div class="team-member-photo">
                                    <div class="team-member-placeholder">
                                        <i class="fas fa-user"></i>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <div class="team-member-info">
                                    <h4 class="team-member-name"><?php echo e($member['name'] ?? ''); ?></h4>
                                    <?php if(isset($member['position']) && $member['position']): ?>
                                    <p class="team-member-position"><?php echo e($member['position']); ?></p>
                                    <?php endif; ?>
                                    <?php if(isset($member['bio']) && $member['bio']): ?>
                                    <p class="team-member-bio"><?php echo e($member['bio']); ?></p>
                                    <?php endif; ?>
                                    <div class="team-member-contact">
                                        <?php if(isset($member['email']) && $member['email']): ?>
                                        <a href="mailto:<?php echo e($member['email']); ?>" class="team-contact-link" title="Email">
                                            <i class="fas fa-envelope"></i>
                                        </a>
                                        <?php endif; ?>
                                        <?php if(isset($member['phone']) && $member['phone']): ?>
                                        <a href="tel:<?php echo e($member['phone']); ?>" class="team-contact-link" title="Phone">
                                            <i class="fas fa-phone"></i>
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                    <div class="team-member-social">
                                        <?php if(isset($member['facebook']) && $member['facebook']): ?>
                                        <a href="<?php echo e($member['facebook']); ?>" target="_blank" rel="noopener" class="social-link" title="Facebook">
                                            <i class="fab fa-facebook-f"></i>
                                        </a>
                                        <?php endif; ?>
                                        <?php if(isset($member['twitter']) && $member['twitter']): ?>
                                        <a href="<?php echo e($member['twitter']); ?>" target="_blank" rel="noopener" class="social-link" title="Twitter">
                                            <i class="fab fa-twitter"></i>
                                        </a>
                                        <?php endif; ?>
                                        <?php if(isset($member['linkedin']) && $member['linkedin']): ?>
                                        <a href="<?php echo e($member['linkedin']); ?>" target="_blank" rel="noopener" class="social-link" title="LinkedIn">
                                            <i class="fab fa-linkedin-in"></i>
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<?php if($page->custom_js): ?>
<script>
<?php echo $page->custom_js; ?>

</script>
<?php endif; ?>

<?php if($page->page_type === 'contact'): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    const submitBtn = document.getElementById('contactSubmitBtn');
    const messageDiv = document.getElementById('contactFormMessage');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Disable submit button
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
            messageDiv.style.display = 'none';
            
            // Get form data
            const formData = new FormData(contactForm);
            
            // Submit via AJAX
            fetch(contactForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    messageDiv.className = 'alert alert-success';
                    messageDiv.innerHTML = '<i class="fas fa-check-circle"></i> ' + (data.message || 'Thank you! Your message has been sent successfully. We\'ll get back to you soon.');
                    messageDiv.style.display = 'block';
                    
                    // Reset form
                    contactForm.reset();
                    
                    // Scroll to message
                    messageDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                } else {
                    // Show error message
                    messageDiv.className = 'alert alert-danger';
                    messageDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> ' + (data.message || 'Something went wrong. Please try again.');
                    messageDiv.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                messageDiv.className = 'alert alert-danger';
                messageDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> An error occurred. Please try again.';
                messageDiv.style.display = 'block';
            })
            .finally(() => {
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Send Message';
            });
        });
    }
});
</script>
<?php endif; ?>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.page-banner img {
    width: 100%;
    height: auto;
    max-height: 400px;
    object-fit: cover;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-color, #333);
    margin-bottom: 1rem;
}

.page-description {
    font-size: 1.1rem;
    margin-bottom: 0;
}

.page-body {
    line-height: 1.8;
    color: var(--text-color, #333);
}

.page-body h1,
.page-body h2,
.page-body h3,
.page-body h4,
.page-body h5,
.page-body h6 {
    margin-top: 2rem;
    margin-bottom: 1rem;
    font-weight: 600;
}

.page-body p {
    margin-bottom: 1rem;
}

.page-body img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 1rem 0;
}

.page-body ul,
.page-body ol {
    margin-bottom: 1rem;
    padding-left: 2rem;
}

.page-body blockquote {
    border-left: 4px solid var(--primary-color, #6c5ce7);
    padding-left: 1rem;
    margin: 1.5rem 0;
    font-style: italic;
    color: var(--text-muted, #666);
}

.page-sidebar .card {
    border: 1px solid var(--border-color, #dee2e6);
    box-shadow: var(--shadow-sm, 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075));
}

.page-sidebar .card-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.contact-map {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: var(--shadow-md, 0 0.5rem 1rem rgba(0, 0, 0, 0.15));
}

/* Contact Page Styles */
.contact-details-card,
.contact-form-card {
    background: #fff;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: var(--shadow-sm, 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075));
    height: 100%;
}

.contact-section-title {
    font-size: 1.75rem;
    font-weight: 600;
    color: var(--text-color, #333);
    margin-bottom: 1rem;
}

.contact-section-description {
    color: var(--text-muted, #666);
    margin-bottom: 2rem;
    line-height: 1.6;
}

.contact-info-list {
    margin-bottom: 2rem;
}

.contact-info-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 1.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid var(--border-color, #e9ecef);
}

.contact-info-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.contact-info-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--primary-color, #6c5ce7);
    color: white;
    border-radius: 50%;
    margin-right: 1rem;
    flex-shrink: 0;
}

.contact-info-icon i {
    font-size: 1.25rem;
}

.contact-info-content h5 {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-color, #333);
    margin-bottom: 0.25rem;
}

.contact-info-content a {
    color: var(--primary-color, #6c5ce7);
    text-decoration: none;
    transition: color 0.3s ease;
}

.contact-info-content a:hover {
    color: var(--primary-color-dark, #5a4fd4);
    text-decoration: underline;
}

.contact-info-content p {
    color: var(--text-muted, #666);
    margin-bottom: 0;
    line-height: 1.6;
}

.contact-form-card .form-label {
    font-weight: 500;
    color: var(--text-color, #333);
    margin-bottom: 0.5rem;
}

.contact-form-card .form-control {
    border: 1px solid var(--border-color, #dee2e6);
    border-radius: 8px;
    padding: 0.75rem 1rem;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.contact-form-card .form-control:focus {
    border-color: var(--primary-color, #6c5ce7);
    box-shadow: 0 0 0 0.2rem rgba(108, 92, 231, 0.25);
}

.contact-form-card .btn-primary {
    background: var(--primary-color, #6c5ce7);
    border: none;
    padding: 0.75rem 2rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.contact-form-card .btn-primary:hover {
    background: var(--primary-color-dark, #5a4fd4);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(108, 92, 231, 0.3);
}

.contact-form-card .btn-primary:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

#contactFormMessage {
    margin-top: 1rem;
}

#contactFormMessage.alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}

#contactFormMessage.alert-danger {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
}

/* Team Section Styles */
.team-section {
    margin-top: 3rem;
    padding-top: 3rem;
    border-top: 2px solid var(--border-color, #dee2e6);
}

.section-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-color, #333);
    margin-bottom: 2rem;
}

.team-member-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: var(--shadow-sm, 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075));
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.team-member-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-md, 0 0.5rem 1rem rgba(0, 0, 0, 0.15));
}

.team-member-photo {
    width: 100%;
    height: 300px;
    overflow: hidden;
    background: var(--bg-light, #f8f9fa);
    display: flex;
    align-items: center;
    justify-content: center;
}

.team-member-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.team-member-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.team-member-placeholder i {
    font-size: 5rem;
    opacity: 0.5;
}

.team-member-info {
    padding: 1.5rem;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.team-member-name {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-color, #333);
    margin-bottom: 0.5rem;
}

.team-member-position {
    font-size: 1rem;
    color: var(--primary-color, #6c5ce7);
    font-weight: 500;
    margin-bottom: 1rem;
}

.team-member-bio {
    font-size: 0.9rem;
    color: var(--text-muted, #666);
    line-height: 1.6;
    margin-bottom: 1rem;
    flex-grow: 1;
}

.team-member-contact {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--border-color, #dee2e6);
}

.team-contact-link {
    color: var(--text-muted, #666);
    font-size: 1.1rem;
    transition: color 0.3s ease;
    text-decoration: none;
}

.team-contact-link:hover {
    color: var(--primary-color, #6c5ce7);
}

.team-member-social {
    display: flex;
    gap: 0.75rem;
    justify-content: center;
}

.social-link {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: var(--bg-light, #f8f9fa);
    color: var(--text-muted, #666);
    text-decoration: none;
    transition: all 0.3s ease;
}

.social-link:hover {
    background: var(--primary-color, #6c5ce7);
    color: white;
    transform: translateY(-2px);
}

.social-link i {
    font-size: 1rem;
}
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.storefront', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/storefront/page.blade.php ENDPATH**/ ?>