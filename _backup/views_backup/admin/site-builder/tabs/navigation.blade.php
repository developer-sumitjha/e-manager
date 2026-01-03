<div id="navigation-tab" class="tab-pane">
    <form id="navigationForm">
        @csrf
        
        <div class="form-section">
            <h3 class="form-section-title">
                <i class="fas fa-bars"></i>
                Navigation Settings
            </h3>
            
            <div class="form-group">
                <label class="form-label">Show Categories Menu</label>
                <label class="toggle-switch">
                    <input type="checkbox" name="show_categories_menu" {{ $settings->show_categories_menu ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                </label>
            </div>
            
            <div class="form-group">
                <label class="form-label">Show Search Bar</label>
                <label class="toggle-switch">
                    <input type="checkbox" name="show_search_bar" {{ $settings->show_search_bar ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                </label>
            </div>
            
            <div class="form-group">
                <label class="form-label">Show Cart Icon</label>
                <label class="toggle-switch">
                    <input type="checkbox" name="show_cart_icon" {{ $settings->show_cart_icon ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                </label>
            </div>
        </div>
        
        <div class="text-end">
            <button type="button" class="btn-builder btn-builder-primary" onclick="saveNavigation()">
                <i class="fas fa-save"></i>
                Save Navigation
            </button>
        </div>
    </form>
</div>

<script>
function saveNavigation() {
    const form = document.getElementById('navigationForm');
    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('show_categories_menu', form.querySelector('input[name="show_categories_menu"]').checked ? '1' : '0');
    formData.append('show_search_bar', form.querySelector('input[name="show_search_bar"]').checked ? '1' : '0');
    formData.append('show_cart_icon', form.querySelector('input[name="show_cart_icon"]').checked ? '1' : '0');
    
    showSaveIndicator('saving');
    
    fetch('{{ route('admin.site-builder.update-navigation') }}', {
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
            showSaveIndicator('saved');
            showAlert('Navigation settings saved successfully!');
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




