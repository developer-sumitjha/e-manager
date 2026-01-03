<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @php($site = optional(App\Models\Setting::where('key','public_site_settings')->first()))
        @php($data = $site->value ? json_decode($site->value, true) : [])

        <title>E‑Manager Nepal — SaaS Business Suite</title>
        <meta name="description" content="Nepal’s modern business management suite: inventory, orders, deliveries, accounting, analytics and more — offered as SaaS.">
        <meta property="og:title" content="{{ data_get($data,'branding.site_name','E‑Manager Nepal — SaaS Business Suite') }}">
        <meta property="og:description" content="Run your business on one modern dashboard. Built in Nepal, for Nepal.">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url('/') }}">
        <meta property="og:image" content="{{ asset('favicon.ico') }}">
        <link rel="icon" href="{{ asset('favicon.ico') }}">

        <!-- Fonts & Icons -->
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Styles: Completely new theme -->
        <style>
            :root{--ink:#0b1220;--ink2:#233041;--brand:#6c5ce7;--brand2:#a855f7;--paper:#f8fafc;--card:#ffffff;--crimson:#d0102a;--deepblue:#003893;--saffron:#f59e0b}
            *{box-sizing:border-box;margin:0;padding:0}
            html{scroll-behavior:smooth}
            body{font-family:Inter,system-ui,sans-serif;color:var(--ink);background:linear-gradient(180deg,#f6f7fb 0%,#ffffff 60%,#f9fbff 100%)}
            .container{max-width:1180px;margin:0 auto;padding:0 1rem}
            .header{position:fixed;inset:0 0 auto 0;background:rgba(255,255,255,.75);backdrop-filter:saturate(160%) blur(16px);border-bottom:1px solid rgba(2,8,23,.06);z-index:1000;transition:box-shadow .2s ease}
            .header.is-scrolled{box-shadow:0 8px 24px rgba(2,8,23,.08)}
            .row{display:flex;align-items:center;justify-content:space-between;padding:1rem 0}
            .brand{display:flex;align-items:center;gap:.5rem;font-weight:900;color:var(--ink);text-decoration:none}
            .brand i{color:var(--brand)}
            .nav{display:flex;gap:1.2rem}
            .nav a{color:#394456;text-decoration:none;font-weight:600;padding:.55rem .9rem;border-radius:999px}
            .nav a:hover{background:rgba(108,92,231,.08)}
            .actions{display:flex;gap:.6rem}
            .btn{display:inline-flex;align-items:center;gap:.5rem;padding:.7rem 1.1rem;border-radius:999px;border:1px solid rgba(99,102,241,.25);text-decoration:none;font-weight:700;cursor:pointer;transition:transform .15s ease, box-shadow .15s ease}
            .btn-outline{background:rgba(255,255,255,.7);color:#4338ca}
            .btn-primary{background:linear-gradient(135deg,var(--brand),var(--brand2));color:#fff;border-color:transparent;box-shadow:0 8px 24px rgba(108,92,231,.25)}
            .btn:hover{transform:translateY(-1px);box-shadow:0 8px 20px rgba(2,132,199,.18)}
            .btn:focus-visible{outline:3px solid #0ea5e9;outline-offset:2px}
            .btn-flag{background:linear-gradient(135deg,var(--crimson),var(--deepblue));color:#fff;border-color:rgba(255,255,255,.25)}
            .hero{padding:9.5rem 0 4rem;position:relative;overflow:hidden}
            .orb{position:absolute;inset:-120px -180px auto -180px;width:720px;height:720px;border-radius:9999px;background:radial-gradient(circle at 30% 30%, rgba(124,58,237,.25), rgba(59,130,246,.18) 35%, rgba(236,72,153,.12) 60%, transparent 70%);filter:blur(6px)}
            .grid2{display:grid;grid-template-columns:1.05fr .95fr;gap:2rem;align-items:center}
            .title{font-size:clamp(2.6rem,5vw,4rem);font-weight:900;letter-spacing:-.035em;line-height:1.08;background:linear-gradient(135deg,#0f172a 10%,#4338ca 55%,#a855f7 100%);-webkit-background-clip:text;background-clip:text;color:transparent;text-shadow:0 10px 30px rgba(108,92,231,.18)}
            .kicker{display:inline-block;background:rgba(99,102,241,.12);border:1px solid rgba(99,102,241,.25);color:#4338ca;padding:.28rem .7rem;border-radius:999px;font-weight:700}
            .lead{color:#4b5563;margin:1rem 0 1.25rem;font-size:clamp(1rem,2.2vw,1.15rem);letter-spacing:.01em}
            .hero-cta{display:flex;gap:.6rem;flex-wrap:wrap}
            .input{border:1px solid #e5e7eb;border-radius:12px;padding:.75rem 1rem;min-width:260px;box-shadow:inset 0 1px 0 rgba(255,255,255,.6);background:rgba(255,255,255,.9)}
            .mock{background:linear-gradient(135deg,#ffffff,#f8faff);border:1px solid rgba(2,8,23,.06);border-radius:22px;box-shadow:0 30px 80px rgba(2,8,23,.12), inset 0 1px 0 rgba(255,255,255,.6);min-height:360px;position:relative;overflow:hidden}
            .mock:before{content:"";position:absolute;inset:-20% -30% auto -30%;height:220px;background:radial-gradient(closest-side,rgba(108,92,231,.25),transparent);filter:blur(24px);animation:float 6s ease-in-out infinite alternate}
            @keyframes float{from{transform:translateY(0)}to{transform:translateY(-16px)}}
            .section{padding:4rem 0}
            /* minimal sections for classic one-page */
            .features{display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:1.1rem}
            .card{background:var(--card);border:1px solid rgba(2,8,23,.06);border-radius:18px;padding:1.35rem;box-shadow:0 10px 30px rgba(2,8,23,.06)}
            .card h2,.card h3{letter-spacing:-.01em}
            .clients{display:flex;gap:2rem;flex-wrap:wrap;justify-content:center;opacity:.8}
            .pricing{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:1.1rem}
            .price{font-size:2rem;font-weight:900}
            .muted{color:#64748b}
            .footer{background:#0b1220;color:#cbd5e1;padding:2rem 0}
            .footer .cta{display:flex;justify-content:center;margin-bottom:1rem}
            @media (max-width: 900px){.grid2{grid-template-columns:1fr}}

            /* Prayer flags separator */
            .flags{display:flex;justify-content:center;gap:.5rem;filter:drop-shadow(0 4px 8px rgba(0,0,0,.08));padding:1.2rem 0}
            .flag{width:26px;height:18px;border-radius:3px}
            .flag.red{background:var(--crimson)}
            .flag.blue{background:var(--deepblue)}
            .flag.saffron{background:var(--saffron)}
            .swoop{height:24px;background:linear-gradient(90deg,rgba(0,0,0,0) 0%, rgba(208,16,42,.12) 15%, rgba(0,56,147,.12) 50%, rgba(245,158,11,.12) 85%, rgba(0,0,0,0) 100%)}

            /* Reveal animations */
            .reveal{opacity:0;transform:translateY(14px);animation:reveal .8s ease forwards}
            .reveal.delay-1{animation-delay:.15s}
            .reveal.delay-2{animation-delay:.3s}
            @keyframes reveal{to{opacity:1;transform:none}}

            /* Reduced motion */
            @media (prefers-reduced-motion: reduce){
                html{scroll-behavior:auto}
                .reveal{opacity:1;transform:none;animation:none}
                .mock:before{animation:none}
                .btn{transition:none}
            }

            /* Typography polish */
            .nav a{letter-spacing:.01em}
            .btn-primary{letter-spacing:.01em;text-shadow:0 6px 18px rgba(108,92,231,.35)}
            .hero .lead{text-shadow:0 1px 0 rgba(255,255,255,.6)}
            .section h2{font-size:clamp(1.5rem,3.5vw,2rem)!important;font-weight:800;letter-spacing:-.02em;background:linear-gradient(90deg,#111827,#4338ca 60%,#a855f7);-webkit-background-clip:text;background-clip:text;color:transparent;text-align:center}
            .section h2::after{content:"";display:block;width:72px;height:4px;border-radius:999px;background:linear-gradient(90deg,#6c5ce7,#a855f7);margin:.6rem auto 0;opacity:.8}
            .features .card p.lead{line-height:1.75}
            .features .card p.lead::before{content:"\201C";color:#a855f7;margin-right:.25rem}
            .features .card p.lead::after{content:"\201D";color:#6c5ce7;margin-left:.25rem}

            /* Dark feature band & testimonials */
            .band{background:#0f172a;color:#e5e7eb;padding:3rem 0;position:relative}
            .band .container{display:grid;grid-template-columns:1.1fr .9fr;gap:2rem;align-items:center}
            .band h3{font-size:clamp(1.25rem,2.5vw,1.6rem);font-weight:800;margin-bottom:.5rem}
            .band p{color:#94a3b8}
            .band-list{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
            .band .item{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.06);border-radius:14px;padding:1rem}
            .testimonial{background:#0b1220;color:#e2e8f0;padding:3rem 0}
            .tcard{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.06);border-radius:16px;padding:1.25rem}
            .tstars{color:#fde047}

            /* Pricing cards polish */
            .pricing .card{border-radius:20px}
            .pricing .card .btn{align-self:flex-start}
            </style>
    </head>
    <body>
        <header class="header">
            <div class="container">
                <div class="row">
                    @php($pub = optional(App\Models\Setting::where('key','public_site_settings')->first()))
                    @php($data = $pub->value ? json_decode($pub->value,true) : [])
                    <a class="brand" href="/"><i class="fas fa-mountain"></i> {{ $data['branding']['site_name'] ?? 'E‑Manager Nepal' }}</a>
                    <nav class="nav" aria-label="Primary">
                        <a href="#about">About</a>
                        <a href="#suite">Suite</a>
                        <a href="#pricing">Pricing</a>
                        <a href="#contact">Contact</a>
                </nav>
                    <div class="actions">
                        <a class="btn btn-outline" href="{{ route('vendor.login') }}"><i class="fas fa-right-to-bracket"></i> Admin Login</a>
                        <a class="btn btn-primary" href="{{ route('vendor.register') }}"><i class="fas fa-user-plus"></i> Create Admin</a>
                    </div>
                </div>
            </div>
        </header>

        <main>
            <!-- HERO -->
            <section class="hero" id="home">
                <div class="orb" aria-hidden="true"></div>
                <div class="container grid2">
                    <div>
                        <span class="kicker reveal">SaaS for Nepali Businesses</span>
                        <h1 class="title reveal delay-1">{{ data_get($data,'hero.title','Run your business on one modern dashboard') }}</h1>
                        <p class="lead reveal delay-2">{{ data_get($data,'hero.subtitle','Inventory, orders, deliveries, accounting and analytics — all stitched together with delightful UX and real‑time insights. Built in Nepal, for Nepal.') }}</p>
                        <form class="hero-cta" action="{{ route('public.signup') }}" method="get" aria-label="Start free trial">
                            <input class="input" name="email" placeholder="Work email" aria-label="Work email" inputmode="email">
                            <a class="btn btn-primary" href="{{ data_get($data,'hero.cta_link', route('public.signup')) }}" aria-label="Start free trial"><i class="fas fa-rocket" aria-hidden="true"></i> {{ data_get($data,'hero.cta_text','Start free trial') }}</a>
                        </form>
                    </div>
                    <div class="mock" aria-label="dashboard preview">
                        <img src="{{ asset('storage/hero-admin-dashboard.jpg') }}" alt="Admin dashboard preview" loading="lazy" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:.96">
                    </div>
                </div>
            </section>

            <!-- Sub Hero Cards (newsletter + features list) -->
            <section class="section" style="padding-top:0">
                <div class="container" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                    <div class="pill-card pill-purple">
                        <div style="font-weight:800;margin-bottom:.25rem">Always Update Every Day</div>
                        <div class="muted">Get platform news and tips in your inbox.</div>
                        <form class="email-pill" action="{{ route('public.signup') }}" method="get">
                            <input class="input" name="email" placeholder="Your Email" aria-label="Your Email" inputmode="email">
                            <button class="btn btn-outline" type="submit">Request</button>
                        </form>
                    </div>
                    <div class="pill-card">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;align-items:center">
                            <ul class="muted" style="list-style:none;padding:0;margin:0;line-height:2">
                                <li><i class="fas fa-check tick"></i> Online Payment</li>
                                <li><i class="fas fa-check tick"></i> Platform Support</li>
                                <li><i class="fas fa-check tick"></i> Secure Transaction</li>
                                <li><i class="fas fa-check tick"></i> Responsive Web App</li>
                            </ul>
                            <div>
                                <div style="font-weight:800;margin-bottom:.25rem">How can I help you?</div>
                                <div class="muted">Explore features, pricing and more from our public site.</div>
                                <a href="#features" class="btn" style="margin-top:.5rem;border-color:rgba(2,8,23,.1)">Read More →</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Stats Row -->
            <section class="section" style="padding-top:1rem">
                <div class="container stats">
                    <div class="stat"><div class="muted">Years of Experience</div><div class="price" style="font-size:1.6rem">25+</div></div>
                    <div class="stat"><div class="muted">Total Transaction</div><div class="price" style="font-size:1.6rem">3,452+</div></div>
                    <div class="stat"><div class="muted">Active User</div><div class="price" style="font-size:1.6rem">751+</div></div>
                    <div class="stat"><div class="muted">Positive Reviews</div><div class="price" style="font-size:1.6rem">592+</div></div>
                </div>
            </section>

            <div class="flags" aria-hidden="true">
                <span class="flag red"></span>
                <span class="flag blue"></span>
                <span class="flag saffron"></span>
                <span class="flag red"></span>
                <span class="flag blue"></span>
                <span class="flag saffron"></span>
            </div>
            <div class="swoop" aria-hidden="true"></div>

            <!-- ABOUT -->
            <section class="section" id="about">
                <div class="container grid2">
                    <div class="card reveal">
                        <h2 style="margin-bottom:.5rem">Made for Nepali SMEs</h2>
                        <p class="lead">Whether you sell fashion, electronics or groceries, E‑Manager helps you manage stock, process orders, coordinate deliveries and stay on top of finances.</p>
                        <ul style="line-height:1.9;color:#334155;margin-top:.5rem">
                            <li>• Multi‑tenant isolation for each company</li>
                            <li>• Employee roles & granular permissions</li>
                            <li>• Manual delivery workflow with COD settlements</li>
                    </ul>
                    </div>
                    <div class="card reveal delay-1" style="overflow:hidden">
                        <h2 style="margin-bottom:.5rem">Why choose us</h2>
                        <p class="lead">Fast setup, local support, and predictable pricing. Scale from your first 100 orders to thousands without changing tools.</p>
                        <div class="clients" style="margin-top:1rem">
                            <img src="https://dummyimage.com/110x36/0b1220/ffffff&text=Sajilo" alt="client">
                            <img src="https://dummyimage.com/110x36/0b1220/ffffff&text=Gaadi" alt="client">
                            <img src="https://dummyimage.com/110x36/0b1220/ffffff&text=Kirana" alt="client">
                        </div>
                        <img src="https://images.unsplash.com/photo-1545045739-7f5b759bb3fb?q=80&w=1200&auto=format&fit=crop" alt="Kathmandu" style="width:100%;height:160px;object-fit:cover;border-radius:12px;margin-top:1rem">
                    </div>
                </div>
            </section>

            <!-- SUITE -->
            <section class="section" id="suite" style="background:var(--paper)">
                <div class="container">
                    <h2 style="text-align:center;margin-bottom:1.5rem">Everything you need in one suite</h2>
                    <div class="features">
                        <div class="card reveal"><h3><i class="fas fa-box"></i> Inventory</h3><p class="muted">Real‑time stock, low‑stock alerts, bulk updates.</p></div>
                        <div class="card reveal delay-1"><h3><i class="fas fa-bag-shopping"></i> Orders</h3><p class="muted">Fast order processing with statuses & notes.</p></div>
                        <div class="card reveal delay-2"><h3><i class="fas fa-motorcycle"></i> Delivery</h3><p class="muted">Manual riders, route status and COD collection.</p></div>
                        <div class="card reveal"><h3><i class="fas fa-calculator"></i> Accounting</h3><p class="muted">Invoices, payments and expense tracking.</p></div>
                        <div class="card reveal delay-1"><h3><i class="fas fa-chart-line"></i> Analytics</h3><p class="muted">KPIs and export — know your business at a glance.</p></div>
                        <div class="card reveal delay-2"><h3><i class="fas fa-palette"></i> Site Builder</h3><p class="muted">Launch a storefront with themes and blocks.</p></div>
                    </div>
                </div>
            </section>

            <!-- Nepal highlight -->
            <section class="section" style="padding-top:2rem">
                <div class="container" style="text-align:center">
                    <div class="reveal" style="font-weight:900;font-size:1.25rem;color:var(--deepblue)"><i class="fa-solid fa-flag"></i> Proudly built in Nepal</div>
                    <svg viewBox="0 0 1200 140" aria-hidden="true" style="width:100%;margin-top:1rem">
                        <defs>
                            <linearGradient id="np1" x1="0" x2="1">
                                <stop offset="0%" stop-color="#003893"/>
                                <stop offset="100%" stop-color="#d0102a"/>
                            </linearGradient>
                        </defs>
                        <path d="M0,100 C200,20 400,140 600,70 C800,0 1000,120 1200,60 L1200,140 L0,140 Z" fill="url(#np1)" opacity=".15"/>
                        <path d="M0,110 C240,60 420,100 640,60 C860,20 1040,90 1200,70" fill="none" stroke="#003893" stroke-opacity=".3" stroke-width="2"/>
                    </svg>
                </div>
            </section>

            <!-- PRICING -->
            <section class="section" id="pricing">
                <div class="container">
                    <h2 style="text-align:center;margin-bottom:1.25rem">Straightforward pricing</h2>
                    @php($plans = \App\Models\SubscriptionPlan::whereIn('slug',["standard","pro"]) ->where('is_active',true)->orderBy('sort_order')->get())
                    <div class="pricing">
                        @foreach($plans as $plan)
                        <div class="card reveal" style="display:flex;flex-direction:column;gap:.5rem">
                            <div class="muted" style="text-transform:uppercase;font-weight:800">{{ $plan->name }}</div>
                            <div class="price">NPR {{ number_format($plan->price_monthly,0) }} <span class="muted" style="font-size:1rem">/ mo</span></div>
                            <div class="muted">or NPR {{ number_format($plan->price_yearly,0) }} / yr</div>
                            <ul class="muted" style="line-height:1.9;margin:.5rem 0">
                                <li>Orders: {{ $plan->max_orders_per_month >= 99999 ? 'Unlimited' : number_format($plan->max_orders_per_month) }}/mo</li>
                                <li>Users: {{ $plan->max_users >= 99999 ? 'Unlimited' : number_format($plan->max_users) }}</li>
                                <li>Storage: {{ number_format($plan->max_storage_gb) }} GB</li>
                                <li>60‑day free trial</li>
                            </ul>
                            <a class="btn btn-primary" href="{{ route('public.signup') }}?plan_id={{ $plan->id }}">Start free trial</a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <!-- CONTACT -->
            <section class="section" id="contact" style="background:var(--paper)">
                <div class="container grid2">
                    <div>
                        <h2>Talk to our team</h2>
                        <p class="lead">We’re based in Nepal and ready to help you onboard smoothly.</p>
                        <p class="muted">Email: support@emanager.local<br>Phone: +977‑98XXXXXXX</p>
                    </div>
                    <form class="card reveal" action="{{ route('public.signup') }}" method="get">
                        <input class="input" name="name" placeholder="Full name" style="margin-bottom:.6rem">
                        <input class="input" name="email" placeholder="Work email" style="margin-bottom:.6rem">
                        <textarea class="input" name="message" placeholder="How can we help?" style="height:120px"></textarea>
                        <button class="btn btn-primary" type="submit" style="margin-top:.6rem">Send</button>
                    </form>
                </div>
            </section>

            <!-- Testimonials -->
            <section class="section" style="padding-top:2rem">
                <div class="container">
                    <h2 style="text-align:center;margin-bottom:1rem">What businesses in Nepal say</h2>
                    <div class="features">
                        <div class="card reveal">
                            <div style="display:flex;gap:.75rem;align-items:center;margin-bottom:.5rem">
                                <img src="https://images.unsplash.com/photo-1599566150163-29194dcaad36?q=80&w=200&auto=format&fit=crop" alt="Prakash" style="width:44px;height:44px;border-radius:999px;object-fit:cover">
                                <div><strong>Prakash Shrestha</strong><div class="muted">Owner, Sajilo Kirana</div></div>
                            </div>
                            <p class="lead">“Inventory and COD settlements finally feel easy. Local support is a big plus.”</p>
                        </div>
                        <div class="card reveal delay-1">
                            <div style="display:flex;gap:.75rem;align-items:center;margin-bottom:.5rem">
                                <img src="https://images.unsplash.com/photo-1531123897727-8f129e1688ce?q=80&w=200&auto=format&fit=crop" alt="Sangita" style="width:44px;height:44px;border-radius:999px;object-fit:cover">
                                <div><strong>Sangita Gurung</strong><div class="muted">Manager, Everest Fashion</div></div>
                            </div>
                            <p class="lead">“Beautiful UI, fast reports and the site builder saved us weeks.”</p>
                        </div>
                        <div class="card reveal delay-2">
                            <div style="display:flex;gap:.75rem;align-items:center;margin-bottom:.5rem">
                                <img src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?q=80&w=200&auto=format&fit=crop" alt="Rabin" style="width:44px;height:44px;border-radius:999px;object-fit:cover">
                                <div><strong>Rabin Khadka</strong><div class="muted">Director, Gaadi Auto</div></div>
                            </div>
                            <p class="lead">“From 100 to 2,000+ orders a month without changing tools.”</p>
                        </div>
                    </div>
                </div>
            </section>
            </main>

        <!-- Dark Feature Band -->
        <section class="band">
            <div class="container">
                <div>
                    <h3>Make quick business growth</h3>
                    <p>Dashboard tools to move faster: orders, deliveries, accounting and analytics in one place.</p>
                    <div class="band-list" style="margin-top:1rem">
                        <div class="item"><strong>Publishing</strong><div>Share, collaborate and publish updates for your team.</div></div>
                        <div class="item"><strong>Analytics</strong><div>Track performance and identify growth hotspots.</div></div>
                        <div class="item"><strong>Engagement</strong><div>Delight customers and keep your audience close.</div></div>
                        <div class="item"><strong>Security</strong><div>Best practices for data safety and privacy.</div></div>
                    </div>
                </div>
                <div>
                    <div class="tcard">
                        <div class="tstars">★★★★★</div>
                        <p style="margin:.5rem 0 0">“All our operations now run from a single dashboard. It’s fast and reliable.”</p>
                        <div class="muted" style="margin-top:.5rem">— A growing Nepali retailer</div>
                    </div>
                </div>
            </div>
        </section>

        <footer class="footer">
            <div class="container">
                <div class="cta">
                    <a class="btn btn-outline btn-flag" href="{{ route('super.login') }}"><i class="fas fa-crown"></i> Super Admin Login</a>
                </div>
                <div style="text-align:center">&copy; {{ date('Y') }} E‑Manager Nepal. All rights reserved.</div>
        </div>
        </footer>
    </body>
</html>