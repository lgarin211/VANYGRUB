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

            // Get original file info to preserve format
            $originalName = $data['original_name'] ?? pathinfo($tempFile, PATHINFO_BASENAME);
            $originalExtension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

            // Ensure filename keeps original extension to preserve file format
            $baseFilename = $data['filename'] ?? (Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '-' . time());
            $filename = $baseFilename . '.' . $originalExtension; // Force original extension

            // Move file from temp to proper location
            $finalPath = "media/{$type}/{$folder}/{$filename}";

            // Ensure directory exists
            $directory = dirname("storage/app/public/{$finalPath}");
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            // Copy file to final location WITHOUT any format conversion
            Storage::disk('public')->copy($tempFile, $finalPath);

            // Verify file was copied successfully
            if (!Storage::disk('public')->exists($finalPath)) {
                throw new \Exception("Failed to copy file to final location");
            }

            // Get file details from the final location
            $size = Storage::disk('public')->size($finalPath);
            $mimeType = Storage::disk('public')->mimeType($finalPath);
            $url = asset(Storage::url($finalPath));

            // Log for debugging
            \Log::info('File upload details', [
                'original' => $originalName,
                'extension' => $originalExtension,
                'final_path' => $finalPath,
                'mime_type' => $mimeType,
                'size' => $size
            ]);

            // Verify type detection based on actual file extension
            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp', 'tiff', 'ico'];
            $videoExtensions = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm', 'mkv', 'ogv'];
            $documentExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'rtf'];

            // Verify type based on extension (most reliable for preserving file format)
            $verifiedType = $type; // Default to form selection
            if (in_array($originalExtension, $imageExtensions)) {
                $verifiedType = 'image';
            } elseif (in_array($originalExtension, $videoExtensions)) {
                $verifiedType = 'video';
            } elseif (in_array($originalExtension, $documentExtensions)) {
                $verifiedType = 'document';
            }

            // If type is different from form selection, move to correct directory
            if ($verifiedType !== $type) {
                $correctPath = "media/{$verifiedType}/{$folder}/{$filename}";

                // Ensure correct directory exists
                $correctDirectory = dirname("storage/app/public/{$correctPath}");
                if (!file_exists($correctDirectory)) {
                    mkdir($correctDirectory, 0755, true);
                }

                Storage::disk('public')->move($finalPath, $correctPath);
                $finalPath = $correctPath;
                $url = asset(Storage::url($finalPath));
                $type = $verifiedType;

                \Log::info('File moved to correct type directory', [
                    'from' => $finalPath,
                    'to' => $correctPath,
                    'type' => $type
                ]);
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
