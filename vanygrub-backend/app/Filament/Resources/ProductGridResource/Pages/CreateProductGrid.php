<?php

namespace App\Filament\Resources\ProductGridResource\Pages;

use App\Filament\Resources\ProductGridResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProductGrid extends CreateRecord
{
    protected static string $resource = ProductGridResource::class;
}
