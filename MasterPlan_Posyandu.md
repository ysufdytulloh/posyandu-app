# Master Plan — Sistem Informasi Posyandu (Rebuild)

> Rebuild sistem lama — multi-cabang, multi-role, portal orang tua
> **Disusun oleh:** Moh. Yusuf Hidayatulloh | Kediri, 2026

---

## 01 · Ringkasan Proyek

| Key | Value |
|---|---|
| **Nama Proyek** | Sistem Informasi Posyandu (Rebuild) |
| **Tipe Sistem** | Web Application — Multi-Cabang, Multi-Role |
| **Stack Utama** | Laravel 10 + Filament 3 + MySQL |
| **Target Live** | Awal 2027 |
| **Scope** | Beberapa posyandu dalam 1 desa, data tergabung dibedakan per cabang |
| **Developer** | Moh. Yusuf Hidayatulloh |
| **Maintenance** | Diserahkan ke klien pasca-launch |

---

## 02 · Role & Hak Akses

| Role | Panel | Hak Akses |
|---|---|---|
| **Admin Desa** | Filament Panel 1 — `/admin` | Akses penuh semua modul semua cabang. Kelola user, posyandu, laporan level desa. |
| **Kader** | Filament Panel 2 — `/kader` | Input semua data transaksi. Terikat 1 cabang. Buatkan akun orang tua. |
| **Orang Tua / Ibu** | Portal Custom Blade + Livewire — `/portal` | Lihat riwayat timbang & status gizi anak sendiri. Lihat jadwal imunisasi. Download KMS. Mobile-first. |

---

## 03 · Modul Sistem Final (17 Modul)

| No | Modul | Status | Keterangan |
|---|---|---|---|
| 1 | Dashboard | 🟢 Baru | Overview statistik per cabang & level desa |
| 2 | Manajemen Posyandu/Cabang | 🟢 Baru | CRUD cabang, hanya Admin Desa |
| 3 | Data Ibu | 🔵 Dimodifikasi | Termasuk riwayat kehamilan (tabel terpisah) |
| 4 | Data Anak | ⚪ Dipertahankan | Relasi ke ibu, filter per cabang |
| 5 | Data Lansia | ⚪ Dipertahankan | Master data lansia per cabang |
| 6 | Data Kader | ⚪ Dipertahankan | Terikat ke 1 posyandu/cabang |
| 7 | Jenis Imunisasi | 🟣 Lookup Table | Master: BCG, Polio, DPT, Campak, dll |
| 8 | Jenis PMT | 🟣 Lookup Table | Master jenis PMT yang didistribusikan |
| 9 | Timbang Balita | 🔵 Dimodifikasi | BB + TB + tanggal, z-score otomatis |
| 10 | Grafik KMS | 🔵 Dimodifikasi | Kurva pertumbuhan standar Kemenkes, time series per anak |
| 11 | Imunisasi | ⚪ Dipertahankan | Pencatatan per anak per jenis + tanggal |
| 12 | Vitamin A | ⚪ Dipertahankan | Distribusi 2x setahun (Feb & Agu) per anak |
| 13 | Pemeriksaan Lansia | 🟢 Baru | 9 field + tanggal + kalkulasi IMT otomatis |
| 14 | PMT Distribusi | 🟢 Baru | Distribusi ke balita/ibu hamil/lansia (polymorphic) |
| 15 | Portal Orang Tua | 🟢 Baru | Custom Blade+Livewire, mobile-first, akun dibuat kader |
| 16 | Laporan | 🔵 Dimodifikasi | 7 jenis laporan + diagram + export PDF & Excel |
| 17 | Manajemen User | 🔵 Dimodifikasi | 3 role, kader buatkan akun orang tua |

**Modul dihapus dari sistem lama:** PKK, UKM, Buku Tamu, Agenda, Kegiatan

---

## 04 · Arsitektur Teknis

### Panel Architecture

