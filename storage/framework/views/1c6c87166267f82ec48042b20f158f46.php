<div id="homepage-tab" class="tab-pane">
    <form id="homepageForm">
        <?php echo csrf_field(); ?>
        
        
        <div class="form-section">
            <h3 class="form-section-title">
                <i class="fas fa-images"></i>
                Hero Slides / Carousel
            </h3>
            <p class="text-muted mb-3">Create slides to replace the hero banner. Each slide has a two-column layout with content on the left and image on the right.</p>
            
            <div class="form-group">
                <label class="form-label">Show Hero Slides</label>
                <label class="toggle-switch">
                    <input type="checkbox" name="show_hero_slides" id="showHeroSlides" <?php echo e(($settings->additional_settings['show_hero_slides'] ?? false) ? 'checked' : ''); ?>>
                    <span class="toggle-slider"></span>
                </label>
                <small class="text-muted d-block mt-2">Enable hero slides carousel on homepage</small>
            </div>
            
            <div id="slidesContainer">
                <?php
                    $slides = $settings->additional_settings['hero_slides'] ?? [];
                    if (empty($slides) || !is_array($slides)) {
                        $slides = [
                            [
                                'heading' => $settings->banner_title ?? '',
                                'subheading' => $settings->banner_subtitle ?? '',
                                'button_text' => $settings->banner_button_text ?? 'Shop Now',
                                'button_link' => $settings->banner_button_link ?? '/products',
                                'image' => $settings->banner_image ?? '',
                                'background_color' => '#ffffff',
                                'text_color' => '#000000',
                                'button_bg_color' => '#000000',
                                'button_text_color' => '#ffffff'
                            ]
                        ];
                    }
                ?>
                
                <?php $__currentLoopData = $slides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="slide-item mb-4" data-slide-index="<?php echo e($index); ?>">
                    <div class="card" style="border: 1px solid #000000; border-radius: 12px;">
                        <div class="card-header" style="background: #ffffff; border-bottom: 1px solid #000000; padding: 1rem 1.5rem;">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0" style="font-weight: 600; color: #000000;">
                                    <i class="fas fa-sliders-h"></i> Slide <?php echo e($index + 1); ?>

                                </h5>
                                <button type="button" class="btn btn-sm" onclick="removeSlide(<?php echo e($index); ?>)" style="color: #000000; border: 1px solid #000000; background: #ffffff;">
                                    <i class="fas fa-trash"></i> Remove
                                </button>
                            </div>
                        </div>
                        <div class="card-body" style="padding: 1.5rem;">
                            <div class="row">
                                
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Heading</label>
                                        <input type="text" class="form-control slide-heading" name="slides[<?php echo e($index); ?>][heading]" value="<?php echo e($slide['heading'] ?? ''); ?>" placeholder="Enter heading">
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Subheading</label>
                                        <textarea class="form-control slide-subheading" name="slides[<?php echo e($index); ?>][subheading]" rows="3" placeholder="Enter subheading"><?php echo e($slide['subheading'] ?? ''); ?></textarea>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label">Button Text</label>
                                                <input type="text" class="form-control slide-button-text" name="slides[<?php echo e($index); ?>][button_text]" value="<?php echo e($slide['button_text'] ?? 'Shop Now'); ?>" placeholder="Shop Now">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label">Button Link</label>
                                                <input type="text" class="form-control slide-button-link" name="slides[<?php echo e($index); ?>][button_link]" value="<?php echo e($slide['button_link'] ?? '/products'); ?>" placeholder="/products">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Background Color</label>
                                        <div class="color-picker-wrapper">
                                            <input type="color" class="form-control color-input slide-bg-color" name="slides[<?php echo e($index); ?>][background_color]" value="<?php echo e($slide['background_color'] ?? '#ffffff'); ?>" style="width: 80px; height: 45px; padding: 0; border: 1px solid #000000; border-radius: 8px; cursor: pointer;">
                                            <div class="color-preview slide-bg-color-preview" style="background-color: <?php echo e($slide['background_color'] ?? '#ffffff'); ?>;"></div>
                                            <input type="text" class="form-control slide-bg-color-text" value="<?php echo e($slide['background_color'] ?? '#ffffff'); ?>" placeholder="#ffffff" style="flex: 1;">
                                        </div>
                                        <small class="text-muted d-block mt-2">Choose a background color for this slide</small>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Text Color</label>
                                        <div class="color-picker-wrapper">
                                            <input type="color" class="form-control color-input slide-text-color" name="slides[<?php echo e($index); ?>][text_color]" value="<?php echo e($slide['text_color'] ?? '#000000'); ?>" style="width: 80px; height: 45px; padding: 0; border: 1px solid #000000; border-radius: 8px; cursor: pointer;">
                                            <div class="color-preview slide-text-color-preview" style="background-color: <?php echo e($slide['text_color'] ?? '#000000'); ?>;"></div>
                                            <input type="text" class="form-control slide-text-color-text" value="<?php echo e($slide['text_color'] ?? '#000000'); ?>" placeholder="#000000" style="flex: 1;">
                                        </div>
                                        <small class="text-muted d-block mt-2">Color for heading and subheading text</small>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Button Background Color</label>
                                        <div class="color-picker-wrapper">
                                            <input type="color" class="form-control color-input slide-btn-bg-color" name="slides[<?php echo e($index); ?>][button_bg_color]" value="<?php echo e($slide['button_bg_color'] ?? '#000000'); ?>" style="width: 80px; height: 45px; padding: 0; border: 1px solid #000000; border-radius: 8px; cursor: pointer;">
                                            <div class="color-preview slide-btn-bg-color-preview" style="background-color: <?php echo e($slide['button_bg_color'] ?? '#000000'); ?>;"></div>
                                            <input type="text" class="form-control slide-btn-bg-color-text" value="<?php echo e($slide['button_bg_color'] ?? '#000000'); ?>" placeholder="#000000" style="flex: 1;">
                                        </div>
                                        <small class="text-muted d-block mt-2">Background color for the CTA button</small>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Button Text Color</label>
                                        <div class="color-picker-wrapper">
                                            <input type="color" class="form-control color-input slide-btn-text-color" name="slides[<?php echo e($index); ?>][button_text_color]" value="<?php echo e($slide['button_text_color'] ?? '#ffffff'); ?>" style="width: 80px; height: 45px; padding: 0; border: 1px solid #000000; border-radius: 8px; cursor: pointer;">
                                            <div class="color-preview slide-btn-text-color-preview" style="background-color: <?php echo e($slide['button_text_color'] ?? '#ffffff'); ?>;"></div>
                                            <input type="text" class="form-control slide-btn-text-color-text" value="<?php echo e($slide['button_text_color'] ?? '#ffffff'); ?>" placeholder="#ffffff" style="flex: 1;">
                                        </div>
                                        <small class="text-muted d-block mt-2">Text color for the CTA button</small>
                                    </div>
                                </div>
                                
                                
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Slide Image</label>
                                        <div class="file-upload-area slide-image-upload" data-slide-index="<?php echo e($index); ?>" style="cursor: pointer;">
                                            <input type="file" name="slide_images[<?php echo e($index); ?>]" class="slide-image-input" accept="image/*" style="display: none;" data-slide-index="<?php echo e($index); ?>">
                                            <div class="file-upload-icon">
                                                <i class="fas fa-cloud-upload-alt"></i>
                                            </div>
                                            <p class="mb-0"><strong>Click to upload</strong> or drag and drop</p>
                                            <p class="text-muted small mb-0">Recommended: 1200x600px, JPG or PNG up to 5MB</p>
                                        </div>
                                        <div class="slide-image-preview mt-3" data-slide-index="<?php echo e($index); ?>" style="display: <?php echo e(!empty($slide['image']) ? 'block' : 'none'); ?>; max-width: 100%; border: 1px solid #000000; border-radius: 12px; overflow: hidden;">
                                            <?php if(!empty($slide['image'])): ?>
                                                <img src="<?php echo e(Storage::url($slide['image'])); ?>" alt="Slide Image" style="width: 100%; height: auto; display: block;">
                                            <?php else: ?>
                                                <img src="" alt="Slide Preview" style="width: 100%; height: auto; display: block;">
                                            <?php endif; ?>
                                        </div>
                                        <input type="hidden" class="slide-image-path" name="slides[<?php echo e($index); ?>][image]" value="<?php echo e($slide['image'] ?? ''); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            
            <div class="mb-3">
                <button type="button" class="btn btn-outline-primary" onclick="addSlide()" style="border-color: #000000; color: #000000;">
                    <i class="fas fa-plus"></i> Add New Slide
                </button>
            </div>
        </div>
        
        
        <div class="form-section">
            <h3 class="form-section-title">
                <i class="fas fa-home"></i>
                Homepage Sections
            </h3>
            
            <div class="form-group">
                <label class="form-label">Show Featured Products</label>
                <label class="toggle-switch">
                    <input type="checkbox" name="show_featured_products" <?php echo e($settings->show_featured_products ? 'checked' : ''); ?>>
                    <span class="toggle-slider"></span>
                </label>
            </div>
            
            <div class="form-group">
                <label class="form-label">Show New Arrivals</label>
                <label class="toggle-switch">
                    <input type="checkbox" name="show_new_arrivals" <?php echo e($settings->show_new_arrivals ? 'checked' : ''); ?>>
                    <span class="toggle-slider"></span>
                </label>
            </div>
            
            <div class="form-group">
                <label class="form-label">Show Categories</label>
                <label class="toggle-switch">
                    <input type="checkbox" name="show_categories" <?php echo e($settings->show_categories ? 'checked' : ''); ?>>
                    <span class="toggle-slider"></span>
                </label>
            </div>
            
            <div class="form-group">
                <label class="form-label">Show Testimonials</label>
                <label class="toggle-switch">
                    <input type="checkbox" name="show_testimonials" <?php echo e($settings->show_testimonials ? 'checked' : ''); ?>>
                    <span class="toggle-slider"></span>
                </label>
            </div>
            
            <div class="form-group">
                <label class="form-label">Show About Section</label>
                <label class="toggle-switch">
                    <input type="checkbox" name="show_about_section" <?php echo e($settings->show_about_section ? 'checked' : ''); ?>>
                    <span class="toggle-slider"></span>
                </label>
            </div>
        </div>
        
        <div class="text-end">
            <button type="button" class="btn-builder btn-builder-primary" onclick="saveHomepage()">
                <i class="fas fa-save"></i>
                Save Homepage
            </button>
        </div>
    </form>
