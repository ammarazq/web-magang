@php
    if ($mhs->dokumen) {
        $persentase = $mhs->dokumen->getPersentaseKelengkapan();
        $jumlahDokumen = $mhs->dokumen->getJumlahDokumen();
    } else {
        $persentase = 0;
        $jumlahDokumen = ['uploaded' => 0, 'total' => 0];
    }
    
    // Tentukan class warna berdasarkan persentase
    if ($persentase == 100) {
        $colorClass = 'bg-success';
        $textClass = 'text-success';
    } elseif ($persentase >= 70) {
        $colorClass = 'bg-info';
        $textClass = 'text-info';
    } elseif ($persentase >= 40) {
        $colorClass = 'bg-warning';
        $textClass = 'text-warning';
    } else {
        $colorClass = 'bg-danger';
        $textClass = 'text-danger';
    }
@endphp
<tr>
    <td>
        <strong>{{ $mhs->nama_lengkap }}</strong><br>
        <small class="text-muted">{{ $mhs->email }}</small>
    </td>
    <td><span class="badge bg-info">{{ $mhs->jenjang }}</span></td>
    <td>{{ $mhs->nama_program_studi }}</td>
    <td>
        <div class="progress" style="height: 25px;">
            <div class="progress-bar {{ $colorClass }}" 
                 role="progressbar" 
                 style="width: {{ $persentase }}%;" 
                 aria-valuenow="{{ $persentase }}" 
                 aria-valuemin="0" 
                 aria-valuemax="100">
                <strong>{{ $persentase }}%</strong>
            </div>
        </div>
        <small class="text-muted">
            <i class="fas fa-file-alt"></i> 
            {{ $jumlahDokumen['uploaded'] }}/{{ $jumlahDokumen['total'] }} dokumen
        </small>
    </td>
    <td>
        @if($mhs->dokumen)
            @if($mhs->dokumen->status_dokumen === 'belum_lengkap')
                <span class="badge bg-warning">Belum Lengkap</span>
            @elseif($mhs->dokumen->status_dokumen === 'lengkap')
                <span class="badge bg-info">Lengkap</span>
            @elseif($mhs->dokumen->status_dokumen === 'diverifikasi')
                <span class="badge bg-success">Diverifikasi</span>
            @elseif($mhs->dokumen->status_dokumen === 'ditolak')
                <span class="badge bg-danger">Ditolak</span>
            @endif
        @else
            <span class="badge bg-secondary">Belum Upload</span>
        @endif
    </td>
    <td>
        @if($mhs->dokumen)
            <a href="{{ route('admin.detail', $mhs->id) }}" class="btn btn-sm btn-primary">
                <i class="fas fa-eye"></i> Detail
            </a>
        @else
            <span class="text-muted">-</span>
        @endif
    </td>
</tr>
