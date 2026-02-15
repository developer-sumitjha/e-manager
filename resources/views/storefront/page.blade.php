@extends('layouts.storefront')

@section('title', ($page->meta_title ?? $page->title) . ' — ' . $tenant->business_name)
@section('meta_description', $page->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($page->description ?? $page->content), 155))
@section('meta_keywords', $page->meta_keywords ?? '')
@section('og_title', $page->title)
@section('og_description', $page->meta_description ?? \Illuminate\Support\Str::limit(strip_tags($page->description ?? $page->content), 155))
@section('og_image', $page->banner_image_url ?? $settings->logo_url ?? '')
@section('og_url', url()->current())

@push('styles')
@if($page->custom_css)
<style>
{!! $page->custom_css !!}
</style>
@endif
@endpush

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ \App\Helpers\StorefrontHelper::route('storefront.preview', [$tenant->subdomain]) }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $page->title }}</li>
        </ol>
    </nav>

    <!-- Page Banner -->
    @if($page->banner_image_url)
    <div class="page-banner mb-4">
        <img src="{{ $page->banner_image_url }}" alt="{{ $page->title }}" class="img-fluid rounded">
    </div>
    @endif

    <!-- Page Header -->
    <div class="page-header mb-4">
        <h1 class="page-title">{{ $page->title }}</h1>
        @if($page->description)
        <p class="page-description text-muted">{{ $page->description }}</p>
        @endif
    </div>

    <!-- Page Content -->
    <div class="page-content">
        @if($page->template === 'full-width')
        <div class="row">
            <div class="col-12">
                <div class="page-body">
                    {!! $page->content !!}
                </div>
            </div>
        </div>
        @elseif($page->template === 'sidebar')
        <div class="row">
            <div class="col-md-8">
                <div class="page-body">
                    {!! $page->content !!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="page-sidebar">
                    <!-- Sidebar content can be added here -->
                    @if($page->page_type === 'contact')
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Contact Information</h5>
                            @if($page->contact_email)
                            <p class="mb-2">
                                <i class="fas fa-envelope"></i> 
                                <a href="mailto:{{ $page->contact_email }}">{{ $page->contact_email }}</a>
                            </p>
                            @endif
                            @if($page->contact_phone)
                            <p class="mb-2">
                                <i class="fas fa-phone"></i> 
                                <a href="tel:{{ $page->contact_phone }}">{{ $page->contact_phone }}</a>
                            </p>
                            @endif
                            @if($page->contact_address)
                            <p class="mb-0">
                                <i class="fas fa-map-marker-alt"></i> {{ $page->contact_address }}
                            </p>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @else
        <!-- Default template -->
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="page-body">
                    {!! $page->content !!}
                </div>
            </div>
        </div>
        @endif

        <!-- Contact Page Specific Content -->
        @if($page->page_type === 'contact')
        <div class="row mt-4">
            <div class="col-lg-10 mx-auto">
                @if($page->map_iframe)
                <div class="contact-map mb-4">
                    <div class="ratio ratio-16x9">
                        {!! $page->map_iframe !!}
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- About Page Specific Content - Team Members -->
        @if($page->page_type === 'about' && $page->team_members && count($page->team_members) > 0)
        <div class="row mt-5">
            <div class="col-12">
                <div class="team-section">
                    <h2 class="section-title text-center mb-4">Our Team</h2>
                    <div class="row g-4">
                        @foreach($page->team_members as $member)
                        <div class="col-md-6 col-lg-4">
                            <div class="team-member-card">
                                @if(isset($member['photo']) && $member['photo'])
                                <div class="team-member-photo">
                                    <img src="{{ asset('storage/' . $member['photo']) }}" alt="{{ $member['name'] ?? 'Team Member' }}" class="img-fluid">
                                </div>
                                @else
                                <div class="team-member-photo">
                                    <div class="team-member-placeholder">
                                        <i class="fas fa-user"></i>
                                    </div>
                                </div>
                                @endif
                                <div class="team-member-info">
                                    <h4 class="team-member-name">{{ $member['name'] ?? '' }}</h4>
                                    @if(isset($member['position']) && $member['position'])
                                    <p class="team-member-position">{{ $member['position'] }}</p>
                                    @endif
                                    @if(isset($member['bio']) && $member['bio'])
                                    <p class="team-member-bio">{{ $member['bio'] }}</p>
                                    @endif
                                    <div class="team-member-contact">
                                        @if(isset($member['email']) && $member['email'])
                                        <a href="mailto:{{ $member['email'] }}" class="team-contact-link" title="Email">
                                            <i class="fas fa-envelope"></i>
                                        </a>
                                        @endif
                                        @if(isset($member['phone']) && $member['phone'])
                                        <a href="tel:{{ $member['phone'] }}" class="team-contact-link" title="Phone">
                                            <i class="fas fa-phone"></i>
                                        </a>
                                        @endif
                                    </div>
                                    <div class="team-member-social">
                                        @if(isset($member['facebook']) && $member['facebook'])
                                        <a href="{{ $member['facebook'] }}" target="_blank" rel="noopener" class="social-link" title="Facebook">
                                            <i class="fab fa-facebook-f"></i>
                                        </a>
                                        @endif
                                        @if(isset($member['twitter']) && $member['twitter'])
                                        <a href="{{ $member['twitter'] }}" target="_blank" rel="noopener" class="social-link" title="Twitter">
                                            <i class="fab fa-twitter"></i>
                                        </a>
                                        @endif
                                        @if(isset($member['linkedin']) && $member['linkedin'])
                                        <a href="{{ $member['linkedin'] }}" target="_blank" rel="noopener" class="social-link" title="LinkedIn">
                                            <i class="fab fa-linkedin-in"></i>
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
@if($page->custom_js)
<script>
{!! $page->custom_js !!}
</script>
@endif
@endpush

@push('styles')
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
@endpush
