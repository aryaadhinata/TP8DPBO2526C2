```
Saya Mohammad Arya Dhinata dengan NIM 2504992 mengerjakan Tugas Praktikum 8 dalam mata kuliah Desain
Pemrograman Berbasis Objek untuk keberkahan-Nya maka saya tidak melakukan kecurangan seperti
yang di spesifikasikan. Aamiin
```

# Aplikasi Manajemen Akademik

Aplikasi web sederhana untuk mengelola data dosen, mata kuliah, dan jadwal kuliah. Program dibuat menggunakan PHP dengan pola arsitektur MVC, MySQL sebagai basis data, serta Bootstrap untuk tampilan.

## Rekaman Layar

Dokumentasi penggunaan aplikasi tersedia dalam bentuk rekaman layar:

[▶ Tonton rekaman layar TP8DPBO2526C2]
<video src="Documentation/TP8DPBO2526C2.mp4" controls="controls" style="max-width: 100%;">
</video>

## Fitur

- Mengelola data dosen: tambah, lihat, ubah, dan hapus.
- Mengelola mata kuliah serta dosen pengampunya.
- Mengelola jadwal kuliah berdasarkan mata kuliah, hari, waktu, dan ruangan.
- Menampilkan jumlah mata kuliah per dosen dan jumlah jadwal per mata kuliah.
- Memeriksa bentrok waktu penggunaan ruangan dan jadwal dosen.
- Menampilkan informasi relasi saat data dosen atau mata kuliah akan dihapus.

## Desain Program

Program menggunakan pola **Model-View-Controller (MVC)**:

```text
Pengguna
   |
   v
Controller (project/controller/)
   | validasi input dan alur permintaan
   v
Model (project/model/)
   | query melalui mysqli
   v
MySQL (tp_mvc26)
   |
   v
View (project/view/)
   | HTML dan Bootstrap
   v
Pengguna
```

- **Controller** menerima permintaan, memvalidasi input, memanggil model, lalu memilih tampilan atau melakukan redirect.
- **Model** menjalankan operasi basis data untuk dosen, mata kuliah, dan jadwal.
- **View** menampilkan formulir, navigasi, tabel data, pesan kesalahan, dan notifikasi.

### Relasi Basis Data

```text
lecturers (1) ──────< courses (0..N) ──────< schedules (0..N)
                      lecturer_id              course_id
```

- Satu dosen dapat mengampu beberapa mata kuliah.
- Mata kuliah boleh belum memiliki dosen (`lecturer_id` bernilai `NULL`).
- Satu mata kuliah dapat memiliki beberapa jadwal.
- Menghapus dosen akan membuat mata kuliah terkait tetap ada, tetapi tanpa dosen pengampu (`ON DELETE SET NULL`).
- Menghapus mata kuliah akan menghapus jadwalnya (`ON DELETE CASCADE`).

## Alur Penggunaan

1. Pengguna membuka halaman utama melalui `controller/index.php`.
2. Pengguna memilih menu **Lecturers**, **Courses**, atau **Schedules**.
3. Controller membaca data melalui model dan mengirimkannya ke view untuk ditampilkan.
4. Untuk menambah atau mengubah data, controller memvalidasi isian formulir sebelum menyimpan perubahan.
5. Untuk menghapus data, aplikasi meminta konfirmasi lalu controller menjalankan penghapusan.
6. Setelah operasi berhasil, pengguna diarahkan kembali ke daftar data dengan notifikasi.

### Pemeriksaan Jadwal

Jadwal hanya dapat disimpan jika waktu selesai lebih akhir daripada waktu mulai. Aplikasi juga menolak jadwal yang beririsan pada hari yang sama apabila:

- ruangan yang sama sedang digunakan; atau
- mata kuliah yang sama atau dosen pengampunya sudah memiliki jadwal lain.

Perubahan dosen pengampu juga diperiksa agar jadwal mata kuliah tersebut tidak bertabrakan dengan jadwal lain dosen yang dipilih.

## Struktur Direktori

```text
.
├── Documentation/
│   └── TP8DPBO2526C2.mp4
├── project/
│   ├── controller/
│   │   ├── create.php
│   │   ├── delete.php
│   │   ├── index.php
│   │   ├── read.php
│   │   └── update.php
│   ├── model/
│   │   ├── connection.php
│   │   ├── Courses.php
│   │   ├── Lecturer.php
│   │   └── Schedules.php
│   └── view/
│       ├── assets/
│       │   ├── css/
│       │   │   └── bootstrap.min.css
│       │   └── js/
│       │       ├── bootstrap.bundle.min.js
│       │       ├── bootstrap.min.js
│       │       ├── jquery.min.js
│       │       └── popper.min.js
│       ├── course_form.php
│       ├── courses.php
│       ├── index.php
│       ├── lecturer_form.php
│       ├── lecturers.php
│       ├── navbar.php
│       ├── schedule_form.php
│       └── schedules.php
├── ReadME.md
└── tp_mvc26.sql
```
