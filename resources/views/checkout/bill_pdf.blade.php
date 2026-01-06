<h2>Order Bill</h2>
<p>Order Number: {{ $order->order_number }}</p>
<p>Name: {{ $order->name }}</p>
<p>Email: {{ $order->email }}</p>
<p>Phone: {{ $order->phone }}</p>
<p>Address: {{ $order->address }}, {{ $order->country }}</p>
<hr>
<table width="100%" border="1" cellspacing="0" cellpadding="5">
    <tr>
        <th>Product</th>
        <th>Price</th>
        <th>Qty</th>
        <th>Subtotal</th>
    </tr>
    @foreach($order->items as $item)
    <tr>
        <td>{{ $item->product_name }}</td>
        <td>₹{{ number_format($item->price,2) }}</td>
        <td>{{ $item->quantity }}</td>
        <td>₹{{ number_format($item->subtotal,2) }}</td>
    </tr>
    @endforeach
</table>
<hr>
<p>Subtotal: ₹{{ number_format($order->subtotal,2) }}</p>
<p>Tax: ₹{{ number_format($order->tax,2) }}</p>
<p><strong>Total: ₹{{ number_format($order->total,2) }}</strong></p>
