<?php
// Test file untuk mengecek sistem upload gambar
echo "Testing image upload system...\n";

// Cek direktori about-images
$aboutImagesPath = __DIR__ . '/storage/app/public/about-images';
if (!is_dir($aboutImagesPath)) {
    mkdir($aboutImagesPath, 0755, true);
    echo "✅ Created about-images directory\n";
} else {
    echo "✅ About-images directory exists\n";
}

// Cek permission
if (is_writable($aboutImagesPath)) {
    echo "✅ About-images directory is writable\n";
} else {
    echo "❌ About-images directory is not writable\n";
}

// Cek apakah storage public sudah linked
$publicStoragePath = __DIR__ . '/public/storage';
if (is_link($publicStoragePath)) {
    echo "✅ Storage link exists\n";
} else {
    echo "❌ Storage link not found. Run: php artisan storage:link\n";
}

echo "\nTest completed!\n";
?>