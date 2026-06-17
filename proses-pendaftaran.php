<?php
/**
 * ============================================================
 * proses-pendaftaran.php - Backend Proses & Kirim Email
 * ============================================================
 * Proyek  : Pendaftaran Workshop Library PHP & Composer
 * Kampus  : STIKOM Bali
 * Dosen   : Gede Herdian Setiawan, S.Kom., M.T.
 * ============================================================
 * File ini menangani:
 * 1. Validasi data dari form pendaftaran (server-side)
 * 2. Pengiriman email konfirmasi menggunakan PHPMailer + SMTP Gmail
 * 3. Redirect ke halaman sukses atau error
 * ============================================================
 */

// Muat semua library yang diinstall via Composer (autoloading)
require 'vendor/autoload.php';

// Set zona waktu default ke WITA (Waktu Indonesia Tengah) untuk STIKOM Bali
date_default_timezone_set('Asia/Makassar');

// Gunakan namespace PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// ============================================================
// LANGKAH 1: PASTIKAN REQUEST MENGGUNAKAN METHOD POST
// ============================================================
// Jika bukan POST, redirect ke halaman form
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// ============================================================
// LANGKAH 2: AMBIL DAN BERSIHKAN DATA DARI FORM
// ============================================================
// Gunakan trim() untuk menghapus spasi di awal/akhir
$nama    = trim($_POST['nama']  ?? '');
$email   = trim($_POST['email'] ?? '');
$no_hp   = trim($_POST['no_hp'] ?? '');
$prodi   = trim($_POST['prodi'] ?? '');
$pesan   = trim($_POST['pesan'] ?? ''); // Field opsional

// ============================================================
// LANGKAH 3: VALIDASI SERVER-SIDE
// ============================================================
$errors = []; // Array untuk menampung semua pesan error

// Cek apakah field wajib terisi
if (empty($nama)) {
    $errors[] = 'Nama Lengkap wajib diisi.';
}

if (empty($email)) {
    $errors[] = 'Alamat Email wajib diisi.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    // Validasi format email menggunakan filter bawaan PHP
    $errors[] = 'Format alamat email tidak valid. Contoh yang benar: nama@gmail.com';
}

if (empty($no_hp)) {
    $errors[] = 'Nomor HP / WhatsApp wajib diisi.';
}

if (empty($prodi)) {
    $errors[] = 'Program Studi wajib dipilih.';
}

// Jika ada error, redirect kembali ke form dengan pesan error
if (!empty($errors)) {
    $pesan_error = implode(' ', $errors);

    // Simpan juga nilai yang sudah diisi agar tidak hilang (UX friendly)
    $query = http_build_query([
        'error' => $pesan_error,
        'nama'  => $nama,
        'email' => $email,
        'no_hp' => $no_hp,
        'prodi' => $prodi,
        'pesan' => $pesan,
    ]);

    header('Location: index.php?' . $query);
    exit;
}

// ============================================================
// LANGKAH 4: SIAPKAN DATA UNTUK EMAIL
// ============================================================

// Format waktu pendaftaran dalam Bahasa Indonesia
$nama_bulan = [
    1  => 'Januari', 2  => 'Februari', 3  => 'Maret',
    4  => 'April',   5  => 'Mei',      6  => 'Juni',
    7  => 'Juli',    8  => 'Agustus',  9  => 'September',
    10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];
$tgl_daftar = date('d') . ' ' . $nama_bulan[(int)date('n')] . ' ' . date('Y') . ', ' . date('H:i:s') . ' WITA';

// Sanitasi data untuk keamanan tampilan di HTML email
$nama_safe   = htmlspecialchars($nama,  ENT_QUOTES, 'UTF-8');
$email_safe  = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
$no_hp_safe  = htmlspecialchars($no_hp, ENT_QUOTES, 'UTF-8');
$prodi_safe  = htmlspecialchars($prodi, ENT_QUOTES, 'UTF-8');
$pesan_safe  = !empty($pesan)
               ? nl2br(htmlspecialchars($pesan, ENT_QUOTES, 'UTF-8'))
               : '<em style="color:#9ca3af;">Tidak ada pesan tambahan.</em>';

