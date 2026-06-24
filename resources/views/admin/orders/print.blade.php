<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt: {{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            color: #000;
            line-height: 1.2;
            margin: 0;
            padding: 0;
            background: #fff;
        }
        .receipt-box {
            width: 80mm;
            max-width: 100%;
            margin: 0 auto;
            padding: 5mm;
            font-size: 15px;
        }
        .text-center {
            text-align: center;
        }
        .text-left {
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        .bold {
            font-weight: bold;
        }
        h2 {
            margin: 0 0 5px 0;
            font-size: 24px;
        }
        p {
            margin: 2px 0;
        }
        .divider {
            border-bottom: 1px dashed #000;
            margin: 8px 0;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
        }
        .items-table th {
            border-bottom: 1px dashed #000;
            padding-bottom: 5px;
            font-weight: bold;
        }
        .items-table td {
            padding: 4px 0;
            vertical-align: top;
        }
        .item-name {
            display: block;
            margin-bottom: 2px;
        }
        .totals-grid {
            display: flex;
            justify-content: space-between;
            margin-top: 2px;
        }
        @media print {
            body { margin: 0; padding: 0; }
            @page {
                margin: 0;
                size: 80mm auto;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-box">
        <div class="text-center">
            <h2>NEW LIBAS</h2>
            <p>Wear Everywhere</p>
            <p>support@newlibas.com</p>
            <div class="divider"></div>
            <h3 style="margin: 5px 0; font-size: 18px;">SALES RECEIPT</h3>
            <p>Order: <strong>{{ $order->order_number }}</strong></p>
            <p>Date: {{ $order->created_at->format('M d, Y h:i A') }}</p>
        </div>

        <div class="divider"></div>

        <div class="text-left">
            <p class="bold">Billed To:</p>
            <p>{{ $order->first_name }} {{ $order->last_name }}</p>
            <p>{{ $order->phone }}</p>
            <p>{{ $order->address }}</p>
            @if($order->apartment)<p>{{ $order->apartment }}</p>@endif
            <p>{{ $order->city }}, {{ $order->postal_code }}</p>
        </div>

        <div class="divider"></div>

        <table class="items-table">
            <thead>
                <tr>
                    <th class="text-left">Item</th>
                    <th class="text-center">Qty</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td class="text-left" style="width: 60%;">
                        <span class="item-name">{{ $item->product_name }}</span>
                        @if($item->color)<small>Color: {{ $item->color }}</small><br>@endif
                        <small>@ Rs.{{ number_format($item->price, 2) }}</small>
                    </td>
                    <td class="text-center" style="width: 15%;">{{ $item->quantity }}</td>
                    <td class="text-right" style="width: 25%;">Rs.{{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="divider"></div>

        <div class="totals-grid">
            <span>Subtotal:</span>
            <span>Rs.{{ number_format($order->subtotal, 2) }}</span>
        </div>
        <div class="totals-grid">
            <span>Shipping:</span>
            <span>{{ $order->shipping == 0 ? 'Free' : 'Rs.' . number_format($order->shipping, 2) }}</span>
        </div>
        @if($order->discount_amount > 0)
        <div class="totals-grid">
            <span>Discount @if($order->coupon_code)<small>({{ $order->coupon_code }})</small>@endif:</span>
            <span>-Rs.{{ number_format($order->discount_amount, 2) }}</span>
        </div>
        @endif
        <div class="divider"></div>
        <div class="totals-grid bold" style="font-size: 18px;">
            <span>TOTAL:</span>
            <span>Rs.{{ number_format($order->total, 2) }}</span>
        </div>

        <div class="divider"></div>
        
        <div class="text-center">
            <p>Payment: {{ $order->payment_method == 'cod' ? 'Cash on Delivery' : 'Card' }}</p>
            <p style="margin-top: 10px; font-size: 16px;" class="bold">THANK YOU FOR YOUR PURCHASE!</p>
            <p style="font-size: 12px; margin-top: 5px;">* Please keep this receipt for your records *</p>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
