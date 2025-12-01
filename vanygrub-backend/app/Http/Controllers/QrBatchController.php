<?php

namespace App\Http\Controllers;

use App\Models\CustomerReview;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;

class QrBatchController extends Controller
{
    public function preview(Request $request)
    {
        $tokens = session('qr_batch_tokens', []);
        $quantity = $request->get('quantity', count($tokens));
        $paperSize = $request->get('paper', 'a4'); // a4 atau a3

        if (empty($tokens)) {
            return redirect()->back()->with('error', 'Tidak ada QR codes untuk ditampilkan');
        }

        // Generate QR codes data
        $qrCodes = [];
        foreach ($tokens as $token) {
            $reviewUrl = config('app.url') . "/review/{$token}";

            // Generate QR as SVG for better print quality
            $qrSvg = QrCode::format('svg')
                ->size(300)
                ->margin(0)
                ->errorCorrection('M')
                ->generate($reviewUrl);

            $qrCodes[] = [
                'token' => $token,
                'url' => $reviewUrl,
                'qr_svg' => $qrSvg
            ];
        }

        // Layout berbeda berdasarkan ukuran kertas
        if ($paperSize === 'a3') {
            // A3: 4 kolom x 3 baris = 12 QR per halaman
            $pages = array_chunk($qrCodes, 12);
            $layout = [
                'cols' => 4,
                'rows' => 3,
                'qr_per_page' => 12
            ];
        } else {
            // A4: 2 kolom x 2 baris = 4 QR per halaman
            $pages = array_chunk($qrCodes, 4);
            $layout = [
                'cols' => 2,
                'rows' => 2,
                'qr_per_page' => 4
            ];
        }

        return view('qr.batch-preview', [
            'pages' => $pages,
            'total_quantity' => count($qrCodes),
            'generated_date' => now()->format('d M Y H:i'),
            'paper_size' => $paperSize,
            'layout' => $layout
        ]);
    }

    public function selectPaper(Request $request)
    {
        $tokens = session('qr_batch_tokens', []);
        $quantity = $request->get('quantity', count($tokens));

        if (empty($tokens)) {
            return redirect()->back()->with('error', 'Tidak ada QR codes untuk ditampilkan');
        }

        return view('qr.paper-selection', [
            'total_quantity' => count($tokens),
            'quantity' => $quantity
        ]);
    }
}
