# Prompt untuk Sesi Baru — Kompre Simulator

Salin seluruh isi di bawah garis ini ke sesi Claude Code yang baru.

---

Baca `CLAUDE.md` di root project ini lebih dulu, seluruhnya, sebelum menyentuh apa pun. Dokumen itu adalah peta project dan sudah final. **Jangan menggenerate ulang project, jangan merancang ulang arsitektur, jangan mengusulkan stack lain.** Pekerjaan ini sudah berjalan 12 commit dan tinggal dilanjutkan.

## Kondisi sekarang

Semua item di ceklist Status Pembangunan sudah tercentang: fondasi database, fondasi AI, generator soal, sisi admin, Level Akhir, Level Awal, dan Level Menengah. Commit terakhir `f573f43`. Ada satu perubahan kecil milik admin yang sengaja dibiarkan belum ter-commit di `resources/js/components/soal/form-mockup.tsx`.

Aplikasinya sudah bisa dipakai. Yang belum: 10 mahasiswa asli belum diinput, soalnya belum digenerate massal, dan tampilan hasil perombakan terakhir belum diperiksa admin di browser.

## Aturan yang paling sering dilanggar — patuhi ini

1. **Dilarang memakai Playwright atau otomasi browser apa pun.** Verifikasi lewat `npm run types:check`, `npm run build`, `./vendor/bin/phpstan analyse`, dan `php artisan tinker`. Urusan tampilan diperiksa admin sendiri. Jangan menyalakan `php artisan serve` tanpa diminta.
2. **Dilarang menulis komentar di dalam kode.** Pengecualian tunggal: docblock yang murni berisi tipe dan dituntut PHPStan level 7 (`@return BelongsTo<Model, $this>`, `@property`, `@var`).
3. **Mahasiswa tidak memakai layout bersidebar.** Sidebar hanya untuk admin. Mahasiswa punya `student-layout` dan `drill-layout` di `resources/js/layouts/latihan/`.
4. **Layar pengerjaan menampilkan satu langkah saja**, bukan semua alat sekaligus. Satu instruksi, satu alat, satu tombol utama.
5. **Timer hanya tampil bila ada gunanya.** Level Awal tidak menampilkan timer sama sekali karena itu tutorial. Menengah kecil, Akhir jadi tokoh utama.
6. **Segala yang dibaca mahasiswa wajib bahasa Indonesia**, termasuk nama tabel dan kolom di soal (`nama_pelanggan`, `kode_paket`, `total_bayar`). Kode aplikasi kita sendiri tetap Inggris.
7. **Dilarang menginstall komponen shadcn/ui sendiri.** Minta ke admin, sebutkan nama komponennya, lalu tunggu.
8. **Dilarang memakai dialog bawaan browser** (`confirm`, `alert`). Sudah ada `resources/js/components/confirm-dialog.tsx`.
9. **Mobile first**, lalu naik ke `sm`, `md`, `lg`. Utilitas safe area `.safe-x`, `.safe-t`, `.safe-b`, `.safe-dock` sudah tersedia di `resources/css/app.css`.
10. **Wajib verifikasi ke context7 atau source di `vendor/` sebelum memakai API apa pun.** Kalau tidak bisa diverifikasi, katakan tidak tahu.

## Jebakan lingkungan yang sudah terbukti

- **Queue wajib jalan.** Generate soal lewat `GenerateProblemJob`. Tanpa `php artisan queue:work`, soal menggantung berstatus `queued` selamanya dan admin akan mengira aplikasinya rusak.
- **Dev server Vite bisa basi.** Kalau muncul `Element type is invalid ... got: object`, itu bukan bug kode melainkan modul yang sudah dihapus tapi masih ada di graf dev server. Solusinya `rm -rf node_modules/.vite` lalu restart `npm run dev`.
- **Model Gemini dipin ke `gemini-3.5-flash`.** Default bawaan paket `gemini-3.7-flash` timeout total di key ini. Jangan diubah tanpa menguji.
- **Hasil terstruktur diambil dengan `$response->toArray()`**, bukan `->output`. Properti itu tidak ada di `laravel/ai` 0.11.
- **`Rule::unique('auth.users', ...)` akan meledak** karena Laravel membaca `auth` sebagai nama koneksi. Pakai `Rule::unique(User::class, ...)`.
- **`diffInSeconds()` di Carbon 3 mengembalikan float**, wajib di-cast ke int sebelum masuk kolom integer.
- Tidak ada test suite. Itu keputusan admin, jangan menawarkan membuatnya.

## Yang perlu dikerjakan berikutnya

1. Tunggu laporan admin setelah dia memeriksa layar pengerjaan hasil perombakan terakhir. Perbaiki yang dia keluhkan, jangan menambah fitur baru sebelum itu beres.
2. Bantu admin memasukkan 10 mahasiswa lewat dashboard, lalu generate soal per mahasiswa per level dan pastikan queue worker jalan saat itu.
3. Siapkan deployment ke VPS: `queue:work` sebagai service (supervisor atau systemd), ganti kata sandi admin `admin@kompre.test`, dan rotasi `GEMINI_API_KEY`.

## Cara kerja yang diharapkan

Kerjakan sampai tuntas lalu laporkan apa adanya. Kalau ada yang gagal, sebutkan gagalnya. Jangan mengklaim sudah memeriksa tampilan kalau belum. Jangan menumpuk kode dalam satu file — batas wajar 200 baris, controller tipis, logic di Action class, komponen React dipecah per bagian.

Perbarui bagian **Status Pembangunan** di `CLAUDE.md` setiap menyelesaikan sesuatu. Bagian lain di dokumen itu jangan diubah.
