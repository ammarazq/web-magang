<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Google Drive Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk Google Drive API integration.
    | Untuk mendapatkan credentials, ikuti langkah-langkah di GOOGLE_DRIVE_SETUP.md
    |
    */

    // Enable/Disable Google Drive Backup
    'enabled' => env('GOOGLE_DRIVE_ENABLED', false),

    // Google Cloud Project Client ID
    'client_id' => env('GOOGLE_DRIVE_CLIENT_ID'),

    // Google Cloud Project Client Secret
    'client_secret' => env('GOOGLE_DRIVE_CLIENT_SECRET'),

    // Redirect URI setelah OAuth (harus sama dengan yang di Google Cloud Console)
    'redirect_uri' => env('GOOGLE_DRIVE_REDIRECT_URI', env('APP_URL') . '/admin/google-drive/callback'),

    // Path ke file credentials JSON dari Google Cloud Console
    'credentials_path' => storage_path('app/google-drive/credentials.json'),

    // Path untuk menyimpan token setelah OAuth
    'token_path' => storage_path('app/google-drive/token.json'),

    // Nama folder root di Google Drive untuk menyimpan semua dokumen
    'root_folder_name' => env('GOOGLE_DRIVE_ROOT_FOLDER', 'Dokumen Mahasiswa Portal'),

    // Scopes yang dibutuhkan
    'scopes' => [
        \Google\Service\Drive::DRIVE_FILE, // Akses file yang dibuat oleh aplikasi
        \Google\Service\Drive::DRIVE_METADATA_READONLY, // Baca metadata
    ],

    // Application Name
    'application_name' => env('GOOGLE_DRIVE_APP_NAME', 'Portal Mahasiswa'),

    // Auto backup saat upload (true) atau manual trigger (false)
    'auto_backup' => env('GOOGLE_DRIVE_AUTO_BACKUP', true),

    // Retry upload jika gagal
    'retry_attempts' => env('GOOGLE_DRIVE_RETRY', 3),

    // Timeout untuk setiap request (detik)
    'timeout' => env('GOOGLE_DRIVE_TIMEOUT', 120),
];
