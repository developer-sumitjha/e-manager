<div id="advanced-tab" class="tab-pane">
    <form id="advancedForm">
        @csrf
        
        <div class="form-section">
            <h3 class="form-section-title">
                <i class="fas fa-code"></i>
                Custom Code
            </h3>
            
            <div class="form-group">
                <label class="form-label">Custom CSS</label>
                <textarea class="form-control" name="custom_css" rows="6" placeholder="/* Your custom CSS here */&#10;.my-class {&#10;  color: red;&#10;}" style="font-family: monospace;">{{ $settings->custom_css }}</textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label">Custom JavaScript</label>
                <textarea class="form-control" name="custom_js" rows="6" placeholder="// Your custom JS here&#10;console.log('Hello!');" style="font-family: monospace;">{{ $settings->custom_js }}</textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label">Header Code</label>
                <textarea class="form-control" name="header_code" rows="4" placeholder="<!-- Code to inject in <head> -->" style="font-family: monospace;">{{ $settings->header_code }}</textarea>
                <small class="text-muted">Injected in the &lt;head&gt; section</small>
            </div>
            
            <div class="form-group">
                <label class="form-label">Footer Code</label>
                <textarea class="form-control" name="footer_code" rows="4" placeholder="<!-- Code to inject before </body> -->" style="font-family: monospace;">{{ $settings->footer_code }}</textarea>
                <small class="text-muted">Injected before closing &lt;/body&gt; tag</small>
            </div>
        </div>
        
        <div class="form-section">
            <h3 class="form-section-title">
                <i class="fas fa-bell"></i>
                Notifications
            </h3>
            
            <div class="form-group">
                <label class="form-label">Show Cookie Notice</label>
                <label class="toggle-switch">
                    <input type="checkbox" name="show_cookie_notice" {{ $settings->show_cookie_notice ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                </label>
            </div>
            
            <div class="form-group">
                <label class="form-label">Cookie Notice Text</label>
                <textarea class="form-control" name="cookie_notice_text" rows="2" placeholder="We use cookies to enhance your experience...">{{ $settings->cookie_notice_text }}</textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label">Show Promo Popup</label>
                <label class="toggle-switch">
                    <input type="checkbox" name="show_promo_popup" {{ $settings->show_promo_popup ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                </label>
            </div>
            
            <div class="form-group">
                <label class="form-label">Promo Popup Content</label>
                <textarea class="form-control" name="promo_popup_content" rows="3" placeholder="Get 10% off on your first order!">{{ $settings->promo_popup_content }}</textarea>
            </div>
        </div>
        
        <div class="form-section">
            <h3 class="form-section-title">
                <i class="fas fa-tools"></i>
                Maintenance
            </h3>
            
            <div class="form-group">
                <label class="form-label">Maintenance Mode</label>
                <label class="toggle-switch">
                    <input type="checkbox" name="maintenance_mode" {{ $settings->maintenance_mode ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                </label>
                <small class="text-muted d-block mt-2">Enable to show maintenance page to visitors</small>
            </div>
            
            <div class="form-group">
                <label class="form-label">Maintenance Message</label>
                <textarea class="form-control" name="maintenance_message" rows="3" placeholder="We're currently updating our store. Check back soon!">{{ $settings->maintenance_message }}</textarea>
            </div>
        </div>
        
        <div class="text-end">
            <button type="button" class="btn-builder btn-builder-primary" onclick="saveAdvanced()">
                <i class="fas fa-save"></i>
                Save Advanced
            </button>
        </div>
    </form>
</div>

<script>
function saveAdvanced() {
    const form = document.getElementById('advancedForm');
    const formData = new FormData(form);
    
    // Save custom code first
    showSaveIndicator('saving');
    
    fetch('{{ route('admin.site-builder.update-custom-code') }}', {
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
            // Save notifications
            const notifData = new FormData();
            notifData.append('_token', '{{ csrf_token() }}');
            notifData.append('show_cookie_notice', form.querySelector('input[name="show_cookie_notice"]').checked ? '1' : '0');
            notifData.append('cookie_notice_text', form.querySelector('textarea[name="cookie_notice_text"]').value);
            notifData.append('show_promo_popup', form.querySelector('input[name="show_promo_popup"]').checked ? '1' : '0');
            notifData.append('promo_popup_content', form.querySelector('textarea[name="promo_popup_content"]').value);
            
            return fetch('{{ route('admin.site-builder.update-notifications') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: notifData
            });
        } else {
            throw new Error(data.message || 'Failed to save');
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Save maintenance
            const maintData = new FormData();
            maintData.append('_token', '{{ csrf_token() }}');
            maintData.append('maintenance_mode', form.querySelector('input[name="maintenance_mode"]').checked ? '1' : '0');
            maintData.append('maintenance_message', form.querySelector('textarea[name="maintenance_message"]').value);
            
            return fetch('{{ route('admin.site-builder.update-maintenance') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: maintData
            });
        } else {
            throw new Error(data.message || 'Failed to save');
        }
    })
    .then(response => response.json())
    .then(data => {
        showSaveIndicator('saved');
        showAlert('Advanced settings saved successfully!');
    })
    .catch(error => {
        showSaveIndicator('saved');
        showAlert('Error: ' + error.message, 'danger');
    });
}
</script>




