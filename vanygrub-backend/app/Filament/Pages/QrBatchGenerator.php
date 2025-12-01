<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Notifications\Notification;
use App\Models\CustomerReview;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class QrBatchGenerator extends Page implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;

    protected static ?string $navigationIcon = 'heroicon-o-qr-code';
    protected static ?string $navigationLabel = 'QR Batch Generator';
    protected static ?string $title = 'QR Code Batch Generator';
    protected static string $view = 'filament.pages.qr-batch-generator';
    protected static ?string $navigationGroup = 'Review Management';
    protected static ?int $navigationSort = 3;

    public ?array $data = [];
    public int $quantity = 10;
    public string $paper_type = 'a4';

    public function mount(): void
    {
        $this->form->fill([
            'quantity' => $this->quantity,
            'paper_type' => $this->paper_type
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('QR Code Batch Generator')
                    ->description('Generate multiple QR codes untuk customer review dengan pilihan ukuran kertas A4 atau A3')
                    ->schema([
                        TextInput::make('quantity')
                            ->label('Jumlah QR Code')
                            ->helperText(function ($get) {
                                $quantity = (int) $get('quantity') ?: 10;
                                $paperType = $get('paper_type') ?: 'a4';
                                $qrPerPage = $paperType === 'a3' ? 12 : 4;
                                $pages = ceil($quantity / $qrPerPage);
                                $paperName = $paperType === 'a3' ? 'A3' : 'A4';

                                return "Quantity: {$quantity} QR codes → {$pages} halaman {$paperName} ({$qrPerPage} QR per halaman)";
                            })
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(1000)
                            ->default(10)
                            ->required()
                            ->suffix('QR Codes')
                            ->live()
                            ->afterStateUpdated(function ($state) {
                                $this->quantity = (int) $state;
                            }),

                        Select::make('paper_type')
                            ->label('Ukuran Kertas')
                            ->options([
                                'a4' => 'A4 (210×297mm) - 2×2 Grid, QR 70×70mm, 4 QR per halaman',
                                'a3' => 'A3 (297×420mm) - 4×3 Grid, QR 58×58mm, 12 QR per halaman'
                            ])
                            ->default('a4')
                            ->required()
                            ->helperText('Pilih ukuran kertas untuk layout QR codes')
                            ->live()
                            ->afterStateUpdated(function ($state) {
                                $this->paper_type = $state;
                            }),
                    ])
                    ->columns(2)
            ])
            ->statePath('data');
    }

    public function generateQrBatchAction(): Action
    {
        return Action::make('generateQrBatch')
            ->label('Generate QR Codes PDF')
            ->icon('heroicon-o-document-arrow-down')
            ->color('success')
            ->size('lg')
            ->action(function () {
                $this->generateQrBatch();
            });
    }

    protected function generateQrBatch()
    {
        try {
            $formData = $this->form->getState();
            $quantity = $formData['quantity'] ?? $this->quantity;
            $paperType = $formData['paper_type'] ?? $this->paper_type;

            if ($quantity < 1 || $quantity > 1000) {
                Notification::make()
                    ->title('Error')
                    ->body('Jumlah QR harus antara 1-1000')
                    ->danger()
                    ->send();
                return;
            }

            // Generate tokens and save to database
            $tokens = [];
            for ($i = 0; $i < $quantity; $i++) {
                $token = 'VNY-' . time() . '-' . strtoupper(Str::random(9));

                // Create placeholder review entry
                $review = CustomerReview::create([
                    'review_token' => $token,
                    'customer_name' => null,
                    'customer_email' => null,
                    'order_id' => null,
                    'photo_url' => null,
                    'review_text' => null,
                    'rating' => null,
                    'is_approved' => false,
                    'is_featured' => false
                ]);

                $tokens[] = [
                    'token' => $token,
                    'url' => config('app.url') . "/review/{$token}"
                ];
            }

            // Save token IDs to session for preview
            $tokenIds = collect($tokens)->pluck('token')->toArray();
            session(['qr_batch_tokens' => $tokenIds]);

            // Create direct preview URL with selected paper type
            $previewUrl = route('qr.batch.preview', [
                'quantity' => $quantity,
                'paper' => $paperType
            ]);

            $paperName = $paperType === 'a3' ? 'A3 (12 QR per halaman)' : 'A4 (4 QR per halaman)';

            Notification::make()
                ->title('Success!')
                ->body("Berhasil generate {$quantity} QR codes untuk {$paperName}. <a href='{$previewUrl}' target='_blank' style='color: #059669; text-decoration: underline; font-weight: bold;'>🖨️ Buka Halaman Print</a>")
                ->success()
                ->persistent()
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('Terjadi kesalahan: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }



    protected function getHeaderActions(): array
    {
        return [
            $this->generateQrBatchAction(),
        ];
    }

    public function getTitle(): string
    {
        return 'QR Code Batch Generator';
    }
}
