<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Gallery Configuration
    |--------------------------------------------------------------------------
    */

    'max_file_size' => env('GALLERY_MAX_FILE_SIZE', 52428800), // 50MB in bytes

    'allowed_types' => [
        'image' => [
            'mimes' => ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'],
            'extensions' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'],
            'max_size' => env('GALLERY_IMAGE_MAX_SIZE', 10485760), // 10MB
        ],
        'video' => [
            'mimes' => ['video/mp4', 'video/avi', 'video/mov', 'video/wmv', 'video/webm'],
            'extensions' => ['mp4', 'avi', 'mov', 'wmv', 'webm'],
            'max_size' => env('GALLERY_VIDEO_MAX_SIZE', 52428800), // 50MB
        ],
        'audio' => [
            'mimes' => ['audio/mp3', 'audio/wav', 'audio/ogg', 'audio/m4a'],
            'extensions' => ['mp3', 'wav', 'ogg', 'm4a'],
            'max_size' => env('GALLERY_AUDIO_MAX_SIZE', 20971520), // 20MB
        ],
        'document' => [
            'mimes' => ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
            'extensions' => ['pdf', 'doc', 'docx'],
            'max_size' => env('GALLERY_DOCUMENT_MAX_SIZE', 10485760), // 10MB
        ],
    ],

    'storage' => [
        'disk' => env('GALLERY_STORAGE_DISK', 'public'),
        'path' => env('GALLERY_STORAGE_PATH', 'gallery'),
    ],

    'thumbnails' => [
        'generate' => env('GALLERY_GENERATE_THUMBNAILS', true),
        'sizes' => [
            'thumb' => ['width' => 150, 'height' => 150],
            'medium' => ['width' => 300, 'height' => 300],
            'large' => ['width' => 800, 'height' => 600],
        ],
    ],

    'categories' => [
        'products',
        'banners',
        'logos',
        'documents',
        'media',
        'uncategorized'
    ],

];