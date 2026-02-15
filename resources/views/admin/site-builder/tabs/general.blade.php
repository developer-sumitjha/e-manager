<div id="general-tab" class="tab-pane active">
    <form id="generalForm">
        @csrf
        
        <div class="form-section">
            <h3 class="form-section-title">
                <i class="fas fa-store"></i>
                Basic Information
            </h3>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Site Name *</label>
                        <input type="text" class="form-control" name="site_name" value="{{ $settings->site_name }}" required>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Site Tagline</label>
                        <input type="text" class="form-control" name="site_tagline" value="{{ $settings->site_tagline }}" placeholder="Your catchy tagline">
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Site Description</label>
                <textarea class="form-control" name="site_description" rows="3" placeholder="Describe your store...">{{ $settings->site_description }}</textarea>
            </div>
        </div>
        
        <div class="form-section">
            <h3 class="form-section-title">
                <i class="fas fa-image"></i>
                Branding
            </h3>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Logo</label>
                        <div class="file-upload-area">
                            <input type="file" name="logo" accept="image/*" style="display: none;">
                            <div class="file-upload-icon">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <p class="mb-0"><strong>Click to upload</strong> or drag and drop</p>
                            <p class="text-muted small mb-0">PNG, JPG, SVG up to 2MB</p>
                        </div>
                        @if($settings->logo)
                        <div class="image-preview-box mt-3">
                            @php
                                $logoUrl = '';
                                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($settings->logo)) {
                                    $logoUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($settings->logo);
                                } else {
                                    // Fallback for local development or if Storage::url fails
                                    $logoUrl = asset('storage/' . $settings->logo);
                                }
                            @endphp
                            <img src="{{ $logoUrl }}" alt="Current Logo" style="max-width: 100%; height: auto; border: 1px solid #ddd; border-radius: 8px; padding: 8px;">
                        </div>
                        @endif
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Favicon</label>
                        <div class="file-upload-area">
                            <input type="file" name="favicon" accept=".ico,.png,.jpg" style="display: none;">
                            <div class="file-upload-icon">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <p class="mb-0"><strong>Click to upload</strong> or drag and drop</p>
                            <p class="text-muted small mb-0">ICO, PNG up to 512KB</p>
                        </div>
                        @if($settings->favicon)
                        <div class="image-preview-box mt-3">
                            @php
                                $faviconUrl = '';
                                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($settings->favicon)) {
                                    $faviconUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($settings->favicon);
                                } else {
                                    // Fallback for local development or if Storage::url fails
                                    $faviconUrl = asset('storage/' . $settings->favicon);
                                }
                            @endphp
                            <img src="{{ $faviconUrl }}" alt="Current Favicon" style="max-width: 100%; height: auto; border: 1px solid #ddd; border-radius: 8px; padding: 8px;">
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-section">
            <h3 class="form-section-title">
                <i class="fas fa-address-card"></i>
                Contact Information
            </h3>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Contact Email</label>
                        <input type="email" class="form-control" name="contact_email" value="{{ $settings->contact_email }}" placeholder="hello@yourdomain.com">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Contact Phone</label>
                        <input type="tel" class="form-control" name="contact_phone" value="{{ $settings->contact_phone }}" placeholder="+977 9841234567">
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Address</label>
                <textarea class="form-control" name="address" rows="2" placeholder="Your business address">{{ $settings->address }}</textarea>
            </div>
        </div>
        
        <div class="text-end">
            <button type="button" class="btn-builder btn-builder-primary" onclick="saveGeneral()">
                <i class="fas fa-save"></i>
                Save Changes
            </button>
        </div>
    </form>
</div>

<script>
function saveGeneral() {
    const form = document.getElementById('generalForm');
    const formData = new FormData(form);
    
    showSaveIndicator('saving');
    
    // Save basic info
    fetch('{{ route('admin.site-builder.update-basic-info') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Upload logo if present
            const logoFile = form.querySelector('input[name="logo"]').files[0];
            if (logoFile) {
                const logoData = new FormData();
                logoData.append('logo', logoFile);
                
                return fetch('{{ route('admin.site-builder.upload-logo') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: logoData
                });
            }
            return Promise.resolve({json: () => ({success: true})});
        } else {
            throw new Error(data.message || 'Failed to save');
        }
    })
    .then(response => response.json ? response.json() : response)
    .then(data => {
        // Upload favicon if present
        const faviconFile = form.querySelector('input[name="favicon"]').files[0];
        if (faviconFile) {
            const faviconData = new FormData();
            faviconData.append('favicon', faviconFile);
            
            return fetch('{{ route('admin.site-builder.upload-favicon') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: faviconData
            });
        }
        return Promise.resolve({json: () => ({success: true})});
    })
    .then(response => response.json ? response.json() : response)
    .then(data => {
        showSaveIndicator('saved');
        showAlert('General settings saved successfully!');
        setTimeout(() => {
            location.reload();
        }, 1500);
    })
    .catch(error => {
        showSaveIndicator('saved');
        showAlert('Error saving settings: ' + error.message, 'danger');
    });
}

// Handle logo and favicon preview
document.addEventListener('DOMContentLoaded', function() {
    // Logo preview
    const logoInput = document.querySelector('input[name="logo"]');
    const logoUploadArea = logoInput?.closest('.file-upload-area');
    
    if (logoInput && logoUploadArea) {
        logoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    let previewBox = logoUploadArea.closest('.form-group').querySelector('.image-preview-box');
                    if (!previewBox) {
                        previewBox = document.createElement('div');
                        previewBox.className = 'image-preview-box mt-3';
                        logoUploadArea.closest('.form-group').appendChild(previewBox);
                    }
                    
                    let img = previewBox.querySelector('img');
                    if (!img) {
                        img = document.createElement('img');
                        img.style.cssText = 'max-width: 100%; height: auto; border: 1px solid #ddd; border-radius: 8px; padding: 8px;';
                        img.alt = 'Logo Preview';
                        previewBox.appendChild(img);
                    }
                    
                    img.src = e.target.result;
                    previewBox.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Favicon preview
    const faviconInput = document.querySelector('input[name="favicon"]');
    const faviconUploadArea = faviconInput?.closest('.file-upload-area');
    
    if (faviconInput && faviconUploadArea) {
        faviconInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    let previewBox = faviconUploadArea.closest('.form-group').querySelector('.image-preview-box');
                    if (!previewBox) {
                        previewBox = document.createElement('div');
                        previewBox.className = 'image-preview-box mt-3';
                        faviconUploadArea.closest('.form-group').appendChild(previewBox);
                    }
                    
                    let img = previewBox.querySelector('img');
                    if (!img) {
                        img = document.createElement('img');
                        img.style.cssText = 'max-width: 100%; height: auto; border: 1px solid #ddd; border-radius: 8px; padding: 8px;';
                        img.alt = 'Favicon Preview';
                        previewBox.appendChild(img);
                    }
                    
                    img.src = e.target.result;
                    previewBox.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>






