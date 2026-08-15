@extends('layouts.app')

@section('content')
<div style="padding-top: 100px;">
    <div class="container">
        
        @if(session('success_absen'))
            <div style="background: rgba(40,167,69,0.1); color: #28a745; padding: 16px; border-radius: var(--radius-sm); border: 1px solid rgba(40,167,69,0.3); margin-bottom: 20px;">
                {{ session('success_absen') }}
            </div>
        @endif
        @if(session('success_izin'))
            <div style="background: rgba(0,123,255,0.1); color: #4dabf7; padding: 16px; border-radius: var(--radius-sm); border: 1px solid rgba(0,123,255,0.3); margin-bottom: 20px;">
                ✅ {{ session('success_izin') }}
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

        <div class="dashboard-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 40px; flex-wrap: wrap; gap: 20px;">
            <div>
                <span class="section-label">Dashboard Member</span>
                <h2 style="color: var(--accent-gold);">Selamat datang, {{ Auth::user()->name }}</h2>
                <p><span class="nia-badge" style="background: var(--gradient-gold); padding: 6px 16px; border-radius: 20px; font-weight: 700; font-size: 0.85rem; color: #1a1a1a; letter-spacing: 1px;">{{ Auth::user()->nia }}</span></p>
            </div>
            
            <!-- Profile Editor -->
            <div style="display: flex; align-items: center; gap: 20px;">
                <div class="avatar-preview" style="width: 80px; height: 80px; border-radius: 50%; border: 3px solid var(--accent-gold); overflow: hidden; background: var(--dark-surface);">
                    <img id="avatar-image" src="{{ Auth::user()->avatar_url ? asset('storage/' . Auth::user()->avatar_url) : '' }}" style="width:100%; height:100%; object-fit:cover; display: {{ Auth::user()->avatar_url ? 'block' : 'none' }};">
                    @if(!Auth::user()->avatar_url)
                        <div id="avatar-initials" style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-weight: 800; color: var(--accent-gold); font-size: 1.5rem;">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                    @endif
                </div>
                <div>
                    <input type="file" id="avatar-input" accept="image/*" style="display: none;">
                    <button class="btn btn-outline btn-sm" onclick="document.getElementById('avatar-input').click()">Ubah Foto Profile</button>
                </div>
            </div>
        </div>

        <div class="dashboard-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
            <div class="dashboard-card card" style="padding: 24px;">
                <h3>🤺 Absensi Latihan</h3>
                <p class="text-muted" style="margin-bottom: 20px; font-size: 0.85rem;">Lakukan absensi di Kedai Ibu Dina. Pastikan Anda berada di lokasi.</p>
                <a href="{{ route('dashboard.absen') }}" class="btn btn-primary" style="width: 100%;">Absen Sekarang</a>
            </div>

            <!-- Card Pengajuan Izin -->
            <div class="dashboard-card card" style="padding: 24px;">
                <h3>📝 Ajukan Izin</h3>
                <p class="text-muted" style="margin-bottom: 20px; font-size: 0.85rem;">Tidak bisa hadir latihan? Ajukan izin sakit atau keperluan di sini.</p>
                <button class="btn btn-outline" style="width: 100%;" onclick="document.getElementById('izinModal').style.display='flex'">Ajukan Izin</button>
            </div>
            
            <!-- Rekapan Absensi -->
            <div class="dashboard-card card" style="padding: 24px; grid-column: 1 / -1;">
                <h3>📋 Rekapan Absensi</h3>
                <div class="table-wrapper" style="margin-top: 16px; overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; text-align: left;">
                        <thead>
                            <tr style="border-bottom: 1px solid var(--dark-border);">
                                <th style="padding: 12px 8px;">Waktu</th>
                                <th style="padding: 12px 8px;">Status</th>
                                <th style="padding: 12px 8px;">Keterangan</th>
                                <th style="padding: 12px 8px;">Bukti</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                // Merge attendance + approved leaves into one timeline
                                $combined = collect();
                                foreach($attendances as $a) {
                                    $combined->push((object)[
                                        'date' => $a->created_at,
                                        'type' => 'hadir',
                                        'label' => 'Hadir',
                                        'keterangan' => '-',
                                        'photo' => $a->photo_path,
                                    ]);
                                }
                                foreach($leaveRequests as $lr) {
                                    $statusLabel = $lr->status === 'approved' ? ($lr->type === 'sakit' ? 'Izin Sakit' : 'Izin Keperluan') : ($lr->status === 'pending' ? 'Menunggu' : 'Ditolak');
                                    $combined->push((object)[
                                        'date' => \Carbon\Carbon::parse($lr->date),
                                        'type' => $lr->status === 'approved' ? 'izin' : ($lr->status === 'pending' ? 'pending' : 'rejected'),
                                        'label' => $statusLabel,
                                        'keterangan' => $lr->reason,
                                        'photo' => $lr->proof_path,
                                    ]);
                                }
                                $combined = $combined->sortByDesc('date');
                            @endphp

                            @forelse($combined as $item)
                            <tr style="border-bottom: 1px solid var(--dark-border);">
                                <td style="padding: 12px 8px;">{{ \Carbon\Carbon::parse($item->date)->translatedFormat('d M Y, H:i') }}</td>
                                <td style="padding: 12px 8px;">
                                    @if($item->type === 'hadir')
                                        <span style="color: #28a745; font-weight: bold;">✅ {{ $item->label }}</span>
                                    @elseif($item->type === 'izin')
                                        <span style="color: #4dabf7; font-weight: bold;">📋 {{ $item->label }}</span>
                                    @elseif($item->type === 'pending')
                                        <span style="color: #ffc107; font-weight: bold;">⏳ {{ $item->label }}</span>
                                    @else
                                        <span style="color: #dc3545; font-weight: bold;">❌ {{ $item->label }}</span>
                                    @endif
                                </td>
                                <td style="padding: 12px 8px; max-width: 250px; word-wrap: break-word; font-size: 0.85rem; color: var(--text-secondary);">{{ $item->keterangan }}</td>
                                <td style="padding: 12px 8px;">
                                    @if($item->photo)
                                        <img src="{{ asset('storage/' . $item->photo) }}" alt="Bukti" style="width: 60px; height: 60px; border-radius: var(--radius-sm); object-fit: cover; cursor: pointer;" onclick="window.open(this.src, '_blank')">
                                    @else
                                        <span style="color: var(--text-muted);">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" style="text-align:center; color:var(--text-muted); padding:30px;">Belum ada data absensi atau izin.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($myAchievements->count() > 0)
            <div class="dashboard-card card" style="padding: 24px; grid-column: 1 / -1; border-color: var(--primary-red);">
                <h3 style="color: var(--accent-gold);">🏅 Prestasi Saya</h3>
                <p class="text-muted" style="margin-bottom: 20px; font-size: 0.85rem;">Selamat! Ini adalah rekam jejak medali dan kejuaraan Anda.</p>
                <div class="achievements-grid" style="margin-top: 20px;">
                    @foreach($myAchievements as $ma)
                        <div class="achievement-personal">
                            <div style="display: flex; gap: 16px; align-items: flex-start;">
                                <div style="font-size: 2rem;">🏆</div>
                                <div class="achievement-info">
                                    @if($ma->category) <span class="category">{{ $ma->category }}</span> @endif
                                    <h4 style="margin: 5px 0; color: #fff;">{{ $ma->title }}</h4>
                                    <div class="winner-badge">{{ $ma->year ?? '—' }}</div>
                                    @if($ma->description) <div style="font-size: 0.85rem; margin-top: 8px; color: var(--text-secondary);">{{ $ma->description }}</div> @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="dashboard-card card" style="padding: 24px; grid-column: 1 / -1;">
                <h3>🏆 Prestasi Klub</h3>
                <div class="achievements-grid" style="margin-top: 20px;">
                    @forelse($achievements as $a)
                        <div class="achievement-card" style="margin-bottom: 16px;">
                            <div class="achievement-year"><span>{{ $a->year ?? '—' }}</span></div>
                            <div class="achievement-info">
                                @if($a->category) <span class="category">{{ $a->category }}</span> @endif
                                <h4>{{ $a->title }}</h4>
                                @if($a->description) <div class="desc">{{ $a->description }}</div> @endif
                            </div>
                        </div>
                    @empty
                        <div style="color: var(--text-muted); padding: 20px 0;">Belum ada data prestasi.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pengajuan Izin -->
