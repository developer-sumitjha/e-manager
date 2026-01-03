@extends('super-admin.layout')

@section('content')
<div class="container py-4">
    <h1 class="h4 mb-3"><i class="fas fa-paint-brush"></i> Public Site Builder</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('super.site-builder.public.update') }}" method="POST">
        @csrf
        <div class="row g-3">
            <div class="col-lg-7">
                <div class="card mb-3">
                    <div class="card-header fw-bold">Branding</div>
                    <div class="card-body">
                        <div class="mb-2">
                            <label class="form-label">Site Name</label>
                            <input name="branding[site_name]" class="form-control" value="{{ $settings['branding']['site_name'] ?? '' }}">
                        </div>
                        <div class="mb-2 d-flex gap-3 align-items-end">
                            <div>
                                <label class="form-label">Logo</label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="file" class="form-control" id="logoFile">
                                    <button class="btn btn-sm btn-outline-primary" type="button" onclick="uploadAsset('logo')">Upload</button>
                                </div>
                                <div class="small text-muted mt-1">PNG/SVG up to 2MB</div>
                            </div>
                            <img id="logoPreview" src="{{ $settings['branding']['logo_url'] ?? '' }}" style="height:40px" alt="">
                        </div>
                        <div class="mb-2 d-flex gap-3 align-items-end">
                            <div>
                                <label class="form-label">Favicon</label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="file" class="form-control" id="faviconFile">
                                    <button class="btn btn-sm btn-outline-primary" type="button" onclick="uploadAsset('favicon')">Upload</button>
                                </div>
                            </div>
                            <img id="faviconPreview" src="{{ $settings['branding']['favicon_url'] ?? '' }}" style="height:24px" alt="">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Primary Color</label>
                            <input name="branding[primary_color]" class="form-control" value="{{ $settings['branding']['primary_color'] ?? '#6c5ce7' }}">
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header fw-bold">Hero</div>
                    <div class="card-body">
                        <div class="mb-2">
                            <label class="form-label">Title</label>
                            <input name="hero[title]" class="form-control" value="{{ $settings['hero']['title'] ?? '' }}">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Subtitle</label>
                            <textarea name="hero[subtitle]" class="form-control" rows="2">{{ $settings['hero']['subtitle'] ?? '' }}</textarea>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label">CTA Text</label>
                                <input name="hero[cta_text]" class="form-control" value="{{ $settings['hero']['cta_text'] ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">CTA Link</label>
                                <input name="hero[cta_link]" class="form-control" value="{{ $settings['hero']['cta_link'] ?? '' }}">
                            </div>
                        </div>
                        <div class="mt-3 d-flex gap-3 align-items-end">
                            <div>
                                <label class="form-label">Hero Image</label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="file" class="form-control" id="heroFile">
                                    <button class="btn btn-sm btn-outline-primary" type="button" onclick="uploadAsset('hero')">Upload</button>
                                </div>
                                <div class="small text-muted mt-1">JPG/PNG up to 2MB</div>
                            </div>
                            <img id="heroPreview" src="{{ $settings['hero']['image_url'] ?? '' }}" style="height:64px;border-radius:6px" alt="">
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header fw-bold">Sections</div>
                    <div class="card-body row g-2">
                        @php($sec=$settings['sections'])
                        <div class="col-md-6 form-check"><input class="form-check-input" type="checkbox" id="s1" name="sections[show_about]" {{ ($sec['show_about']??true)?'checked':'' }}><label class="form-check-label" for="s1">About</label></div>
                        <div class="col-md-6 form-check"><input class="form-check-input" type="checkbox" id="s2" name="sections[show_features]" {{ ($sec['show_features']??true)?'checked':'' }}><label class="form-check-label" for="s2">Features</label></div>
                        <div class="col-md-6 form-check"><input class="form-check-input" type="checkbox" id="s3" name="sections[show_clients]" {{ ($sec['show_clients']??true)?'checked':'' }}><label class="form-check-label" for="s3">Clients</label></div>
                        <div class="col-md-6 form-check"><input class="form-check-input" type="checkbox" id="s4" name="sections[show_pricing]" {{ ($sec['show_pricing']??true)?'checked':'' }}><label class="form-check-label" for="s4">Pricing</label></div>
                        <div class="col-md-6 form-check"><input class="form-check-input" type="checkbox" id="s5" name="sections[show_contact]" {{ ($sec['show_contact']??true)?'checked':'' }}><label class="form-check-label" for="s5">Contact</label></div>
                    </div>
                </div>

                <button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> Save Settings</button>
            </div>

            <div class="col-lg-5">
                <div class="card mb-3">
                    <div class="card-header fw-bold">Live Preview (static)</div>
                    <div class="card-body">
                        <div class="p-3 rounded" style="background:linear-gradient(180deg,#f6f7fb,#fff);border:1px solid #eee">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="fw-bold">{{ $settings['branding']['site_name'] ?? 'E‑Manager' }}</div>
                                <div class="text-muted">Admin Login • Create Admin</div>
                            </div>
                            <h5 style="color:#111">{{ $settings['hero']['title'] ?? '' }}</h5>
                            <div class="text-muted mb-2">{{ $settings['hero']['subtitle'] ?? '' }}</div>
                            <a class="btn btn-sm btn-primary" style="background: {{ $settings['branding']['primary_color'] ?? '#6c5ce7' }}; border-color: transparent">{{ $settings['hero']['cta_text'] ?? 'Start free trial' }}</a>
                        </div>
                        <a class="btn btn-outline-secondary w-100 mt-3" href="{{ route('public.landing') }}" target="_blank">Open Landing</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<form id="uploadForm" action="{{ route('super.site-builder.public.upload') }}" method="POST" enctype="multipart/form-data" class="d-none">
    @csrf
    <input type="hidden" name="type" id="uploadType">
    <input type="file" name="file" id="uploadFile">
</form>

<script>
function uploadAsset(type){
    const map = { logo: 'logoFile', favicon: 'faviconFile', hero: 'heroFile' };
    const input = document.getElementById(map[type]);
    if(!input || !input.files.length){ alert('Choose a file first'); return; }
    const form = document.getElementById('uploadForm');
    document.getElementById('uploadType').value = type;
    const dyn = document.getElementById('uploadFile');
    dyn.files = input.files;
    const fd = new FormData(form);
    fetch(form.action, { method: 'POST', body: fd, headers:{ 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
        .then(r=>r.json()).then(d=>{
            if(d.success){
                if(type==='logo') document.getElementById('logoPreview').src = d.url;
                if(type==='favicon') document.getElementById('faviconPreview').src = d.url;
                if(type==='hero') document.getElementById('heroPreview').src = d.url;
            } else { alert('Upload failed'); }
        }).catch(()=>alert('Upload failed'))
}
</script>
@endsection





