@extends('layouts.app')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="text-center" style="margin-bottom: 32px;">
            <div style="width:56px;height:56px;background:var(--gradient-gold);border-radius:var(--radius-md);display:inline-flex;align-items:center;justify-content:center;font-size:1.5rem;margin-bottom:16px;">⚔️</div>
            <h2>Bergabung dengan SFC</h2>
            <p class="subtitle">Daftarkan dirimu dan dapatkan NIA</p>
        </div>
        <div class="card">
            <form method="POST" action="{{ route('register') }}" id="register-form">
                @csrf
                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Masukkan nama lengkapmu" value="{{ old('name') }}" required autofocus>
                    @error('name')
                        <small class="text-danger" style="display:block;margin-top:6px;font-size:0.8rem;">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="email@contoh.com" value="{{ old('email') }}" required>
                    @error('email')
                        <small class="text-danger" style="display:block;margin-top:6px;font-size:0.8rem;">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <div style="position: relative;">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required minlength="6" style="padding-right: 48px;">
                        <button type="button" id="toggle-password" onclick="togglePasswordVisibility('password', 'toggle-password')" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--text-muted);font-size:1.1rem;padding:4px;" title="Tampilkan password">👁️</button>
                    </div>
                    @error('password')
                        <small class="text-danger" style="display:block;margin-top:6px;font-size:0.8rem;">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <div style="position: relative;">
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Ulangi password" required minlength="6" style="padding-right: 48px;">
                        <button type="button" id="toggle-password-confirm" onclick="togglePasswordVisibility('password_confirmation', 'toggle-password-confirm')" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--text-muted);font-size:1.1rem;padding:4px;" title="Tampilkan password">👁️</button>
                    </div>
                    <small id="password-match-msg" style="display:none;margin-top:6px;font-size:0.8rem;"></small>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;margin-top:8px;">Daftar Sekarang</button>
            </form>
        </div>
        <div class="auth-footer">
            Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a>
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

    // Real-time password match check
    const pw = document.getElementById('password');
    const pwConfirm = document.getElementById('password_confirmation');
    const matchMsg = document.getElementById('password-match-msg');

    function checkPasswordMatch() {
        if (pwConfirm.value.length === 0) {
            matchMsg.style.display = 'none';
            return;
        }
        matchMsg.style.display = 'block';
        if (pw.value === pwConfirm.value) {
            matchMsg.textContent = '✅ Password cocok';
            matchMsg.style.color = '#28a745';
        } else {
            matchMsg.textContent = '❌ Password tidak cocok';
            matchMsg.style.color = 'var(--primary-red)';
        }
    }

    pw.addEventListener('input', checkPasswordMatch);
    pwConfirm.addEventListener('input', checkPasswordMatch);

    // Prevent submit if passwords don't match
    document.getElementById('register-form').addEventListener('submit', function(e) {
        if (pw.value !== pwConfirm.value) {
            e.preventDefault();
            alert('Password dan Konfirmasi Password harus sama!');
            pwConfirm.focus();
        }
    });
</script>
@endpush
@endsection