<div id="izinModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 2000; align-items: center; justify-content: center; padding: 20px;">
    <div class="card" style="background: var(--dark-card); padding: 30px; max-width: 500px; width: 100%; border-radius: var(--radius-md); position: relative;">
        <button onclick="document.getElementById('izinModal').style.display='none'" style="position: absolute; top: 16px; right: 16px; background: none; border: none; color: var(--text-muted); font-size: 1.5rem; cursor: pointer;">&times;</button>
        
        <h3 style="margin-bottom: 6px;">📝 Form Pengajuan Izin</h3>
        <p class="text-muted" style="margin-bottom: 24px; font-size: 0.85rem;">Isi form berikut untuk mengajukan izin tidak hadir latihan.</p>
        
        <form action="{{ route('dashboard.izin.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group mb-4">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 0.9rem;">Tipe Izin</label>
                <select name="type" class="form-control" required style="width: 100%; padding: 12px; background: var(--dark-surface); border: 1px solid var(--dark-border); border-radius: var(--radius-sm); color: var(--text-light); font-size: 0.95rem;">
                    <option value="">-- Pilih Tipe --</option>
                    <option value="sakit">🤒 Izin Sakit</option>
                    <option value="izin">📋 Izin Keperluan</option>
                </select>
            </div>
            <div class="form-group mb-4">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 0.9rem;">Tanggal Izin</label>
                <input type="date" name="date" class="form-control" required value="{{ date('Y-m-d') }}" style="width: 100%; padding: 12px; background: var(--dark-surface); border: 1px solid var(--dark-border); border-radius: var(--radius-sm); color: var(--text-light); font-size: 0.95rem;">
            </div>
            <div class="form-group mb-4">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 0.9rem;">Keterangan / Alasan</label>
                <textarea name="reason" class="form-control" rows="3" required placeholder="Jelaskan alasan izin Anda..." style="width: 100%; padding: 12px; background: var(--dark-surface); border: 1px solid var(--dark-border); border-radius: var(--radius-sm); color: var(--text-light); font-size: 0.95rem; resize: vertical;"></textarea>
            </div>
            <div class="form-group mb-4">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; font-size: 0.9rem;">Bukti Foto <span style="color: var(--text-muted); font-weight: 400;">(Opsional)</span></label>
                <input type="file" name="proof" accept="image/*" style="width: 100%; padding: 10px; background: var(--dark-surface); border: 1px solid var(--dark-border); border-radius: var(--radius-sm); color: var(--text-light); font-size: 0.9rem;" onchange="previewProof(this)">
                <div id="proof-preview" style="margin-top: 10px;"></div>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px; font-size: 1rem;">Kirim Pengajuan Izin</button>
        </form>
    </div>
