@extends('layouts.storefront')

@section('title', 'My Addresses — ' . $tenant->business_name)

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('storefront.preview', $tenant->subdomain) }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('customer.dashboard', $tenant->subdomain) }}">My Account</a></li>
            <li class="breadcrumb-item active" aria-current="page">Addresses</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Account Menu</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('customer.dashboard', $tenant->subdomain) }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    <a href="{{ route('customer.profile', $tenant->subdomain) }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-user"></i> Profile
                    </a>
                    <a href="{{ route('customer.addresses', $tenant->subdomain) }}" class="list-group-item list-group-item-action active">
                        <i class="fas fa-map-marker-alt"></i> Addresses
                    </a>
                    <a href="{{ route('customer.orders', $tenant->subdomain) }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-shopping-bag"></i> Orders
                    </a>
                    <form method="POST" action="{{ route('customer.logout', $tenant->subdomain) }}">
                        @csrf
                        <button type="submit" class="list-group-item list-group-item-action text-danger">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">My Addresses</h4>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                    <i class="fas fa-plus"></i> Add Address
                </button>
            </div>

            @if(count($addresses) > 0)
                <div class="row">
                    @foreach($addresses as $address)
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">{{ ucfirst($address['type']) }} Address</h6>
                                @if($address['is_default'])
                                    <span class="badge bg-primary">Default</span>
                                @endif
                            </div>
                            <div class="card-body">
                                <p class="mb-1"><strong>{{ $address['first_name'] }} {{ $address['last_name'] }}</strong></p>
                                @if($address['company'])
                                    <p class="mb-1">{{ $address['company'] }}</p>
                                @endif
                                <p class="mb-1">{{ $address['address_line_1'] }}</p>
                                @if($address['address_line_2'])
                                    <p class="mb-1">{{ $address['address_line_2'] }}</p>
                                @endif
                                <p class="mb-1">{{ $address['city'] }}, {{ $address['state'] }} {{ $address['postal_code'] }}</p>
                                <p class="mb-1">{{ $address['country'] }}</p>
                                <p class="mb-0"><i class="fas fa-phone"></i> {{ $address['phone'] }}</p>
                            </div>
                            <div class="card-footer">
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" onclick="editAddress('{{ $address['id'] }}')">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-outline-danger" onclick="deleteAddress('{{ $address['id'] }}')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No addresses saved</h5>
                    <p class="text-muted">Add your first address to make checkout faster.</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                        <i class="fas fa-plus"></i> Add Address
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Add/Edit Address Modal -->
<div class="modal fade" id="addAddressModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addressForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="type" class="form-label">Address Type</label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="billing">Billing Address</option>
                                <option value="shipping">Shipping Address</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" id="is_default" name="is_default">
                                <label class="form-check-label" for="is_default">
                                    Set as default address
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="company" class="form-label">Company (Optional)</label>
                        <input type="text" class="form-control" id="company" name="company">
                    </div>

                    <div class="mb-3">
                        <label for="address_line_1" class="form-label">Address Line 1</label>
                        <input type="text" class="form-control" id="address_line_1" name="address_line_1" required>
                    </div>

                    <div class="mb-3">
                        <label for="address_line_2" class="form-label">Address Line 2 (Optional)</label>
                        <input type="text" class="form-control" id="address_line_2" name="address_line_2">
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control" id="city" name="city" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="state" class="form-label">State</label>
                            <input type="text" class="form-control" id="state" name="state" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="postal_code" class="form-label">Postal Code</label>
                            <input type="text" class="form-control" id="postal_code" name="postal_code" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="country" class="form-label">Country</label>
                            <input type="text" class="form-control" id="country" name="country" value="Nepal" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Address</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Address Form -->
<form id="deleteAddressForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
let editingAddressId = null;

function editAddress(addressId) {
    // Find the address data
    const addresses = @json($addresses);
    const address = addresses.find(addr => addr.id === addressId);
    
    if (!address) return;
    
    editingAddressId = addressId;
    
    // Populate the form
    document.getElementById('type').value = address.type;
    document.getElementById('is_default').checked = address.is_default;
    document.getElementById('first_name').value = address.first_name;
    document.getElementById('last_name').value = address.last_name;
    document.getElementById('company').value = address.company || '';
    document.getElementById('address_line_1').value = address.address_line_1;
    document.getElementById('address_line_2').value = address.address_line_2 || '';
    document.getElementById('city').value = address.city;
    document.getElementById('state').value = address.state;
    document.getElementById('postal_code').value = address.postal_code;
    document.getElementById('country').value = address.country;
    document.getElementById('phone').value = address.phone;
    
    // Update form action and method
    document.getElementById('addressForm').action = `/storefront/{{ $tenant->subdomain }}/account/addresses/${addressId}`;
    document.getElementById('addressForm').innerHTML = document.getElementById('addressForm').innerHTML.replace('@csrf', '@csrf\n                        @method("PUT")');
    
    // Update modal title
    document.querySelector('#addAddressModal .modal-title').textContent = 'Edit Address';
    
    // Show modal
    new bootstrap.Modal(document.getElementById('addAddressModal')).show();
}

function deleteAddress(addressId) {
    if (confirm('Are you sure you want to delete this address?')) {
        document.getElementById('deleteAddressForm').action = `/storefront/{{ $tenant->subdomain }}/account/addresses/${addressId}`;
        document.getElementById('deleteAddressForm').submit();
    }
}

// Reset form when modal is hidden
document.getElementById('addAddressModal').addEventListener('hidden.bs.modal', function() {
    editingAddressId = null;
    document.getElementById('addressForm').reset();
    document.getElementById('addressForm').action = `/storefront/{{ $tenant->subdomain }}/account/addresses`;
    document.querySelector('#addAddressModal .modal-title').textContent = 'Add Address';
});
</script>
@endpush
@endsection