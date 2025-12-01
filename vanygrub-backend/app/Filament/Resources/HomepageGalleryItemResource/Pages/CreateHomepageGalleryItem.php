<?php

namespace App\Filament\Resources\HomepageGalleryItemResource\Pages;

use App\Filament\Resources\HomepageGalleryItemResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateHomepageGalleryItem extends CreateRecord
{
    protected static string $resource = HomepageGalleryItemResource::class;

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