```
┌─────────────────────┐  ┌─────────────────────┐  ┌─────────────────────┐
│   Panel Admin Desa  │  │    Panel Kader       │  │  Portal Orang Tua   │
│   Filament 3        │  │   Filament 3         │  │  Blade + Livewire   │
│   Route: /admin     │  │   Route: /kader      │  │  Route: /portal     │
├─────────────────────┤  ├─────────────────────┤  ├─────────────────────┤
│ - Akses semua       │  │ - Data difilter by   │  │ - Mobile-first UI   │
│   cabang & modul    │  │   posyandu_id        │  │ - Data filter by    │
│ - Kelola user &     │  │ - Input semua        │  │   ibu_id            │
│   posyandu          │  │   transaksi          │  │ - Read-only         │
│ - Laporan desa      │  │ - Buat akun ortu     │  │ - Download KMS      │
└─────────────────────┘  └─────────────────────┘  └─────────────────────┘
```

### Stack & Library

| Layer | Teknologi | Keterangan |
|---|---|---|
| Backend | Laravel 10 | Framework utama |
| Admin Panel | Filament 3 | 2 panel terpisah (Admin + Kader) |
| Portal Ortu | Blade + Livewire | Custom, mobile-first |
| Database | MySQL | Soft delete semua tabel transaksi |
| Export Excel | maatwebsite/excel | Laporan format .xlsx |
| Export PDF | barryvdh/laravel-dompdf | Laporan format .pdf |
| Chart / Grafik | Chart.js (Filament Widget) | Grafik KMS & dashboard |
| Auth | Filament built-in auth | Per panel terpisah |
| Z-Score Ref | Tabel MySQL (seeded) | Standar WHO/Kemenkes (~3000 rows) |

---

## 05 · Keputusan Desain Database

| Keputusan | Pilihan | Alasan |
|---|---|---|
| Tabel `users` | 1 tabel + kolom `role` & `posyandu_id`/`ibu_id` | Cukup untuk skala ini, tidak over-engineer |
| Data Ibu + Kehamilan | Tabel `ibu` (master) + tabel `kehamilan` (riwayat) | 1 ibu bisa hamil berkali-kali |
| Kalkulasi Z-Score | Simpan hasil di tabel `hasil_gizi` | Performa laporan & grafik KMS lebih cepat |
| PMT Distribusi | Polymorphic relation (`penerima_type` + `penerima_id`) | Menghindari 3 tabel terpisah yang redundan |
| Soft Delete | Semua tabel pakai `deleted_at` | Data medis tidak boleh hilang permanen |
| Laporan | Generate on-the-fly (v1) | Data 1 desa tidak sebesar itu, aman |
| Grafik KMS | Query dari `timbang_balita` (time series) | Tidak butuh tabel tambahan |

---

## 06 · Rencana Tabel Database (15 Tabel)

### Master Data

| No | Tabel | Field Utama |
|---|---|---|
| 1 | `posyandu` | id, nama, alamat, kecamatan, kelurahan, rt, rw |
| 2 | `users` | id, name, email, password, role, posyandu_id (nullable), ibu_id (nullable) |
| 3 | `ibu` | id, posyandu_id, nik, nama, tgl_lahir, alamat, no_hp, goldar |
| 4 | `kehamilan` | id, ibu_id, hpht, usia_kehamilan, tgl_perkiraan_lahir, status |
| 5 | `anak` | id, ibu_id, posyandu_id, nik, nama, jk, tgl_lahir, anak_ke |
| 6 | `lansia` | id, posyandu_id, nik, nama, jk, tgl_lahir, alamat, no_hp |

### Lookup Table

| No | Tabel | Field Utama |
|---|---|---|
| 7 | `jenis_imunisasi` | id, nama, kode, usia_rekomendasi, keterangan |
| 8 | `jenis_pmt` | id, nama, satuan, keterangan |

### Referensi

| No | Tabel | Field Utama |
|---|---|---|
| 9 | `zscore_referensi` | id, jenis (BB/U·TB/U·BB/TB), jk, usia_bulan, sd_min3..sd_plus3 |

### Transaksi