</div>

<script>
let slideIndex = <?php echo e(count($slides)); ?>;

// Add new slide
function addSlide() {
    const container = document.getElementById('slidesContainer');
    const slideHtml = `
        <div class="slide-item mb-4" data-slide-index="${slideIndex}">
            <div class="card" style="border: 1px solid #000000; border-radius: 12px;">
                <div class="card-header" style="background: #ffffff; border-bottom: 1px solid #000000; padding: 1rem 1.5rem;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0" style="font-weight: 600; color: #000000;">
                            <i class="fas fa-sliders-h"></i> Slide ${slideIndex + 1}
                        </h5>
                        <button type="button" class="btn btn-sm" onclick="removeSlide(${slideIndex})" style="color: #000000; border: 1px solid #000000; background: #ffffff;">
                            <i class="fas fa-trash"></i> Remove
                        </button>
                    </div>
                </div>
                <div class="card-body" style="padding: 1.5rem;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Heading</label>
                                <input type="text" class="form-control slide-heading" name="slides[${slideIndex}][heading]" placeholder="Enter heading">
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">Subheading</label>
                                <textarea class="form-control slide-subheading" name="slides[${slideIndex}][subheading]" rows="3" placeholder="Enter subheading"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Button Text</label>
                                        <input type="text" class="form-control slide-button-text" name="slides[${slideIndex}][button_text]" value="Shop Now" placeholder="Shop Now">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Button Link</label>
                                        <input type="text" class="form-control slide-button-link" name="slides[${slideIndex}][button_link]" value="/products" placeholder="/products">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">Background Color</label>
                                <div class="color-picker-wrapper">
                                    <input type="color" class="form-control color-input slide-bg-color" name="slides[${slideIndex}][background_color]" value="#ffffff" style="width: 80px; height: 45px; padding: 0; border: 1px solid #000000; border-radius: 8px; cursor: pointer;">
                                    <div class="color-preview slide-bg-color-preview" style="background-color: #ffffff;"></div>
                                    <input type="text" class="form-control slide-bg-color-text" value="#ffffff" placeholder="#ffffff" style="flex: 1;">
                                </div>
                                <small class="text-muted d-block mt-2">Choose a background color for this slide</small>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">Text Color</label>
                                <div class="color-picker-wrapper">
                                    <input type="color" class="form-control color-input slide-text-color" name="slides[${slideIndex}][text_color]" value="#000000" style="width: 80px; height: 45px; padding: 0; border: 1px solid #000000; border-radius: 8px; cursor: pointer;">
                                    <div class="color-preview slide-text-color-preview" style="background-color: #000000;"></div>
                                    <input type="text" class="form-control slide-text-color-text" value="#000000" placeholder="#000000" style="flex: 1;">
                                </div>
                                <small class="text-muted d-block mt-2">Color for heading and subheading text</small>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">Button Background Color</label>
                                <div class="color-picker-wrapper">
                                    <input type="color" class="form-control color-input slide-btn-bg-color" name="slides[${slideIndex}][button_bg_color]" value="#000000" style="width: 80px; height: 45px; padding: 0; border: 1px solid #000000; border-radius: 8px; cursor: pointer;">
                                    <div class="color-preview slide-btn-bg-color-preview" style="background-color: #000000;"></div>
                                    <input type="text" class="form-control slide-btn-bg-color-text" value="#000000" placeholder="#000000" style="flex: 1;">
                                </div>
                                <small class="text-muted d-block mt-2">Background color for the CTA button</small>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">Button Text Color</label>
                                <div class="color-picker-wrapper">
                                    <input type="color" class="form-control color-input slide-btn-text-color" name="slides[${slideIndex}][button_text_color]" value="#ffffff" style="width: 80px; height: 45px; padding: 0; border: 1px solid #000000; border-radius: 8px; cursor: pointer;">
                                    <div class="color-preview slide-btn-text-color-preview" style="background-color: #ffffff;"></div>
                                    <input type="text" class="form-control slide-btn-text-color-text" value="#ffffff" placeholder="#ffffff" style="flex: 1;">
                                </div>
                                <small class="text-muted d-block mt-2">Text color for the CTA button</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Slide Image</label>
                                <div class="file-upload-area slide-image-upload" data-slide-index="${slideIndex}" style="cursor: pointer;">
                                    <input type="file" name="slide_images[${slideIndex}]" class="slide-image-input" accept="image/*" style="display: none;" data-slide-index="${slideIndex}">
                                    <div class="file-upload-icon">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                    </div>
                                    <p class="mb-0"><strong>Click to upload</strong> or drag and drop</p>
                                    <p class="text-muted small mb-0">Recommended: 1200x600px, JPG or PNG up to 5MB</p>
                                </div>
                                <div class="slide-image-preview mt-3" data-slide-index="${slideIndex}" style="display: none; max-width: 100%; border: 1px solid #000000; border-radius: 12px; overflow: hidden;">
                                    <img src="" alt="Slide Preview" style="width: 100%; height: auto; display: block;">
                                </div>
                                <input type="hidden" class="slide-image-path" name="slides[${slideIndex}][image]" value="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', slideHtml);
    slideIndex++;
    setupSlideImageUpload();
    setupBackgroundColorPicker();
}

// Remove slide
function removeSlide(index) {
    const slideItem = document.querySelector(`.slide-item[data-slide-index="${index}"]`);
    if (slideItem) {
        slideItem.remove();
        // Renumber remaining slides
        renumberSlides();
    }
}

// Renumber slides
function renumberSlides() {
    const slides = document.querySelectorAll('.slide-item');
    slides.forEach((slide, index) => {
        const header = slide.querySelector('.card-header h5');
        if (header) {
            header.innerHTML = `<i class="fas fa-sliders-h"></i> Slide ${index + 1}`;
        }
        slide.setAttribute('data-slide-index', index);
    });
}

// Setup image upload for slides
function setupSlideImageUpload() {
    document.querySelectorAll('.slide-image-upload').forEach(uploadArea => {
        const input = uploadArea.querySelector('.slide-image-input');
        const slideIndex = uploadArea.getAttribute('data-slide-index');
        const preview = document.querySelector(`.slide-image-preview[data-slide-index="${slideIndex}"]`);
        const pathInput = uploadArea.closest('.form-group').querySelector('.slide-image-path');
        
        if (!input.hasAttribute('data-listener-added')) {
            input.setAttribute('data-listener-added', 'true');
            
            uploadArea.addEventListener('click', () => input.click());
            
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        if (preview) {
                            preview.querySelector('img').src = e.target.result;
                            preview.style.display = 'block';
                        }
                    };
                    reader.readAsDataURL(file);
                    
                    // Upload image
                    uploadSlideImage(slideIndex, file, pathInput);
                }
            });
        }
    });
}

// Upload slide image
function uploadSlideImage(slideIndex, file, pathInput) {
    const formData = new FormData();
    formData.append('slide_image', file);
    formData.append('slide_index', slideIndex);
    
    fetch('<?php echo e(route('admin.site-builder.upload-slide-image')); ?>', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && pathInput) {
            pathInput.value = data.image_path;
        }
    })
    .catch(error => {
        console.error('Error uploading slide image:', error);
    });
}

// Setup color picker for a specific type
function setupColorPicker(selector, previewSelector, textSelector) {
    document.querySelectorAll(selector).forEach(colorInput => {
        const wrapper = colorInput.closest('.color-picker-wrapper');
        if (!wrapper) return;
        
        const preview = wrapper.querySelector(previewSelector);
        const textInput = wrapper.querySelector(textSelector);
        
        // Initialize preview and text input with current color
        const currentColor = colorInput.value || (selector.includes('bg-color') ? '#ffffff' : selector.includes('text-color') && !selector.includes('btn') ? '#000000' : selector.includes('btn-bg') ? '#000000' : '#ffffff');
        if (preview) preview.style.backgroundColor = currentColor;
        if (textInput) textInput.value = currentColor;
        
        // Skip if already initialized
        if (colorInput.hasAttribute('data-color-initialized')) {
            return;
        }
        colorInput.setAttribute('data-color-initialized', 'true');
        
        // Sync color input to preview and text
        colorInput.addEventListener('input', function() {
            const color = this.value;
            if (preview) preview.style.backgroundColor = color;
            if (textInput) textInput.value = color;
        });
        
        // Sync text input to color input and preview
        if (textInput) {
            textInput.addEventListener('input', function() {
                const color = this.value;
                if (/^#[0-9A-F]{6}$/i.test(color)) {
                    colorInput.value = color;
                    if (preview) preview.style.backgroundColor = color;
                }
            });
        }
        
        // Click preview to open color picker
        if (preview) {
            preview.addEventListener('click', function() {
                colorInput.click();
            });
        }
    });
}

// Setup all color pickers
function setupBackgroundColorPicker() {
    setupColorPicker('.slide-bg-color', '.slide-bg-color-preview', '.slide-bg-color-text');
    setupColorPicker('.slide-text-color', '.slide-text-color-preview', '.slide-text-color-text');
    setupColorPicker('.slide-btn-bg-color', '.slide-btn-bg-color-preview', '.slide-btn-bg-color-text');
    setupColorPicker('.slide-btn-text-color', '.slide-btn-text-color-preview', '.slide-btn-text-color-text');
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    setupSlideImageUpload();
    setupBackgroundColorPicker();
});

// Save homepage
function saveHomepage() {
    const form = document.getElementById('homepageForm');
    const formData = new FormData();
    formData.append('_token', '<?php echo e(csrf_token()); ?>');
    formData.append('show_featured_products', form.querySelector('input[name="show_featured_products"]').checked ? '1' : '0');
    formData.append('show_new_arrivals', form.querySelector('input[name="show_new_arrivals"]').checked ? '1' : '0');
    formData.append('show_categories', form.querySelector('input[name="show_categories"]').checked ? '1' : '0');
    formData.append('show_testimonials', form.querySelector('input[name="show_testimonials"]').checked ? '1' : '0');
    formData.append('show_about_section', form.querySelector('input[name="show_about_section"]').checked ? '1' : '0');
    formData.append('show_hero_slides', document.getElementById('showHeroSlides').checked ? '1' : '0');
    
    // Collect slides data
    const slides = [];
    document.querySelectorAll('.slide-item').forEach((slideItem, index) => {
        const slideData = {
            heading: slideItem.querySelector('.slide-heading')?.value || '',
            subheading: slideItem.querySelector('.slide-subheading')?.value || '',
            button_text: slideItem.querySelector('.slide-button-text')?.value || 'Shop Now',
            button_link: slideItem.querySelector('.slide-button-link')?.value || '/products',
            image: slideItem.querySelector('.slide-image-path')?.value || '',
            background_color: slideItem.querySelector('.slide-bg-color')?.value || '#ffffff',
            text_color: slideItem.querySelector('.slide-text-color')?.value || '#000000',
            button_bg_color: slideItem.querySelector('.slide-btn-bg-color')?.value || '#000000',
            button_text_color: slideItem.querySelector('.slide-btn-text-color')?.value || '#ffffff'
        };
        slides.push(slideData);
    });
    
    formData.append('hero_slides', JSON.stringify(slides));
    
    showSaveIndicator('saving');
    
    fetch('<?php echo e(route('admin.site-builder.update-homepage')); ?>', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSaveIndicator('saved');
            showAlert('Homepage settings saved successfully!');
        } else {
            throw new Error(data.message || 'Failed to save');
        }
    })
    .catch(error => {
        showSaveIndicator('saved');
        showAlert('Error: ' + error.message, 'danger');
    });
}
</script>






<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/e-manager/resources/views/admin/site-builder/tabs/homepage.blade.php ENDPATH**/ ?>