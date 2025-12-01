<?php

namespace App\Filament\Resources\HomepageGalleryItemResource\Pages;

use App\Filament\Resources\HomepageGalleryItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditHomepageGalleryItem extends EditRecord
{
    protected static string $resource = HomepageGalleryItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('preview')
                ->label('Preview Item')
                ->icon('heroicon-o-eye')
                ->url(fn() => $this->record->target)
                ->openUrlInNewTab()
                ->visible(fn() => $this->record->target !== '/'),
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
            ->title('Gallery item updated')
            ->body('The gallery item has been updated successfully.');
    }
}
