<?php

namespace App\Filament\Resources\MediaResource\Pages;

use App\Filament\Resources\MediaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CreateMedia extends CreateRecord
{
    protected static string $resource = MediaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Handle file upload
        if (isset($data['file']) && $data['file']) {
            $tempFile = $data['file'];
            $type = $data['type'] ?? 'image';
            $folder = $data['folder'] ?? 'general';

            // Get file info
            $originalName = $data['original_name'] ?? pathinfo($tempFile, PATHINFO_BASENAME);
            $filename = $data['filename'] ?? (Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '-' . time() . '.' . pathinfo($originalName, PATHINFO_EXTENSION));

            // Move file from temp to proper location
            $finalPath = "media/{$type}/{$folder}/{$filename}";

            // Copy file to final location
            Storage::disk('public')->copy($tempFile, $finalPath);

            // Get file details
            $size = Storage::disk('public')->size($finalPath);
            $mimeType = Storage::disk('public')->mimeType($finalPath);
            $url = asset(Storage::url($finalPath));

            // Double-check type detection based on actual file MIME type and extension
            $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'];
            $videoExtensions = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm', 'mkv', 'ogv'];

            // Correct type if needed based on extension (most reliable)
            if (in_array($extension, $imageExtensions)) {
                $type = 'image';
            } elseif (in_array($extension, $videoExtensions)) {
                $type = 'video';
            } else {
                $type = 'document';
            }

            // If type changed, move file to correct directory
            if ($type !== $data['type']) {
                $correctPath = "media/{$type}/{$folder}/{$filename}";
                Storage::disk('public')->move($finalPath, $correctPath);
                $finalPath = $correctPath;
                $url = asset(Storage::url($finalPath));
            }

            // Delete temp file
            Storage::disk('public')->delete($tempFile);

            // Update data
            $data['path'] = $finalPath;
            $data['url'] = $url;
            $data['size'] = $size;
            $data['mime_type'] = $mimeType;
            $data['filename'] = $filename;
            $data['original_name'] = $originalName;
            $data['type'] = $type; // Use corrected type

            // Remove file from data as it's not a database field
            unset($data['file']);
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
            ->title('Media uploaded successfully')
            ->body('Your media file has been uploaded and is ready to use.');
    }
}
