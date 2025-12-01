'use client';

import { useState } from 'react';
import { jsPDF } from 'jspdf';
import QRCode from 'qrcode';

export default function QRBatchGenerator() {
  const [quantity, setQuantity] = useState<number>(10);
  const [generating, setGenerating] = useState(false);
  const [progress, setProgress] = useState(0);

  const generateQRBatch = async () => {
    if (quantity < 1 || quantity > 1000) {
      alert('Jumlah QR harus antara 1-1000');
      return;
    }

    setGenerating(true);
    setProgress(0);

    try {
      // Create PDF document (A4 size: 210 x 297 mm)
      const pdf = new jsPDF('portrait', 'mm', 'a4');
      const pageWidth = 210;
      const pageHeight = 297;
      const margin = 10;
      const qrSize = 80; // QR code size in mm
      const labelHeight = 15; // Height for text label
      const spacing = 15; // Spacing between QRs
      
      // Calculate positions for 2 columns layout
      const col1X = margin;
      const col2X = pageWidth / 2 + 5;
      const contentWidth = (pageWidth - (2 * margin)) / 2;
      
      let currentPage = 1;
      let yPosition = margin + 10;
      let column = 0; // 0 for left, 1 for right

      for (let i = 0; i < quantity; i++) {
        // Generate unique token for each QR
        const token = `VNY-${Date.now()}-${Math.random().toString(36).substr(2, 9).toUpperCase()}`;
        const reviewUrl = `${window.location.origin}/review/${token}`;

        // Generate QR code as data URL
        const qrDataUrl = await QRCode.toDataURL(reviewUrl, {
          width: 300,
          margin: 1,
          color: {
            dark: '#000000',
            light: '#FFFFFF'
          }
        });

        // Determine position
        const xPosition = column === 0 ? col1X : col2X;
        
        // Check if we need a new page
        if (yPosition + qrSize + labelHeight + spacing > pageHeight - margin) {
          if (column === 0) {
            // Move to second column
            column = 1;
            yPosition = margin + 10;
          } else {
            // Add new page
            pdf.addPage();
            currentPage++;
            yPosition = margin + 10;
            column = 0;
          }
        }

        // Draw QR code sticker background
        pdf.setFillColor(248, 249, 250); // Light gray background
        pdf.roundedRect(xPosition - 2, yPosition - 2, qrSize + 4, qrSize + labelHeight + 4, 2, 2, 'F');
        
        // Draw border
        pdf.setDrawColor(220, 220, 220);
        pdf.setLineWidth(0.5);
        pdf.roundedRect(xPosition - 2, yPosition - 2, qrSize + 4, qrSize + labelHeight + 4, 2, 2, 'S');

        // Add QR code image
        pdf.addImage(qrDataUrl, 'PNG', xPosition, yPosition, qrSize, qrSize);

        // Add VNY Store branding
        pdf.setFont('helvetica', 'bold');
        pdf.setFontSize(12);
        pdf.setTextColor(220, 38, 38); // Red color
        const brandText = 'VNY STORE REVIEW';
        const brandTextWidth = pdf.getTextWidth(brandText);
        pdf.text(brandText, xPosition + (qrSize - brandTextWidth) / 2, yPosition + qrSize + 8);

        // Add token as small text
        pdf.setFont('helvetica', 'normal');
        pdf.setFontSize(8);
        pdf.setTextColor(100, 100, 100); // Gray color
        const tokenWidth = pdf.getTextWidth(token);
        pdf.text(token, xPosition + (qrSize - tokenWidth) / 2, yPosition + qrSize + 12);

        // Move to next position
        if (column === 0) {
          column = 1; // Move to right column
        } else {
          column = 0; // Move to left column next row
          yPosition += qrSize + labelHeight + spacing;
        }

        // Update progress
        setProgress(Math.round(((i + 1) / quantity) * 100));
      }

      // Save PDF
      const fileName = `VNY_Store_QR_Batch_${quantity}_${new Date().toISOString().split('T')[0]}.pdf`;
      pdf.save(fileName);

      alert(`Berhasil generate ${quantity} QR codes dalam file ${fileName}`);

    } catch (error) {
      console.error('Error generating QR batch:', error);
      alert('Terjadi kesalahan saat generate QR codes');
    } finally {
      setGenerating(false);
      setProgress(0);
    }
  };

  return (
    <div className="min-h-screen py-12 bg-gray-50">
      <div className="container max-w-2xl px-4 mx-auto">
        {/* Header */}
        <div className="mb-12 text-center">
          <h1 className="mb-4 text-4xl font-bold text-red-600">VNY STORE</h1>
          <h2 className="mb-2 text-2xl font-semibold text-gray-800">QR Code Batch Generator</h2>
          <p className="text-gray-600">Generate multiple QR codes untuk review dalam format PDF siap cetak</p>
        </div>

        {/* Generator Form */}
        <div className="p-8 bg-white rounded-lg shadow-lg">
          <div className="space-y-6">
            {/* Quantity Input */}
            <div>
              <label className="block mb-4 text-lg font-semibold text-gray-800">
                Jumlah QR Code yang akan dibuat
              </label>
              <input
                type="number"
                min="1"
                max="1000"
                value={quantity}
                onChange={(e) => setQuantity(parseInt(e.target.value) || 0)}
                className="w-full p-4 text-xl text-center border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-600 focus:border-transparent"
                placeholder="Masukkan jumlah QR (1-1000)"
                disabled={generating}
              />
              <p className="mt-2 text-sm text-center text-gray-500">
                Akan dibuat dalam format A4 dengan 2 kolom (2 QR per baris)
              </p>
            </div>

            {/* Preview Info */}
            <div className="p-4 border border-blue-200 rounded-lg bg-blue-50">
              <h3 className="mb-2 font-semibold text-blue-800">Preview Layout:</h3>
              <div className="space-y-1 text-sm text-blue-700">
                <p>• Ukuran kertas: A4 (210 x 297 mm)</p>
                <p>• Layout: 2 kolom, 2 QR per baris</p>
                <p>• Total halaman: {Math.ceil(quantity / 4)} halaman</p>
                <p>• QR per halaman: 4 QR codes</p>
                <p>• Ukuran QR: 80mm x 80mm (cocok untuk stiker)</p>
                <p>• Include: Branding VNY Store + Token unik</p>
              </div>
            </div>

            {/* Progress Bar */}
            {generating && (
              <div className="space-y-2">
                <div className="flex justify-between text-sm text-gray-600">
                  <span>Generating QR Codes...</span>
                  <span>{progress}%</span>
                </div>
                <div className="w-full h-3 bg-gray-200 rounded-full">
                  <div 
                    className="h-3 transition-all duration-300 bg-red-600 rounded-full"
                    style={{ width: `${progress}%` }}
                  ></div>
                </div>
                <p className="text-sm text-center text-gray-500">
                  Mohon tunggu, sedang membuat PDF...
                </p>
              </div>
            )}

            {/* Generate Button */}
            <div className="text-center">
              <button
                onClick={generateQRBatch}
                disabled={generating || quantity < 1 || quantity > 1000}
                className={`px-8 py-4 rounded-lg text-white font-semibold text-lg transition-colors ${
                  generating || quantity < 1 || quantity > 1000
                    ? 'bg-gray-400 cursor-not-allowed' 
                    : 'bg-red-600 hover:bg-red-700'
                }`}
              >
                {generating ? `Generating... (${progress}%)` : `Generate ${quantity} QR Codes 🎯`}
              </button>
            </div>

            {/* Instructions */}
            <div className="p-6 mt-8 border border-yellow-200 rounded-lg bg-yellow-50">
              <h3 className="mb-2 font-semibold text-yellow-800">📝 Petunjuk Penggunaan:</h3>
              <div className="space-y-2 text-sm text-yellow-700">
                <p><strong>1.</strong> Masukkan jumlah QR yang diinginkan (1-1000)</p>
                <p><strong>2.</strong> Klik tombol &quot;Generate QR Codes&quot;</p>
                <p><strong>3.</strong> PDF akan otomatis terdownload</p>
                <p><strong>4.</strong> Print PDF menggunakan printer dengan kualitas tinggi</p>
                <p><strong>5.</strong> Gunting sesuai garis dan tempel sebagai stiker pada produk</p>
                <p><strong>6.</strong> Customer bisa scan QR untuk memberikan review</p>
              </div>
            </div>

            {/* Technical Info */}
            <div className="p-4 mt-4 border border-gray-200 rounded-lg bg-gray-50">
              <h3 className="mb-2 font-semibold text-gray-800">ℹ️ Info Teknis:</h3>
              <div className="space-y-1 text-xs text-gray-600">
                <p>• Setiap QR memiliki token unik untuk tracking</p>
                <p>• QR mengarah ke: {window.location.origin}/review/[token]</p>
                <p>• Format: PDF dengan resolusi tinggi untuk print</p>
                <p>• Background: Abu-abu muda dengan border untuk memudahkan pemotongan</p>
                <p>• Font: Helvetica untuk keterbacaan maksimal</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}