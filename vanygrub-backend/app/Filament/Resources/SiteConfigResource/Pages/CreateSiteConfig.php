<?php

namespace App\Filament\Resources\SiteConfigResource\Pages;

use App\Filament\Resources\SiteConfigResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSiteConfig extends CreateRecord
{
    protected static string $resource = SiteConfigResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->processValueField($data);
    }

    private function processValueField(array $data): array
    {
        $type = $data['type'] ?? 'text';

        $data['value'] = match ($type) {
            'text', 'url', 'email' => $data['value_text'] ?? '',
            'textarea' => $data['value_textarea'] ?? '',
            'color' => $data['value_color'] ?? '',
            'number' => (int) ($data['value_number'] ?? 0),
            'array' => $data['value_array'] ?? [],
            'json' => $data['value_json'] ?? null,
            default => $data['value_text'] ?? ''
        };

        // Remove temporary fields
        unset(
            $data['value_text'],
            $data['value_textarea'],
            $data['value_color'],
            $data['value_number'],
            $data['value_array'],
            $data['value_json']
        );

        return $data;
    }
}
