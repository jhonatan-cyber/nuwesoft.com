<?php

// Soporta dos formatos: variables separadas (CLOUDINARY_CLOUD_NAME/KEY/SECRET) o single URL (CLOUDINARY_URL=cloudinary://key:secret@cloud)
$cloudinaryUrl = env('CLOUDINARY_URL', '');
$parsed = $cloudinaryUrl ? parse_url($cloudinaryUrl) : null;

return [
    'cloud_name' => env('CLOUDINARY_CLOUD_NAME', $parsed['host'] ?? ''),
    'api_key' => env('CLOUDINARY_API_KEY', $parsed['user'] ?? ''),
    'api_secret' => env('CLOUDINARY_API_SECRET', $parsed['pass'] ?? ''),
    'secure' => env('CLOUDINARY_SECURE', true),
    // Prefijo por entorno para no contaminar prod desde dev (ej: nuwesoft/local, nuwesoft/production)
    'folder_prefix' => env('CLOUDINARY_FOLDER_PREFIX', 'nuwesoft/' . env('APP_ENV', 'production')),
];
