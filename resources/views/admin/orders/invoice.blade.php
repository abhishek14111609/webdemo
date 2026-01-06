<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 14px;
            color: #333;
        }

        .header {
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #23284a;
        }

        .invoice-details {
            float: right;
            text-align: right;
        }

        .billing-info {
            margin-bottom: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f8f9fa;
        }

        .total-section {
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="logo">Eternal Diamonds</div>
        <div class="invoice-details">
            <strong>Invoice #{{ $order->order_number }}</strong><br>
            Date: {{ $order->created_at->format('M d, Y') }}
        </div>
    </div>

    <div class="billing-info">
        <strong>Billed To:</strong><br>
        {{ $order->name }}<br>
        {{ $order->address }}<br>
        {{ $order->city }}, {{ $order->state }} {{ $order->zip }}<br>
        {{ $order->country }}<br>
        Phone: {{ $order->phone }}<br>
        Email: {{ $order->email }}
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Item</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td>₹{{ number_format($item->price, 2) }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>₹{{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <p>Subtotal: ₹{{ number_format($order->subtotal, 2) }}</p>
        <p>Tax: ₹{{ number_format($order->tax, 2) }}</p>
        <h3>Total: ₹{{ number_format($order->total, 2) }}</h3>
    </div>
</body>

</html>