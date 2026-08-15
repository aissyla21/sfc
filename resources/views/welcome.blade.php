<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semarang Fencing Club — Developing Future Champions</title>
    <meta name="description" content="Semarang Fencing Club (SFC) adalah klub anggar premier di Semarang. Bergabunglah dan raih prestasimu bersama kami.">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

    <!-- ===== NAVBAR ===== -->
    <nav class="navbar" id="navbar">
        <a href="{{ url('/') }}" class="navbar-brand">
            <div class="logo-icon"></div>
            SFC
        </a>
        <ul class="navbar-nav">
            <li><a href="#home">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#program">Program</a></li>
            <li><a href="#achievement">Achievement</a></li>
            <li><a href="#gallery">Gallery</a></li>
            <li><a href="#news">News</a></li>
        </ul>
        <div class="navbar-right">
            @guest
                <a href="{{ route('login') }}" class="btn btn-outline btn-sm">Login</a>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Join SFC</a>
            @else
                <div class="profile-dropdown" id="profileDropdown">
                    <div class="profile-trigger" onclick="document.getElementById('profileDropdown').classList.toggle('active')">
                        @if(Auth::user()->avatar_url)
                            <img src="{{ asset('storage/' . Auth::user()->avatar_url) }}" alt="Avatar">
                        @else
                            <span class="initials">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</span>
                        @endif
                    </div>
                    <div class="profile-menu">
                        <div class="profile-menu-header">
                            <div class="avatar">
                                @if(Auth::user()->avatar_url)
                                    <img src="{{ asset('storage/' . Auth::user()->avatar_url) }}" alt="Avatar">
                                @else
                                    <span class="initials">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</span>
                                @endif
                            </div>
                            <div class="info">
                                <h4>{{ Auth::user()->name }}</h4>
                                <span>{{ Auth::user()->nia }}</span>
                            </div>
                        </div>
                        <div class="profile-menu-body">
                            <a href="{{ Auth::user()->role === 'pelatih' ? route('pelatih.dashboard') : route('dashboard') }}">📊 Dashboard</a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="logout-btn">🚪 Keluar</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endguest
        </div>
    </nav>

    <!-- ===== HERO ===== -->
    <section class="hero" id="home">
        <div class="container">
            <div class="hero-content">
                <div class="hero-label">Semarang Fencing Club</div>
                <h1 class="hero-title">
                    Developing<br>
                    <span class="highlight">Future<br>Champions</span>
                </h1>
                <p class="hero-desc">
                    Wujudkan potensi terbaikmu bersama Semarang Fencing Club. Berlatih, berkembang, dan raih prestasi bersama pelatih berpengalaman.
                </p>
                <div class="hero-actions">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Gabung Sekarang</a>
                    <a href="#about" class="btn btn-outline btn-lg">Pelajari SFC</a>
                </div>
                <div class="hero-stats">
                    <div class="hero-stat">
                        <div class="number">50+</div>
                        <div class="label">Anggota Aktif</div>
                    </div>
                    <div class="hero-stat">
                        <div class="number">15+</div>
                        <div class="label">Tahun Berdiri</div>
                    </div>
                    <div class="hero-stat">
                        <div class="number">100+</div>
                        <div class="label">Medali Diraih</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hero-decor"></div>
    </section>

    <!-- ===== ABOUT ===== -->
    <section class="about section-padding section-light" id="about">
        <div class="container">
            <div class="about-grid">
                <div class="about-image">
                    <div class="placeholder">⚔️</div>
                </div>
                <div class="about-text">
                    <span class="section-label">Tentang Kami</span>
                    <h2 class="section-title">Klub Anggar Terbaik<br>di Kota Semarang</h2>
                    <p class="section-desc">
                        Semarang Fencing Club (SFC) didirikan dengan visi mencetak atlet anggar yang berprestasi. 
                        Dengan pelatih bersertifikasi dan fasilitas latihan memadai, kami siap membawa anggota ke level kompetisi nasional dan internasional.
                    </p>
                    <div class="about-features">
                        <div class="about-feature">
                            <div class="icon">🏆</div>
                            <div>
                                <h4><a href="#achievement">Berprestasi</a></h4>
                                <p>Juara di berbagai kejuaraan</p>
                            </div>
                        </div>
                        <div class="about-feature">
                            <div class="icon">👨‍🏫</div>
                            <div>
                                <h4><a href="javascript:void(0)" onclick="openModal('modal-pelatih')">Pelatih Pro</a></h4>
                                <p>Bersertifikasi nasional</p>
                            </div>
                        </div>
                        <div class="about-feature">
                            <div class="icon">🤺</div>
                            <div>
                                <h4><a href="javascript:void(0)" onclick="openModal('modal-senjata')">3 Senjata</a></h4>
                                <p>Foil, Épée, dan Sabre</p>
                            </div>
                        </div>
                        <div class="about-feature">
                            <div class="icon">👥</div>
                            <div>
                                <h4><a href="#program">Komunitas</a></h4>
                                <p>Anggota solid & supportif</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== PROGRAM ===== -->
    <section class="section-padding section-red" id="program">
        <div class="container">
            <div class="text-center">
                <span class="section-label">Program Latihan</span>
                <h2 class="section-title">Pilih Program Sesuai Levelmu</h2>
                <p class="section-desc" style="margin: 0 auto;">Kami menyediakan berbagai program latihan dari pemula hingga atlet kompetisi.</p>
            </div>
            <div class="programs-grid">
                <div class="program-card">
                    <div class="program-icon">🌱</div>
                    <h3>Beginner Class</h3>
                    <p>Program dasar untuk pemula. Belajar footwork, stance, dan teknik dasar menyerang & bertahan.</p>
                </div>
                <div class="program-card">
                    <div class="program-icon">⚡</div>
                    <h3>Intermediate</h3>
                    <p>Latihan intensif untuk yang sudah menguasai dasar. Fokus pada taktik, tempo permainan, dan sparring.</p>
                </div>
                <div class="program-card">
                    <div class="program-icon">🏅</div>
                    <h3>Competition Squad</h3>
                    <p>Tim inti untuk kompetisi resmi. Latihan 5x seminggu dengan program persiapan turnamen khusus.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== ACHIEVEMENTS ===== -->
    <section class="achievements section-padding" id="achievement">
        <div class="container">
            <div class="text-center">
                <span class="section-label">Prestasi</span>
                <h2 class="section-title">Medali & Kejuaraan Kami</h2>
                <p class="section-desc" style="margin: 0 auto;">Daftar pencapaian membanggakan dari para atlet Semarang Fencing Club.</p>
            </div>
            <div class="achievements-grid">
                @forelse($achievements as $a)
                    <div class="achievement-card" style="{{ $a->image_path ? 'flex-direction:column;' : '' }}">
                        @if(!empty($a->image_path) && is_array($a->image_path))
                            <div style="width:100%; height:160px; border-radius:var(--radius-sm); overflow-x:auto; overflow-y:hidden; display:flex; gap:8px; margin-bottom:12px; scroll-snap-type: x mandatory;">
                                @foreach($a->image_path as $imgPath)
                                    <img src="{{ asset('storage/' . $imgPath) }}" alt="{{ $a->title }}" style="min-width:100%; height:100%; object-fit:cover; scroll-snap-align: start; border-radius:var(--radius-sm);">
                                @endforeach
                            </div>
                        @elseif($a->image_path && is_string($a->image_path))
                            <div style="width:100%;height:160px;border-radius:var(--radius-sm);overflow:hidden;margin-bottom:12px;">
                                <img src="{{ asset('storage/' . $a->image_path) }}" alt="{{ $a->title }}" style="width:100%;height:100%;object-fit:cover;">
                            </div>
                        @endif
                        <div style="display:flex;gap:16px;">
                            <div class="achievement-year">
                                <span>{{ $a->year ?? '—' }}</span>
                            </div>
                            <div class="achievement-info">
                                @if($a->category)
                                    <span class="category">{{ $a->category }}</span>
                                @endif
                                <h4>{{ $a->title }}</h4>
                                @if($a->winner_name)
                                    <div class="winner-badge">🏅 {{ $a->winner_name }}</div>
                                @endif
                                @if($a->location)
                                    <div class="location">📍 {{ $a->location }}</div>
                                @endif
                                @if($a->description)
                                    <div class="desc">{{ $a->description }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="achievements-empty">
                        <div class="icon">🏆</div>
                        <p>Data prestasi belum ditambahkan oleh admin.<br>Prestasi terbaru akan muncul di sini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- ===== GALLERY ===== -->
    <section class="section-padding section-light" id="gallery">
        <div class="container">
            <div class="text-center">
                <span class="section-label">Galeri</span>
                <h2 class="section-title">Momen Kebanggaan Kami</h2>
            </div>
            <div class="gallery-grid">
                @forelse($galleries as $photo)
                    <div class="gallery-item">
                        <img src="{{ asset('storage/' . $photo->image_path) }}" alt="{{ $photo->caption ?? 'Galeri SFC' }}" style="width:100%;height:100%;object-fit:cover;">
                        @if($photo->caption)
                            <div class="gallery-caption">{{ $photo->caption }}</div>
                        @endif
                    </div>
                @empty
                    <div class="gallery-item"><div class="placeholder-img">🤺</div></div>
                    <div class="gallery-item"><div class="placeholder-img">⚔️</div></div>
                    <div class="gallery-item"><div class="placeholder-img">🏅</div></div>
                    <div class="gallery-item"><div class="placeholder-img">🏆</div></div>
                    <div class="gallery-item"><div class="placeholder-img">👥</div></div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- ===== NEWS ===== -->
    <section class="news section-padding" id="news">
        <div class="container">
            <div class="text-center">
                <span class="section-label">Berita</span>
                <h2 class="section-title">Kabar Terbaru SFC</h2>
            </div>
            <div class="news-grid">
                @forelse($news as $n)
                    <div class="news-card">
                        @if(!empty($n->image_path) && is_array($n->image_path))
                            <div class="thumb" style="padding:0; overflow-x:auto; overflow-y:hidden; display:flex; scroll-snap-type: x mandatory;">
                                @foreach($n->image_path as $imgPath)
                                    <img src="{{ asset('storage/' . $imgPath) }}" alt="{{ $n->title }}" style="min-width:100%; height:100%; object-fit:cover; scroll-snap-align: start;">
                                @endforeach
                            </div>
                        @elseif($n->image_path && is_string($n->image_path))
                            <div class="thumb" style="padding:0;"><img src="{{ asset('storage/' . $n->image_path) }}" alt="{{ $n->title }}" style="width:100%;height:100%;object-fit:cover;"></div>
                        @else
                            <div class="thumb">{{ $n->icon ?? '📰' }}</div>
                        @endif
                        <div class="content">
                            <div class="date">{{ \Carbon\Carbon::parse($n->date)->translatedFormat('d F Y') }}</div>
                            <h4>{{ $n->title }}</h4>
                            <p>{{ Str::limit($n->content, 120) }}</p>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: 1/-1; text-align: center; color: var(--text-muted); padding: 40px;">
                        <div style="font-size: 3rem; margin-bottom: 16px; opacity: 0.3;">📰</div>
                        <p>Belum ada berita terbaru.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- ===== CTA ===== -->
    <section class="cta section-padding">
        <div class="container">
            <div class="cta-inner">
                <h2>Siap Jadi Juara?</h2>
                <p>Bergabunglah dengan Semarang Fencing Club dan mulai perjalananmu menjadi atlet anggar profesional.</p>
                <a href="{{ route('register') }}" class="btn btn-gold btn-lg">Daftar Sekarang — Gratis!</a>
            </div>
        </div>
    </section>

    <!-- ===== FOOTER ===== -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <h3>Semarang Fencing Club</h3>
                    <p>Membentuk atlet anggar berprestasi sejak 2010. Mari bersama-sama meraih kemenangan di setiap pertandingan.</p>
                </div>
                <div class="footer-col">
                    <h4>Navigasi</h4>
                    <a href="#home">Home</a>
                    <a href="#about">About</a>
                    <a href="#program">Program</a>
                    <a href="#achievement">Achievement</a>
                </div>
                <div class="footer-col">
                    <h4>Info</h4>
                    <a href="#gallery">Gallery</a>
                    <a href="#news">News</a>
                    <a href="{{ route('register') }}">Daftar</a>
                    <a href="{{ route('login') }}">Login</a>
                </div>
                <div class="footer-col">
                    <h4>Kontak</h4>
                    <a href="https://share.google/fZA8uuCGlGHruzI9A" target="_blank">📍 Kedai Ibu Dina, Pondok Cabe</a>
                    <a href="https://www.tiktok.com/@semarangfencingclub" target="_blank">🎵 @semarangfencingclub</a>
                    <a href="https://wa.me/6289699212987?text=Hallo%20aku%20ingin%20bergabung%20semarang%20fencing%20club" target="_blank">📱 +62 896-9921-2987</a>
                    <a href="https://instagram.com/semarangfencingclub" target="_blank">📸 @semarangfencingclub</a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} Semarang Fencing Club. All rights reserved.</p>
                <p>Built with ❤️ in Semarang</p>
            </div>
        </div>
    </footer>

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Close profile dropdown on outside click
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('profileDropdown');
            if (dropdown && !dropdown.contains(e.target)) {
                dropdown.classList.remove('active');
            }
        });

        function openModal(id) {
            document.getElementById(id).classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
            document.body.style.overflow = '';
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal-overlay')) {
                event.target.classList.remove('active');
                document.body.style.overflow = '';
            }
        }

        // Smooth reveal on scroll
        const observerOptions = { threshold: 0.1, rootMargin: '0px 0px -50px 0px' };
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.program-card, .achievement-card, .news-card, .about-feature, .gallery-item').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(el);
        });
    </script>

    <!-- ===== MODALS ===== -->
    <div id="modal-pelatih" class="modal-overlay">
        <div class="modal-content">
            <button class="modal-close" onclick="closeModal('modal-pelatih')">&times;</button>
            <span class="section-label" style="color:var(--primary-red);">Pelatih Kami</span>
            <h2 style="margin-bottom:20px; color:#111;">Pelatih Profesional SFC</h2>
            <div class="modal-grid">
                <div class="modal-card">
                    <div class="icon">👨‍🏫</div>
                    <h4 style="color:#111;">Coach Budi Santoso</h4>
                    <p style="color:#555;">Pelatih Kepala spesialis Épée. Pengalaman lebih dari 10 tahun mencetak juara nasional.</p>
                </div>
                <div class="modal-card">
                    <div class="icon">👩‍🏫</div>
                    <h4 style="color:#111;">Coach Siti Aminah</h4>
                    <p style="color:#555;">Pelatih spesialis Foil. Mantan atlet nasional yang berdedikasi mengembangkan atlet muda.</p>
                </div>
                <div class="modal-card">
                    <div class="icon">👨‍🏫</div>
                    <h4 style="color:#111;">Coach Andi Darmawan</h4>
                    <p style="color:#555;">Pelatih spesialis Sabre. Fokus pada kecepatan, kelincahan, dan strategi permainan ofensif.</p>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-senjata" class="modal-overlay">
        <div class="modal-content">
            <button class="modal-close" onclick="closeModal('modal-senjata')">&times;</button>
            <span class="section-label" style="color:var(--primary-red);">Disiplin Senjata</span>
            <h2 style="margin-bottom:20px; color:#111;">3 Senjata Anggar</h2>
            <div class="modal-grid">
                <div class="modal-card">
                    <div class="icon">🤺</div>
                    <h4 style="color:#111;">Foil (Floret)</h4>
                    <p style="color:#555;">Senjata ringan untuk tusukan. Target area sah: badan (torso). Memakai aturan 'right of way'.</p>
                </div>
                <div class="modal-card">
                    <div class="icon">⚔️</div>
                    <h4 style="color:#111;">Épée (Degen)</h4>
                    <p style="color:#555;">Senjata lebih berat. Target area sah: seluruh tubuh. Tanpa aturan 'right of way'.</p>
                </div>
                <div class="modal-card">
                    <div class="icon">🗡️</div>
                    <h4 style="color:#111;">Sabre (Sabel)</h4>
                    <p style="color:#555;">Senjata tusuk dan sabet. Target area sah: atas pinggang. Cepat dan memakai 'right of way'.</p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
