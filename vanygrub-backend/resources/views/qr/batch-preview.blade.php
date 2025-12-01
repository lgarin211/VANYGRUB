<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VNY Store QR Batch - {{ $total_quantity }} QR Codes</title>
    <style>
        @page {
            size: {{ $paper_size === 'a3' ? 'A3' : 'A4' }} portrait;
            margin: 10mm;
        }

        @media print {
            body { margin: 0; }
            .no-print { display: none !important; }
            .page-break { page-break-after: always; }
            .page-break:last-child { page-break-after: auto; }
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: white;
            color: #333;
        }

        .container {
            width: 100%;
            max-width: {{ $paper_size === 'a3' ? '297mm' : '210mm' }};
            margin: 0 auto;
            padding: {{ $paper_size === 'a3' ? '8mm' : '10mm' }};
            box-sizing: border-box;
        }

        .print-controls {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 2px solid #dc2626;
        }

        .print-btn {
            background: #dc2626;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            margin-right: 10px;
        }

        .print-btn:hover {
            background: #b91c1c;
        }

        .back-btn {
            background: #6b7280;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
        }

        .page {
            width: {{ $paper_size === 'a3' ? '297mm' : '190mm' }};
            min-height: {{ $paper_size === 'a3' ? '420mm' : '277mm' }};
            margin: 0 auto 20px auto;
            position: relative;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .page-header {
            text-align: center;
            margin-bottom: 15mm;
            padding-bottom: 5mm;
            border-bottom: 2px solid #dc2626;
        }

        .page-header h1 {
            font-size: 18px;
            color: #dc2626;
            margin: 0 0 5px 0;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .page-header p {
            font-size: 10px;
            color: #666;
            margin: 0;
        }

        .qr-grid {
            display: grid;
            grid-template-columns: {{ $paper_size === 'a3' ? '1fr 1fr 1fr 1fr' : '1fr 1fr' }};
            grid-template-rows: {{ $paper_size === 'a3' ? '1fr 1fr 1fr' : '1fr 1fr' }};
            gap: {{ $paper_size === 'a3' ? '2mm' : '5mm' }};
            height: {{ $paper_size === 'a3' ? '380mm' : '240mm' }};
            padding: 0;
        }

        .qr-item {
            border: 1px dashed #ccc;
            border-radius: 8px;
            background: #f8f9fa;
            padding: {{ $paper_size === 'a3' ? '2mm' : '5mm' }};
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            box-sizing: border-box;
        }

        .qr-code {
            width: {{ $paper_size === 'a3' ? '58mm' : '70mm' }};
            height: {{ $paper_size === 'a3' ? '58mm' : '70mm' }};
            margin-bottom: 3mm;
        }

        .qr-code svg {
            width: 100%;
            height: 100%;
        }

        .brand-text {
            font-weight: bold;
            font-size: {{ $paper_size === 'a3' ? '9px' : '11px' }};
            color: #dc2626;
            margin-bottom: 2mm;
            letter-spacing: 1px;
        }

        .token-text {
            font-size: {{ $paper_size === 'a3' ? '7px' : '8px' }};
            color: #666;
            font-family: 'Courier New', monospace;
            margin-bottom: 2mm;
            word-break: break-all;
        }

        .instructions {
            font-size: {{ $paper_size === 'a3' ? '6px' : '7px' }};
            color: #888;
            line-height: 1.2;
        }

        .cut-guides {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
        }

        .cut-guide-v {
            position: absolute;
            left: 50%;
            top: 0;
            bottom: 0;
            border-left: 1px dashed #ccc;
            opacity: 0.5;
        }

        .cut-guide-h {
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            border-top: 1px dashed #ccc;
            opacity: 0.5;
        }

        .footer {
            position: absolute;
            bottom: 5mm;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #888;
            border-top: 1px solid #eee;
            padding-top: 2mm;
        }

        @media screen {
            .page {
                margin-bottom: 30px;
                border: 1px solid #ddd;
            }
        }
    </style>
</head>
<body>
    <!-- Print Controls (hidden when printing) -->
    <div class="print-controls no-print">
        <button class="print-btn" onclick="window.print()">🖨️ Print PDF</button>
        <a href="javascript:history.back()" class="back-btn">← Kembali</a>
        <div style="margin-top: 10px; font-size: 12px; color: #666;">
            {{ $total_quantity }} QR Codes | {{ count($pages) }} Halaman
        </div>
    </div>

    @foreach($pages as $pageIndex => $qrCodes)
    <div class="page {{ !$loop->last ? 'page-break' : '' }}">
        <!-- Header hanya di halaman pertama -->
        @if($pageIndex === 0)
        <div class="page-header">
            <h1>VNY STORE - QR CODE BATCH</h1>
            <p>{{ $total_quantity }} QR Codes | Generated: {{ $generated_date }}</p>
        </div>
        @endif

        <!-- Cut Guides -->
        <div class="cut-guides">
            <div class="cut-guide-v"></div>
            <div class="cut-guide-h"></div>
        </div>

        <!-- QR Codes Grid -->
        <div class="qr-grid">
            @for($i = 0; $i < $layout['qr_per_page']; $i++)
                @if(isset($qrCodes[$i]))
                    <div class="qr-item">
                        <div class="qr-code">
                            {!! $qrCodes[$i]['qr_svg'] !!}
                        </div>
                        <div class="brand-text">VNY STORE REVIEW</div>
                        <div class="token-text">{{ $qrCodes[$i]['token'] }}</div>
                        <div class="instructions">
                            Scan untuk memberikan review<br>
                            Tempel pada kemasan produk
                        </div>
                    </div>
                @else
                    <div class="qr-item" style="opacity: 0;">
                        <!-- Empty space untuk halaman yang tidak penuh -->
                    </div>
                @endif
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

    <script>
        // Auto print dialog on load (optional)
        // window.onload = function() { window.print(); }

        // Print function
        function printPage() {
            window.print();
        }

        // Keyboard shortcut
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
        });
    </script>
</body>
</html>
