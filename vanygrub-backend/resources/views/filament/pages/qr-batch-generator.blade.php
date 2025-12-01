<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Form Section -->
        <div class="fi-section">
            <x-filament::section>
                <x-slot name="heading">
                    <div class="flex items-center gap-2">
                        <x-heroicon-o-qr-code class="h-5 w-5" />
                        QR Code Batch Generator
                    </div>
                </x-slot>

                <x-slot name="description">
                    Generate multiple QR codes untuk customer review dalam format PDF siap cetak
                </x-slot>

                <div class="space-y-4">
                    {{ $this->form }}

                    <!-- Preview Info -->
                    @if($this->quantity > 0)
                    <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <h3 class="font-semibold text-blue-800 mb-2">📄 Preview Layout:</h3>
                        <div class="text-sm text-blue-700 space-y-1">
                            <p>• <strong>Ukuran kertas:</strong> A4 (210 x 297 mm)</p>
                            <p>• <strong>Layout:</strong> 2 kolom, 2 QR per baris (4 QR per halaman)</p>
                            <p>• <strong>Total halaman:</strong> {{ ceil($this->quantity / 4) }} halaman</p>
                            <p>• <strong>QR per halaman:</strong> 4 QR codes</p>
                            <p>• <strong>Ukuran QR:</strong> 80mm x 80mm (cocok untuk stiker)</p>
                            <p>• <strong>Include:</strong> Branding VNY Store + Token unik</p>
                        </div>
                    </div>
                    @endif
                </div>
            </x-filament::section>
        </div>

        <!-- Instructions Section -->
        <div class="fi-section">
            <x-filament::section>
                <x-slot name="heading">
                    📋 Petunjuk Penggunaan
                </x-slot>

                <div class="prose prose-sm max-w-none">
                    <ol class="space-y-2">
                        <li><strong>Input Jumlah:</strong> Masukkan jumlah QR yang diinginkan (1-1000)</li>
                        <li><strong>Generate:</strong> Klik tombol "Generate QR Codes PDF" di bagian atas</li>
                        <li><strong>Download:</strong> PDF akan otomatis terdownload</li>
                        <li><strong>Print:</strong> Print PDF menggunakan printer dengan kualitas tinggi</li>
                        <li><strong>Potong:</strong> Gunting sesuai garis dan tempel sebagai stiker pada produk</li>
                        <li><strong>Scan:</strong> Customer bisa scan QR untuk memberikan review</li>
                    </ol>
                </div>
            </x-filament::section>
        </div>

        <!-- Technical Info Section -->
        <div class="fi-section">
            <x-filament::section>
                <x-slot name="heading">
                    ⚙️ Info Teknis
                </x-slot>

                <div class="prose prose-sm max-w-none">
                    <ul class="space-y-1 text-gray-600">
                        <li>• Setiap QR memiliki token unik untuk tracking</li>
                        <li>• QR mengarah ke: <code>{{ config('app.url') }}/review/[token]</code></li>
                        <li>• Format: PDF dengan resolusi tinggi untuk print</li>
                        <li>• Background: Abu-abu muda dengan border untuk memudahkan pemotongan</li>
                        <li>• Font: Roboto untuk keterbacaan maksimal</li>
                        <li>• Error Correction: Level M (15% recovery)</li>
                        <li>• Database: Token tersimpan di tabel customer_reviews</li>
                    </ul>
                </div>
            </x-filament::section>
        </div>
    </div>
</x-filament-panels::page>
