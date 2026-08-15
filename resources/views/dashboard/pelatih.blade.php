@extends('layouts.app')

@section('content')
<div style="padding-top: 100px;">
    <div class="container">
        <div class="dashboard-header mb-4">
            <span class="section-label">Panel Admin</span>
            <h2 style="color: var(--accent-gold);">Dashboard Pelatih</h2>
            <p>Selamat datang, <strong>{{ Auth::user()->name }}</strong></p>
        </div>

        @if(session('success_news'))
            <div style="background: rgba(40,167,69,0.1); color: #28a745; padding: 16px; border-radius: var(--radius-sm); border: 1px solid rgba(40,167,69,0.3); margin-bottom: 20px;">
                {{ session('success_news') }}
            </div>
        @endif
        @if(session('success_achievement'))
            <div style="background: rgba(40,167,69,0.1); color: #28a745; padding: 16px; border-radius: var(--radius-sm); border: 1px solid rgba(40,167,69,0.3); margin-bottom: 20px;">
                {{ session('success_achievement') }}
            </div>
        @endif
        @if(session('success_gallery'))
            <div style="background: rgba(40,167,69,0.1); color: #28a745; padding: 16px; border-radius: var(--radius-sm); border: 1px solid rgba(40,167,69,0.3); margin-bottom: 20px;">
                {{ session('success_gallery') }}
            </div>
        @endif
        @if(session('success_leave'))
            <div style="background: rgba(0,123,255,0.1); color: #4dabf7; padding: 16px; border-radius: var(--radius-sm); border: 1px solid rgba(0,123,255,0.3); margin-bottom: 20px;">
                {{ session('success_leave') }}
            </div>
        @endif
        @if($errors->any())
            <div style="background: rgba(220,53,69,0.1); color: #dc3545; padding: 16px; border-radius: var(--radius-sm); border: 1px solid rgba(220,53,69,0.3); margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Tab Menu Buttons -->
        <div class="admin-tabs" style="display: flex; gap: 10px; margin-bottom: 24px; overflow-x: auto; padding-bottom: 10px;">
            <button class="admin-tab active" onclick="switchTab('izin', this)">📩 Izin Masuk @if($pendingLeaves->count() > 0)<span style="background: var(--primary-red); color: white; border-radius: 50%; padding: 2px 8px; font-size: 0.8rem; margin-left: 5px;">{{ $pendingLeaves->count() }}</span>@endif</button>
            <button class="admin-tab" onclick="switchTab('absensi', this)">📊 Rekap Absensi</button>
            <button class="admin-tab" onclick="switchTab('prestasi', this)">🏆 Prestasi</button>
            <button class="admin-tab" onclick="switchTab('berita', this)">📰 Berita</button>
            <button class="admin-tab" onclick="switchTab('galeri', this)">📸 Galeri</button>
        </div>

        <!-- Tab Content: Izin Masuk -->
        <div class="admin-tab-content active" id="tab-izin">
            <div class="dashboard-card card" style="padding: 24px; margin-bottom: 30px;">
                <h3 style="color: var(--accent-gold);">📩 Menunggu Persetujuan</h3>
                <p class="text-muted" style="margin-bottom: 20px; font-size: 0.85rem;">Daftar izin anggota yang membutuhkan persetujuan Anda.</p>
                
                @if($pendingLeaves->isEmpty())
                    <p style="text-align: center; color: var(--text-muted); padding: 30px 0;">Tidak ada pengajuan izin baru.</p>
                @else
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; text-align: left; min-width: 800px;">
                            <thead>
                                <tr style="border-bottom: 1px solid var(--dark-border);">
                                    <th style="padding: 12px 8px; color: var(--text-muted);">Tgl Izin</th>
                                    <th style="padding: 12px 8px; color: var(--text-muted);">Anggota</th>
                                    <th style="padding: 12px 8px; color: var(--text-muted);">Tipe</th>
                                    <th style="padding: 12px 8px; color: var(--text-muted);">Alasan</th>
                                    <th style="padding: 12px 8px; color: var(--text-muted);">Bukti</th>
                                    <th style="padding: 12px 8px; color: var(--text-muted); text-align: right;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingLeaves as $leave)
                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                    <td style="padding: 16px 8px; font-weight: bold;">{{ \Carbon\Carbon::parse($leave->date)->translatedFormat('d M Y') }}</td>
                                    <td style="padding: 16px 8px;">
                                        <div style="font-weight: 600;">{{ $leave->user->name }}</div>
                                        <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $leave->user->nia }}</div>
                                    </td>
                                    <td style="padding: 16px 8px;">
                                        @if($leave->type == 'sakit')
                                            <span style="background: rgba(220,53,69,0.1); color: #dc3545; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem;">🤒 Sakit</span>
                                        @else
                                            <span style="background: rgba(0,123,255,0.1); color: #4dabf7; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem;">📋 Keperluan</span>
                                        @endif
                                    </td>
                                    <td style="padding: 16px 8px; max-width: 200px; font-size: 0.9rem;">{{ $leave->reason }}</td>
                                    <td style="padding: 16px 8px;">
                                        @if($leave->proof_path)
                                            <a href="{{ asset('storage/' . $leave->proof_path) }}" target="_blank" style="color: var(--accent-gold); text-decoration: none; font-size: 0.9rem;">Lihat Foto</a>
                                        @else
                                            <span style="color: var(--text-muted); font-size: 0.9rem;">-</span>
                                        @endif
                                    </td>
                                    <td style="padding: 16px 8px; text-align: right;">
                                        <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                            <form action="{{ route('pelatih.leave.approve', $leave->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm" style="background: #28a745; color: white; border: none; padding: 6px 12px;">Setuju</button>
                                            </form>
                                            <form action="{{ route('pelatih.leave.reject', $leave->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-outline btn-sm" style="color: #dc3545; border-color: #dc3545; padding: 6px 12px;">Tolak</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Riwayat Izin -->
            <div class="dashboard-card card" style="padding: 24px;">
                <h3>📜 Riwayat Izin Bulan Ini</h3>
                @if($processedLeaves->isEmpty())
                    <p style="text-align: center; color: var(--text-muted); padding: 20px 0;">Belum ada izin yang diproses.</p>
                @else
                    <div style="overflow-x: auto; margin-top: 16px;">
                        <table style="width: 100%; border-collapse: collapse; text-align: left;">
                            <thead>
                                <tr style="border-bottom: 1px solid var(--dark-border);">
                                    <th style="padding: 10px 8px; color: var(--text-muted);">Tanggal</th>
                                    <th style="padding: 10px 8px; color: var(--text-muted);">Anggota</th>
                                    <th style="padding: 10px 8px; color: var(--text-muted);">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($processedLeaves as $leave)
                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                    <td style="padding: 12px 8px; font-size: 0.9rem;">{{ \Carbon\Carbon::parse($leave->date)->translatedFormat('d M Y') }}</td>
                                    <td style="padding: 12px 8px; font-size: 0.9rem;">{{ $leave->user->name }}</td>
                                    <td style="padding: 12px 8px; font-size: 0.9rem;">
                                        @if($leave->status == 'approved')
                                            <span style="color: #28a745;">Disetujui</span>
                                        @else
                                            <span style="color: #dc3545;">Ditolak</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- Tab Content: Absensi -->
        <div class="admin-tab-content" id="tab-absensi">
            <div class="dashboard-card card" style="padding: 24px;">
                <h3>📊 Rekap Absensi Bulan Ini</h3>
                <p class="text-muted" style="margin-bottom: 20px; font-size: 0.85rem;">Total Hari Latihan (Senin-Sabtu) sejauh ini: <strong>{{ $trainingDays }} hari</strong></p>
                
                @if(empty($attendanceRecap))
                    <p style="text-align: center; color: var(--text-muted); padding: 20px 0;">Tidak ada anggota terdaftar.</p>
                @else
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; text-align: left; min-width: 600px;">
                            <thead>
                                <tr style="border-bottom: 1px solid var(--dark-border);">
                                    <th style="padding: 12px 8px; color: var(--text-muted);">Nama Anggota</th>
                                    <th style="padding: 12px 8px; color: var(--text-muted); text-align: center;">Hadir ✅</th>
                                    <th style="padding: 12px 8px; color: var(--text-muted); text-align: center;">Izin 📋</th>
                                    <th style="padding: 12px 8px; color: var(--text-muted); text-align: center;">Alfa ❌</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendanceRecap as $recap)
                                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                        <td style="padding: 16px 8px;">
                                            <div style="font-weight: 600;">{{ $recap->user->name }}</div>
                                            <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $recap->user->nia }}</div>
                                        </td>
                                        <td style="padding: 16px 8px; text-align: center; color: #28a745; font-weight: bold; font-size: 1.1rem;">{{ $recap->hadir }}</td>
                                        <td style="padding: 16px 8px; text-align: center; color: #4dabf7; font-weight: bold; font-size: 1.1rem;">{{ $recap->izin }}</td>
                                        <td style="padding: 16px 8px; text-align: center; color: #dc3545; font-weight: bold; font-size: 1.1rem;">{{ $recap->alfa }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- Tab Content: Prestasi -->
        <div class="admin-tab-content" id="tab-prestasi">
            <div class="dashboard-card card" style="padding: 24px;">
                <h3>🏆 Tambah Prestasi</h3>
                <p class="text-muted" style="margin-bottom: 20px; font-size: 0.85rem;">Tambahkan data medali/kejuaraan yang akan tampil di halaman depan.</p>
                <form action="{{ route('pelatih.achievement.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-4">
                        <label>Judul Kejuaraan</label>
                        <input type="text" name="title" class="form-control" required placeholder="Contoh: Kejuaraan Nasional 2026">
                    </div>
                    <div class="form-group mb-4">
                        <label>Nama Pemenang</label>
                        <input type="text" name="winner_name" class="form-control" placeholder="Nama anggota yang menang">
                    </div>
                    <div class="form-group mb-4">
                        <label>Kategori</label>
                        <input type="text" name="category" class="form-control" placeholder="Contoh: Juara 1 Épée Putra">
                    </div>
                    <div class="form-grid-2 mb-4">
                        <div class="form-group">
                            <label>Tahun</label>
                            <input type="text" name="year" class="form-control" required placeholder="2026">
                        </div>
                        <div class="form-group">
                            <label>Lokasi (Opsional)</label>
                            <input type="text" name="location" class="form-control" placeholder="Semarang">
                        </div>
                    </div>
                    <div class="form-group mb-4">
                        <label>Deskripsi Singkat</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Detail prestasi..."></textarea>
                    </div>
                    <div class="form-group mb-4">
                        <label>Foto Kejuaraan (Bisa pilih lebih dari 1)</label>
                        <input type="file" name="images[]" class="form-control" accept="image/*" multiple style="padding: 10px;" onchange="previewImages(this, 'preview-achievement')">
                        <div id="preview-achievement" class="image-preview-row"></div>
                    </div>
                    <button type="submit" class="btn btn-gold" style="width: 100%;">Simpan Prestasi</button>
                </form>
            </div>
        </div>

        <!-- Tab Content: Berita -->
        <div class="admin-tab-content" id="tab-berita">
            <div class="dashboard-card card" style="padding: 24px;">
                <h3>📰 Tambah Kabar Terbaru</h3>
                <p class="text-muted" style="margin-bottom: 20px; font-size: 0.85rem;">Tambahkan berita terbaru SFC yang akan tampil di halaman depan.</p>
                <form action="{{ route('pelatih.news.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-4">
                        <label>Judul Berita</label>
                        <input type="text" name="title" class="form-control" required placeholder="Judul berita...">
                    </div>
                    <div class="form-grid-2 mb-4">
                        <div class="form-group">
                            <label>Tanggal</label>
                            <input type="date" name="date" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Ikon (Emoji)</label>
                            <input type="text" name="icon" class="form-control" placeholder="📰">
                        </div>
                    </div>
                    <div class="form-group mb-4">
                        <label>Konten Berita</label>
                        <textarea name="content" class="form-control" rows="4" required placeholder="Isi berita..."></textarea>
                    </div>
                    <div class="form-group mb-4">
                        <label>Foto Berita (Bisa pilih lebih dari 1)</label>
                        <input type="file" name="images[]" class="form-control" accept="image/*" multiple style="padding: 10px;" onchange="previewImages(this, 'preview-news')">
                        <div id="preview-news" class="image-preview-row"></div>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Terbitkan Berita</button>
                </form>
            </div>
        </div>

        <!-- Tab Content: Galeri -->
        <div class="admin-tab-content" id="tab-galeri">
            <div class="dashboard-card card" style="padding: 24px;">
                <h3>📸 Tambah Foto Galeri</h3>
                <p class="text-muted" style="margin-bottom: 20px; font-size: 0.85rem;">Upload foto terbaru untuk ditampilkan di bagian Galeri halaman depan.</p>
                <form action="{{ route('pelatih.gallery.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-4">
                        <label>File Foto (bisa pilih lebih dari 1)</label>
                        <input type="file" name="images[]" class="form-control" required accept="image/*" multiple style="padding: 10px;" onchange="previewImages(this, 'preview-gallery')">
                        <div id="preview-gallery" class="image-preview-row"></div>
                    </div>
                    <div class="form-group mb-4">
                        <label>Caption (Opsional)</label>
                        <input type="text" name="caption" class="form-control" placeholder="Keterangan foto...">
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Upload Foto</button>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
function switchTab(tabName, btn) {
    document.querySelectorAll('.admin-tab-content').forEach(el => {
        el.classList.remove('active');
    });
    document.querySelectorAll('.admin-tab').forEach(el => {
        el.classList.remove('active');
    });
    document.getElementById('tab-' + tabName).classList.add('active');
    btn.classList.add('active');
}

function previewImages(input, previewContainerId) {
    const previewContainer = document.getElementById(previewContainerId);
    previewContainer.innerHTML = '';

    if (input.files) {
        Array.from(input.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = '80px';
                img.style.height = '80px';
                img.style.objectFit = 'cover';
                img.style.borderRadius = '8px';
                img.style.border = '1px solid var(--dark-border)';
                previewContainer.appendChild(img);
            }
            reader.readAsDataURL(file);
        });
    }
}
</script>
@endsection
