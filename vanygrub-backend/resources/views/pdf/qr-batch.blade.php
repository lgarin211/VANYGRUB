<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>VNY Store QR Batch - {{ $total_quantity }} QR Codes</title>
    <style>
        @page {
            margin: 10mm;
            size: A4 portrait;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            background: white;
        }
        
        .page {
            width: 190mm;
            height: 277mm;
            padding: 0;
            margin: 0 auto;
            page-break-after: always;
            position: relative;
        }
        
        .page:last-child {
            page-break-after: avoid;
        }
        
        .qr-container {
            display: table;
            width: 100%;
            height: 100%;
            border-collapse: separate;
            border-spacing: 5mm;
        }
        
        .qr-row {
            display: table-row;
            height: 50%;
        }
        
        .qr-item {
            display: table-cell;
            width: 50%;
            vertical-align: middle;
            text-align: center;
            border: 1px dashed #ccc;
            border-radius: 8px;
            background: #f8f9fa;
            padding: 5mm;
            box-sizing: border-box;
        }
        
        .qr-code {
            width: 70mm;
            height: 70mm;
            margin: 0 auto 3mm auto;
            display: block;
        }
        
        .brand-text {
            font-weight: bold;
            font-size: 12px;
            color: #dc2626;
            margin-bottom: 2mm;
            letter-spacing: 1px;
        }
        
        .token-text {
            font-size: 8px;
            color: #666;
            font-family: 'Courier New', monospace;
            margin-top: 2mm;
            word-break: break-all;
        }
        
        .instructions {
            font-size: 7px;
            color: #888;
            margin-top: 2mm;
            line-height: 1.2;
        }
        
        .header {
            text-align: center;
            margin-bottom: 5mm;
            padding-bottom: 3mm;
            border-bottom: 2px solid #dc2626;
        }
        
        .header h1 {
            font-size: 18px;
            color: #dc2626;
            margin: 0;
            font-weight: bold;
        }
        
        .header p {
            font-size: 10px;
            color: #666;
            margin: 1mm 0 0 0;
        }
        
        .footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #888;
            padding: 2mm 0;
            border-top: 1px solid #eee;
        }
        
        .cut-guide {
            position: absolute;
            border: 1px dashed #ccc;
            opacity: 0.5;
        }
        
        .cut-guide.vertical {
            width: 0;
            height: 100%;
            left: 50%;
            top: 0;
        }
        
        .cut-guide.horizontal {
            width: 100%;
            height: 0;
            left: 0;
            top: 50%;
        }
    </style>
</head>
<body>
    @foreach($pages as $pageIndex => $qrCodes)
    <div class="page">
        <!-- Header only on first page -->
        @if($pageIndex === 0)
        <div class="header">
            <h1>VNY STORE - QR CODE BATCH</h1>
            <p>{{ $total_quantity }} QR Codes | Generated: {{ $generated_date }}</p>
        </div>
        @endif
        
        <!-- Cut guides -->
        <div class="cut-guide vertical"></div>
        <div class="cut-guide horizontal"></div>
        
        <!-- QR Codes Container -->
        <div class="qr-container">
            @for($row = 0; $row < 2; $row++)
            <div class="qr-row">
                @for($col = 0; $col < 2; $col++)
                    @php
                        $index = $row * 2 + $col;
                        $qrCode = $qrCodes[$index] ?? null;
                    @endphp
                    
                    <div class="qr-item">
                        @if($qrCode)
                            <img src="{{ $qrCode['qr_image'] }}" class="qr-code" alt="QR Code">
                            <div class="brand-text">VNY STORE REVIEW</div>
                            <div class="token-text">{{ $qrCode['token'] }}</div>
                            <div class="instructions">
                                Scan untuk memberikan review<br>
                                Tempel pada kemasan produk
                            </div>
                        @else
                            <!-- Empty space for incomplete pages -->
                            <div style="height: 70mm;"></div>
                        @endif
                    </div>
                @endfor
            </div>
            @endfor
        </div>
        
        <!-- Footer -->
        <div class="footer">
            Halaman {{ $pageIndex + 1 }} dari {{ count($pages) }} | 
            VNY Store Customer Review System | 
            {{ config('app.url') }}
        </div>
    </div>
    @endforeach
</body>
</html>