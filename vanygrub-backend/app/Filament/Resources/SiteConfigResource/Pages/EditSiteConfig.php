<?php

namespace App\Filament\Resources\SiteConfigResource\Pages;

use App\Filament\Resources\SiteConfigResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSiteConfig extends EditRecord
{
    protected static string $resource = SiteConfigResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Populate the dynamic fields based on type
        $type = $data['type'] ?? 'text';
        $value = $data['value'] ?? null;

        match ($type) {
            'text', 'url', 'email' => $data['value_text'] = $value,
            'textarea' => $data['value_textarea'] = $value,
            'color' => $data['value_color'] = $value,
            'number' => $data['value_number'] = (string) $value,
            'array' => $data['value_array'] = is_array($value) ? $value : [],
            'json' => $data['value_json'] = is_array($value) || is_object($value) ? json_encode($value, JSON_PRETTY_PRINT) : $value,
            default => $data['value_text'] = $value
        };

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
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
