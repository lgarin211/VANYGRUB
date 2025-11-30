<?php

namespace App\Filament\Resources\ProductGridResource\Pages;

use App\Filament\Resources\ProductGridResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductGrids extends ListRecords
{
    protected static string $resource = ProductGridResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
