<?php
/**
 * ============================================================
 * sukses.php - Halaman Konfirmasi Pendaftaran Berhasil
 * ============================================================
 * Proyek  : Pendaftaran Workshop Library PHP & Composer
 * Kampus  : STIKOM Bali
 * Dosen   : Gede Herdian Setiawan, S.Kom., M.T.
 * ============================================================
 * Halaman ini ditampilkan setelah email konfirmasi berhasil
 * dikirimkan kepada pendaftar.
 * ============================================================
 */

// Ambil dan sanitasi parameter dari URL
// Parameter dikirim oleh proses-pendaftaran.php via redirect
$email = '';
$nama  = '';

if (!empty($_GET['email'])) {
    $email = htmlspecialchars(urldecode($_GET['email']), ENT_QUOTES, 'UTF-8');
}

if (!empty($_GET['nama'])) {
    $nama = htmlspecialchars(urldecode($_GET['nama']), ENT_QUOTES, 'UTF-8');
}

// Jika tidak ada email di URL, redirect ke form (mencegah akses langsung)
if (empty($email)) {
    header('Location: index.php');
    exit;
}

// Ambil hanya bagian nama depan untuk sapaan yang ramah
$nama_depan = !empty($nama) ? explode(' ', $nama)[0] : 'Pendaftar';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Pendaftaran Workshop Library PHP & Composer STIKOM Bali berhasil. Email konfirmasi telah dikirim.">
    <title>Pendaftaran Berhasil! | Workshop STIKOM Bali</title>

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
         * CSS Kustom - Halaman Sukses STIKOM Bali
         * ============================================================ */

        :root {
            --hijau-utama: #16a34a;
            --hijau-muda:  #22c55e;
            --biru-utama:  #0d47a1;
            --biru-muda:   #1976d2;
            --abu-bg:      #f0f4f8;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f0fdf4 0%, #f0f4f8 50%, #e8f5e9 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
        }

        /* ---- NAVBAR ---- */
        .navbar-custom {
            background: linear-gradient(90deg, #0d47a1, #1565c0, #1976d2);
            box-shadow: 0 2px 16px rgba(13, 71, 161, 0.3);
            padding: 0.9rem 0;
        }

        .navbar-brand-text {
            font-weight: 700;
            font-size: 1.05rem;
            color: #ffffff !important;
            line-height: 1.3;
        }

        .navbar-brand-text small {
            font-weight: 400;
            font-size: 0.78rem;
            color: rgba(255,255,255,0.8);
            display: block;
        }

        /* ---- ICON CENTANG ANIMASI ---- */
        .success-circle {
            width: 110px;
            height: 110px;
            background: linear-gradient(135deg, #22c55e, #16a34a);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 8px 32px rgba(34, 197, 94, 0.4);
            animation: bounceIn 0.7s cubic-bezier(0.175, 0.885, 0.32, 1.275) both;
        }

        .success-circle i {
            font-size: 3rem;
            color: #ffffff;
        }

        @keyframes bounceIn {
            0%   { transform: scale(0); opacity: 0; }
            60%  { transform: scale(1.1); }
            80%  { transform: scale(0.95); }
            100% { transform: scale(1); opacity: 1; }
        }

        /* ---- CARD UTAMA ---- */
        .sukses-card {
            background: #ffffff;
            border: none;
            border-radius: 24px;
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.10);
            overflow: hidden;
            animation: fadeInUp 0.6s ease 0.2s both;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(28px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .card-top-border {
            height: 6px;
            background: linear-gradient(90deg, #22c55e, #16a34a, #15803d);
            width: 100%;
        }

        /* ---- BADGE EMAIL ---- */
        .email-badge {
            background: #f0fdf4;
            border: 2px solid #bbf7d0;
            border-radius: 50px;
            padding: 0.6rem 1.4rem;
            font-size: 0.95rem;
            font-weight: 600;
            color: #15803d;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            word-break: break-all;
            max-width: 100%;
        }

        /* ---- LANGKAH-LANGKAH ---- */
        .step-card {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 1rem 1.25rem;
        }

        .step-number {
            width: 28px;
            height: 28px;
            background: var(--biru-utama);
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        /* ---- TOMBOL AKSI ---- */
        .btn-kembali {
            border: 2px solid var(--biru-utama);
            color: var(--biru-utama);
            border-radius: 12px;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            transition: all 0.25s ease;
            background: transparent;
        }

        .btn-kembali:hover {
            background: var(--biru-utama);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(13,71,161,0.3);
        }

        .btn-gmail {
            background: linear-gradient(135deg, #ea4335, #c5221f);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-weight: 700;
            padding: 0.75rem 1.5rem;
            transition: all 0.25s ease;
            box-shadow: 0 4px 16px rgba(234,67,53,0.35);
        }

        .btn-gmail:hover {
            background: linear-gradient(135deg, #d33b2c, #b31412);
            transform: translateY(-2px);
            box-shadow: 0 6px 24px rgba(234,67,53,0.45);
            color: #fff;
        }

        /* ---- KONFETI DEKORASI ---- */
        .confetti-line {
            text-align: center;
            font-size: 1.5rem;
            letter-spacing: 4px;
            animation: fadeInUp 0.8s ease 0.5s both;
        }

        /* ---- FOOTER ---- */
        .footer-custom {
            text-align: center;
            padding: 1.5rem 0;
            color: #9ca3af;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>

    <!-- ============================================================
         NAVBAR / HEADER
    ============================================================ -->
    <nav class="navbar navbar-custom">
        <div class="container">
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center"
                     style="width:44px;height:44px;background:rgba(255,255,255,0.15);
                            border-radius:12px;border:1px solid rgba(255,255,255,0.25);">
                    <i class="bi bi-mortarboard-fill" style="font-size:1.3rem;color:#fff;"></i>
                </div>
                <div class="navbar-brand-text">
                    STIKOM BALI
                    <small>Workshop Library PHP &amp; Composer</small>
                </div>
            </div>
        </div>
    </nav>

    <!-- ============================================================
         KONTEN UTAMA - HALAMAN SUKSES
    ============================================================ -->
    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-9 col-lg-7 col-xl-6">

                <!-- Dekorasi Konfeti -->
                <div class="confetti-line mb-3">🎉 🎊 🎉</div>

                <!-- ---- CARD SUKSES ---- -->
                <div class="sukses-card">

                    <!-- Garis atas hijau -->
                    <div class="card-top-border"></div>

                    <div class="p-4 p-md-5">

                        <!-- Icon Centang -->
                        <div class="success-circle">
                            <i class="bi bi-check-lg"></i>
                        </div>

                        <!-- Judul Sukses -->
                        <div class="text-center mb-4">
                            <h1 class="fw-800 mb-2" style="font-size:1.8rem;font-weight:800;color:#15803d;">
                                Pendaftaran Berhasil!
                            </h1>
                            <p class="text-secondary mb-0" style="font-size:1rem;">
                                Halo, <strong><?php echo $nama_depan; ?></strong>! 👋
                                Terima kasih telah mendaftar workshop kami.
                            </p>
                        </div>

                        <!-- Kotak Info Email -->
                        <div class="text-center mb-4">
                            <p class="text-secondary mb-2" style="font-size:0.9rem;">
                                <i class="bi bi-envelope-check-fill text-success me-1"></i>
                                Email konfirmasi telah dikirim ke:
                            </p>
                            <div class="d-flex justify-content-center">
                                <span class="email-badge">
                                    <i class="bi bi-at"></i>
                                    <?php echo $email; ?>
                                </span>
                            </div>
                        </div>

                        <hr style="border-color:#e5e7eb;margin:1.5rem 0;">

                        <!-- Langkah Selanjutnya -->
                        <div class="mb-4">
                            <p class="fw-semibold mb-3" style="font-size:0.85rem;color:#6b7280;
                               text-transform:uppercase;letter-spacing:1px;">
                                <i class="bi bi-list-check me-1"></i>Langkah Selanjutnya
                            </p>

                            <div class="d-flex flex-column gap-2">
                                <!-- Langkah 1 -->
                                <div class="step-card d-flex align-items-start gap-3">
                                    <div class="step-number">1</div>
                                    <div>
                                        <p class="mb-0 fw-semibold" style="font-size:0.9rem;color:#1f2937;">
                                            Cek kotak masuk email Anda
                                        </p>
                                        <p class="mb-0" style="font-size:0.82rem;color:#6b7280;">
                                            Email konfirmasi sudah dikirim. Cek juga folder <em>Spam</em> atau <em>Promosi</em>.
                                        </p>
                                    </div>
                                </div>

                                <!-- Langkah 2 -->
                                <div class="step-card d-flex align-items-start gap-3">
                                    <div class="step-number">2</div>
                                    <div>
                                        <p class="mb-0 fw-semibold" style="font-size:0.9rem;color:#1f2937;">
                                            Simpan email konfirmasi
                                        </p>
                                        <p class="mb-0" style="font-size:0.82rem;color:#6b7280;">
                                            Email berisi detail pendaftaran Anda sebagai bukti resmi.
                                        </p>
                                    </div>
                                </div>

                                <!-- Langkah 3 -->
                                <div class="step-card d-flex align-items-start gap-3">
                                    <div class="step-number">3</div>
                                    <div>
                                        <p class="mb-0 fw-semibold" style="font-size:0.9rem;color:#1f2937;">
                                            Tunggu info lanjutan dari panitia
                                        </p>
                                        <p class="mb-0" style="font-size:0.82rem;color:#6b7280;">
                                            Panitia akan menghubungi melalui WhatsApp atau email Anda.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Alert Tips Spam -->
                        <div class="alert border-0 rounded-3 d-flex gap-2 align-items-start mb-4"
                             style="background:#fff7ed;border-left:4px solid #fb923c !important;
                                    border-left-width:4px !important;">
                            <i class="bi bi-exclamation-circle-fill flex-shrink-0 mt-1"
                               style="color:#ea7c1e;"></i>
                            <small style="color:#92400e;line-height:1.6;">
                                <strong>Tidak menemukan email?</strong> Coba cek folder
                                <strong>Spam</strong>, <strong>Promosi</strong>, atau
                                <strong>Semua Surat</strong> di Gmail Anda. Tandai email kami
                                sebagai "Bukan Spam" jika ditemukan di sana.
                            </small>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="d-grid gap-3">
                            <!-- Tombol Gmail - Target Utama -->
                            <a href="https://mail.google.com"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="btn btn-gmail text-center"
                               id="btn-buka-gmail">
                                <i class="bi bi-google me-2"></i>
                                Buka Gmail Saya
                            </a>

                            <!-- Tombol Kembali ke Form -->
                            <a href="index.php"
                               class="btn btn-kembali text-center"
                               id="btn-kembali-form">
                                <i class="bi bi-arrow-left-circle me-2"></i>
                                Kembali ke Form Pendaftaran
                            </a>
                        </div>

                    </div><!-- /card-body -->
                </div><!-- /sukses-card -->

                <!-- Footer -->
                <div class="footer-custom mt-4">
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

</body>
</html>
