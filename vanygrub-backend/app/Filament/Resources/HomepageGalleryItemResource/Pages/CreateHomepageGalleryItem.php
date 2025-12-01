<?php

namespace App\Filament\Resources\HomepageGalleryItemResource\Pages;

use App\Filament\Resources\HomepageGalleryItemResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class CreateHomepageGalleryItem extends CreateRecord
{
    protected static string $resource = HomepageGalleryItemResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Convert relative path to full URL for image field
        if (isset($data['image']) && $data['image']) {
            $data['image'] = asset(Storage::url($data['image']));
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Gallery item created')
            ->body('The gallery item has been created successfully and is now available on the homepage.');
    }
}
