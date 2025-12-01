<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use App\Services\ReviewService;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),

            Actions\Action::make('printInvoice')
                ->label('Print Invoice')
                ->icon('heroicon-o-printer')
                ->color('secondary')
                ->url(fn(): string => route('orders.invoice', $this->record))
                ->openUrlInNewTab(),

            Actions\Action::make('updateStatus')
                ->label('Update Status')
                ->icon('heroicon-o-arrow-path')
                ->color('primary')
                ->form([
                    \Filament\Forms\Components\Select::make('status')
                        ->label('Status Baru')
                        ->options([
                            'pending' => 'Menunggu Pembayaran',
                            'processing' => 'Diproses',
                            'shipped' => 'Dikirim',
                            'delivered' => 'Terkirim',
                            'cancelled' => 'Dibatalkan'
                        ])
                        ->required()
                        ->default($this->record->status),

                    \Filament\Forms\Components\Textarea::make('notes')
                        ->label('Catatan Update')
                        ->placeholder('Opsional: Tambahkan catatan untuk update status')
                ])
                ->action(function (array $data): void {
                    $oldStatus = $this->record->status;
                    $this->record->update([
                        'status' => $data['status']
                    ]);

                    // Update notes if provided
                    if (!empty($data['notes'])) {
                        $this->record->update([
                            'notes' => $this->record->notes . "\n\n" . date('d/m/Y H:i') . " - Status diubah dari {$oldStatus} ke {$data['status']}: " . $data['notes']
                        ]);
                    }

                    \Filament\Notifications\Notification::make()
                        ->title('Status berhasil diupdate')
                        ->body("Order {$this->record->order_number} statusnya diubah menjadi {$data['status']}")
                        ->success()
                        ->send();

                    $this->refreshFormData(['status']);
                }),

            Actions\Action::make('generateReviewQR')
                ->label('Generate Review QR')
                ->icon('heroicon-o-qr-code')
                ->color('info')
                ->visible(fn(): bool => !$this->record->hasReview())
                ->action(function (): void {
                    $reviewService = app(ReviewService::class);
                    $reviewToken = $reviewService->generateReviewToken($this->record);
                    $qrPath = $reviewService->generateCustomQRCode($reviewToken, $this->record);

                    \Filament\Notifications\Notification::make()
                        ->title('QR Code Generated')
                        ->body("Review QR code generated for order {$this->record->order_number}")
                        ->success()
                        ->send();
                })
                ->requiresConfirmation()
                ->modalHeading('Generate Review QR Code')
                ->modalDescription('This will generate a QR code that customers can scan to submit their review.')
                ->modalSubmitActionLabel('Generate'),

            Actions\Action::make('viewReviewQR')
                ->label('View Review')
                ->icon('heroicon-o-eye')
                ->color('success')
                ->visible(fn(): bool => $this->record->hasReview())
                ->url(function (): string {
                    $review = $this->record->customerReviews()->first();
                    return "/review/{$review->review_token}";
                })
                ->openUrlInNewTab(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Informasi Order')
                    ->schema([
                        TextEntry::make('order_number')
                            ->label('Nomor Order'),

                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'pending' => 'warning',
                                'processing' => 'primary',
                                'shipped' => 'info',
                                'delivered' => 'success',
                                'cancelled' => 'danger',
                                default => 'gray',
                            }),

                        TextEntry::make('created_at')
                            ->label('Tanggal Order')
                            ->dateTime('d M Y, H:i'),

                        TextEntry::make('updated_at')
                            ->label('Terakhir Diupdate')
                            ->dateTime('d M Y, H:i'),
                    ])->columns(2),

                Section::make('Informasi Customer')
                    ->schema([
                        TextEntry::make('customer_name')
                            ->label('Nama Customer'),

                        TextEntry::make('customer_email')
                            ->label('Email')
                            ->copyable(),

                        TextEntry::make('phone')
                            ->label('Nomor Telepon')
                            ->copyable(),

                        TextEntry::make('shipping_address')
                            ->label('Alamat Pengiriman')
                            ->columnSpanFull(),

                        TextEntry::make('notes')
                            ->label('Catatan')
                            ->columnSpanFull()
                            ->placeholder('Tidak ada catatan'),
                    ])->columns(2),

                Section::make('Informasi Pembayaran')
                    ->schema([
                        TextEntry::make('subtotal')
                            ->label('Subtotal')
                            ->money('IDR'),

                        TextEntry::make('discount_amount')
                            ->label('Diskon')
                            ->money('IDR'),

                        TextEntry::make('total_amount')
                            ->label('Total')
                            ->money('IDR')
                            ->weight('bold'),
                    ])->columns(3),
            ]);
    }
}
