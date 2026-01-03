<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class OrderTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $order;
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

        // Create test order
        $this->order = Order::factory()->create([
            'user_id' => $this->user->id,
            'order_number' => 'ORD-001',
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'total' => 200.00,
            'subtotal' => 180.00,
            'tax' => 20.00,
            'shipping' => 0.00
        ]);

        // Create order items
        OrderItem::factory()->create([
            'order_id' => $this->order->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
            'price' => 100.00,
            'total' => 200.00
        ]);
    }

    /** @test */
    public function authenticated_user_can_view_orders_index()
    {
        $response = $this->actingAs($this->user)
            ->get(route('admin.orders.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.orders.index');
        $response->assertViewHas('orders');
        $response->assertSee('Orders');
    }

    /** @test */
    public function unauthenticated_user_redirected_to_login()
    {
        $response = $this->get(route('admin.orders.index'));
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function orders_index_shows_correct_data()
    {
        $response = $this->actingAs($this->user)
            ->get(route('admin.orders.index'));

        $response->assertStatus(200);
        $response->assertSee($this->order->order_number);
        $response->assertSee($this->user->name);
        $response->assertSee('Rs. ' . number_format($this->order->total, 2));
    }

    /** @test */
    public function orders_index_filters_by_status()
    {
        // Create orders with different statuses
        Order::factory()->create(['status' => 'confirmed']);
        Order::factory()->create(['status' => 'processing']);
        Order::factory()->create(['status' => 'cancelled']);

        $response = $this->actingAs($this->user)
            ->get(route('admin.orders.index', ['status' => 'confirmed']));

        $response->assertStatus(200);
        $response->assertViewHas('orders');
    }

    /** @test */
    public function orders_index_searches_by_order_number()
    {
        $response = $this->actingAs($this->user)
            ->get(route('admin.orders.index', ['search' => 'ORD-001']));

        $response->assertStatus(200);
        $response->assertSee('ORD-001');
    }

    /** @test */
    public function orders_index_searches_by_customer_name()
    {
        $response = $this->actingAs($this->user)
            ->get(route('admin.orders.index', ['search' => $this->user->name]));

        $response->assertStatus(200);
        $response->assertSee($this->user->name);
    }

    /** @test */
    public function authenticated_user_can_view_order_details()
    {
        $response = $this->actingAs($this->user)
            ->get(route('admin.orders.show', $this->order));

        $response->assertStatus(200);
        $response->assertViewIs('admin.orders.show');
        $response->assertViewHas('order');
        $response->assertSee($this->order->order_number);
        $response->assertSee($this->user->name);
        $response->assertSee('Rs. ' . number_format($this->order->total, 2));
    }

    /** @test */
    public function order_detail_shows_order_items()
    {
        $response = $this->actingAs($this->user)
            ->get(route('admin.orders.show', $this->order));

        $response->assertStatus(200);
        $response->assertSee($this->product->name);
        $response->assertSee('2'); // quantity
        $response->assertSee('Rs. ' . number_format($this->product->price, 2));
    }

    /** @test */
    public function authenticated_user_can_view_order_edit_form()
    {
        $response = $this->actingAs($this->user)
            ->get(route('admin.orders.edit', $this->order));

        $response->assertStatus(200);
        $response->assertViewIs('admin.orders.edit');
        $response->assertViewHas('order');
        $response->assertSee('Edit Order');
    }

    /** @test */
    public function authenticated_user_can_update_order()
    {
        $updateData = [
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'notes' => 'Updated order notes',
            'delivery_instructions' => 'Leave at front door'
        ];

        $response = $this->actingAs($this->user)
            ->put(route('admin.orders.update', $this->order), $updateData);

        $response->assertRedirect(route('admin.orders.show', $this->order));

        $this->assertDatabaseHas('orders', [
            'id' => $this->order->id,
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'notes' => 'Updated order notes',
            'delivery_instructions' => 'Leave at front door'
        ]);
    }

    /** @test */
    public function order_update_validates_required_fields()
    {
        $response = $this->actingAs($this->user)
            ->put(route('admin.orders.update', $this->order), []);

        $response->assertSessionHasErrors(['status', 'payment_status']);
    }

    /** @test */
    public function authenticated_user_can_change_order_status()
    {
        $response = $this->actingAs($this->user)
            ->put(route('admin.orders.update-status', $this->order), [
                'status' => 'confirmed',
                'payment_status' => 'paid'
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Order status updated successfully'
        ]);

        $this->assertDatabaseHas('orders', [
            'id' => $this->order->id,
            'status' => 'confirmed',
            'payment_status' => 'paid'
        ]);
    }

    /** @test */
    public function order_status_change_validates_status()
    {
        $response = $this->actingAs($this->user)
            ->put(route('admin.orders.update-status', $this->order), [
                'status' => 'invalid_status',
                'payment_status' => 'paid'
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['status']);
    }

    /** @test */
    public function authenticated_user_can_export_order()
    {
        $response = $this->actingAs($this->user)
            ->get(route('admin.orders.export', $this->order));

        $response->assertStatus(200);
        $response->assertViewIs('admin.orders.export');
        $response->assertSee($this->order->order_number);
    }

    /** @test */
    public function authenticated_user_can_perform_bulk_actions()
    {
        $order2 = Order::factory()->create(['status' => 'pending']);
        $order3 = Order::factory()->create(['status' => 'pending']);

        $response = $this->actingAs($this->user)
            ->post(route('admin.orders.bulk-action'), [
                'action' => 'confirm',
                'order_ids' => [$this->order->id, $order2->id, $order3->id]
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => '3 orders confirmed.'
        ]);

        $this->assertDatabaseHas('orders', [
            'id' => $this->order->id,
            'status' => 'confirmed'
        ]);
    }

    /** @test */
    public function bulk_action_validates_required_fields()
    {
        $response = $this->actingAs($this->user)
            ->post(route('admin.orders.bulk-action'), []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['action', 'order_ids']);
    }

    /** @test */
    public function authenticated_user_can_delete_order()
    {
        $response = $this->actingAs($this->user)
            ->delete(route('admin.orders.destroy', $this->order));

        $response->assertRedirect(route('admin.orders.index'));

        $this->assertDatabaseMissing('orders', [
            'id' => $this->order->id
        ]);
    }

    /** @test */
    public function order_deletion_removes_related_order_items()
    {
        $this->actingAs($this->user)
            ->delete(route('admin.orders.destroy', $this->order));

        $this->assertDatabaseMissing('order_items', [
            'order_id' => $this->order->id
        ]);
    }

    /** @test */
    public function orders_index_pagination_works()
    {
        // Create additional orders
        Order::factory()->count(15)->create();

        $response = $this->actingAs($this->user)
            ->get(route('admin.orders.index'));

        $response->assertStatus(200);
        $response->assertViewHas('orders');
        
        // Check if pagination is present
        $response->assertSee('pagination');
    }

    /** @test */
    public function orders_index_filters_by_payment_status()
    {
        Order::factory()->create(['payment_status' => 'paid']);
        Order::factory()->create(['payment_status' => 'unpaid']);

        $response = $this->actingAs($this->user)
            ->get(route('admin.orders.index', ['payment_status' => 'paid']));

        $response->assertStatus(200);
        $response->assertViewHas('orders');
    }

    /** @test */
    public function orders_index_filters_by_date_range()
    {
        $fromDate = now()->subDays(7)->format('Y-m-d');
        $toDate = now()->format('Y-m-d');

        $response = $this->actingAs($this->user)
            ->get(route('admin.orders.index', [
                'date_from' => $fromDate,
                'date_to' => $toDate
            ]));

        $response->assertStatus(200);
        $response->assertViewHas('orders');
    }

    /** @test */
    public function order_creation_requires_authentication()
    {
        $response = $this->get(route('admin.orders.create'));
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function authenticated_user_can_view_order_creation_form()
    {
        $response = $this->actingAs($this->user)
            ->get(route('admin.orders.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.orders.create');
        $response->assertSee('Create New Order');
    }
}
