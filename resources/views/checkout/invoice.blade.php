<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->id }}</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; color:#222; }
        .invoice-box { max-width: 800px; margin:auto; padding:30px; border:1px solid #eee; }
        .company { text-align: right; }
        .logo { font-size: 24px; font-weight:800; color:#d4af37; }
        table { width:100%; border-collapse: collapse; margin-top:20px; }
        table th, table td { padding:8px; border:1px solid #ddd; text-align:left; }
        .total-row td { border-top:2px solid #000; font-weight:700; }
        .small { font-size:12px; color:#666; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div style="display:flex; justify-content:space-between;">
            <div>
                <div class="logo">Eternal Diamonds</div>
                <div class="small">123 Diamond St.<br>City, State<br>GSTIN: 12ABCDE3456F7Z8</div>
            </div>
            <div class="company">
                <div><strong>Invoice</strong></div>
                <div>Invoice #: {{ $order->id }}</div>
                <div>Date: {{ $order->created_at->format('d M Y') }}</div>
            </div>
        </div>

        <hr>

        <div style="display:flex; justify-content:space-between; margin-top:10px;">
            <div>
                <strong>Bill To:</strong><br>
                {{ $order->shipping_name }}<br>
                {{ $order->shipping_address }}<br>
                {{ $order->shipping_country }}<br>
                <span class="small">Phone: {{ $order->shipping_phone }} | Email: {{ $order->shipping_email }}</span>
            </div>

            <div>
                <strong>Payment:</strong><br>
                Payment ID: {{ $order->razorpay_payment_id }}<br>
                Status: {{ ucfirst($order->status) }}
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Price (₹)</th>
                    <th>Subtotal (₹)</th>
                </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $item->product ? $item->product->name : 'Product #' . $item->product_id }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price, 2) }}</td>
                    <td>{{ number_format($item->subtotal, 2) }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="4" style="text-align:right">Total (₹)</td>
                    <td>₹{{ number_format($order->amount, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <p class="small" style="margin-top:12px;">
            This is a computer generated invoice and does not require signature.
        </p>
    </div>
</body>
</html>