</div>

<!-- Modal Cropper JS -->
<div id="cropperModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 2000; align-items: center; justify-content: center;">
    <div class="card" style="background: var(--dark-card); padding: 24px; max-width: 500px; width: 90%;">
        <h3 style="margin-bottom: 16px;">Sesuaikan Foto Profile</h3>
        <div style="width: 100%; height: 300px; background: #000; margin-bottom: 20px;">
            <img id="cropper-image" src="" style="max-width: 100%;">
        </div>
        <div style="display: flex; gap: 10px; justify-content: flex-end;">
            <button class="btn btn-outline" onclick="closeCropper()">Batal</button>
            <button class="btn btn-primary" onclick="saveAvatar()">Simpan</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Proof preview
    function previewProof(input) {
        const preview = document.getElementById('proof-preview');
        preview.innerHTML = '';
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = '100px';
                img.style.height = '100px';
                img.style.objectFit = 'cover';
                img.style.borderRadius = '8px';
                img.style.border = '1px solid var(--dark-border)';
                preview.appendChild(img);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Avatar cropper
    let cropper;
    const modal = document.getElementById('cropperModal');
    const image = document.getElementById('cropper-image');
    
    document.getElementById('avatar-input').addEventListener('change', function(e) {
        const files = e.target.files;
        if (files && files.length > 0) {
            const reader = new FileReader();
            reader.onload = function(event) {
                image.src = event.target.result;
                modal.style.display = 'flex';
                
                if (cropper) {
                    cropper.destroy();
                }
                
                cropper = new Cropper(image, {
                    aspectRatio: 1,
                    viewMode: 1,
                    dragMode: 'move',
                    autoCropArea: 1,
                    restore: false,
                    guides: false,
                    center: false,
                    highlight: false,
                    cropBoxMovable: false,
                    cropBoxResizable: false,
                    toggleDragModeOnDblclick: false,
                });
            };
            reader.readAsDataURL(files[0]);
        }
    });

    function closeCropper() {
        modal.style.display = 'none';
        if (cropper) {
            cropper.destroy();
        }
        document.getElementById('avatar-input').value = '';
    }

    function saveAvatar() {
        if (!cropper) return;
        
        const canvas = cropper.getCroppedCanvas({
            width: 300,
            height: 300
        });
        
        const base64Image = canvas.toDataURL('image/png');
        
        fetch('{{ route("dashboard.avatar.update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ avatar: base64Image })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('avatar-image').src = data.url;
                document.getElementById('avatar-image').style.display = 'block';
                const initials = document.getElementById('avatar-initials');
                if(initials) initials.style.display = 'none';
                
                // Update navbar profile image if it exists
                const navProfileImg = document.querySelector('.profile-trigger img');
                if (navProfileImg) navProfileImg.src = data.url;
                
                closeCropper();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal mengunggah foto profile.');
        });
    }
</script>
@endpush
@endsection