| No | Tabel | Field Utama |
|---|---|---|
| 10 | `timbang_balita` | id, anak_id, posyandu_id, tgl_periksa, berat, tinggi, kader_id |
| 11 | `hasil_gizi` | id, timbang_id, bbU_zscore, tbU_zscore, bbTb_zscore, status_bbU, status_tbU, status_bbTb |
| 12 | `imunisasi` | id, anak_id, jenis_imunisasi_id, tgl_imunisasi, kader_id, ket |
| 13 | `vitamin_a` | id, anak_id, posyandu_id, tgl_distribusi, dosis, kader_id |
| 14 | `periksa_lansia` | id, lansia_id, posyandu_id, tgl_periksa, berat, tinggi, imt, tensi_sistol, tensi_diastol, gula_darah, kolesterol, asam_urat, lingkar_perut, keluhan, kader_id |
| 15 | `pmt_distribusi` | id, jenis_pmt_id, penerima_type, penerima_id, posyandu_id, tgl_distribusi, jumlah, satuan, kader_id |

### Relasi Antar Tabel (Big Picture)

```
posyandu
  ├── users (role: kader, posyandu_id FK)
  ├── ibu
  │   ├── kehamilan (ibu_id FK)
  │   ├── anak (ibu_id FK)
  │   │   ├── timbang_balita → hasil_gizi
  │   │   ├── imunisasi
  │   │   ├── vitamin_a
  │   │   └── pmt_distribusi (polymorphic)
  │   └── pmt_distribusi (polymorphic, saat hamil)
  └── lansia
      ├── periksa_lansia
      └── pmt_distribusi (polymorphic)

users (role: orang_tua, ibu_id FK)
  └── lihat data anak milik ibu-nya sendiri

zscore_referensi
  └── dipakai ZScoreCalculator saat input timbang_balita → hasil_gizi
```

---

## 07 · Rencana Modul Laporan (7 Laporan)

Semua laporan tersedia dalam format **PDF + Excel + Diagram/Chart**

| No | Jenis Laporan | Filter | Output |
|---|---|---|---|
| 1 | Rekap Timbang Balita per Bulan | Per cabang / per bulan | Tabel + Grafik bar |
| 2 | Status Gizi Balita (Stunting / Normal / Overweight) | Per cabang / per bulan | Tabel + Grafik pie/donut |
| 3 | Rekap Imunisasi per Bulan | Per jenis / per cabang | Tabel + Grafik bar |
| 4 | Rekap Distribusi Vitamin A | Per bulan / per cabang | Tabel |
| 5 | Rekap Distribusi PMT | Per jenis / per penerima | Tabel + Grafik bar |
| 6 | Rekap Pemeriksaan Lansia per Bulan | Per cabang / per bulan | Tabel + rata-rata vital sign |
| 7 | Rekapitulasi Seluruh Posyandu (Level Desa) | Semua cabang / per bulan | Dashboard diagram gabungan |

---

## 08 · Fase Development (9 Fase)

> Setiap fase harus selesai sebelum lanjut ke fase berikutnya.

---

### 🔘 Fase 1 — Setup & Fondasi `±1 minggu`

- [ ] Setup project Laravel 10 baru
- [ ] Install & konfigurasi Filament 3 — setup 2 panel (Admin Desa + Kader)
- [ ] Setup database MySQL + konfigurasi `.env`
- [ ] Setup autentikasi per panel (Filament built-in auth)
- [ ] Install library: `maatwebsite/excel`, `barryvdh/laravel-dompdf`
- [ ] Setup layout portal orang tua (Blade + Livewire, mobile-first)
- [ ] Migration & seeder awal: tabel `zscore_referensi` (data WHO/Kemenkes)

---

### 🟢 Fase 2 — Master Data `±1 minggu`

- [ ] Migration & model: `posyandu`, `users`, `ibu`, `kehamilan`, `anak`, `lansia`
- [ ] Migration & seeder: `jenis_imunisasi`, `jenis_pmt` (lookup table)
- [ ] Filament resource: CRUD Posyandu (Admin Desa only)
- [ ] Filament resource: CRUD Data Ibu + sub-form Riwayat Kehamilan
- [ ] Filament resource: CRUD Data Anak (relasi ke ibu)
- [ ] Filament resource: CRUD Data Lansia
- [ ] Filament resource: CRUD Data Kader (terikat posyandu)
- [ ] Filament resource: CRUD Jenis Imunisasi & Jenis PMT (Admin only)
- [ ] Middleware scope: Kader hanya lihat data posyandu-nya sendiri

---

