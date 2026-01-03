<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order #{{ $order->order_number }} - Export</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
        }
        .order-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .order-details, .customer-details {
            flex: 1;
        }
        .order-details h3, .customer-details h3 {
            color: #007bff;
            margin-top: 0;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th, .items-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .items-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .items-table tfoot td {
            font-weight: bold;
            background-color: #f8f9fa;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-confirmed { background-color: #d1ecf1; color: #0c5460; }
        .status-processing { background-color: #d4edda; color: #155724; }
        .status-shipped { background-color: #cce5ff; color: #004085; }
        .status-completed { background-color: #d4edda; color: #155724; }
        .status-cancelled { background-color: #f8d7da; color: #721c24; }
        .payment-paid { background-color: #d4edda; color: #155724; }
        .payment-unpaid { background-color: #f8d7da; color: #721c24; }
        .payment-refunded { background-color: #fff3cd; color: #856404; }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        @media print {
            body { margin: 0; padding: 15px; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Order #{{ $order->order_number }}</h1>
        <p>Generated on {{ now()->format('M j, Y \a\t g:i A') }}</p>
    </div>

    <div class="order-info">
        <div class="order-details">
            <h3>Order Information</h3>
            <p><strong>Order ID:</strong> {{ $order->order_number }}</p>
            <p><strong>Order Date:</strong> {{ $order->created_at->format('M j, Y \a\t g:i A') }}</p>
            <p><strong>Status:</strong> 
                <span class="status-badge status-{{ $order->status }}">
                    {{ ucfirst($order->status) }}
                </span>
            </p>
            <p><strong>Payment Status:</strong> 
                <span class="status-badge payment-{{ $order->payment_status }}">
                    {{ ucfirst($order->payment_status) }}
                </span>
            </p>
            <p><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method ?? 'N/A')) }}</p>
            @if($order->is_manual)
            <p><strong>Order Type:</strong> Manual Order</p>
            @endif
        </div>

        <div class="customer-details">
            <h3>Customer Information</h3>
            <p><strong>Name:</strong> {{ $order->user->name ?? 'Guest' }}</p>
            <p><strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}</p>
            <p><strong>Phone:</strong> {{ $order->user->phone ?? 'N/A' }}</p>
            @if($order->shipping_address || $order->receiver_name)
            <h4>Shipping Address</h4>
            @if($order->receiver_name)
            <p><strong>Receiver:</strong> {{ $order->receiver_name }}</p>
            @endif
            @if($order->receiver_phone)
            <p><strong>Phone:</strong> {{ $order->receiver_phone }}</p>
            @endif
            @if($order->shipping_address)
            <p>{{ $order->shipping_address }}</p>
            @endif
            @if($order->receiver_city)
            <p>{{ $order->receiver_city }}{{ $order->receiver_area ? ', ' . $order->receiver_area : '' }}</p>
            @endif
            @endif
        </div>
    </div>

    <h3>Order Items</h3>
    <table class="items-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
            <tr>
                <td>
                    <strong>{{ $item->product_name ?? $item->product->name ?? 'N/A' }}</strong>
                    @if($item->product && $item->product->sku)
                        <br><small>SKU: {{ $item->product->sku }}</small>
                    @endif
                </td>
                <td>Rs. {{ number_format($item->price, 2) }}</td>
                <td>{{ $item->quantity }}</td>
                <td>Rs. {{ number_format($item->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: right;"><strong>Subtotal:</strong></td>
                <td><strong>Rs. {{ number_format($order->subtotal, 2) }}</strong></td>
            </tr>
            @if($order->tax > 0)
            <tr>
                <td colspan="3" style="text-align: right;">Tax:</td>
                <td>Rs. {{ number_format($order->tax, 2) }}</td>
            </tr>
            @endif
            @if($order->shipping > 0)
            <tr>
                <td colspan="3" style="text-align: right;">Shipping:</td>
                <td>Rs. {{ number_format($order->shipping, 2) }}</td>
            </tr>
            @endif
            <tr style="background-color: #f8f9fa;">
                <td colspan="3" style="text-align: right;"><strong>Total:</strong></td>
                <td><strong>Rs. {{ number_format($order->total, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>

    @if($order->notes)
    <h3>Order Notes</h3>
    <p>{{ $order->notes }}</p>
    @endif

    @if($order->delivery_instructions)
    <h3>Delivery Instructions</h3>
    <p>{{ $order->delivery_instructions }}</p>
    @endif

    <div class="footer">
        <p>This order was generated from {{ config('app.name', 'E-Manager') }} on {{ now()->format('M j, Y \a\t g:i A') }}</p>
        <p class="no-print">
            <button onclick="window.print()" style="background: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer;">Print Order</button>
            <button onclick="window.close()" style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; margin-left: 10px;">Close</button>
        </p>
    </div>
</body>
</html>


