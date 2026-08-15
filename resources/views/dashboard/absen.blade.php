@extends('layouts.app')

@section('content')
<div style="padding-top: 100px;">
    <div class="container">
        <div class="dashboard-header mb-4 text-center">
            <span class="section-label">Absensi</span>
            <h2 style="color: var(--accent-gold);">Absen Latihan Sekarang</h2>
            <p>Pastikan Anda berada di <strong>{{ $location->name }}</strong> untuk dapat melakukan absensi.</p>
        </div>

        <div class="card" style="max-width: 600px; margin: 0 auto; padding: 24px;">
            <div id="location-status" style="text-align: center; margin-bottom: 20px; padding: 12px; background: rgba(255,255,255,0.05); border-radius: var(--radius-sm);">
                🔍 Mencari lokasi Anda...
            </div>
            
            <!-- Camera live preview -->
            <div id="camera-container" style="width: 100%; aspect-ratio: 4/3; background: #000; border-radius: var(--radius-md); overflow: hidden; position: relative; margin-bottom: 20px;">
                <video id="camera-preview" autoplay playsinline style="width: 100%; height: 100%; object-fit: cover;"></video>
                <canvas id="photo-canvas" style="display: none;"></canvas>
                <img id="photo-result" src="" style="display: none; width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0;">
                
                <!-- Fallback message when camera fails -->
                <div id="camera-fallback" style="display: none; width: 100%; height: 100%; position: absolute; top: 0; left: 0; background: var(--dark-surface); flex-direction: column; align-items: center; justify-content: center; gap: 12px; color: var(--text-muted); text-align: center; padding: 20px;">
                    <div style="font-size: 3rem;">📷</div>
                    <p style="margin: 0; font-size: 0.9rem;">Kamera tidak tersedia (Blocked/HTTP).<br>Gunakan tombol di bawah untuk upload foto selfie.</p>
                </div>
            </div>

            <form action="{{ route('dashboard.absen.store') }}" method="POST" id="absen-form">
                @csrf
                <input type="hidden" name="photo" id="input-photo">
                <input type="hidden" name="latitude" id="input-lat">
                <input type="hidden" name="longitude" id="input-lng">
                <input type="hidden" name="location_id" value="{{ $location->id }}">
                
                <!-- File upload fallback (hidden by default) -->
                <input type="file" id="file-upload" accept="image/*" capture="user" style="display: none;">

                <div style="display: flex; gap: 16px; flex-wrap: wrap;">
                    <button type="button" id="btn-retake" class="btn btn-outline" style="flex: 1; display: none;">Ambil Ulang</button>
                    <button type="button" id="btn-capture" class="btn btn-primary" style="flex: 1;" disabled>Ambil Foto & Absen</button>
                    <button type="button" id="btn-upload" class="btn btn-outline" style="flex: 1; display: none;" disabled>📷 Upload Foto Selfie</button>
                    <button type="submit" id="btn-submit" class="btn btn-gold" style="flex: 1; display: none;">Kirim Absensi</button>
                </div>
            </form>
            
            <div class="text-center mt-4">
                <a href="{{ route('dashboard') }}" style="color: var(--text-muted); font-size: 0.85rem;">Batal dan kembali ke Dashboard</a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const targetLat = {{ $location->latitude }};
    const targetLng = {{ $location->longitude }};
    const targetRadius = {{ $location->radius_meter }};
    
    let userLat = null;
    let userLng = null;
    let inRange = false;
    let cameraAvailable = false;

    const locStatus = document.getElementById('location-status');
    const btnCapture = document.getElementById('btn-capture');
    const btnUpload = document.getElementById('btn-upload');
    const btnRetake = document.getElementById('btn-retake');
    const btnSubmit = document.getElementById('btn-submit');
    const video = document.getElementById('camera-preview');
    const canvas = document.getElementById('photo-canvas');
    const photoResult = document.getElementById('photo-result');
    const cameraFallback = document.getElementById('camera-fallback');
    const fileUpload = document.getElementById('file-upload');
    const inputPhoto = document.getElementById('input-photo');
    const inputLat = document.getElementById('input-lat');
    const inputLng = document.getElementById('input-lng');

    // Haversine formula
    function getDistance(lat1, lon1, lat2, lon2) {
        const R = 6371e3;
        const p1 = lat1 * Math.PI/180;
        const p2 = lat2 * Math.PI/180;
        const dp = (lat2-lat1) * Math.PI/180;
        const dl = (lon2-lon1) * Math.PI/180;
        const a = Math.sin(dp/2) * Math.sin(dp/2) +
                Math.cos(p1) * Math.cos(p2) *
                Math.sin(dl/2) * Math.sin(dl/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return R * c;
    }

    function enableAbsenButton() {
        if (cameraAvailable) {
            btnCapture.disabled = false;
        } else {
            btnUpload.disabled = false;
        }
    }

    function disableAbsenButton() {
        btnCapture.disabled = true;
        btnUpload.disabled = true;
    }

    // Initialize Camera
    async function initCamera() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } });
            video.srcObject = stream;
            cameraAvailable = true;
        } catch (err) {
            console.error("Camera error:", err);
            cameraAvailable = false;
            // Show fallback UI
            video.style.display = 'none';
            cameraFallback.style.display = 'flex';
            btnCapture.style.display = 'none';
            btnUpload.style.display = 'block';
        }
    }

    // File upload handler (fallback for camera)
    fileUpload.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        
        const reader = new FileReader();
        reader.onload = function(event) {
            const base64 = event.target.result;
            photoResult.src = base64;
            inputPhoto.value = base64;
            
            cameraFallback.style.display = 'none';
            video.style.display = 'none';
            photoResult.style.display = 'block';
            
            btnUpload.style.display = 'none';
            btnCapture.style.display = 'none';
            btnRetake.style.display = 'block';
            btnSubmit.style.display = 'block';
        };
        reader.readAsDataURL(file);
    });

    // Initialize Location
    if ("geolocation" in navigator) {
        navigator.geolocation.watchPosition((position) => {
            userLat = position.coords.latitude;
            userLng = position.coords.longitude;
            inputLat.value = userLat;
            inputLng.value = userLng;
            
            const distance = getDistance(userLat, userLng, targetLat, targetLng);
            
            if (distance <= targetRadius) {
                inRange = true;
                const distText = distance >= 1000 ? (distance/1000).toFixed(1) + ' km' : Math.round(distance) + 'm';
                locStatus.innerHTML = `✅ Lokasi sesuai (Jarak: ${distText})`;
                locStatus.style.color = '#28a745';
                enableAbsenButton();
            } else {
                inRange = false;
                const distText = distance >= 1000 ? (distance/1000).toFixed(1) + ' km' : Math.round(distance) + 'm';
                const radText = targetRadius >= 1000 ? (targetRadius/1000).toFixed(0) + ' km' : targetRadius + 'm';
                locStatus.innerHTML = `⚠️ Di luar area latihan (Jarak: ${distText}). Anda harus berada di radius ${radText}.`;
                locStatus.style.color = 'var(--primary-red)';
                disableAbsenButton(); 
            }
        }, (error) => {
            let msg = '❌ Gagal mendapatkan lokasi GPS. ';
            if (error.code === 1) {
                msg += 'Izin lokasi ditolak. Cek izin di pengaturan browser Anda.';
            } else if (error.code === 2) {
                msg += 'Lokasi tidak tersedia. Pastikan GPS HP menyala.';
            } else if (error.code === 3) {
                msg += 'Waktu habis. Coba refresh halaman.';
            }
            if (location.protocol !== 'https:' && location.hostname !== 'localhost' && location.hostname !== '127.0.0.1') {
                msg += '<br><br>💡 <strong>Tips:</strong> Anda mengakses via HTTP. Buka Chrome, ketik <code>chrome://flags/#unsafely-treat-insecure-origin-as-secure</code>, tambahkan <code>' + location.origin + '</code>, lalu Enabled & Relaunch.';
            }
            locStatus.innerHTML = msg;
            locStatus.style.color = 'var(--primary-red)';
        }, {
            enableHighAccuracy: true,
            timeout: 15000,
            maximumAge: 0
        });
    } else {
        locStatus.innerHTML = '❌ Geolocation tidak didukung di browser ini.';
    }

    initCamera();

    // Capture from live camera
    btnCapture.addEventListener('click', () => {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
        
        const photoData = canvas.toDataURL('image/png');
        photoResult.src = photoData;
        inputPhoto.value = photoData;
        
        video.style.display = 'none';
        photoResult.style.display = 'block';
        
        btnCapture.style.display = 'none';
        btnRetake.style.display = 'block';
        btnSubmit.style.display = 'block';
    });

    // Upload photo button click
    btnUpload.addEventListener('click', () => {
        fileUpload.click();
    });

    // Retake
    btnRetake.addEventListener('click', () => {
        inputPhoto.value = '';
        photoResult.style.display = 'none';
        fileUpload.value = '';
        
        if (cameraAvailable) {
            video.style.display = 'block';
            btnCapture.style.display = 'block';
            btnUpload.style.display = 'none';
        } else {
            cameraFallback.style.display = 'flex';
            btnCapture.style.display = 'none';
            btnUpload.style.display = 'block';
        }
        
        btnRetake.style.display = 'none';
        btnSubmit.style.display = 'none';
    });

</script>
@endpush
@endsection
