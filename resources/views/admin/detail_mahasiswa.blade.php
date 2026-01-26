<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Mahasiswa - {{ $mahasiswa->nama_lengkap }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .doc-card {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            border: 2px solid #dee2e6;
        }
        .doc-uploaded {
            border-color: #28a745;
            background: #d4edda;
        }
        .doc-missing {
            border-color: #ffc107;
            background: #fff3cd;
        }
    </style>
</head>
<body>
    @include('admin.navbar')

    <div class="container my-5">
        <div class="row">
            <!-- Info Mahasiswa -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-user"></i> Data Mahasiswa</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <td><strong>ID</strong></td>
                                <td>{{ $mahasiswa->id }}</td>
                            </tr>
                            <tr>
                                <td><strong>Nama</strong></td>
                                <td>{{ $mahasiswa->nama_lengkap }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email</strong></td>
                                <td>{{ $mahasiswa->email }}</td>
                            </tr>
                            <tr>
                                <td><strong>NIK</strong></td>
                                <td>{{ $mahasiswa->nik ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Jenjang</strong></td>
                                <td><span class="badge bg-info">{{ $mahasiswa->jenjang }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Program Studi</strong></td>
                                <td>{{ $mahasiswa->program_studi }}</td>
                            </tr>
                            <tr>
                                <td><strong>Jalur Program</strong></td>
                                <td><span class="badge bg-secondary">{{ $mahasiswa->jalur_program }}</span></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Status Dokumen -->
                <div class="card shadow-sm mt-3">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-chart-pie"></i> Status Dokumen</h5>
                    </div>
                    <div class="card-body">
                        <h6>Kelengkapan: {{ $dokumen->getPersentaseKelengkapan() }}%</h6>
                        <div class="progress mb-3" style="height: 25px;">
                            <div class="progress-bar" role="progressbar" style="width: {{ $dokumen->getPersentaseKelengkapan() }}%">
                                {{ $dokumen->getPersentaseKelengkapan() }}%
                            </div>
                        </div>

                        <p class="mb-2"><strong>Status Verifikasi:</strong></p>
                        @if($dokumen->status_dokumen === 'belum_lengkap')
                            <span class="badge bg-warning">Belum Lengkap</span>
                        @elseif($dokumen->status_dokumen === 'lengkap')
                            <span class="badge bg-info">Lengkap - Menunggu Verifikasi</span>
                        @elseif($dokumen->status_dokumen === 'diverifikasi')
                            <span class="badge bg-success">Diverifikasi</span>
                        @else
                            <span class="badge bg-danger">Ditolak</span>
                        @endif

                        @if($dokumen->verified_by)
                            <hr>
                            <p class="mb-1 small"><strong>Diverifikasi oleh:</strong></p>
                            <p class="mb-1">{{ $dokumen->verifiedBy->name }}</p>
                            <p class="text-muted small">{{ $dokumen->verified_at->format('d M Y, H:i') }}</p>
                        @endif

                        @if($dokumen->catatan_verifikasi)
                            <hr>
                            <p class="mb-1 small"><strong>Catatan Admin:</strong></p>
                            <p class="small">{{ $dokumen->catatan_verifikasi }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Daftar Dokumen -->
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-folder-open"></i> Dokumen Mahasiswa</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $documents = [];
                            
                            // Dokumen berdasarkan jenjang dan jalur program
                            if(in_array($mahasiswa->jenjang, ['D3', 'D4'])) {
                                // D3/D4 (RPL atau Non RPL - keduanya sama, 5 dokumen)
                                $documents = [
                                    'formulir_pendaftaran' => 'Formulir Pendaftaran',
                                    'formulir_keabsahan' => 'Lembar Keabsahan Dokumen',
                                    'foto_formal' => 'Scan Foto Formal',
                                    'ktp' => 'Scan KTP Asli',
                                    'ijazah_slta' => 'FC Ijazah SLTA Legalisir',
                                ];
                            }
                            elseif($mahasiswa->jenjang === 'S1' && $mahasiswa->jalur_program === 'Non RPL') {
                                // S1 Non RPL (5 dokumen)
                                $documents = [
                                    'formulir_pendaftaran' => 'Formulir Pendaftaran',
                                    'formulir_keabsahan' => 'Lembar Keabsahan Dokumen',
                                    'foto_formal' => 'Scan Foto Formal',
                                    'ktp' => 'Scan KTP Asli',
                                    'ijazah_slta' => 'FC Ijazah SLTA Legalisir',
                                ];
                            }
                            elseif($mahasiswa->jenjang === 'S1' && $mahasiswa->jalur_program === 'RPL') {
                                // S1 RPL (6 wajib + 1 opsional)
                                $documents = [
                                    'formulir_pendaftaran' => 'Formulir Pendaftaran',
                                    'formulir_keabsahan' => 'Lembar Keabsahan Dokumen',
                                    'foto_formal' => 'Scan Foto Formal',
                                    'ktp' => 'Scan KTP Asli',
                                    'ijazah_slta_asli' => 'Ijazah SLTA Asli',
                                    'transkrip_nilai' => 'Transkrip Nilai D3/D4/S1',
                                    'ijazah_d3_d4_s1' => 'FC Ijazah D3/D4/S1 Legalisir (jika sudah lulus)',
                                ];
                            }
                            elseif($mahasiswa->jenjang === 'S2') {
                                // S2 Magister (14 dokumen)
                                $documents = [
                                    'formulir_pendaftaran' => 'Formulir Pendaftaran',
                                    'formulir_keabsahan' => 'Lembar Keabsahan Dokumen',
                                    'foto_formal' => 'Scan Foto Formal',
                                    'ktp' => 'Scan KTP Asli',
                                    'ijazah_slta' => 'FC Ijazah SLTA Legalisir',
                                    'sertifikat_akreditasi_prodi' => 'Sertifikat Akreditasi Prodi D4/S1',
                                    'transkrip_d3_d4_s1' => 'FC Ijazah dan Transkrip Nilai D4/S1 Legalisir',
                                    'riwayat_hidup' => 'Daftar Riwayat Hidup',
                                    'sertifikat_toefl' => 'Sertifikat TOEFL min. 450 (maks. 2 tahun terakhir)',
                                    'rancangan_penelitian' => 'Rancangan Penelitian Singkat',
                                    'sk_mampu_komputer' => 'SK Mampu Menggunakan Komputer',
                                    'bukti_tes_tpa' => 'Bukti telah mengikuti Tes Potensi Akademik (TPA)',
                                    'seleksi_tes_substansi' => 'Mengikuti Seleksi Tes Substansi',
                                    'formulir_isian_foto' => 'Formulir Isian Foto',
                                ];
                            }
                            elseif($mahasiswa->jenjang === 'S3') {
                                // S3 Doktoral (13 dokumen)
                                $documents = [
                                    'formulir_pendaftaran' => 'Formulir Pendaftaran',
                                    'formulir_keabsahan' => 'Lembar Keabsahan Dokumen',
                                    'foto_formal' => 'Scan Foto Formal',
                                    'ktp' => 'Scan KTP Asli',
                                    'ijazah_slta' => 'FC Ijazah SLTA Legalisir',
                                    'sertifikat_akreditasi_prodi' => 'Sertifikat Akreditasi Prodi S2',
                                    'transkrip_d3_d4_s1' => 'FC Ijazah dan Transkrip Nilai S2 Legalisir',
                                    'riwayat_hidup' => 'Daftar Riwayat Hidup',
                                    'sertifikat_toefl' => 'Sertifikat TOEFL min. 500 (maks. 2 tahun terakhir)',
                                    'rancangan_penelitian' => 'Rancangan Penelitian Singkat',
                                    'sk_mampu_komputer' => 'SK Mampu Menggunakan Komputer',
                                    'bukti_tes_tpa' => 'Bukti telah mengikuti Tes Potensi Akademik (TPA)',
                                    'seleksi_tes_substansi' => 'Mengikuti Seleksi Tes Substansi',
                                ];
                            }
                        @endphp

                        @foreach($documents as $field => $name)
                            <div class="doc-card {{ $dokumen->$field ? 'doc-uploaded' : 'doc-missing' }}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        @if($dokumen->$field)
                                            <i class="fas fa-check-circle text-success"></i>
                                        @else
                                            <i class="fas fa-times-circle text-warning"></i>
                                        @endif
                                        <strong>{{ $name }}</strong>
                                    </div>
                                    <div>
                                        @if($dokumen->$field)
                                            <a href="{{ route('admin.view', ['dokumenId' => $dokumen->id, 'field' => $field]) }}" target="_blank" class="btn btn-sm btn-outline-primary me-2">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                            <a href="{{ route('admin.download', ['dokumenId' => $dokumen->id, 'field' => $field]) }}" class="btn btn-sm btn-outline-success">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        @else
                                            <span class="badge bg-warning">Belum diupload</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Form Verifikasi -->
                @if($dokumen->status_dokumen === 'lengkap' || $dokumen->status_dokumen === 'ditolak')
                <div class="card shadow-sm mt-3">
                    <div class="card-header bg-warning">
                        <h5 class="mb-0"><i class="fas fa-check-double"></i> Verifikasi Dokumen</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.verifikasi', $dokumen->id) }}" method="POST" id="verifikasiForm">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label"><strong>Status Verifikasi</strong></label>
                                <select name="status" id="statusSelect" class="form-select" required>
                                    <option value="">-- Pilih Status --</option>
                                    <option value="diverifikasi">Disetujui</option>
                                    <option value="ditolak">Ditolak</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><strong>Catatan <span id="catatanRequired" class="text-danger" style="display:none;">*</span></strong></label>
                                <textarea name="catatan" id="catatanTextarea" class="form-control" rows="4" placeholder="Berikan catatan untuk mahasiswa (wajib jika ditolak)...">{{ $dokumen->catatan_verifikasi }}</textarea>
                                <small class="text-muted" id="catatanHelp">Catatan akan ditampilkan ke mahasiswa</small>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Verifikasi
                            </button>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </form>
                    </div>
                </div>
                
                <script>
                    document.getElementById('statusSelect').addEventListener('change', function() {
                        const catatan = document.getElementById('catatanTextarea');
                        const catatanRequired = document.getElementById('catatanRequired');
                        const catatanHelp = document.getElementById('catatanHelp');
                        
                        if (this.value === 'ditolak') {
                            catatan.required = true;
                            catatanRequired.style.display = 'inline';
                            catatanHelp.innerHTML = '<span class="text-danger">Catatan WAJIB diisi saat menolak dokumen</span>';
                        } else {
                            catatan.required = false;
                            catatanRequired.style.display = 'none';
                            catatanHelp.innerHTML = 'Catatan akan ditampilkan ke mahasiswa';
                        }
                    });
                </script>
                @elseif($dokumen->status_dokumen === 'diverifikasi')
                <div class="alert alert-success mt-3">
                    <i class="fas fa-check-circle"></i> Dokumen sudah diverifikasi dan disetujui.
                </div>
                @else
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-exclamation-triangle"></i> Dokumen belum lengkap. Mahasiswa masih perlu melengkapi beberapa dokumen.
                </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
