@extends('admin.layouts.app')

@section('title', 'Service Stations Finder')
@section('page-title', 'Service Stations Finder')

@push('styles')
<style>
    /* Service Stations Specific Styles */
    .service-stations-container {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 1rem;
        padding: 2rem;
        border: 1px solid rgba(229, 231, 235, 0.5);
        backdrop-filter: blur(10px);
    }

    .search-section {
        background: rgba(139, 92, 246, 0.05);
        border-radius: 1rem;
        padding: 2rem;
        margin-bottom: 2rem;
        border: 1px solid rgba(139, 92, 246, 0.1);
    }

    .search-form {
        display: flex;
        gap: 1rem;
        align-items: end;
    }

    .search-input-group {
        flex: 1;
    }

    .search-input-group label {
        display: block;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .search-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid rgba(229, 231, 235, 0.8);
        border-radius: 0.75rem;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        border-color: var(--primary-color);
        outline: none;
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
    }

    .search-btn {
        background: linear-gradient(135deg, #8B5CF6, #A78BFA);
        color: white;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .search-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(139, 92, 246, 0.3);
    }

    .popular-locations {
        margin-top: 1.5rem;
    }

    .popular-locations h4 {
        color: var(--text-primary);
        margin-bottom: 1rem;
        font-size: 1rem;
        font-weight: 600;
    }

    .location-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .location-tag {
        background: rgba(139, 92, 246, 0.1);
        color: var(--primary-color);
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 1px solid rgba(139, 92, 246, 0.2);
    }

    .location-tag:hover {
        background: var(--primary-color);
        color: white;
    }

    .stations-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }

    .station-card {
        background: rgba(255, 255, 255, 0.8);
        border-radius: 1rem;
        padding: 1.5rem;
        border: 1px solid rgba(229, 231, 235, 0.5);
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .station-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        border-color: var(--primary-color);
    }

    .station-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .station-name {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }

    .station-type {
        background: rgba(34, 197, 94, 0.1);
        color: #22C55E;
        padding: 0.25rem 0.75rem;
        border-radius: 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .station-details {
        margin-bottom: 1rem;
    }

    .detail-row {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
        font-size: 0.9rem;
        color: var(--text-secondary);
    }

    .detail-row i {
        width: 16px;
        color: var(--primary-color);
        font-size: 0.8rem;
    }

    .station-actions {
        display: flex;
        gap: 0.75rem;
    }

    .action-btn {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .action-btn.primary {
        background: var(--primary-color);
        color: white;
    }

    .action-btn.secondary {
        background: rgba(107, 114, 128, 0.1);
        color: var(--text-secondary);
        border: 1px solid rgba(107, 114, 128, 0.3);
    }

    .action-btn:hover {
        transform: translateY(-1px);
    }

    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: var(--text-secondary);
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: var(--text-secondary);
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="service-stations-container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="page-title">
                            <i class="fas fa-map-marker-alt"></i>
                            Service Stations Finder
                        </h1>
                        <p class="page-subtitle">Find Gaaubesi service stations near you</p>
                    </div>
                    <a href="{{ route('admin.gaaubesi.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>

                <!-- Search Section -->
                <div class="search-section">
                    <form method="GET" class="search-form">
                        <div class="search-input-group">
                            <label for="location">Search Location</label>
                            <input type="text" 
                                   id="location" 
                                   name="location" 
                                   class="search-input" 
                                   placeholder="Enter city, district, or area..."
                                   value="{{ $searchLocation }}">
                        </div>
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search"></i> Find Stations
                        </button>
                    </form>

                    <div class="popular-locations">
                        <h4>Popular Locations</h4>
                        <div class="location-tags">
                            @foreach($popularLocations as $location)
                            <span class="location-tag" onclick="searchLocation('{{ $location }}')">
                                {{ $location }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Results Section -->
                @if($searchLocation)
                    @if(count($stations) > 0)
                        <div class="stations-grid">
                            @foreach($stations as $station)
                            <div class="station-card">
                                <div class="station-header">
                                    <h3 class="station-name">{{ $station['name'] ?? 'Service Station' }}</h3>
                                    <span class="station-type">{{ $station['type'] ?? 'Branch' }}</span>
                                </div>
                                
                                <div class="station-details">
                                    @if(isset($station['address']))
                                    <div class="detail-row">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>{{ $station['address'] }}</span>
                                    </div>
                                    @endif
                                    
                                    @if(isset($station['phone']))
                                    <div class="detail-row">
                                        <i class="fas fa-phone"></i>
                                        <span>{{ $station['phone'] }}</span>
                                    </div>
                                    @endif
                                    
                                    @if(isset($station['hours']))
                                    <div class="detail-row">
                                        <i class="fas fa-clock"></i>
                                        <span>{{ $station['hours'] }}</span>
                                    </div>
                                    @endif
                                    
                                    @if(isset($station['services']))
                                    <div class="detail-row">
                                        <i class="fas fa-tools"></i>
                                        <span>{{ implode(', ', $station['services']) }}</span>
                                    </div>
                                    @endif
                                </div>
                                
                                <div class="station-actions">
                                    @if(isset($station['phone']))
                                    <a href="tel:{{ $station['phone'] }}" class="action-btn primary">
                                        <i class="fas fa-phone"></i> Call
                                    </a>
                                    @endif
                                    
                                    @if(isset($station['location']))
                                    <a href="https://maps.google.com/?q={{ urlencode($station['location']) }}" 
                                       target="_blank" 
                                       class="action-btn secondary">
                                        <i class="fas fa-directions"></i> Directions
                                    </a>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-search"></i>
                            <h3>No Stations Found</h3>
                            <p>No service stations found for "{{ $searchLocation }}". Try searching for a different location.</p>
                        </div>
                    @endif
                @else
                    <div class="empty-state">
                        <i class="fas fa-map-marker-alt"></i>
                        <h3>Search for Service Stations</h3>
                        <p>Enter a location above to find nearby Gaaubesi service stations, or click on a popular location.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function searchLocation(location) {
    document.getElementById('location').value = location;
    document.querySelector('.search-form').submit();
}
</script>
@endpush




