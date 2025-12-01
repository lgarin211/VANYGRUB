<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Ukuran Kertas - VNY Store QR Batch</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            margin: 0 0 10px 0;
            font-size: 28px;
            font-weight: bold;
        }

        .header p {
            margin: 0;
            opacity: 0.9;
            font-size: 16px;
        }

        .content {
            padding: 40px;
        }

        .paper-options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .paper-option {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            background: #f9fafb;
        }

        .paper-option:hover {
            border-color: #dc2626;
            background: #fef2f2;
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(220, 38, 38, 0.15);
        }

        .paper-option.selected {
            border-color: #dc2626;
            background: #fef2f2;
            box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.1);
        }

        .paper-icon {
            font-size: 48px;
            margin-bottom: 15px;
            display: block;
        }

        .paper-title {
            font-size: 24px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 10px;
        }

        .paper-size {
            font-size: 16px;
            color: #6b7280;
            margin-bottom: 15px;
        }

        .paper-layout {
            background: #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
            font-size: 14px;
            color: #374151;
        }

        .paper-benefits {
            text-align: left;
            margin-top: 15px;
        }

        .paper-benefits ul {
            margin: 10px 0;
            padding-left: 20px;
            font-size: 14px;
            color: #6b7280;
        }

        .paper-benefits li {
            margin-bottom: 5px;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            align-items: center;
        }

        .btn {
            padding: 15px 30px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: #dc2626;
            color: white;
        }

        .btn-primary:hover:not(:disabled) {
            background: #b91c1c;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background: #4b5563;
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .comparison {
            background: #f0f9ff;
            border: 1px solid #0ea5e9;
            border-radius: 8px;
            padding: 20px;
            margin: 30px 0;
        }

        .comparison h3 {
            color: #0369a1;
            margin: 0 0 15px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .comparison-table {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 15px;
            text-align: center;
            font-size: 14px;
        }

        .comparison-header {
            font-weight: bold;
            color: #1f2937;
            padding-bottom: 10px;
            border-bottom: 2px solid #e5e7eb;
        }

        .comparison-cell {
            padding: 8px;
            color: #6b7280;
        }

        @media (max-width: 768px) {
            .paper-options {
                grid-template-columns: 1fr;
            }

            .comparison-table {
                grid-template-columns: 1fr;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🖨️ Pilih Ukuran Kertas</h1>
            <p>{{ $total_quantity }} QR Codes siap untuk dicetak</p>
        </div>

        <div class="content">
            <form id="paperForm" action="{{ route('qr.batch.preview') }}" method="GET">
                <input type="hidden" name="quantity" value="{{ $quantity }}">
                <input type="hidden" name="paper" id="selectedPaper" value="a4">

                <div class="paper-options">
                    <!-- A4 Option -->
                    <div class="paper-option selected" data-paper="a4" onclick="selectPaper('a4')">
                        <div class="paper-icon">📄</div>
                        <div class="paper-title">Kertas A4</div>
                        <div class="paper-size">210 × 297 mm</div>

                        <div class="paper-layout">
                            <strong>Layout: 2×2 Grid</strong><br>
                            4 QR per halaman
                        </div>

                        <div class="paper-benefits">
                            <ul>
                                <li>Standard office paper</li>
                                <li>QR size: 70×70mm (optimal)</li>
                                <li>Perfect untuk stiker besar</li>
                                <li>Easy cutting & handling</li>
                                <li>{{ ceil($total_quantity / 4) }} halaman total</li>
                            </ul>
                        </div>
                    </div>

                    <!-- A3 Option -->
                    <div class="paper-option" data-paper="a3" onclick="selectPaper('a3')">
                        <div class="paper-icon">📋</div>
                        <div class="paper-title">Kertas A3</div>
                        <div class="paper-size">297 × 420 mm</div>

                        <div class="paper-layout">
                            <strong>Layout: 4×3 Grid</strong><br>
                            12 QR per halaman
                        </div>

                        <div class="paper-benefits">
                            <ul>
                                <li>Large format paper</li>
                                <li>QR size: 58×58mm (optimal)</li>
                                <li>More QR per sheet</li>
                                <li>Efficient untuk quantity besar</li>
                                <li>{{ ceil($total_quantity / 12) }} halaman total</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Comparison Table -->
                <div class="comparison">
                    <h3>
                        📊 Perbandingan Layout
                    </h3>
                    <div class="comparison-table">
                        <div class="comparison-header">Spesifikasi</div>
                        <div class="comparison-header">A4 (2×2)</div>
                        <div class="comparison-header">A3 (4×3)</div>

                        <div class="comparison-cell">QR per halaman</div>
                        <div class="comparison-cell"><strong>4 QR</strong></div>
                        <div class="comparison-cell"><strong>12 QR</strong></div>

                        <div class="comparison-cell">QR Size</div>
                        <div class="comparison-cell">70×70mm</div>
                        <div class="comparison-cell">58×58mm</div>

                        <div class="comparison-cell">Total halaman</div>
                        <div class="comparison-cell">{{ ceil($total_quantity / 4) }} halaman</div>
                        <div class="comparison-cell">{{ ceil($total_quantity / 12) }} halaman</div>

                        <div class="comparison-cell">Kertas dibutuhkan</div>
                        <div class="comparison-cell">{{ ceil($total_quantity / 4) }} lembar A4</div>
                        <div class="comparison-cell">{{ ceil($total_quantity / 12) }} lembar A3</div>
                    </div>
                </div>

                <div class="action-buttons">
                    <a href="javascript:history.back()" class="btn btn-secondary">
                        ← Kembali
                    </a>
                    <button type="submit" class="btn btn-primary" id="generateBtn">
                        🚀 Generate & Preview
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function selectPaper(paperSize) {
            // Remove selected class from all options
            document.querySelectorAll('.paper-option').forEach(option => {
                option.classList.remove('selected');
            });

            // Add selected class to clicked option
            document.querySelector(`[data-paper="${paperSize}"]`).classList.add('selected');

            // Update hidden input
            document.getElementById('selectedPaper').value = paperSize;

            // Update button text
            const btn = document.getElementById('generateBtn');
            if (paperSize === 'a3') {
                btn.innerHTML = '🚀 Generate A3 Layout';
            } else {
                btn.innerHTML = '🚀 Generate A4 Layout';
            }
        }

        // Auto submit on selection (optional)
        document.getElementById('paperForm').addEventListener('change', function() {
            // Auto submit after selection (uncomment if desired)
            // this.submit();
        });
    </script>
</body>
</html>
