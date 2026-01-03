@extends('admin.layouts.app')

@section('title', 'View Product')
@section('page-title', 'View Product')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Product Details</h4>
    <div>
        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Edit Product
        </a>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
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
                            <p class="form-control-plaintext">{{ $product->name }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">SKU</label>
                            <p class="form-control-plaintext">{{ $product->sku }}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Category</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-primary">{{ $product->category->name }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Status</label>
                            <p class="form-control-plaintext">
                                @if($product->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Price</label>
                            <p class="form-control-plaintext">Rs. {{ number_format($product->price, 2) }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Sale Price</label>
                            <p class="form-control-plaintext">
                                @if($product->sale_price)
                                    Rs. {{ number_format($product->sale_price, 2) }}
                                @else
                                    <span class="text-muted">Not set</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Stock</label>
                            <p class="form-control-plaintext">
                                @if($product->stock > 0)
                                    <span class="badge bg-success">{{ $product->stock }} units</span>
                                @else
                                    <span class="badge bg-danger">Out of Stock</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Featured</label>
                            <p class="form-control-plaintext">
                                @if($product->is_featured)
                                    <span class="badge bg-warning">Featured</span>
                                @else
                                    <span class="text-muted">Not featured</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Description</label>
                    <div class="form-control-plaintext">
                        @if($product->description)
                            {{ $product->description }}
                        @else
                            <span class="text-muted">No description provided</span>
                        @endif
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Created At</label>
                            <p class="form-control-plaintext">{{ $product->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Updated At</label>
                            <p class="form-control-plaintext">{{ $product->updated_at->format('M d, Y H:i') }}</p>
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
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" 
                         alt="{{ $product->name }}" 
                         class="img-fluid rounded"
                         style="max-height: 300px;">
                @else
                    <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 200px;">
                        <div class="text-muted">
                            <i class="fas fa-image fa-3x mb-2"></i>
                            <p>No image available</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @if($product->images && count($product->images) > 0)
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Additional Images</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($product->images as $image)
                    <div class="col-6 mb-2">
                        <img src="{{ asset('storage/' . $image) }}" 
                             alt="{{ $product->name }}" 
                             class="img-fluid rounded">
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection





