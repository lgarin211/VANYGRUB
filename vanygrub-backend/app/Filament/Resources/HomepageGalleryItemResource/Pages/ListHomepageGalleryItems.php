<?php

namespace App\Filament\Resources\HomepageGalleryItemResource\Pages;

use App\Filament\Resources\HomepageGalleryItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHomepageGalleryItems extends ListRecords
{
    protected static string $resource = HomepageGalleryItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Add Gallery Item')
                ->icon('heroicon-o-plus'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // You can add statistics widgets here
        ];
    }
}
