<?php
/**
 * ============================================================
 * index.php - Halaman Form Pendaftaran Workshop
 * ============================================================
 * Proyek  : Pendaftaran Workshop Library PHP & Composer
 * Kampus  : STIKOM Bali
 * Dosen   : Gede Herdian Setiawan, S.Kom., M.T.
 * ============================================================
 * Halaman ini menampilkan form pendaftaran workshop yang
 * kemudian akan diproses oleh proses-pendaftaran.php
 * ============================================================
 */

// Ambil pesan error dari URL jika ada (dikirim dari proses-pendaftaran.php)
$pesan_error = '';
if (!empty($_GET['error'])) {
    // Sanitasi pesan error sebelum ditampilkan untuk mencegah XSS
    $pesan_error = htmlspecialchars(urldecode($_GET['error']), ENT_QUOTES, 'UTF-8');
}

// Ambil kembali nilai yang sudah diisi (untuk UX yang lebih baik)
$old_nama    = !empty($_GET['nama'])    ? htmlspecialchars(urldecode($_GET['nama']), ENT_QUOTES, 'UTF-8')    : '';
$old_email   = !empty($_GET['email'])   ? htmlspecialchars(urldecode($_GET['email']), ENT_QUOTES, 'UTF-8')   : '';
$old_no_hp   = !empty($_GET['no_hp'])   ? htmlspecialchars(urldecode($_GET['no_hp']), ENT_QUOTES, 'UTF-8')   : '';
$old_prodi   = !empty($_GET['prodi'])   ? htmlspecialchars(urldecode($_GET['prodi']), ENT_QUOTES, 'UTF-8')   : '';
$old_pesan   = !empty($_GET['pesan'])   ? htmlspecialchars(urldecode($_GET['pesan']), ENT_QUOTES, 'UTF-8')   : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Form pendaftaran Workshop Library PHP & Composer - STIKOM Bali. Daftarkan diri Anda sekarang dan dapatkan email konfirmasi otomatis.">
    <title>Pendaftaran Workshop Library PHP & Composer | STIKOM Bali</title>

    <!-- Bootstrap 5.3.3 CSS via CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">

    <!-- Bootstrap Icons via CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ============================================================
         * CSS Kustom - Pendaftaran Workshop STIKOM Bali
         * ============================================================ */

        :root {
            --biru-utama: #0d47a1;
            --biru-muda:  #1976d2;
            --biru-terang:#42a5f5;
            --hijau:      #2e7d32;
            --abu-bg:     #f0f4f8;
            --putih:      #ffffff;
            --teks-gelap: #1a1a2e;
            --teks-abu:   #6c757d;
            --border:     #dee2e6;
            --shadow:     0 4px 24px rgba(13, 71, 161, 0.10);
            --shadow-hover: 0 8px 32px rgba(13, 71, 161, 0.18);
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #e8f0fe 0%, #f0f4f8 50%, #e3f2fd 100%);
            min-height: 100vh;
            color: var(--teks-gelap);
        }

        /* ---- NAVBAR / HEADER ---- */
        .navbar-custom {
            background: linear-gradient(90deg, #0d47a1 0%, #1565c0 60%, #1976d2 100%);
            box-shadow: 0 2px 16px rgba(13, 71, 161, 0.3);
            padding: 0.9rem 0;
        }

        .navbar-brand-text {
            font-weight: 700;
            font-size: 1.05rem;
            letter-spacing: 0.3px;
            color: #ffffff !important;
            line-height: 1.3;
        }

        .navbar-brand-text small {
            font-weight: 400;
            font-size: 0.78rem;
            color: rgba(255,255,255,0.8);
            display: block;
        }

        .navbar-badge {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            color: #fff;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.25rem 0.65rem;
            border-radius: 20px;
            backdrop-filter: blur(4px);
        }

        /* ---- MAIN CARD ---- */
        .main-card {
            background: var(--putih);
            border: none;
            border-radius: 20px;
            box-shadow: var(--shadow);
            transition: box-shadow 0.3s ease;
            overflow: hidden;
        }

        .main-card:hover {
            box-shadow: var(--shadow-hover);
        }

        .card-header-custom {
            background: linear-gradient(135deg, #0d47a1, #1976d2);
            padding: 2rem 2rem 1.5rem;
            border-radius: 20px 20px 0 0;
        }

        .card-header-custom .icon-box {
            width: 56px;
            height: 56px;
            background: rgba(255,255,255,0.2);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            backdrop-filter: blur(4px);
            border: 1px solid rgba(255,255,255,0.3);
        }

        .card-header-custom h2 {
            font-size: 1.35rem;
            font-weight: 700;
            color: #ffffff;
            margin: 0;
        }

        .card-header-custom p {
            color: rgba(255,255,255,0.85);
            font-size: 0.875rem;
            margin: 0;
        }

        /* ---- FORM STYLING ---- */
        .form-body {
            padding: 2rem;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.875rem;
            color: #374151;
            margin-bottom: 0.4rem;
        }

        .required-star {
            color: #ef4444;
        }

        .form-control,
        .form-select {
            border: 1.5px solid #d1d5db;
            border-radius: 10px;
            padding: 0.65rem 1rem;
            font-size: 0.9rem;
            transition: all 0.25s ease;
            background-color: #fafafa;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--biru-muda);
            box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.15);
            background-color: #fff;
        }

        .input-group-text {
            background: #f3f4f6;
            border: 1.5px solid #d1d5db;
            border-right: none;
            border-radius: 10px 0 0 10px;
            color: var(--teks-abu);
        }

        .input-group .form-control,
        .input-group .form-select {
            border-left: none;
            border-radius: 0 10px 10px 0;
        }

        .form-hint {
            font-size: 0.78rem;
            color: var(--teks-abu);
            margin-top: 0.3rem;
        }

        /* ---- TOMBOL SUBMIT ---- */
        .btn-daftar {
            background: linear-gradient(135deg, #0d47a1, #1976d2);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
            padding: 0.85rem 2rem;
            letter-spacing: 0.3px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 16px rgba(13, 71, 161, 0.35);
        }

        .btn-daftar:hover {
            background: linear-gradient(135deg, #1565c0, #1e88e5);
            transform: translateY(-2px);
            box-shadow: 0 6px 24px rgba(13, 71, 161, 0.45);
            color: #fff;
        }

        .btn-daftar:active {
            transform: translateY(0);
        }

        /* ---- DIVIDER SECTION ---- */
        .section-divider {
            border: none;
            height: 1px;
            background: linear-gradient(90deg, transparent, #dee2e6, transparent);
            margin: 1.5rem 0;
        }

        .section-title {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--teks-abu);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1rem;
        }

        /* ---- BADGE INFO ---- */
        .info-badge {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.82rem;
            color: #1e40af;
        }

        .info-badge i {
            color: var(--biru-muda);
        }

        /* ---- FOOTER ---- */
        .footer-custom {
            text-align: center;
            padding: 1.5rem 0;
            color: var(--teks-abu);
            font-size: 0.8rem;
        }

        /* ---- ANIMASI MASUK ---- */
        .fade-in-up {
            animation: fadeInUp 0.6s ease both;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(24px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ---- RESPONSIVE ---- */
        @media (max-width: 576px) {
            .card-header-custom {
                padding: 1.5rem;
            }
            .form-body {
                padding: 1.5rem;
            }
            .card-header-custom h2 {
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>

    <!-- ============================================================
         NAVBAR / HEADER UTAMA
    ============================================================ -->
    <nav class="navbar navbar-custom">
        <div class="container">
            <div class="d-flex align-items-center gap-3 w-100">
                <!-- Logo / Ikon -->
                <div class="d-flex align-items-center justify-content-center"
                     style="width:44px;height:44px;background:rgba(255,255,255,0.15);
                            border-radius:12px;border:1px solid rgba(255,255,255,0.25);">
                    <i class="bi bi-mortarboard-fill" style="font-size:1.3rem;color:#fff;"></i>
                </div>

                <!-- Teks Brand -->
                <div class="navbar-brand-text">
                    STIKOM BALI
                    <small>Workshop Library PHP &amp; Composer</small>
                </div>

                <!-- Badge -->
                <div class="ms-auto d-none d-sm-block">
                    <span class="navbar-badge">
                        <i class="bi bi-calendar-event me-1"></i>2026
                    </span>
                </div>
            </div>
        </div>
    </nav>

    <!-- ============================================================
         KONTEN UTAMA
    ============================================================ -->
    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-7 col-xl-6">

                <!-- Tampilkan pesan error jika ada (dikirim dari proses-pendaftaran.php) -->
                <?php if (!empty($pesan_error)): ?>
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-start gap-2 mb-4 fade-in-up"
                     role="alert" id="alert-error">
                    <i class="bi bi-exclamation-triangle-fill flex-shrink-0 mt-1" style="font-size:1.1rem;"></i>
                    <div>
                        <strong>Pendaftaran Gagal!</strong><br>
                        <?php echo $pesan_error; ?>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Tutup"></button>
                </div>
                <?php endif; ?>

                <!-- ---- CARD FORM UTAMA ---- -->
                <div class="main-card fade-in-up" style="animation-delay:0.1s;">

                    <!-- Header Card -->
                    <div class="card-header-custom">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-box">
                                <i class="bi bi-person-plus-fill" style="color:#fff;"></i>
                            </div>
                            <div>
                                <h2>Form Pendaftaran Workshop</h2>
                                <p>Library PHP &amp; Composer · STIKOM Bali</p>
                            </div>
                        </div>
                    </div>

                    <!-- Body Form -->
                    <div class="form-body">

                        <!-- Info Email Konfirmasi -->
                        <div class="info-badge d-flex align-items-center gap-2 mb-4">
                            <i class="bi bi-envelope-check-fill flex-shrink-0" style="font-size:1.1rem;"></i>
                            <span>Setelah mendaftar, email konfirmasi akan otomatis dikirimkan ke alamat email Anda.</span>
                        </div>

                        <!-- ---- FORM PENDAFTARAN ---- -->
                        <form action="proses-pendaftaran.php" method="POST" id="form-pendaftaran" novalidate>

                            <!-- === DATA PRIBADI === -->
                            <p class="section-title">
                                <i class="bi bi-person-vcard me-1"></i>Data Pribadi
                            </p>

                            <!-- Field: Nama Lengkap -->
                            <div class="mb-3">
                                <label for="nama" class="form-label">
                                    Nama Lengkap <span class="required-star">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-person"></i>
                                    </span>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="nama"
                                        name="nama"
                                        placeholder="Masukkan nama lengkap Anda"
                                        value="<?php echo $old_nama; ?>"
                                        required
                                        autocomplete="name"
                                    >
                                </div>
                            </div>

                            <!-- Field: Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    Alamat Email <span class="required-star">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-envelope"></i>
                                    </span>
                                    <input
                                        type="email"
                                        class="form-control"
                                        id="email"
                                        name="email"
                                        placeholder="contoh@email.com"
                                        value="<?php echo $old_email; ?>"
                                        required
                                        autocomplete="email"
                                    >
                                </div>
                                <div class="form-hint">
                                    <i class="bi bi-info-circle me-1"></i>Email konfirmasi akan dikirim ke alamat ini.
                                </div>
                            </div>

                            <!-- Field: Nomor HP -->
                            <div class="mb-3">
                                <label for="no_hp" class="form-label">
                                    Nomor HP / WhatsApp <span class="required-star">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-telephone"></i>
                                    </span>
                                    <input
                                        type="tel"
                                        class="form-control"
                                        id="no_hp"
                                        name="no_hp"
                                        placeholder="Contoh: 08123456789"
                                        value="<?php echo $old_no_hp; ?>"
                                        required
                                        autocomplete="tel"
                                    >
                                </div>
                            </div>

                            <hr class="section-divider">

                            <!-- === DATA AKADEMIK === -->
                            <p class="section-title">
                                <i class="bi bi-building me-1"></i>Data Akademik
                            </p>

                            <!-- Field: Program Studi -->
                            <div class="mb-4">
                                <label for="prodi" class="form-label">
                                    Program Studi <span class="required-star">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-book"></i>
                                    </span>
                                    <select class="form-select" id="prodi" name="prodi" required>
                                        <option value="" disabled <?php echo empty($old_prodi) ? 'selected' : ''; ?>>
                                            -- Pilih Program Studi --
                                        </option>
                                        <option value="Sistem Informasi (S.Kom.)"
                                            <?php echo ($old_prodi === 'Sistem Informasi (S.Kom.)') ? 'selected' : ''; ?>>
                                            Sistem Informasi (S.Kom.)
                                        </option>
                                        <option value="Sistem Komputer (S.Kom.)"
                                            <?php echo ($old_prodi === 'Sistem Komputer (S.Kom.)') ? 'selected' : ''; ?>>
                                            Sistem Komputer (S.Kom.)
                                        </option>
                                        <option value="Teknologi Informasi (S.Kom.)"
                                            <?php echo ($old_prodi === 'Teknologi Informasi (S.Kom.)') ? 'selected' : ''; ?>>
                                            Teknologi Informasi (S.Kom.)
                                        </option>
                                        <option value="Bisnis Digital (S.Bns.)"
                                            <?php echo ($old_prodi === 'Bisnis Digital (S.Bns.)') ? 'selected' : ''; ?>>
                                            Bisnis Digital (S.Bns.)
                                        </option>
                                        <option value="Manajemen Informatika (A.Md.Kom.)"
                                            <?php echo ($old_prodi === 'Manajemen Informatika (A.Md.Kom.)') ? 'selected' : ''; ?>>
                                            Manajemen Informatika (A.Md.Kom.)
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <hr class="section-divider">

                            <!-- === PESAN TAMBAHAN === -->
                            <p class="section-title">
                                <i class="bi bi-chat-left-text me-1"></i>Pesan Tambahan (Opsional)
                            </p>

                            <!-- Field: Pesan / Motivasi -->
                            <div class="mb-4">
                                <label for="pesan" class="form-label">
                                    Pesan Tambahan / Motivasi
                                </label>
                                <textarea
                                    class="form-control"
                                    id="pesan"
                                    name="pesan"
                                    rows="3"
                                    placeholder="Tuliskan motivasi Anda mengikuti workshop ini atau pertanyaan yang ingin disampaikan..."
                                    style="border-radius:10px;"
                                ><?php echo $old_pesan; ?></textarea>
                                <div class="form-hint">
                                    <i class="bi bi-pencil me-1"></i>Field ini tidak wajib diisi.
                                </div>
                            </div>

                            <!-- Tombol Submit -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-daftar" id="btn-submit">
                                    <i class="bi bi-envelope-arrow-up me-2"></i>
                                    Daftar Sekarang &amp; Kirim Konfirmasi
                                </button>
                            </div>

                            <!-- Keterangan wajib -->
                            <p class="text-center mt-3 form-hint">
                                <span class="required-star">*</span> Tanda bintang menunjukkan field yang wajib diisi.
                            </p>

                        </form>
                    </div><!-- /form-body -->
                </div><!-- /main-card -->

                <!-- Footer Info -->
                <div class="footer-custom mt-4 fade-in-up" style="animation-delay:0.3s;">
                    <p class="mb-1">
                        <i class="bi bi-shield-check me-1 text-success"></i>
                        Data Anda aman dan hanya digunakan untuk keperluan workshop.
                    </p>
                    <p class="mb-0">
                        &copy; 2026 STIKOM Bali &mdash; Workshop Library PHP &amp; Composer
                    </p>
                </div>

            </div><!-- /col -->
        </div><!-- /row -->
    </main>

    <!-- Bootstrap 5.3.3 JS Bundle via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc4s9bIOgUxi8T/jzmFXMADFQJwkjse/y0/oogyhARlN"
            crossorigin="anonymous"></script>

    <script>
        /**
         * JavaScript - Validasi sisi klien dan UX enhancement
         * Memberikan feedback yang lebih baik sebelum form dikirim
         */

        // Tambahkan efek loading pada tombol saat form disubmit
        document.getElementById('form-pendaftaran').addEventListener('submit', function(e) {
            const btn = document.getElementById('btn-submit');

            // Cek validasi HTML5 terlebih dahulu
            if (!this.checkValidity()) {
                e.preventDefault();
                // Tampilkan semua validasi
                this.classList.add('was-validated');
                return;
            }

            // Ubah tombol menjadi loading state
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Memproses Pendaftaran...';
            btn.disabled = true;
        });
    </script>

</body>
</html>
