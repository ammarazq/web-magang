<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\DokumenMahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Dashboard Admin - Menampilkan statistik
     */
    public function dashboard()
    {
        $admin = Auth::user();

        // Statistics
        $stats = [
            'total_mahasiswa' => Mahasiswa::count(),
            'menunggu_verifikasi' => DokumenMahasiswa::where('status_dokumen', 'lengkap')->count(),
            'sudah_diverifikasi' => DokumenMahasiswa::where('status_dokumen', 'diverifikasi')->count(),
            'ditolak' => DokumenMahasiswa::where('status_dokumen', 'ditolak')->count(),
        ];

        return view('admin.dashboard', compact('admin', 'stats'));
    }

    /**
     * Halaman Verifikasi Dokumen - Menampilkan tabel verifikasi mahasiswa
     */
    public function verifikasiDokumenList()
    {
        $admin = Auth::user();
        
        // Get all mahasiswa dengan dokumen yang perlu diverifikasi
        $mahasiswaList = Mahasiswa::with(['dokumen', 'user'])
            ->whereHas('dokumen', function($query) {
                $query->whereIn('status_dokumen', ['lengkap', 'diverifikasi', 'ditolak']);
            })
            ->orWhereHas('dokumen', function($query) {
                $query->where('status_dokumen', 'belum_lengkap')
                    ->whereNotNull('ijazah_slta'); // Ada yang sudah upload meski belum lengkap
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Statistics untuk halaman verifikasi
        $stats = [
            'total_mahasiswa' => Mahasiswa::count(),
            'menunggu_verifikasi' => DokumenMahasiswa::where('status_dokumen', 'lengkap')->count(),
            'sudah_diverifikasi' => DokumenMahasiswa::where('status_dokumen', 'diverifikasi')->count(),
            'ditolak' => DokumenMahasiswa::where('status_dokumen', 'ditolak')->count(),
        ];

        return view('admin.verifikasi_list', compact('admin', 'mahasiswaList', 'stats'));
    }

    /**
     * Detail dokumen mahasiswa untuk verifikasi
     */
    public function detailMahasiswa($id)
    {
        $mahasiswa = Mahasiswa::with(['dokumen', 'user'])->findOrFail($id);
        $dokumen = $mahasiswa->dokumen;

        if (!$dokumen) {
            return redirect()->route('admin.dashboard')->with('error', 'Mahasiswa belum upload dokumen.');
        }

        return view('admin.detail_mahasiswa', compact('mahasiswa', 'dokumen'));
    }

    /**
     * Verifikasi dokumen mahasiswa
     */
    public function verifikasiDokumen(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:diverifikasi,ditolak',
            'catatan' => $request->status === 'ditolak' ? 'required|string|max:1000' : 'nullable|string|max:1000'
        ], [
            'catatan.required' => 'Catatan wajib diisi saat menolak dokumen.'
        ]);

        $dokumen = DokumenMahasiswa::findOrFail($id);

        // Cek apakah dokumen lengkap sebelum diverifikasi
        if ($request->status === 'diverifikasi' && !$dokumen->isDokumenLengkap()) {
            return redirect()->back()->with('error', 'Dokumen belum lengkap, tidak bisa diverifikasi.');
        }

        $dokumen->status_dokumen = $request->status;
        $dokumen->catatan_verifikasi = $request->catatan;
        $dokumen->verified_by = Auth::id();
        $dokumen->verified_at = now();
        $dokumen->save();

        $statusText = $request->status === 'diverifikasi' ? 'disetujui' : 'ditolak';
        return redirect()->route('admin.dashboard')->with('success', "Dokumen mahasiswa berhasil {$statusText}!");
    }

    /**
     * List semua mahasiswa dengan filter
     */
    public function mahasiswaList(Request $request)
    {
        $query = Mahasiswa::with(['dokumen', 'user']);

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->whereHas('dokumen', function($q) use ($request) {
                $q->where('status_dokumen', $request->status);
            });
        }

        // Filter by jenjang
        if ($request->has('jenjang') && $request->jenjang !== 'all') {
            $query->where('jenjang', $request->jenjang);
        }

        // Search by name or email
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $mahasiswaList = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.mahasiswa_list', compact('mahasiswaList'));
    }

    /**
     * View dokumen inline (untuk preview di browser)
     */
    public function viewDokumen($dokumenId, $field)
    {
        $dokumen = DokumenMahasiswa::findOrFail($dokumenId);
        
        if (!$dokumen->$field) {
            abort(404, 'Dokumen tidak ditemukan.');
        }

        $filePath = storage_path('app/public/' . $dokumen->$field);
        
        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan di server.');
        }

        $mimeType = mime_content_type($filePath);
        
        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"'
        ]);
    }

    /**
     * Download dokumen
     */
    public function downloadDokumen($dokumenId, $field)
    {
        $dokumen = DokumenMahasiswa::findOrFail($dokumenId);
        
        if (!$dokumen->$field) {
            return redirect()->back()->with('error', 'Dokumen tidak ditemukan.');
        }

        $filePath = storage_path('app/public/' . $dokumen->$field);
        
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan di server.');
        }

        return response()->download($filePath);
    }

    /**
     * ==================== USER MANAGEMENT ====================
     */

    /**
     * List semua users (admin dan mahasiswa)
     */
    public function userManagement(Request $request)
    {
        $query = User::query();

        // Filter by role
        if ($request->has('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.user_management', compact('users'));
    }

    /**
     * Form create user baru
     */
    public function createUser()
    {
        return view('admin.create_user');
    }

    /**
     * Store user baru
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,mahasiswa',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users')->with('success', 'User berhasil ditambahkan!');
    }

    /**
     * Form edit user
     */
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent editing super admin (ID 1) if needed
        if ($id == 1 && Auth::id() != 1) {
            return redirect()->route('admin.users')->with('error', 'Tidak dapat mengedit super admin.');
        }

        return view('admin.edit_user', compact('user'));
    }

    /**
     * Update user
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,mahasiswa',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users')->with('success', 'User berhasil diupdate!');
    }

    /**
     * Delete user
     */
    public function deleteUser($id)
    {
        // Prevent deleting self
        if ($id == Auth::id()) {
            return redirect()->route('admin.users')->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        // Prevent deleting super admin
        if ($id == 1) {
            return redirect()->route('admin.users')->with('error', 'Tidak dapat menghapus super admin.');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User berhasil dihapus!');
    }
}

