@extends('layouts.app')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="text-center" style="margin-bottom: 32px;">
            <div style="width:56px;height:56px;background:var(--gradient-red);border-radius:var(--radius-md);display:inline-flex;align-items:center;justify-content:center;font-size:1.5rem;margin-bottom:16px;">🤺</div>
            <h2>Masuk ke Akun</h2>
            <p class="subtitle">Gunakan NIA dan password untuk login</p>
        </div>
        <div class="card">
            <form method="POST" action="{{ route('login') }}" id="login-form">
                @csrf
                <div class="form-group">
                    <label for="nia">NIA (Nomor Induk Anggota)</label>
                    <input type="text" id="nia" name="nia" class="form-control" placeholder="Contoh: SFC-001" value="{{ old('nia') }}" required autofocus>
                    @error('nia')
                        <small class="text-danger" style="display:block;margin-top:6px;font-size:0.8rem;">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <div style="position: relative;">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password" required style="padding-right: 48px;">
                        <button type="button" id="toggle-password" onclick="togglePasswordVisibility('password', 'toggle-password')" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--text-muted);font-size:1.1rem;padding:4px;" title="Tampilkan password">👁️</button>
                    </div>
                </div>
                <button type="button" id="btn-login" class="btn btn-primary" style="width:100%;margin-top:8px;" onclick="confirmLogin()">Login</button>
            </form>
        </div>
        <div class="auth-footer">
            Belum punya akun? <a href="{{ route('register') }}">Join SFC Sekarang</a>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Login -->
<div id="confirmLoginModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);z-index:9999;align-items:center;justify-content:center;backdrop-filter:blur(4px);">
    <div style="background:var(--dark-surface);border:1px solid var(--border-color);border-radius:var(--radius-md);padding:32px;max-width:400px;width:90%;text-align:center;animation:fadeInUp 0.3s ease;">
        <div style="font-size:2.5rem;margin-bottom:16px;">🔐</div>
        <h3 style="margin-bottom:8px;color:var(--text-primary);">Konfirmasi Login</h3>
        <p style="color:var(--text-muted);font-size:0.9rem;margin-bottom:24px;">Apakah Anda yakin ingin masuk dengan NIA <strong id="confirm-nia" style="color:var(--accent-gold);"></strong>?</p>
        <div style="display:flex;gap:12px;">
            <button type="button" class="btn btn-outline" style="flex:1;" onclick="closeConfirmModal()">Batal</button>
            <button type="button" class="btn btn-primary" style="flex:1;" onclick="submitLogin()">Ya, Masuk</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function togglePasswordVisibility(inputId, btnId) {
        const input = document.getElementById(inputId);
        const btn = document.getElementById(btnId);
        if (input.type === 'password') {
            input.type = 'text';
            btn.textContent = '🙈';
            btn.title = 'Sembunyikan password';
        } else {
            input.type = 'password';
            btn.textContent = '👁️';
            btn.title = 'Tampilkan password';
        }
    }

    function confirmLogin() {
        const nia = document.getElementById('nia').value.trim();
        const password = document.getElementById('password').value;
        
        if (!nia || !password) {
            document.getElementById('login-form').reportValidity();
            return;
        }

        document.getElementById('confirm-nia').textContent = nia;
        document.getElementById('confirmLoginModal').style.display = 'flex';
    }

    function closeConfirmModal() {
        document.getElementById('confirmLoginModal').style.display = 'none';
    }

    function submitLogin() {
        document.getElementById('confirmLoginModal').style.display = 'none';
        document.getElementById('login-form').submit();
    }

    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeConfirmModal();
    });
</script>
@endpush
@endsection
