<?php

namespace App\Filament\Resources\HomepageGalleryItemResource\Pages;

use App\Filament\Resources\HomepageGalleryItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class EditHomepageGalleryItem extends EditRecord
{
    protected static string $resource = HomepageGalleryItemResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Convert full URL back to relative path for form display
        if (isset($data['image']) && $data['image']) {
            // Extract relative path from full URL for editing
            $data['image'] = str_replace(asset(Storage::url('')), '', $data['image']);
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Convert relative path to full URL before saving
        if (isset($data['image']) && $data['image']) {
            // If it's not already a full URL, convert it
            if (!str_starts_with($data['image'], 'http')) {
                $data['image'] = asset(Storage::url($data['image']));
            }
        }

        return $data;
    }

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
