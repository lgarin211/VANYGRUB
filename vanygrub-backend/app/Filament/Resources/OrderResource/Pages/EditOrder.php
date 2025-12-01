<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),

            Actions\Action::make('sendNotification')
                ->label('Kirim Notifikasi')
                ->icon('heroicon-o-bell')
                ->color('success')
                ->form([
                    \Filament\Forms\Components\Textarea::make('message')
                        ->label('Pesan')
                        ->required()
                        ->placeholder('Tulis pesan yang akan dikirim ke customer')
                ])
                ->action(function (array $data): void {
                    // Here you can implement email/SMS notification logic
                    Notification::make()
                        ->title('Notifikasi terkirim')
                        ->body('Pesan telah dikirim ke customer')
                        ->success()
                        ->send();
                }),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Order updated')
            ->body('The order has been saved successfully.');
    }
}