// ============================================================
// LANGKAH 5: BUAT KONTEN EMAIL HTML (PROFESIONAL)
// ============================================================
$email_body = <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pendaftaran Workshop</title>
</head>
<body style="margin:0;padding:0;background-color:#f0f4f8;font-family:'Segoe UI',Arial,sans-serif;">

    <!-- Wrapper Utama -->
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
           style="background-color:#f0f4f8;padding:32px 16px;">
        <tr>
            <td align="center">

                <!-- Container Email (max 580px) -->
                <table role="presentation" cellspacing="0" cellpadding="0" border="0"
                       width="580" style="max-width:580px;width:100%;">

                    <!-- ---- HEADER EMAIL ---- -->
                    <tr>
                        <td style="background:linear-gradient(135deg,#0d47a1,#1976d2);
                                   border-radius:16px 16px 0 0;padding:32px 40px;text-align:center;">
                            <p style="margin:0 0 8px;color:rgba(255,255,255,0.8);font-size:13px;
                                      letter-spacing:2px;text-transform:uppercase;font-weight:600;">
                                STIKOM BALI
                            </p>
                            <h1 style="margin:0;color:#ffffff;font-size:22px;font-weight:700;line-height:1.3;">
                                Workshop Library PHP &amp; Composer
                            </h1>
                            <p style="margin:12px 0 0;color:rgba(255,255,255,0.85);font-size:14px;">
                                Materi Perkuliahan · 2026
                            </p>
                        </td>
                    </tr>

                    <!-- ---- BANNER SUKSES ---- -->
                    <tr>
                        <td style="background:#ffffff;padding:32px 40px 0;text-align:center;">
                            <div style="display:inline-block;width:72px;height:72px;
                                        background:linear-gradient(135deg,#22c55e,#16a34a);
                                        border-radius:50%;line-height:72px;font-size:36px;
                                        box-shadow:0 4px 16px rgba(34,197,94,0.35);">
                                ✅
                            </div>
                            <h2 style="margin:16px 0 8px;color:#0d47a1;font-size:20px;font-weight:700;">
                                Pendaftaran Berhasil!
                            </h2>
                            <p style="margin:0;color:#6b7280;font-size:15px;line-height:1.6;">
                                Halo, <strong style="color:#1a1a2e;">$nama_safe</strong>! 👋<br>
                                Terima kasih telah mendaftar workshop. Kami telah menerima
                                pendaftaran Anda dengan sukses.
                            </p>
                        </td>
                    </tr>

                    <!-- ---- DATA PENDAFTAR ---- -->
                    <tr>
                        <td style="background:#ffffff;padding:24px 40px;">
                            <p style="margin:0 0 16px;font-size:13px;font-weight:700;
                                      color:#6b7280;text-transform:uppercase;letter-spacing:1px;">
                                📋 Detail Pendaftaran Anda
                            </p>

                            <!-- Tabel Data -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0"
                                   width="100%" style="border:1px solid #e5e7eb;border-radius:12px;
                                                       overflow:hidden;">
                                <tr style="background:#f9fafb;">
                                    <td style="padding:12px 20px;font-size:13px;font-weight:600;
                                               color:#374151;width:42%;border-bottom:1px solid #e5e7eb;">
                                        👤 Nama Lengkap
                                    </td>
                                    <td style="padding:12px 20px;font-size:14px;color:#111827;
                                               border-bottom:1px solid #e5e7eb;">
                                        $nama_safe
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 20px;font-size:13px;font-weight:600;
                                               color:#374151;border-bottom:1px solid #e5e7eb;">
                                        📧 Alamat Email
                                    </td>
                                    <td style="padding:12px 20px;font-size:14px;color:#111827;
                                               border-bottom:1px solid #e5e7eb;">
                                        $email_safe
                                    </td>
                                </tr>
                                <tr style="background:#f9fafb;">
                                    <td style="padding:12px 20px;font-size:13px;font-weight:600;
                                               color:#374151;border-bottom:1px solid #e5e7eb;">
                                        📱 Nomor HP / WA
                                    </td>
                                    <td style="padding:12px 20px;font-size:14px;color:#111827;
                                               border-bottom:1px solid #e5e7eb;">
                                        $no_hp_safe
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 20px;font-size:13px;font-weight:600;
                                               color:#374151;border-bottom:1px solid #e5e7eb;">
                                        🎓 Program Studi
                                    </td>
                                    <td style="padding:12px 20px;font-size:14px;color:#111827;
                                               border-bottom:1px solid #e5e7eb;">
                                        $prodi_safe
                                    </td>
                                </tr>
                                <tr style="background:#f9fafb;">
                                    <td style="padding:12px 20px;font-size:13px;font-weight:600;
                                               color:#374151;border-bottom:1px solid #e5e7eb;">
                                        💬 Pesan Tambahan
                                    </td>
                                    <td style="padding:12px 20px;font-size:14px;color:#111827;
                                               border-bottom:1px solid #e5e7eb;">
                                        $pesan_safe
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 20px;font-size:13px;font-weight:600;
                                               color:#374151;">
                                        🕐 Waktu Pendaftaran
                                    </td>
                                    <td style="padding:12px 20px;font-size:14px;color:#111827;">
                                        $tgl_daftar
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- ---- INFORMASI TAMBAHAN ---- -->
                    <tr>
                        <td style="background:#ffffff;padding:0 40px 24px;">
                            <div style="background:#eff6ff;border:1px solid #bfdbfe;
                                        border-radius:12px;padding:16px 20px;">
                                <p style="margin:0 0 8px;font-size:13px;font-weight:700;color:#1e40af;">
                                    ℹ️ Informasi Selanjutnya
                                </p>
                                <p style="margin:0;font-size:13px;color:#1e40af;line-height:1.7;">
                                    Tim panitia akan menghubungi Anda melalui nomor WhatsApp atau email
                                    yang telah Anda daftarkan untuk informasi teknis workshop lebih lanjut.
                                    Harap simpan email ini sebagai bukti pendaftaran Anda.
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- ---- FOOTER EMAIL ---- -->
                    <tr>
                        <td style="background:#1e293b;border-radius:0 0 16px 16px;
                                   padding:24px 40px;text-align:center;">
                            <p style="margin:0 0 6px;color:#94a3b8;font-size:13px;">
                                Email ini dikirim secara otomatis oleh sistem pendaftaran.
                            </p>
                            <p style="margin:0 0 12px;color:#94a3b8;font-size:12px;">
                                Harap tidak membalas email ini.
                            </p>
                            <p style="margin:0;color:#64748b;font-size:12px;">
                                &copy; 2026 STIKOM Bali &mdash; Workshop Library PHP &amp; Composer
                            </p>
                        </td>
                    </tr>

                </table><!-- /container email -->
            </td>
        </tr>
    </table><!-- /wrapper -->

</body>
</html>
HTML;

// Versi plain text sebagai fallback (untuk email client yang tidak support HTML)
$email_altbody = "KONFIRMASI PENDAFTARAN WORKSHOP\n"
    . "================================\n"
    . "STIKOM Bali - Workshop Library PHP & Composer\n\n"
    . "Halo, {$nama}!\n\n"
    . "Pendaftaran Anda telah berhasil diterima.\n\n"
    . "DETAIL PENDAFTARAN:\n"
    . "- Nama Lengkap      : {$nama}\n"
    . "- Alamat Email      : {$email}\n"
    . "- Nomor HP / WA     : {$no_hp}\n"
    . "- Program Studi     : {$prodi}\n"
    . "- Pesan Tambahan    : " . (!empty($pesan) ? $pesan : 'Tidak ada') . "\n"
    . "- Waktu Pendaftaran : {$tgl_daftar}\n\n"
    . "Tim panitia akan menghubungi Anda untuk informasi lebih lanjut.\n\n"
    . "© 2026 STIKOM Bali - Workshop Library PHP & Composer";

// ============================================================
// LANGKAH 6: KIRIM EMAIL MENGGUNAKAN PHPMAILER
// ============================================================
try {
    // Buat instance PHPMailer (true = aktifkan exception)
    $mail = new PHPMailer(true);

    // ---------- Konfigurasi Server SMTP ----------
    // $mail->SMTPDebug = SMTP::DEBUG_SERVER; // Aktifkan untuk debugging (nonaktifkan di produksi)
    $mail->isSMTP();                                          // Gunakan SMTP
    $mail->Host       = 'smtp.gmail.com';                    // Server SMTP Gmail
    $mail->SMTPAuth   = true;                                 // Aktifkan autentikasi SMTP
    $mail->Port       = 587;                                  // Port STARTTLS
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;       // Enkripsi STARTTLS

    // ---------- Kredensial Gmail ----------
    // Nilai default (placeholder) untuk di-commit ke Git
    $smtp_username = 'email-anda@gmail.com'; // === GANTI DENGAN EMAIL GMAIL ANDA ===
    $smtp_password = 'xxxx xxxx xxxx xxxx'; // === GANTI DENGAN APP PASSWORD ANDA ===

    // Muat kredensial asli dari config.local.php jika tersedia (diabaikan oleh Git)
    if (file_exists(__DIR__ . '/config.local.php')) {
        include __DIR__ . '/config.local.php';
    }

    $mail->Username = $smtp_username;
    $mail->Password = $smtp_password;

    // ---------- Pengaturan Karakter ----------
    $mail->CharSet = 'UTF-8';                                 // Mendukung huruf Indonesia

    // ---------- Pengirim Email ----------
    $mail->setFrom($smtp_username, 'STIKOM Bali - Workshop PHP');

    // ---------- Penerima Email (email pendaftar) ----------
    $mail->addAddress($email, $nama);

    // Kirim tembusan (BCC) ke penyelenggara agar tahu siapa saja yang daftar
    $mail->addBCC($smtp_username, 'Penyelenggara STIKOM Bali');

    // ---------- Konten Email ----------
    $mail->isHTML(true);                                      // Aktifkan format HTML
    $mail->Subject = 'Konfirmasi Pendaftaran Workshop Library PHP & Composer - STIKOM Bali';
    $mail->Body    = $email_body;                             // Body HTML
    $mail->AltBody = $email_altbody;                          // Fallback plain text

    // ---------- Kirim Email ----------
    $mail->send();

    // Jika berhasil, redirect ke halaman sukses dengan parameter email
    header('Location: sukses.php?email=' . urlencode($email) . '&nama=' . urlencode($nama));
    exit;

} catch (Exception $e) {
    // ============================================================
    // PENANGANAN ERROR - Jika email gagal terkirim
    // ============================================================
    // Tampilkan halaman error yang ramah pengguna
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Gagal Mengirim Email | Workshop STIKOM Bali</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
              integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
              crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Inter', sans-serif; background: #f0f4f8; min-height: 100vh; }
        </style>
    </head>
    <body class="d-flex align-items-center py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                        <!-- Header Merah -->
                        <div class="card-header border-0 text-center py-4"
                             style="background:linear-gradient(135deg,#dc2626,#ef4444);">
                            <div style="font-size:3rem;">❌</div>
                            <h4 class="text-white fw-bold mb-0 mt-2">Gagal Mengirim Email</h4>
                        </div>
                        <div class="card-body p-4 p-md-5 bg-white">
                            <div class="alert alert-danger border-0 rounded-3">
                                <strong><i class="bi bi-exclamation-triangle-fill me-2"></i>Terjadi Kesalahan</strong>
                                <p class="mb-0 mt-2">Sistem tidak dapat mengirimkan email konfirmasi saat ini.</p>
                            </div>

                            <h6 class="fw-bold text-secondary mb-3">
                                <i class="bi bi-tools me-2"></i>Kemungkinan Penyebab:
                            </h6>
                            <ul class="text-secondary" style="line-height:2;">
                                <li>Email dan App Password Gmail belum dikonfigurasi di <code>proses-pendaftaran.php</code></li>
                                <li>App Password yang dimasukkan tidak valid atau telah kedaluwarsa</li>
                                <li>Fitur 2-Step Verification belum diaktifkan di akun Gmail</li>
                                <li>Koneksi internet bermasalah atau server SMTP tidak dapat dijangkau</li>
                            </ul>

                            <!-- Detail error untuk debugging (tampilkan hanya saat development) -->
                            <div class="alert alert-warning border-0 rounded-3 mt-3">
                                <small>
                                    <strong><i class="bi bi-bug-fill me-1"></i>Detail Error (untuk Developer):</strong><br>
                                    <code><?php echo htmlspecialchars($mail->ErrorInfo, ENT_QUOTES, 'UTF-8'); ?></code>
                                </small>
                            </div>

                            <div class="d-grid gap-2 mt-4">
                                <a href="index.php" class="btn btn-primary rounded-3 fw-semibold py-2">
                                    <i class="bi bi-arrow-left-circle me-2"></i>Kembali ke Form Pendaftaran
                                </a>
                                <a href="README.md" class="btn btn-outline-secondary rounded-3 py-2" target="_blank">
                                    <i class="bi bi-book me-2"></i>Baca Panduan Konfigurasi
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-YvpcrYf0tY3lHB60NNkmXc4s9bIOgUxi8T/jzmFXMADFQJwkjse/y0/oogyhARlN"
                crossorigin="anonymous"></script>
    </body>
    </html>
    <?php
    exit;
}
