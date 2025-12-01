<?php

namespace App\Filament\Resources\CustomerReviewResource\Pages;

use App\Filament\Resources\CustomerReviewResource;
use App\Models\Order;
use App\Services\ReviewService;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;

class ListCustomerReviews extends ListRecords
{
    protected static string $resource = CustomerReviewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('generateQRCodes')
                ->label('Generate QR Codes')
                ->icon('heroicon-o-qr-code')
                ->color('primary')
                ->action(function () {
                    $reviewService = app(ReviewService::class);
                    $generated = $reviewService->batchGenerateQRCodes();

                    Notification::make()
                        ->title('QR Codes Generated')
                        ->body(count($generated) . ' QR codes generated for orders without reviews')
                        ->success()
                        ->send();
                })
                ->requiresConfirmation()
                ->modalHeading('Generate QR Codes for Orders')
                ->modalDescription('This will generate QR codes for all orders that don\'t have customer reviews yet.')
                ->modalSubmitActionLabel('Generate'),

            Actions\CreateAction::make(),
        ];
    }
}
