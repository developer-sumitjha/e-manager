@extends('admin.layouts.app')

@section('title', 'Edit Product')
@section('page-title', 'Edit Product')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Edit Product Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="productName" class="form-label">Product Name *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="productName" name="name" value="{{ old('name', $product->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category *</label>
                        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Price *</label>
                            <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $product->price) }}" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="sale_price" class="form-label">Sale Price</label>
                            <input type="number" step="0.01" class="form-control @error('sale_price') is-invalid @enderror" id="sale_price" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}">
                            @error('sale_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="skuInput" class="form-label">SKU *</label>
                            <input type="text" class="form-control @error('sku') is-invalid @enderror" id="skuInput" name="sku" value="{{ old('sku', $product->sku) }}">
                            <small class="text-muted">Suggested SKU: <span id="skuSuggestion">—</span></small>
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="stock" class="form-label">Stock *</label>
                            <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    @if($product->primary_image_url || $product->image)
                    <div class="mb-3">
                        <label class="form-label">Current Image</label>
                        <div>
                            @php
                                $currentImageUrl = $product->primary_image_url;
                                if (!$currentImageUrl && $product->image) {
                                    $currentImageUrl = asset('storage/' . $product->image);
                                }
                            @endphp
                            @if($currentImageUrl)
                                <img src="{{ $currentImageUrl }}" alt="{{ $product->name }}" 
                                     style="max-width: 200px; border-radius: 5px; border: 1px solid #ddd; padding: 5px;"
                                     onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='block';">
                                <div style="display: none; width: 200px; height: 200px; border: 1px solid #ddd; border-radius: 5px; background: #f5f5f5; display: flex; align-items: center; justify-content: center; color: #999;">
                                    <i class="fas fa-image"></i> Image not found
                                </div>
                            @endif
                        </div>
                        <small class="text-muted">Upload a new image to replace this one.</small>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label for="image" class="form-label">Product Image (Optional)</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="imagePreview" class="mt-2" style="display: none;">
                            <label class="form-label">New Image Preview</label>
                            <div>
                                <img id="previewImg" src="" alt="Preview" style="max-width: 200px; border-radius: 5px; border: 1px solid #ddd; padding: 5px;">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                Featured
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Update Product</button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function(){
        let userEditedSku = false;
        function slugToSku(value){
            const base = (value || '').toString().normalize('NFKD')
                .replace(/[\u0300-\u036f]/g,'')
                .replace(/[^a-zA-Z0-9]+/g,'-')
                .replace(/^-+|-+$/g,'')
                .replace(/-{2,}/g,'-')
                .toUpperCase();
            return base || 'SKU';
        }
        function updateSkuSuggestion(){
            const nameEl = document.getElementById('productName');
            const skuEl = document.getElementById('skuInput');
            const sugEl = document.getElementById('skuSuggestion');
            if (!nameEl || !skuEl || !sugEl) return;
            const suggested = slugToSku(nameEl.value);
            sugEl.textContent = suggested;
            if (!userEditedSku && !skuEl.value){
                skuEl.value = suggested;
            }
        }
        
        // Image preview functionality
        function setupImagePreview(){
            const imageInput = document.getElementById('image');
            const imagePreview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');
            
            if (imageInput && imagePreview && previewImg) {
                imageInput.addEventListener('change', function(e){
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e){
                            previewImg.src = e.target.result;
                            imagePreview.style.display = 'block';
                        };
                        reader.readAsDataURL(file);
                    } else {
                        imagePreview.style.display = 'none';
                    }
                });
            }
        }
        
        document.addEventListener('DOMContentLoaded', function(){
            const nameEl = document.getElementById('productName');
            const skuEl = document.getElementById('skuInput');
            if (nameEl){
                nameEl.addEventListener('input', updateSkuSuggestion);
                updateSkuSuggestion();
            }
            if (skuEl){
                skuEl.addEventListener('input', function(){ userEditedSku = true; });
                skuEl.addEventListener('blur', function(){ this.value = slugToSku(this.value); });
            }
            setupImagePreview();
        });
    })();
</script>
@endpush




