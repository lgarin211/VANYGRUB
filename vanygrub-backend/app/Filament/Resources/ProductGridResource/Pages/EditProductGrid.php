<?php

namespace App\Filament\Resources\ProductGridResource\Pages;

use App\Filament\Resources\ProductGridResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductGrid extends EditRecord
{
    protected static string $resource = ProductGridResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
