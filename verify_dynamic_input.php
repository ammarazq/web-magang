<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Hash;

echo "╔══════════════════════════════════════════════════════════╗\n";
echo "║    VERIFIKASI DYNAMIC DATA INPUT - SISTEM PENDAFTARAN    ║\n";
echo "╚══════════════════════════════════════════════════════════╝\n\n";

try {
    // 1. Total records
    echo "📊 STATISTIK DATABASE\n";
    echo "═══════════════════════════════════════════════════════════\n";
    $total = Mahasiswa::count();
    echo "✓ Total Mahasiswa Terdaftar: {$total}\n";
    
    // 2. Per jenis pendaftaran
    $sarjana = Mahasiswa::sarjana()->count();
    $magister = Mahasiswa::magister()->count();
    $doktoral = Mahasiswa::doktoral()->count();
    echo "✓ Sarjana (D3/D4/S1):        {$sarjana}\n";
    echo "✓ Magister (S2):             {$magister}\n";
    echo "✓ Doktoral (S3):             {$doktoral}\n";
    echo "\n";
    
    // 3. Per status verifikasi
    echo "📋 STATUS VERIFIKASI\n";
    echo "═══════════════════════════════════════════════════════════\n";
    $pending = Mahasiswa::pending()->count();
    $verified = Mahasiswa::where('status_verifikasi', 'verified')->count();
    $rejected = Mahasiswa::where('status_verifikasi', 'rejected')->count();
    echo "✓ Pending:   {$pending}\n";
    echo "✓ Verified:  {$verified}\n";
    echo "✓ Rejected:  {$rejected}\n";
    echo "\n";
    
    // 4. Per kewarganegaraan
    echo "🌍 KEWARGANEGARAAN\n";
    echo "═══════════════════════════════════════════════════════════\n";
    $wni = Mahasiswa::where('kewarganegaraan', 'WNI')->count();
    $wna = Mahasiswa::where('kewarganegaraan', 'WNA')->count();
    echo "✓ WNI (Warga Negara Indonesia): {$wni}\n";
    echo "✓ WNA (Warga Negara Asing):     {$wna}\n";
    echo "\n";
    
    // 5. Latest entries (5 terakhir)
    echo "🕐 DATA TERBARU (5 Pendaftar Terakhir)\n";
    echo "═══════════════════════════════════════════════════════════\n";
    $latest = Mahasiswa::latest()->take(5)->get();
    if ($latest->isEmpty()) {
        echo "⚠ Belum ada data pendaftaran\n";
    } else {
        foreach ($latest as $index => $mhs) {
            $num = $index + 1;
            echo "{$num}. {$mhs->nama_lengkap}\n";
            echo "   Email:  {$mhs->email}\n";
            echo "   Jenis:  {$mhs->jenis_pendaftaran}\n";
            echo "   Status: {$mhs->status_verifikasi}\n";
            
            // Handle tanggal_daftar yang mungkin NULL
            $tanggalDaftar = $mhs->tanggal_daftar 
                ? $mhs->tanggal_daftar->format('d M Y H:i:s')
                : ($mhs->created_at ? $mhs->created_at->format('d M Y H:i:s') : 'N/A');
            echo "   Daftar: {$tanggalDaftar}\n";
            echo "\n";
        }
    }
    
    // 6. Password verification check
    echo "🔒 VERIFIKASI KEAMANAN PASSWORD\n";
    echo "═══════════════════════════════════════════════════════════\n";
    $sample = Mahasiswa::first();
    if ($sample) {
        $passwordLength = strlen($sample->password);
        $isHashed = str_starts_with($sample->password, '$2y$');
        
        echo "✓ Sample Password: " . substr($sample->password, 0, 20) . "...\n";
        echo "✓ Password Length: {$passwordLength} karakter\n";
        echo "✓ Password Hashed: " . ($isHashed ? 'YES ✓' : 'NO ✗') . "\n";
        
        if (!$isHashed) {
            echo "⚠ WARNING: Password tidak di-hash! Security risk!\n";
        }
    }
    echo "\n";
    
    // 7. Unique constraint check
    echo "🔑 VERIFIKASI UNIQUE CONSTRAINTS\n";
    echo "═══════════════════════════════════════════════════════════\n";
    
    // Email uniqueness
    $emailDuplicates = Mahasiswa::select('email')
        ->groupBy('email')
        ->havingRaw('COUNT(*) > 1')
        ->count();
    echo "✓ Email Duplikat: " . ($emailDuplicates == 0 ? 'Tidak ada ✓' : "$emailDuplicates ✗") . "\n";
    
    // NIK uniqueness (only WNI)
    $nikDuplicates = Mahasiswa::whereNotNull('nik')
        ->select('nik')
        ->groupBy('nik')
        ->havingRaw('COUNT(*) > 1')
        ->count();
    echo "✓ NIK Duplikat:   " . ($nikDuplicates == 0 ? 'Tidak ada ✓' : "$nikDuplicates ✗") . "\n";
    
    // Passport uniqueness (only WNA)
    $passportDuplicates = Mahasiswa::whereNotNull('passport')
        ->select('passport')
        ->groupBy('passport')
        ->havingRaw('COUNT(*) > 1')
        ->count();
    echo "✓ Passport Duplikat: " . ($passportDuplicates == 0 ? 'Tidak ada ✓' : "$passportDuplicates ✗") . "\n";
    echo "\n";
    
    // 8. Field population check
    echo "📝 VERIFIKASI FIELD POPULATION\n";
    echo "═══════════════════════════════════════════════════════════\n";
    $sample = Mahasiswa::first();
    if ($sample) {
        $requiredFields = [
            'nama_lengkap', 'tempat_lahir', 'tanggal_lahir', 
            'jenis_kelamin', 'nama_ibu', 'agama', 
            'kewarganegaraan', 'no_hp', 'email', 
            'password', 'alamat', 'jenis_pendaftaran', 
            'status_verifikasi'
        ];
        
        $populatedCount = 0;
        foreach ($requiredFields as $field) {
            if (!empty($sample->$field)) {
                $populatedCount++;
            }
        }
        
        $percentage = round(($populatedCount / count($requiredFields)) * 100, 1);
        echo "✓ Field Wajib Terisi: {$populatedCount}/" . count($requiredFields) . " ({$percentage}%)\n";
        
        if ($percentage < 100) {
            echo "⚠ WARNING: Ada field wajib yang kosong!\n";
        }
    }
    echo "\n";
    
    // 9. Conditional field check (Sarjana)
    echo "🎓 VERIFIKASI FIELD KHUSUS SARJANA\n";
    echo "═══════════════════════════════════════════════════════════\n";
    $sarjanaWithFields = Mahasiswa::sarjana()
        ->whereNotNull('jalur_program')
        ->whereNotNull('jenjang')
        ->whereNotNull('program_studi')
        ->count();
    $totalSarjana = Mahasiswa::sarjana()->count();
    echo "✓ Sarjana dengan field lengkap: {$sarjanaWithFields}/{$totalSarjana}\n";
    echo "\n";
    
    // 10. Conditional field check (Magister/Doktoral)
    echo "👔 VERIFIKASI FIELD KHUSUS MAGISTER/DOKTORAL\n";
    echo "═══════════════════════════════════════════════════════════\n";
    $magisterWithStatus = Mahasiswa::magister()
        ->whereNotNull('status_kawin')
        ->count();
    $doktoralWithStatus = Mahasiswa::doktoral()
        ->whereNotNull('status_kawin')
        ->count();
    echo "✓ Magister dengan status_kawin: {$magisterWithStatus}/{$magister}\n";
    echo "✓ Doktoral dengan status_kawin: {$doktoralWithStatus}/{$doktoral}\n";
    echo "\n";
    
    // Final summary
    echo "╔══════════════════════════════════════════════════════════╗\n";
    echo "║                    HASIL VERIFIKASI                      ║\n";
    echo "╚══════════════════════════════════════════════════════════╝\n\n";
    
    $allChecks = [
        'Database connected' => true,
        'Data exists' => $total > 0,
        'Password hashed' => $sample && str_starts_with($sample->password, '$2y$'),
        'No email duplicates' => $emailDuplicates == 0,
        'No NIK duplicates' => $nikDuplicates == 0,
        'No passport duplicates' => $passportDuplicates == 0,
    ];
    
    $passedChecks = count(array_filter($allChecks));
    $totalChecks = count($allChecks);
    
    foreach ($allChecks as $check => $passed) {
        $status = $passed ? '✓ PASS' : '✗ FAIL';
        $color = $passed ? '' : '⚠ ';
        echo "{$color}{$status} - {$check}\n";
    }
    
    echo "\n";
    echo "═══════════════════════════════════════════════════════════\n";
    echo "Score: {$passedChecks}/{$totalChecks} checks passed\n";
    
    if ($passedChecks == $totalChecks) {
        echo "✅ SEMUA VERIFIKASI BERHASIL! SISTEM SIAP DIGUNAKAN.\n";
    } else {
        echo "⚠ ADA MASALAH YANG PERLU DIPERBAIKI!\n";
    }
    echo "═══════════════════════════════════════════════════════════\n";

} catch (\Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}

echo "\n✅ Verifikasi selesai.\n";
