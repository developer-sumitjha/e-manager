@php($plans = \App\Models\SubscriptionPlan::whereIn('slug',["standard","pro"]) ->where('is_active',true)->orderBy('sort_order')->get())
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pricing — Manila</title>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body{font-family:Inter,system-ui,sans-serif;margin:0;color:#0f172a;background:#fff}
        .container{max-width:1100px;margin:0 auto;padding:0 1rem}
        .header{position:sticky;top:0;background:#fff;border-bottom:1px solid rgba(15,23,42,.06)}
        .header .row{display:flex;align-items:center;justify-content:space-between;padding:1rem 0}
        .logo{font-weight:800;text-decoration:none;color:#111827}
        .grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:1.25rem;margin-top:2rem}
        .card{border:1px solid rgba(15,23,42,.08);border-radius:16px;padding:1.25rem}
        .title{font-size:2rem;font-weight:900;margin:2rem 0 .25rem}
        .badge{display:inline-block;background:#eef2ff;color:#4338ca;border-radius:999px;padding:.25rem .6rem;font-weight:700;font-size:.75rem}
        .price{font-size:2rem;font-weight:800;margin:.5rem 0}
        ul{list-style:none;padding:0;margin:1rem 0}
        li{margin:.4rem 0;display:flex;gap:.5rem;align-items:center}
        .btn{display:inline-flex;gap:.5rem;align-items:center;padding:.75rem 1rem;border-radius:10px;border:none;cursor:pointer;font-weight:700}
        .btn-primary{background:linear-gradient(135deg,#667eea,#764ba2);color:#fff}
        .muted{color:#64748b}
    </style>
    </head>
<body>
    <header class="header">
        <div class="container">
            <div class="row">
                <a class="logo" href="/">Manila.</a>
                <nav>
                    <a href="{{ route('public.services') }}" class="muted">Services</a>
                    <a href="{{ route('public.about') }}" class="muted" style="margin-left:1rem">About</a>
                    <a href="{{ route('public.contact') }}" class="muted" style="margin-left:1rem">Contact</a>
                </nav>
            </div>
        </div>
    </header>

    <main class="container">
        <h1 class="title">Simple, transparent pricing</h1>
        <div class="muted">All features included in every plan • 60‑day free trial for new admins</div>

        <div class="grid">
            @foreach($plans as $plan)
            <div class="card">
                <div class="badge">{{ ucfirst($plan->slug) }}</div>
                <h3 style="margin:.5rem 0 0">{{ $plan->name }}</h3>
                <div class="muted">{{ $plan->description }}</div>
                <div class="price">NPR {{ number_format($plan->price_monthly,0) }} <span class="muted" style="font-size:1rem">/ month</span></div>
                <div class="muted">or NPR {{ number_format($plan->price_yearly,0) }} / year</div>
                <ul>
                    <li><i class="fas fa-check" style="color:#10b981"></i> Orders: {{ $plan->max_orders_per_month >= 99999 ? 'Unlimited' : number_format($plan->max_orders_per_month) }}/mo</li>
                    <li><i class="fas fa-check" style="color:#10b981"></i> Users: {{ $plan->max_users >= 99999 ? 'Unlimited' : number_format($plan->max_users) }}</li>
                    <li><i class="fas fa-check" style="color:#10b981"></i> Storage: {{ number_format($plan->max_storage_gb) }} GB</li>
                    <li><i class="fas fa-check" style="color:#10b981"></i> All features included</li>
                    <li><i class="fas fa-check" style="color:#10b981"></i> 60‑day free trial</li>
                </ul>
                <a href="{{ route('public.signup') }}?plan_id={{ $plan->id }}" class="btn btn-primary"><i class="fas fa-rocket"></i> Start free trial</a>
            </div>
            @endforeach
        </div>
    </main>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pricing - E-Manager Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .pricing-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 4rem 0; text-align: center; }
        .pricing-card { background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); margin-bottom: 2rem; height: 100%; }
        .pricing-card.featured { border: 3px solid #8B5CF6; position: relative; }
        .featured-badge { position: absolute; top: -15px; right: 20px; background: #8B5CF6; color: white; padding: 0.5rem 1rem; border-radius: 50px; font-size: 0.85rem; }
        .price { font-size: 3rem; font-weight: 700; color: #1f2937; }
        .price small { font-size: 1rem; color: #6b7280; }
        .feature-list { list-style: none; padding: 0; }
        .feature-list li { padding: 0.5rem 0; }
        .feature-list i { color: #10b981; margin-right: 0.5rem; }
    </style>
</head>
<body>
    <div class="pricing-header">
        <div class="container">
            <h1>Simple, Transparent Pricing</h1>
            <p class="lead">Choose the perfect plan for your business</p>
            <p><strong>14-day free trial</strong> on all plans • No credit card required</p>
        </div>
    </div>

    <div class="container py-5">
        <div class="row">
            @foreach($plans as $plan)
            <div class="col-lg-3 col-md-6">
                <div class="pricing-card {{ $plan->is_featured ? 'featured' : '' }}">
                    @if($plan->is_featured)
                        <div class="featured-badge">
                            <i class="fas fa-star"></i> Most Popular
                        </div>
                    @endif
                    
                    <h3>{{ $plan->name }}</h3>
                    <p class="text-muted">{{ $plan->description }}</p>
                    
                    <div class="price">
                        Rs. {{ number_format($plan->price_monthly, 0) }}
                        <small>/month</small>
                    </div>
                    
                    @if($plan->price_yearly > 0 && $plan->getYearlyDiscount() > 0)
                        <p class="text-success">
                            <small>Save {{ $plan->getYearlyDiscount() }}% with yearly billing</small>
                        </p>
                    @endif

                    <hr>

                    <ul class="feature-list">
                        @foreach($plan->getFeaturesList() as $feature)
                            <li><i class="fas fa-check"></i> {{ $feature }}</li>
                        @endforeach
                    </ul>

                    <div class="d-grid mt-4">
                        <a href="{{ route('public.signup') }}?plan={{ $plan->id }}" class="btn btn-{{ $plan->is_featured ? 'primary' : 'outline-primary' }} btn-lg">
                            Get Started
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-5">
            <p class="lead">Need a custom plan? <a href="mailto:sales@emanager.com">Contact Sales</a></p>
        </div>
    </div>

    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; 2025 E-Manager. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>