### 🔵 Fase 3 — Transaksi Balita `±1.5 minggu`

- [ ] Migration & model: `timbang_balita`, `hasil_gizi`
- [ ] Service class: `ZScoreCalculator` — hitung BB/U, TB/U, BB/TB dari tabel referensi
- [ ] Filament resource: Input Timbang Balita (BB + TB + tanggal) + auto-hitung z-score saat save
- [ ] Filament resource: Hasil Timbang — riwayat per anak + status gizi
- [ ] Filament widget: Grafik KMS (Chart.js) — time series BB per anak vs kurva referensi Kemenkes
- [ ] Filament resource: Input Imunisasi
- [ ] Filament resource: Input Vitamin A

---

### 🟠 Fase 4 — Transaksi Lansia `±1 minggu`

- [ ] Migration & model: `periksa_lansia`
- [ ] Filament resource: Input Pemeriksaan Lansia (9 field + tanggal)
- [ ] Auto-kalkulasi IMT saat save (`berat / tinggi²`)
- [ ] Riwayat pemeriksaan per lansia (tabel + tren vital sign)

---

### 🟣 Fase 5 — PMT Distribusi `±0.5 minggu`

- [ ] Migration & model: `pmt_distribusi` (polymorphic: anak/ibu/lansia)
- [ ] Filament resource: Input distribusi PMT — pilih penerima by tipe lalu pilih nama
- [ ] Riwayat distribusi PMT per penerima

---

### 🔵 Fase 6 — Portal Orang Tua `±1 minggu`

- [ ] Kader generate akun orang tua dari halaman Data Ibu (auto-link ke `ibu_id`)
- [ ] Custom Blade layout mobile-first untuk portal orang tua
- [ ] Livewire component: Dashboard portal — ringkasan data anak
- [ ] Livewire component: Riwayat timbang & status gizi anak
- [ ] Livewire component: Jadwal imunisasi berikutnya
- [ ] Livewire component: Download / cetak KMS anak (PDF via dompdf)

---

### 🟢 Fase 7 — Laporan `±1.5 minggu`

- [ ] Laporan 1: Rekap Timbang Balita — tabel + grafik bar (per cabang/bulan)
- [ ] Laporan 2: Status Gizi — tabel + grafik donut stunting/normal/overweight
- [ ] Laporan 3: Rekap Imunisasi — tabel + grafik bar per jenis
- [ ] Laporan 4: Rekap Vitamin A — tabel distribusi
- [ ] Laporan 5: Rekap PMT — tabel + grafik per jenis penerima
- [ ] Laporan 6: Pemeriksaan Lansia — tabel + rata-rata vital sign per bulan
- [ ] Laporan 7: Rekapitulasi Desa — semua cabang, diagram gabungan
- [ ] Export semua laporan ke PDF (`dompdf`) & Excel (`maatwebsite`)

---

### 🔘 Fase 8 — Migrasi Data Lama `±1 minggu`

- [ ] Terima file Excel dari klien — analisis struktur kolom & sheet
- [ ] Buat import class (Laravel Excel) per jenis data: ibu, anak, lansia
- [ ] Validasi data: format tanggal, NIK duplikat, relasi ibu-anak
- [ ] Dry run import + review hasil dengan klien
- [ ] Final import + cek integritas data

---

### 🟢 Fase 9 — Testing, Deploy & Handover `±1 minggu`

- [ ] Testing fungsional semua modul (manual testing per role)
- [ ] Testing kalkulasi z-score & IMT dengan data sampel
- [ ] Bug fixing & UI polish
- [ ] Deploy ke server hosting/VPS (online via internet)
- [ ] Setup domain & SSL
- [ ] Training klien: Admin Desa & Kader
- [ ] Serah terima project + dokumentasi singkat

---

## 09 · Estimasi Timeline

