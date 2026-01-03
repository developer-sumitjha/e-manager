<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class PendingOrderTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $pendingOrder;
    protected $product;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test user
        $this->user = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password')
        ]);

        // Create test product
        $this->product = Product::factory()->create([
            'name' => 'Test Product',
            'price' => 100.00,
            'is_active' => true
        ]);

        // Create test pending order
        $this->pendingOrder = Order::factory()->create([
            'user_id' => $this->user->id,
            'order_number' => 'PND-001',
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'total' => 200.00,
            'is_manual' => true,
            'receiver_name' => 'John Doe',
            'receiver_phone' => '1234567890',
            'receiver_city' => 'Kathmandu',
            'receiver_area' => 'Thamel'
        ]);

        // Create order items
        OrderItem::factory()->create([
            'order_id' => $this->pendingOrder->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
            'price' => 100.00,
            'total' => 200.00
        ]);
    }

    /** @test */
    public function authenticated_user_can_view_pending_orders_index()
    {
        $response = $this->actingAs($this->user)
            ->get(route('admin.pending-orders.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.pending-orders.index');
        $response->assertViewHas('orders');
        $response->assertSee('Pending Orders');
    }

    /** @test */
    public function unauthenticated_user_redirected_to_login()
    {
        $response = $this->get(route('admin.pending-orders.index'));
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function pending_orders_index_shows_correct_data()
    {
        $response = $this->actingAs($this->user)
            ->get(route('admin.pending-orders.index'));

        $response->assertStatus(200);
        $response->assertSee($this->pendingOrder->order_number);
        $response->assertSee($this->pendingOrder->receiver_name);
        $response->assertSee('Rs. ' . number_format($this->pendingOrder->total, 2));
    }

    /** @test */
    public function pending_orders_index_filters_manual_orders()
    {
        // Create non-manual order
        Order::factory()->create([
            'status' => 'pending',
            'is_manual' => false
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('admin.pending-orders.index'));

        $response->assertStatus(200);
        $response->assertViewHas('orders');
        
        // Should only show manual orders
        $orders = $response->viewData('orders');
        $this->assertTrue($orders->every(function ($order) {
            return $order->is_manual === true;
        }));
    }

    /** @test */
    public function pending_orders_index_searches_by_order_number()
    {
        $response = $this->actingAs($this->user)
            ->get(route('admin.pending-orders.index', ['search' => 'PND-001']));

        $response->assertStatus(200);
        $response->assertSee('PND-001');
    }

    /** @test */
    public function pending_orders_index_searches_by_customer_name()
    {
        $response = $this->actingAs($this->user)
            ->get(route('admin.pending-orders.index', ['search' => 'John Doe']));

        $response->assertStatus(200);
        $response->assertSee('John Doe');
    }

    /** @test */
    public function authenticated_user_can_view_pending_order_details()
    {
        $response = $this->actingAs($this->user)
            ->get(route('admin.pending-orders.show', $this->pendingOrder));

        $response->assertStatus(200);
        $response->assertViewIs('admin.pending-orders.show');
        $response->assertViewHas('order');
        $response->assertSee($this->pendingOrder->order_number);
        $response->assertSee($this->pendingOrder->receiver_name);
    }

    /** @test */
    public function authenticated_user_can_confirm_pending_order()
    {
        $response = $this->actingAs($this->user)
            ->post(route('admin.pending-orders.confirm', $this->pendingOrder));

        $response->assertRedirect(route('admin.pending-orders.index'));

        $this->assertDatabaseHas('orders', [
            'id' => $this->pendingOrder->id,
            'status' => 'confirmed'
        ]);
    }

    /** @test */
    public function authenticated_user_can_reject_pending_order()
    {
        $response = $this->actingAs($this->user)
            ->post(route('admin.pending-orders.reject', $this->pendingOrder));

        $response->assertRedirect(route('admin.pending-orders.index'));

        $this->assertDatabaseHas('orders', [
            'id' => $this->pendingOrder->id,
            'status' => 'rejected'
        ]);
    }

    /** @test */
    public function authenticated_user_can_view_pending_order_creation_form()
    {
        $response = $this->actingAs($this->user)
            ->get(route('admin.pending-orders.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.pending-orders.create');
        $response->assertSee('Create Manual Order');
    }

    /** @test */
    public function authenticated_user_can_create_manual_order()
    {
        $orderData = [
            'customer_name' => 'Jane Doe',
            'customer_phone' => '9876543210',
            'receiver_city' => 'Pokhara',
            'receiver_area' => 'Lakeside',
            'shipping_address' => '123 Test Street, Lakeside, Pokhara',
            'payment_method' => 'cod',
            'total_amount' => 300.00,
            'product_ids' => [$this->product->id],
            'quantities' => [3],
            'notes' => 'Test manual order',
            'delivery_branch' => 'POKHARA',
            'package_access' => 'Can Open',
            'delivery_type' => 'Pickup'
        ];

        $response = $this->actingAs($this->user)
            ->post(route('admin.pending-orders.store'), $orderData);

        $response->assertRedirect(route('admin.pending-orders.index'));

        $this->assertDatabaseHas('orders', [
            'receiver_name' => 'Jane Doe',
            'receiver_phone' => '9876543210',
            'receiver_city' => 'Pokhara',
            'status' => 'pending',
            'is_manual' => true
        ]);
    }

    /** @test */
    public function manual_order_creation_validates_required_fields()
    {
        $response = $this->actingAs($this->user)
            ->post(route('admin.pending-orders.store'), []);

        $response->assertSessionHasErrors([
            'customer_name',
            'customer_phone',
            'receiver_city',
            'receiver_area',
            'shipping_address',
            'payment_method',
            'total_amount',
            'product_ids',
            'quantities'
        ]);
    }

    /** @test */
    public function manual_order_creation_creates_guest_user()
    {
        $orderData = [
            'customer_name' => 'New Customer',
            'customer_phone' => '5555555555',
            'receiver_city' => 'Kathmandu',
            'receiver_area' => 'Baneshwor',
            'shipping_address' => '456 Test Avenue',
            'payment_method' => 'cod',
            'total_amount' => 150.00,
            'product_ids' => [$this->product->id],
            'quantities' => [1]
        ];

        $this->actingAs($this->user)
            ->post(route('admin.pending-orders.store'), $orderData);

        $this->assertDatabaseHas('users', [
            'phone' => '5555555555',
            'name' => 'New Customer'
        ]);
    }

    /** @test */
    public function manual_order_creation_creates_order_items()
    {
        $orderData = [
            'customer_name' => 'Test Customer',
            'customer_phone' => '6666666666',
            'receiver_city' => 'Lalitpur',
            'receiver_area' => 'Patan',
            'shipping_address' => '789 Test Road',
            'payment_method' => 'cod',
            'total_amount' => 200.00,
            'product_ids' => [$this->product->id],
            'quantities' => [2]
        ];

        $this->actingAs($this->user)
            ->post(route('admin.pending-orders.store'), $orderData);

        $this->assertDatabaseHas('order_items', [
            'product_id' => $this->product->id,
            'quantity' => 2,
            'price' => $this->product->price
        ]);
    }

    /** @test */
    public function authenticated_user_can_perform_bulk_actions_on_pending_orders()
    {
        $order2 = Order::factory()->create([
            'status' => 'pending',
            'is_manual' => true
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('admin.pending-orders.bulk-action'), [
                'action' => 'confirm',
                'order_ids' => [$this->pendingOrder->id, $order2->id]
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true
        ]);

        $this->assertDatabaseHas('orders', [
            'id' => $this->pendingOrder->id,
            'status' => 'confirmed'
        ]);
    }

    /** @test */
    public function pending_orders_index_shows_statistics()
    {
        $response = $this->actingAs($this->user)
            ->get(route('admin.pending-orders.index'));

        $response->assertStatus(200);
        $response->assertViewHas('totalPendingOrders');
        $response->assertViewHas('manualOrdersCount');
    }

    /** @test */
    public function rejected_orders_are_listed_separately()
    {
        $rejectedOrder = Order::factory()->create([
            'status' => 'rejected',
            'is_manual' => true
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('admin.rejected-orders.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.pending-orders.index');
        $response->assertSee($rejectedOrder->order_number);
    }
}
