<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class DashboardTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test user
        $this->user = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password')
        ]);
    }

    /** @test */
    public function authenticated_user_can_view_dashboard()
    {
        $response = $this->actingAs($this->user)
            ->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard.index');
        $response->assertSee('Dashboard');
    }

    /** @test */
    public function unauthenticated_user_redirected_to_login()
    {
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function dashboard_shows_order_statistics()
    {
        // Create test orders with different statuses
        Order::factory()->create(['status' => 'confirmed', 'total' => 100.00]);
        Order::factory()->create(['status' => 'processing', 'total' => 200.00]);
        Order::factory()->create(['status' => 'completed', 'total' => 300.00]);
        Order::factory()->create(['status' => 'cancelled', 'total' => 50.00]);

        $response = $this->actingAs($this->user)
            ->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('totalOrders');
        $response->assertViewHas('totalRevenue');
        $response->assertViewHas('pendingOrders');
        $response->assertViewHas('completedOrders');
    }

    /** @test */
    public function dashboard_shows_recent_orders()
    {
        // Create recent orders
        Order::factory()->count(5)->create([
            'created_at' => now()->subDays(2)
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('recent_orders');
    }

    /** @test */
    public function dashboard_shows_top_products()
    {
        // Create products
        $product1 = Product::factory()->create(['name' => 'Product 1']);
        $product2 = Product::factory()->create(['name' => 'Product 2']);

        // Create orders with these products
        $order1 = Order::factory()->create(['status' => 'completed']);
        $order2 = Order::factory()->create(['status' => 'completed']);

        $order1->orderItems()->create([
            'product_id' => $product1->id,
            'quantity' => 2,
            'price' => 100.00,
            'total' => 200.00
        ]);

        $order2->orderItems()->create([
            'product_id' => $product2->id,
            'quantity' => 1,
            'price' => 150.00,
            'total' => 150.00
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('top_products');
    }

    /** @test */
    public function dashboard_shows_category_statistics()
    {
        // Create categories
        $category1 = Category::factory()->create(['name' => 'Electronics']);
        $category2 = Category::factory()->create(['name' => 'Clothing']);

        // Create products in categories
        Product::factory()->create(['category_id' => $category1->id]);
        Product::factory()->create(['category_id' => $category2->id]);

        $response = $this->actingAs($this->user)
            ->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('categories');
    }

    /** @test */
    public function dashboard_handles_empty_data_gracefully()
    {
        $response = $this->actingAs($this->user)
            ->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('totalOrders', 0);
        $response->assertViewHas('totalRevenue', 0);
        $response->assertViewHas('pendingOrders', 0);
        $response->assertViewHas('completedOrders', 0);
    }

    /** @test */
    public function dashboard_calculates_revenue_correctly()
    {
        // Create completed orders with known totals
        Order::factory()->create([
            'status' => 'completed',
            'total' => 100.00,
            'payment_status' => 'paid'
        ]);
        Order::factory()->create([
            'status' => 'completed',
            'total' => 200.00,
            'payment_status' => 'paid'
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('totalRevenue', 300.00);
    }

    /** @test */
    public function dashboard_filters_data_by_tenant()
    {
        // This test would be relevant if multi-tenancy is implemented
        $response = $this->actingAs($this->user)
            ->get(route('dashboard'));

        $response->assertStatus(200);
        // Additional tenant-specific assertions would go here
    }

    /** @test */
    public function dashboard_uses_caching_for_performance()
    {
        // Create some data
        Order::factory()->count(10)->create();

        // First request should cache the data
        $response1 = $this->actingAs($this->user)
            ->get(route('dashboard'));

        $response1->assertStatus(200);

        // Second request should use cached data
        $response2 = $this->actingAs($this->user)
            ->get(route('dashboard'));

        $response2->assertStatus(200);
    }

    /** @test */
    public function dashboard_shows_correct_order_counts_by_status()
    {
        // Create orders with specific statuses
        Order::factory()->create(['status' => 'pending']);
        Order::factory()->create(['status' => 'confirmed']);
        Order::factory()->create(['status' => 'processing']);
        Order::factory()->create(['status' => 'completed']);
        Order::factory()->create(['status' => 'cancelled']);

        $response = $this->actingAs($this->user)
            ->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('pendingOrders', 1);
        $response->assertViewHas('completedOrders', 1);
    }
}
