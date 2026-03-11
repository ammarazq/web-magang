<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\GoogleDriveService;
use App\Models\DokumenMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GoogleDriveController extends Controller
{
    protected $driveService;

    public function __construct(GoogleDriveService $driveService)
    {
        $this->driveService = $driveService;
    }

    /**
     * Check Google Drive configuration status
     */
    public function status()
    {
        $config = [
            'enabled' => config('google-drive.enabled'),
            'auto_backup' => config('google-drive.auto_backup'),
            'configured' => file_exists(storage_path('app/google-drive/credentials.json')),
            'authenticated' => file_exists(storage_path('app/google-drive/token.json')),
        ];

        return view('admin.google-drive.status', compact('config'));
    }

    /**
     * Redirect to Google OAuth authorization page
     */
    public function authorize()
    {
        try {
            $authUrl = $this->driveService->getAuthUrl();
            return redirect($authUrl);
        } catch (\Exception $e) {
            Log::error('Google Drive authorization error: ' . $e->getMessage());
            return back()->with('error', 'Gagal memulai proses otorisasi: ' . $e->getMessage());
        }
    }

    /**
     * Handle OAuth callback from Google
     */
    public function callback(Request $request)
    {
        try {
            $code = $request->input('code');
            
            if (!$code) {
                throw new \Exception('Kode otorisasi tidak ditemukan');
            }

            $this->driveService->handleCallback($code);

            return redirect()->route('admin.google-drive.status')
                ->with('success', 'Otorisasi Google Drive berhasil! Sistem siap melakukan backup.');
        } catch (\Exception $e) {
            Log::error('Google Drive callback error: ' . $e->getMessage());
            return redirect()->route('admin.google-drive.status')
                ->with('error', 'Gagal menyelesaikan otorisasi: ' . $e->getMessage());
        }
    }

    /**
     * Manually trigger backup for a specific document
     */
    public function backup($id)
    {
        try {
            $dokumen = DokumenMahasiswa::with('mahasiswa')->findOrFail($id);

            if (!config('google-drive.enabled')) {
                return back()->with('error', 'Google Drive tidak diaktifkan di konfigurasi');
            }

            $result = $this->driveService->backupDokumenMahasiswa($dokumen);

            if ($result['success']) {
                return back()->with('success', 
                    "Backup berhasil! {$result['uploaded_count']} dari {$result['total_count']} dokumen ter-upload ke Google Drive."
                );
            } else {
                return back()->with('error', 'Backup gagal: ' . ($result['error'] ?? 'Unknown error'));
            }
        } catch (\Exception $e) {
            Log::error('Manual backup error: ' . $e->getMessage());
            return back()->with('error', 'Gagal melakukan backup: ' . $e->getMessage());
        }
    }

    /**
     * Manually trigger backup for all documents
     */
    public function backupAll()
    {
        try {
            if (!config('google-drive.enabled')) {
                return back()->with('error', 'Google Drive tidak diaktifkan di konfigurasi');
            }

            $documents = DokumenMahasiswa::with('mahasiswa')->get();
            $successCount = 0;
            $failCount = 0;
            $totalUploaded = 0;

            foreach ($documents as $dokumen) {
                $result = $this->driveService->backupDokumenMahasiswa($dokumen);
                
                if ($result['success']) {
                    $successCount++;
                    $totalUploaded += $result['uploaded_count'];
                } else {
                    $failCount++;
                    Log::warning("Backup failed for document ID {$dokumen->id}: " . ($result['error'] ?? 'Unknown'));
                }
            }

            return back()->with('success', 
                "Backup selesai! {$successCount} mahasiswa berhasil, {$failCount} gagal. Total {$totalUploaded} file ter-upload."
            );
        } catch (\Exception $e) {
            Log::error('Backup all error: ' . $e->getMessage());
            return back()->with('error', 'Gagal melakukan backup semua dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Download a file from Google Drive
     */
    public function download($id, $fieldName)
    {
        try {
            $dokumen = DokumenMahasiswa::findOrFail($id);
            
            if (!$dokumen->is_backed_up) {
                return back()->with('error', 'Dokumen belum di-backup ke Google Drive');
            }

            $googleDriveFiles = $dokumen->google_drive_files ?? [];
            
            if (!isset($googleDriveFiles[$fieldName])) {
                return back()->with('error', 'File tidak ditemukan di Google Drive');
            }

            $fileId = $googleDriveFiles[$fieldName];
            $content = $this->driveService->downloadFile($fileId);
            
            // Get original filename from database
            $filename = basename($dokumen->{$fieldName});

            return response($content)
                ->header('Content-Type', 'application/octet-stream')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        } catch (\Exception $e) {
            Log::error('Google Drive download error: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengunduh file: ' . $e->getMessage());
        }
    }

    /**
     * Delete backup from Google Drive
     */
    public function deleteBackup($id)
    {
        try {
            $dokumen = DokumenMahasiswa::findOrFail($id);
            
            if (!$dokumen->is_backed_up) {
                return back()->with('info', 'Dokumen tidak memiliki backup di Google Drive');
            }

            // Delete folder and all files
            if ($dokumen->google_drive_folder_id) {
                $this->driveService->deleteFile($dokumen->google_drive_folder_id);
            }

            // Update database
            $dokumen->update([
                'google_drive_folder_id' => null,
                'google_drive_files' => null,
                'is_backed_up' => false,
                'last_backup_at' => null,
            ]);

            return back()->with('success', 'Backup berhasil dihapus dari Google Drive');
        } catch (\Exception $e) {
            Log::error('Delete backup error: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus backup: ' . $e->getMessage());
        }
    }

    /**
     * Show backup statistics
     */
    public function statistics()
    {
        $stats = [
            'total_documents' => DokumenMahasiswa::count(),
            'backed_up' => DokumenMahasiswa::where('is_backed_up', true)->count(),
            'not_backed_up' => DokumenMahasiswa::where('is_backed_up', false)->count(),
            'last_backup' => DokumenMahasiswa::whereNotNull('last_backup_at')
                ->orderBy('last_backup_at', 'desc')
                ->first(),
            'total_files' => 0,
        ];

        // Count total files backed up
        $backedUpDocs = DokumenMahasiswa::where('is_backed_up', true)->get();
        foreach ($backedUpDocs as $doc) {
            if ($doc->google_drive_files && is_array($doc->google_drive_files)) {
                $stats['total_files'] += count($doc->google_drive_files);
            }
        }

        return view('admin.google-drive.statistics', compact('stats'));
    }
}