| Fase | Nama | Durasi | Output |
|---|---|---|---|
| 1 | Setup & Fondasi | ±1 minggu | Project running, 2 panel aktif, auth berjalan |
| 2 | Master Data | ±1 minggu | Semua CRUD master data siap di kedua panel |
| 3 | Transaksi Balita | ±1.5 minggu | Timbang, z-score, grafik KMS, imunisasi, vit A |
| 4 | Transaksi Lansia | ±1 minggu | Pemeriksaan lansia lengkap + kalkulasi IMT |
| 5 | PMT Distribusi | ±0.5 minggu | Distribusi PMT ke semua jenis penerima |
| 6 | Portal Orang Tua | ±1 minggu | Portal mobile-first aktif, download KMS |
| 7 | Laporan | ±1.5 minggu | 7 laporan lengkap + export PDF & Excel |
| 8 | Migrasi Data | ±1 minggu | Data lama (Excel) masuk ke sistem baru |
| 9 | Testing, Deploy & Handover | ±1 minggu | Sistem live online, klien siap pakai |
| **—** | **TOTAL ESTIMASI** | **±9.5 minggu** | **±2.5 bulan development aktif** |

---

## 10 · Setup & Keputusan Teknis Tambahan

Keputusan berikut disepakati sebelum development dimulai, sebagai pelengkap stack utama di Section 04.

### Development Environment

| Key | Value |
|---|---|
| **Local Dev** | MAMP |
| **PHP Version** | 8.1+ (requirement Laravel 10 — pastikan MAMP sudah di versi ini) |
| **Database Local** | MySQL via MAMP |
| **Version Control** | GitHub (setup repo dari Fase 1, commit per fitur) |

### Hosting & Deployment

| Key | Value |
|---|---|
| **Target Hosting** | Shared Hosting (provider belum ditentukan) |
| **Requirement** | PHP 8.1+, akses SSH (wajib untuk `php artisan`), MySQL |
| **⚠️ Catatan** | Konfirmasi ke klien soal provider — pilih yang support SSH agar proses migration & seeding z-score bisa berjalan |

### Role & Permission Management

- **Pilihan: Manual enum di tabel `users`** — tidak pakai Spatie Laravel Permission
- Kolom `role` di tabel `users` bertipe `enum('admin_desa', 'kader', 'orang_tua')`
- Filter akses dihandle via **middleware custom per panel Filament**
- Alasan: role hanya 3, sudah fixed dari briefing, Spatie terlalu overkill

### Z-Score Referensi

- **Sumber data: File resmi Kemenkes** (Tabel Antropometri WHO 2006 yang diadopsi Kemenkes)
- Data dikonversi ke CSV → di-seed ke tabel `zscore_referensi` via Laravel Seeder
- Cakupan: BB/U, TB/U, BB/TB — untuk laki-laki & perempuan, usia 0–60 bulan
- Estimasi ±3.000 baris data referensi

### Frontend & UI

| Key | Value |
|---|---|
| **Admin Panel** | Filament 3 (sudah include UI-nya) |
| **Portal Orang Tua** | Blade + Livewire + **Tailwind CSS** |
| **Alasan Tailwind** | Konsisten dengan Filament yang juga pakai Tailwind, tidak perlu konfigurasi double |

### Locale & Timezone

| Key | Value |
|---|---|
| `APP_TIMEZONE` | `Asia/Jakarta` |
| `APP_LOCALE` | `id` |
| **Format tanggal** | `d/m/Y` (tampilan UI) — `Y-m-d` (di database) |

### File Upload

- **Pakai Laravel Storage biasa** (`storage/app/public`) — tidak pakai Spatie Media Library
- Kebutuhan upload: logo posyandu, foto kader (opsional)
- Cukup simple untuk skala sistem ini

---

## 11 · Next Step Setelah Master Plan

1. ✅ ~~Master Plan selesai & disetujui~~ ← kamu di sini
2. Buat **ERD lengkap** (Entity Relationship Diagram) berdasarkan 15 tabel di atas
3. Review ERD — pastikan relasi, foreign key, dan index sudah benar
4. Buat **migration files** Laravel 10 (urutan: master → lookup → referensi → transaksi)
5. Mulai **development Fase 1** — setup project Laravel 10 di MAMP, init GitHub repo, setup 2 panel Filament
6. Development fase per fase sesuai urutan di Section 08

---

> *Dokumen ini adalah panduan kerja internal. Setiap keputusan sudah disepakati berdasarkan hasil briefing 2 putaran dengan klien. Jika ada perubahan scope dari klien, dokumen ini perlu diupdate sebelum implementasi.*
