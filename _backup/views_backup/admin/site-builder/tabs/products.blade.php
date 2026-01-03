<div id="products-tab" class="tab-pane">
    <form id="productsForm">
        @csrf
        
        <div class="form-section">
            <h3 class="form-section-title">
                <i class="fas fa-box"></i>
                Product Display Settings
            </h3>
            
            <div class="form-group">
                <label class="form-label">Product Card Style</label>
                <select class="form-select" name="product_card_style">
                    <option value="card" {{ $settings->product_card_style == 'card' ? 'selected' : '' }}>Card</option>
                    <option value="grid" {{ $settings->product_card_style == 'grid' ? 'selected' : '' }}>Grid</option>
                    <option value="list" {{ $settings->product_card_style == 'list' ? 'selected' : '' }}>List</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">Products Per Page</label>
                <input type="number" class="form-control" name="products_per_page" value="{{ $settings->products_per_page }}" min="6" max="48">
            </div>
            
            <div class="form-group">
                <label class="form-label">Show Product Ratings</label>
                <label class="toggle-switch">
                    <input type="checkbox" name="show_product_ratings" {{ $settings->show_product_ratings ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                </label>
            </div>
            
            <div class="form-group">
                <label class="form-label">Show Quick View</label>
                <label class="toggle-switch">
                    <input type="checkbox" name="show_quick_view" {{ $settings->show_quick_view ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                </label>
            </div>
            
            <div class="form-group">
                <label class="form-label">Show Add to Cart Button</label>
                <label class="toggle-switch">
                    <input type="checkbox" name="show_add_to_cart_button" {{ $settings->show_add_to_cart_button ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                </label>
            </div>
        </div>
        
        <div class="text-end">
            <button type="button" class="btn-builder btn-builder-primary" onclick="saveProducts()">
                <i class="fas fa-save"></i>
                Save Products
            </button>
        </div>
    </form>
</div>

<script>
function saveProducts() {
    const form = document.getElementById('productsForm');
    const formData = new FormData(form);
    formData.set('show_product_ratings', form.querySelector('input[name="show_product_ratings"]').checked ? '1' : '0');
    formData.set('show_quick_view', form.querySelector('input[name="show_quick_view"]').checked ? '1' : '0');
    formData.set('show_add_to_cart_button', form.querySelector('input[name="show_add_to_cart_button"]').checked ? '1' : '0');
    
    showSaveIndicator('saving');
    
    fetch('{{ route('admin.site-builder.update-products') }}', {
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
            showAlert('Product display settings saved!');
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




