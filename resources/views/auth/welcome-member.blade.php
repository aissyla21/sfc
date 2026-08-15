@extends('layouts.app')

@section('content')
<div class="auth-wrapper">
    <div class="auth-card" style="max-width: 500px;">
        <div class="text-center" style="margin-bottom: 32px;">
            <div style="width:64px;height:64px;background:var(--gradient-gold);border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:2rem;margin-bottom:16px;">🎉</div>
            <h2>Selamat Datang di SFC!</h2>
            <p class="subtitle">Pendaftaran berhasil. Berikut adalah Nomor Induk Anggota (NIA) Anda.</p>
        </div>
        
        <div class="card" style="text-align: center; padding: 40px;">
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 10px;">Nomor Induk Anggota (NIA)</p>
            <div style="font-size: 2.5rem; font-weight: 900; color: var(--accent-gold); letter-spacing: 2px; margin-bottom: 30px; background: rgba(212,175,55,0.1); padding: 10px; border-radius: var(--radius-sm); border: 1px dashed var(--accent-gold);">
                {{ request('nia') ?? session('registered_nia') ?? session('nia') ?? 'SFC-XXX' }}
            </div>
            
            <div style="background: rgba(200,16,46,0.1); border-left: 4px solid var(--primary-red); padding: 16px; text-align: left; margin-bottom: 30px; border-radius: 0 var(--radius-sm) var(--radius-sm) 0;">
                <h4 style="color: var(--primary-red); margin-bottom: 8px; font-size: 0.9rem;">⚠️ Peringatan Penting!</h4>
                <p style="font-size: 0.85rem; color: var(--text-secondary); margin: 0;">
                    Harap <strong>hafalkan NIA Anda</strong> dan <strong>password (minimal 6 karakter)</strong> yang baru saja Anda buat. NIA ini akan selalu digunakan untuk login dan presensi latihan.
                </p>
            </div>
            <a href="{{ route('login') }}" class="btn btn-primary" style="width: 100%;">Login dengan NIA</a>
        </div>
    </div>
</div>
@endsection
