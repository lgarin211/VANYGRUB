<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->order_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
        }
        
        .container {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            border-bottom: 3px solid #dc2626;
            padding-bottom: 20px;
        }
        
        .logo h1 {
            color: #dc2626;
            font-size: 2.5em;
            font-weight: bold;
        }
        
        .invoice-info {
            text-align: right;
        }
        
        .invoice-info h2 {
            color: #333;
            margin-bottom: 10px;
        }
        
        .billing-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }
        
        .bill-to h3 {
            color: #dc2626;
            margin-bottom: 15px;
            font-size: 1.2em;
        }
        
        .customer-details, .order-details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #dc2626;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        .items-table th,
        .items-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        
        .items-table th {
            background: #dc2626;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9em;
        }
        
        .items-table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .font-semibold {
            font-weight: 600;
        }
        
        .total-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #dee2e6;
        }
        
        .total-row:last-child {
            border-bottom: 2px solid #dc2626;
            padding-top: 15px;
            margin-top: 10px;
            font-weight: bold;
            font-size: 1.1em;
        }
        
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-processing { background: #dbeafe; color: #1e40af; }
        .status-shipped { background: #cffafe; color: #0f766e; }
        .status-delivered { background: #dcfce7; color: #166534; }
        .status-cancelled { background: #fecaca; color: #b91c1c; }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #666;
            border-top: 1px solid #dee2e6;
            padding-top: 20px;
        }
        
        .print-only {
            display: none;
        }
        
        @media print {
            body {
                background: white;
            }
            
            .container {
                box-shadow: none;
                margin: 0;
            }
            
            .print-only {
                display: block;
            }
            
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="logo">
                <h1>VNY STORE</h1>
                <p style="color: #666; margin-top: 5px;">Premium Sneakers Collection</p>
            </div>
            <div class="invoice-info">
                <h2>INVOICE</h2>
                <p><strong>{{ $order->order_number }}</strong></p>
                <p>Tanggal: {{ $order->created_at->format('d M Y') }}</p>
                <span class="status-badge status-{{ $order->status }}">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
        </div>

        <!-- Billing Information -->
        <div class="billing-info">
            <div class="bill-to">
                <h3>Bill To:</h3>
                <div class="customer-details">
                    <p><strong>{{ $order->customer_name }}</strong></p>
                    <p>{{ $order->customer_email }}</p>
                    <p>{{ $order->phone }}</p>
                    <br>
                    <p><strong>Alamat Pengiriman:</strong></p>
                    <p>{{ $order->shipping_address }}</p>
                </div>
            </div>
            
            <div class="bill-to">
                <h3>Order Details:</h3>
                <div class="order-details">
                    <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
                    <p><strong>Order Date:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
                    <p><strong>Status:</strong> 
                        <span class="status-badge status-{{ $order->status }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </p>
                    @if($order->notes)
                    <br>
                    <p><strong>Notes:</strong></p>
                    <p>{{ $order->notes }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th class="text-center">Qty</th>
                    <th class="text-right">Harga Satuan</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        <div class="font-semibold">{{ $item->product->name ?? 'Product tidak ditemukan' }}</div>
                        @if($item->product)
                            <div style="color: #666; font-size: 0.9em;">SKU: {{ $item->product->sku ?? '-' }}</div>
                        @endif
                    </td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="text-right font-semibold">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Total Section -->
        <div style="display: flex; justify-content: flex-end;">
            <div style="width: 300px;">
                <div class="total-section">
                    <div class="total-row">
                        <span>Subtotal:</span>
                        <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    
                    @if($order->discount_amount > 0)
                    <div class="total-row">
                        <span>Diskon:</span>
                        <span>- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    
                    <div class="total-row">
                        <span><strong>TOTAL:</strong></span>
                        <span><strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Terima kasih atas pesanan Anda!</strong></p>
            <p>Jika ada pertanyaan, hubungi kami di: <strong>admin@vnystore.com</strong> | <strong>+62 821-1142-4592</strong></p>
            <div class="print-only" style="margin-top: 20px;">
                <p style="font-size: 0.9em; color: #666;">
                    Invoice ini dicetak otomatis pada {{ now()->format('d M Y H:i') }}
                </p>
            </div>
        </div>
    </div>

    <script>
        // Auto print when opened
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        }
    </script>
</body>
</html>