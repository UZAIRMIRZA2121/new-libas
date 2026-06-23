<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice: {{ $order->order_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 40px;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            color: #4f46e5;
        }
        .details-grid {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
        }
        .details-col h3 {
            margin-top: 0;
            color: #555;
            text-transform: uppercase;
            font-size: 14px;
        }
        .details-col p {
            margin: 5px 0;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th {
            background: #f8fafc;
            border-bottom: 2px solid #eee;
            text-align: left;
            padding: 10px;
        }
        .items-table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .text-right {
            text-align: right;
        }
        .totals {
            width: 300px;
            margin-left: auto;
        }
        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
        }
        .totals-row.grand-total {
            border-top: 2px solid #eee;
            margin-top: 10px;
            padding-top: 10px;
            font-weight: bold;
            font-size: 1.2em;
        }
        @media print {
            body { padding: 0; }
            .invoice-box { box-shadow: none; border: none; }
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="header">
            <div>
                <h1>INVOICE</h1>
                <p>Order: <strong>{{ $order->order_number }}</strong><br>Date: {{ $order->created_at->format('M d, Y') }}</p>
            </div>
            <div class="text-right">
                <h2 style="margin:0;">New Libas</h2>
                <p>Wear Everywhere<br>support@newlibas.com</p>
            </div>
        </div>

        <div class="details-grid">
            <div class="details-col">
                <h3>Billed To (Customer Details)</h3>
                <p><strong>{{ $order->first_name }} {{ $order->last_name }}</strong></p>
                <p>{{ $order->email }}</p>
                <p>{{ $order->phone }}</p>
            </div>
            <div class="details-col text-right">
                <h3>Shipping Details</h3>
                <p>{{ $order->address }}</p>
                @if($order->apartment)<p>{{ $order->apartment }}</p>@endif
                <p>{{ $order->city }}, {{ $order->postal_code }}</p>
                
                <h3 style="margin-top: 20px;">Payment Information</h3>
                <p>Method: <strong>{{ $order->payment_method == 'cod' ? 'Cash on Delivery' : 'Credit/Debit Card' }}</strong></p>
                <p>Status: <strong style="text-transform: capitalize;">{{ str_replace('_', ' ', $order->status) }}</strong></p>
            </div>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Item Description</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        <strong>{{ $item->product_name }}</strong>
                        @if($item->color)<br><small>Color: {{ $item->color }}</small>@endif
                    </td>
                    <td>Rs.{{ number_format($item->price, 2) }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td class="text-right">Rs.{{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <div class="totals-row">
                <span>Subtotal</span>
                <span>Rs.{{ number_format($order->subtotal, 2) }}</span>
            </div>
            <div class="totals-row">
                <span>Shipping</span>
                <span>{{ $order->shipping == 0 ? 'Free' : 'Rs.' . number_format($order->shipping, 2) }}</span>
            </div>
            <div class="totals-row grand-total">
                <span>Total Amount</span>
                <span>Rs.{{ number_format($order->total, 2) }}</span>
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
