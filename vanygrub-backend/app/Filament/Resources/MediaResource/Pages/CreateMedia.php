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

            // Try to get extension from multiple sources
            $originalExtension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

            // If no extension found from original name, try to detect from MIME type
            if (empty($originalExtension)) {
                try {
                    $mimeType = Storage::disk('public')->mimeType($tempFile);
                    $originalExtension = $this->getExtensionFromMimeType($mimeType);
                } catch (\Exception $e) {
                    // Default extension based on type
                    $originalExtension = $type === 'image' ? 'png' : ($type === 'video' ? 'mp4' : 'pdf');
                }
            }

            // Ensure we have an extension
            if (empty($originalExtension)) {
                $originalExtension = $type === 'image' ? 'png' : ($type === 'video' ? 'mp4' : 'pdf');
            }

            // Generate filename with guaranteed extension
            $baseFilename = $data['filename'] ?? (Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '-' . time());

            // Remove extension from baseFilename if it already has one, then add the correct one
            $baseFilename = pathinfo($baseFilename, PATHINFO_FILENAME);
            $filename = $baseFilename . '.' . $originalExtension;            // Move file from temp to proper location
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

    /**
     * Get file extension from MIME type
     */
    private function getExtensionFromMimeType(string $mimeType): string
    {
        $mimeToExtension = [
            // Images
            'image/jpeg' => 'jpg',
            'image/jpg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            'image/svg+xml' => 'svg',
            'image/bmp' => 'bmp',
            'image/tiff' => 'tiff',
            'image/x-icon' => 'ico',

            // Videos
            'video/mp4' => 'mp4',
            'video/avi' => 'avi',
            'video/quicktime' => 'mov',
            'video/x-ms-wmv' => 'wmv',
            'video/x-flv' => 'flv',
            'video/webm' => 'webm',
            'video/x-msvideo' => 'avi',
            'video/ogg' => 'ogv',

            // Documents
            'application/pdf' => 'pdf',
            'application/msword' => 'doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
            'application/vnd.ms-excel' => 'xls',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
            'application/vnd.ms-powerpoint' => 'ppt',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
            'text/plain' => 'txt',
            'application/rtf' => 'rtf',
        ];

        return $mimeToExtension[$mimeType] ?? 'bin';
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
