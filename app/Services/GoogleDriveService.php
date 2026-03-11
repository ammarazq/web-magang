<?php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Google\Service\Drive\Permission;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Exception;

class GoogleDriveService
{
    protected $client;
    protected $service;
    protected $rootFolderId;

    public function __construct()
    {
        $this->initializeClient();
    }

    /**
     * Initialize Google Client
     */
    protected function initializeClient()
    {
        try {
            $this->client = new Client();
            $this->client->setApplicationName(config('google-drive.application_name'));
            $this->client->setScopes(config('google-drive.scopes'));
            
            // Set credentials
            $credentialsPath = config('google-drive.credentials_path');
            if (file_exists($credentialsPath)) {
                $this->client->setAuthConfig($credentialsPath);
            }
            
            // Set redirect URI
            $this->client->setRedirectUri(config('google-drive.redirect_uri'));
            
            $this->client->setAccessType('offline');
            $this->client->setPrompt('select_account consent');

            // Load token jika ada
            $tokenPath = config('google-drive.token_path');
            if (file_exists($tokenPath)) {
                $accessToken = json_decode(file_get_contents($tokenPath), true);
                $this->client->setAccessToken($accessToken);
            }

            // Refresh token jika expired
            if ($this->client->isAccessTokenExpired()) {
                if ($this->client->getRefreshToken()) {
                    $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
                    file_put_contents($tokenPath, json_encode($this->client->getAccessToken()));
                }
            }

            $this->service = new Drive($this->client);
        } catch (Exception $e) {
            Log::error('Google Drive initialization failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Check if Google Drive is properly configured and authenticated
     */
    public function isConfigured(): bool
    {
        try {
            if (!config('google-drive.enabled')) {
                return false;
            }

            $tokenPath = config('google-drive.token_path');
            if (!file_exists($tokenPath)) {
                return false;
            }

            return !$this->client->isAccessTokenExpired();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Get OAuth URL for authorization
     */
    public function getAuthUrl(): string
    {
        return $this->client->createAuthUrl();
    }

    /**
     * Handle OAuth callback
     */
    public function handleCallback(string $code): bool
    {
        try {
            $accessToken = $this->client->fetchAccessTokenWithAuthCode($code);
            
            if (isset($accessToken['error'])) {
                throw new Exception($accessToken['error']);
            }

            // Save token
            $tokenPath = config('google-drive.token_path');
            file_put_contents($tokenPath, json_encode($accessToken));

            return true;
        } catch (Exception $e) {
            Log::error('Google Drive OAuth failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get or create root folder
     */
    protected function getRootFolderId(): string
    {
        if ($this->rootFolderId) {
            return $this->rootFolderId;
        }

        try {
            $folderName = config('google-drive.root_folder_name');
            
            // Cari folder yang sudah ada
            $response = $this->service->files->listFiles([
                'q' => "name='{$folderName}' and mimeType='application/vnd.google-apps.folder' and trashed=false",
                'spaces' => 'drive',
                'fields' => 'files(id, name)',
            ]);

            if (count($response->files) > 0) {
                $this->rootFolderId = $response->files[0]->id;
                return $this->rootFolderId;
            }

            // Buat folder baru jika belum ada
            $fileMetadata = new DriveFile([
                'name' => $folderName,
                'mimeType' => 'application/vnd.google-apps.folder',
            ]);

            $folder = $this->service->files->create($fileMetadata, [
                'fields' => 'id',
            ]);

            $this->rootFolderId = $folder->id;
            return $this->rootFolderId;
        } catch (Exception $e) {
            Log::error('Failed to get/create root folder: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create folder for mahasiswa
     */
    public function createMahasiswaFolder(string $mahasiswaName, string $nim): string
    {
        try {
            $rootFolderId = $this->getRootFolderId();
            $folderName = "{$mahasiswaName} - {$nim}";

            // Cek apakah folder sudah ada
            $response = $this->service->files->listFiles([
                'q' => "name='{$folderName}' and '{$rootFolderId}' in parents and mimeType='application/vnd.google-apps.folder' and trashed=false",
                'spaces' => 'drive',
                'fields' => 'files(id, name)',
            ]);

            if (count($response->files) > 0) {
                return $response->files[0]->id;
            }

            // Buat folder baru
            $fileMetadata = new DriveFile([
                'name' => $folderName,
                'mimeType' => 'application/vnd.google-apps.folder',
                'parents' => [$rootFolderId],
            ]);

            $folder = $this->service->files->create($fileMetadata, [
                'fields' => 'id',
            ]);

            return $folder->id;
        } catch (Exception $e) {
            Log::error('Failed to create mahasiswa folder: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Upload file to Google Drive
     */
    public function uploadFile(string $localPath, string $fileName, string $folderId, string $mimeType = null): array
    {
        try {
            if (!file_exists($localPath)) {
                throw new Exception("File tidak ditemukan: {$localPath}");
            }

            $fileMetadata = new DriveFile([
                'name' => $fileName,
                'parents' => [$folderId],
            ]);

            $content = file_get_contents($localPath);
            
            if (!$mimeType) {
                $mimeType = mime_content_type($localPath);
            }

            $file = $this->service->files->create($fileMetadata, [
                'data' => $content,
                'mimeType' => $mimeType,
                'uploadType' => 'multipart',
                'fields' => 'id, name, webViewLink, webContentLink, size, createdTime',
            ]);

            return [
                'id' => $file->id,
                'name' => $file->name,
                'webViewLink' => $file->webViewLink,
                'webContentLink' => $file->webContentLink ?? null,
                'size' => $file->size,
                'createdTime' => $file->createdTime,
            ];
        } catch (Exception $e) {
            Log::error('Failed to upload file to Google Drive: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update existing file in Google Drive
     */
    public function updateFile(string $fileId, string $localPath, string $mimeType = null): array
    {
        try {
            if (!file_exists($localPath)) {
                throw new Exception("File tidak ditemukan: {$localPath}");
            }

            $content = file_get_contents($localPath);
            
            if (!$mimeType) {
                $mimeType = mime_content_type($localPath);
            }

            $file = $this->service->files->update($fileId, new DriveFile(), [
                'data' => $content,
                'mimeType' => $mimeType,
                'uploadType' => 'multipart',
                'fields' => 'id, name, webViewLink, webContentLink, size, modifiedTime',
            ]);

            return [
                'id' => $file->id,
                'name' => $file->name,
                'webViewLink' => $file->webViewLink,
                'webContentLink' => $file->webContentLink ?? null,
                'size' => $file->size,
                'modifiedTime' => $file->modifiedTime,
            ];
        } catch (Exception $e) {
            Log::error('Failed to update file in Google Drive: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete file from Google Drive
     */
    public function deleteFile(string $fileId): bool
    {
        try {
            $this->service->files->delete($fileId);
            return true;
        } catch (Exception $e) {
            Log::error('Failed to delete file from Google Drive: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Download file from Google Drive
     */
    public function downloadFile(string $fileId, string $savePath): bool
    {
        try {
            $response = $this->service->files->get($fileId, [
                'alt' => 'media',
            ]);

            $content = $response->getBody()->getContents();
            file_put_contents($savePath, $content);

            return true;
        } catch (Exception $e) {
            Log::error('Failed to download file from Google Drive: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get file info
     */
    public function getFileInfo(string $fileId): ?array
    {
        try {
            $file = $this->service->files->get($fileId, [
                'fields' => 'id, name, mimeType, size, webViewLink, webContentLink, createdTime, modifiedTime',
            ]);

            return [
                'id' => $file->id,
                'name' => $file->name,
                'mimeType' => $file->mimeType,
                'size' => $file->size,
                'webViewLink' => $file->webViewLink,
                'webContentLink' => $file->webContentLink ?? null,
                'createdTime' => $file->createdTime,
                'modifiedTime' => $file->modifiedTime,
            ];
        } catch (Exception $e) {
            Log::error('Failed to get file info from Google Drive: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Make file publicly accessible
     */
    public function makePublic(string $fileId): bool
    {
        try {
            $permission = new Permission([
                'type' => 'anyone',
                'role' => 'reader',
            ]);

            $this->service->permissions->create($fileId, $permission);
            return true;
        } catch (Exception $e) {
            Log::error('Failed to make file public: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Backup all dokumen mahasiswa to Google Drive
     */
    public function backupDokumenMahasiswa(\App\Models\DokumenMahasiswa $dokumen): bool
    {
        try {
            $mahasiswa = $dokumen->mahasiswa;
            
            // Create folder for mahasiswa if not exists
            if (!$dokumen->google_drive_folder_id) {
                $folderId = $this->createMahasiswaFolder(
                    $mahasiswa->nama_lengkap,
                    $mahasiswa->id
                );
                $dokumen->google_drive_folder_id = $folderId;
                $dokumen->save();
            } else {
                $folderId = $dokumen->google_drive_folder_id;
            }

            $googleDriveFiles = json_decode($dokumen->google_drive_files, true) ?? [];
            $uploadedCount = 0;

            // Get all uploaded document fields
            $documentFields = $dokumen->getDokumenFields();

            foreach ($documentFields as $field) {
                $filename = $dokumen->$field;
                
                if ($filename) {
                    $localPath = public_path('dokumen_mahasiswa/' . $filename);
                    
                    if (file_exists($localPath)) {
                        try {
                            // Check if file already uploaded
                            if (isset($googleDriveFiles[$field])) {
                                // Update existing file
                                $fileInfo = $this->updateFile(
                                    $googleDriveFiles[$field],
                                    $localPath
                                );
                            } else {
                                // Upload new file
                                $fileInfo = $this->uploadFile(
                                    $localPath,
                                    $filename,
                                    $folderId
                                );
                                $googleDriveFiles[$field] = $fileInfo['id'];
                            }
                            
                            $uploadedCount++;
                        } catch (Exception $e) {
                            Log::error("Failed to backup {$field}: " . $e->getMessage());
                        }
                    }
                }
            }

            // Update database
            $dokumen->google_drive_files = json_encode($googleDriveFiles);
            $dokumen->is_backed_up = $uploadedCount > 0;
            $dokumen->last_backup_at = now();
            $dokumen->save();

            return true;
        } catch (Exception $e) {
            Log::error('Failed to backup dokumen mahasiswa: ' . $e->getMessage());
            return false;
        }
    }
}
