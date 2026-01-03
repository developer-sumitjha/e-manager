<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Manager - Business Management Platform for Nepal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .hero { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 6rem 0; }
        .hero h1 { font-size: 3.5rem; font-weight: 700; margin-bottom: 1.5rem; }
        .hero p { font-size: 1.3rem; margin-bottom: 2rem; }
        .cta-btn { padding: 1rem 2.5rem; font-size: 1.1rem; font-weight: 600; border-radius: 50px; }
        .feature-card { background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); margin-bottom: 2rem; text-align: center; }
        .feature-card i { font-size: 3rem; color: #8B5CF6; margin-bottom: 1rem; }
        .pricing-card { background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); margin-bottom: 2rem; }
        .pricing-card.featured { border: 3px solid #8B5CF6; }
        .price { font-size: 2.5rem; font-weight: 700; color: #1f2937; }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero">
        <div class="container text-center">
            <h1>Powerful Business Management for Nepal</h1>
            <p>Complete order management, inventory, delivery & accounting system</p>
            <div class="d-flex gap-3 justify-content-center">
                <a href="{{ route('public.signup') }}" class="btn btn-light cta-btn">Start Free Trial</a>
                <a href="{{ route('public.pricing') }}" class="btn btn-outline-light cta-btn">View Pricing</a>
            </div>
            <p class="mt-3"><small>14-day free trial • No credit card required</small></p>
        </div>
    </section>

    <!-- Features -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Everything You Need to Run Your Business</h2>
            <div class="row">
                <div class="col-md-4"><div class="feature-card"><i class="fas fa-shopping-cart"></i><h4>Order Management</h4><p>Track orders from creation to delivery</p></div></div>
                <div class="col-md-4"><div class="feature-card"><i class="fas fa-boxes"></i><h4>Inventory Control</h4><p>Real-time stock tracking</p></div></div>
                <div class="col-md-4"><div class="feature-card"><i class="fas fa-truck"></i><h4>Delivery System</h4><p>Manual & logistics integration</p></div></div>
                <div class="col-md-4"><div class="feature-card"><i class="fas fa-calculator"></i><h4>Accounting</h4><p>Complete financial management</p></div></div>
                <div class="col-md-4"><div class="feature-card"><i class="fas fa-chart-line"></i><h4>Analytics</h4><p>Business insights & reports</p></div></div>
                <div class="col-md-4"><div class="feature-card"><i class="fas fa-users"></i><h4>Multi-User</h4><p>Team collaboration</p></div></div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="bg-light py-5">
        <div class="container text-center">
            <h2>Ready to Get Started?</h2>
            <p class="lead">Join hundreds of businesses in Nepal</p>
            <a href="{{ route('public.signup') }}" class="btn btn-primary btn-lg">Start Your Free Trial</a>
        </div>
    </section>

    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p class="mb-0">&copy; 2025 E-Manager. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>







