# Absensi Guru 

## 1. Nama Project
*Absensi* - Platform Absensi
## 2. Nama Kelompok & Anggota (Kelompok 4)
* Isnaini Saputra (701230107)
* Ridwan Saputra (701230304)
* Subhan Dani Saputra (701230108)

## 3. Deskripsi Singkat Aplikasi

Sistem Absensi Guru adalah aplikasi berbasis Website yang digunakan untuk melakukan pencatatan kehadiran guru secara digital dan real-time di lingkungan sekolah. Sistem ini memudahkan guru dalam melakukan absensi masuk melalui kamera, serta memberikan kemudahan bagi admin sekolah dalam melihat laporan absensi harian dan bulanan.

Proyek ini terdiri dari dua bagian utama:
Dashboard Guru dan Dashboard Admin Sekolah.

Guru melakukan absensi melalui halaman khusus, sementara admin mengelola data, melihat riwayat, dan membuat laporan.

Requirement Sistem

Minimal spesifikasi untuk menjalankan aplikasi:

Server / Hosting

PHP 7.4+

MySQL 5.7+

Apache/Nginx Web Server

512 MB RAM

150 MB Storage

Client (Guru & Admin)

Peramban Browser (Chrome/Firefox)

Koneksi Internet

Kamera (untuk scan QR)

Pola Arsitektur

Arsitektur : N-Tier, memisahkan sistem menjadi layer berbeda untuk meningkatkan keamanan, skalabilitas, dan maintainability.

1. Tier Client (Presentation Layer)

Digunakan oleh guru dan admin untuk interaksi UI.

Dashboard Guru (Website)

Melakukan absensi melalui kamera

Melihat riwayat absensi

Dashboard Admin Sekolah (Website)

Mengelola data guru

Melihat laporan absensi

Export data PDF
Fungsi:
Menangani antarmuka pengguna (UI), input data, dan interaksi user secara langsung.

2. Tier Server & Database (Data & Logic Layer)

Server Aplikasi (PHP Native/Laravel)

Business logic absensi

Role akses (Admin/Guru)

Export laporan

Database MySQL

Penyimpanan data absensi

Penyimpanan akun guru dan admin

Jadwal absensi

Log aktivitas

Fungsi:
Melakukan proses perhitungan data, penyimpanan informasi, keamanan informasi, dan sinkronisasi data antar pengguna.

## 4. Tujuan Sistem / Permasalahan yang Diselesaikan
Permasalahan

Proses absensi manual (kertas, tanda tangan) rentan manipulasi dan sulit direkap.

Pencatatan laporan bulanan memakan waktu panjang.

Tidak tersedia data absensi real-time dan dokumentasi digital.

Tujuan

Menyediakan sistem absensi digital untuk guru secara real-time.

Memudahkan admin sekolah dalam melihat laporan kehadiran harian dan bulanan.

Menyediakan laporan kehadiran yang cepat, transparan, dan akurat.

## 5. Teknologi yang Digunakan
Bahasa Pemrograman

PHP 7/8 (Native/Laravel)

JavaScript (Ajax/Fetch)

Database

MySQL (Relational Database)

Web Server

Apache / Nginx

Framework & Library Pendukung

Bootstrap (UI)

Instascan.js 

DomPDF (Export PDF)

Tools Pengembangan

Visual Studio Code / PHPStorm

XAMPP / Laragon

Git Version Control

phpMyAdmin

## 6. Cara Menjalankan Aplikasi
a. Instalasi (Developer Mode)

Clone repository:

https://saputra050905.github.io/Absensi/


Masuk ke folder project:
cd absensi-guru


Import database:

Buka phpMyAdmin

Buat database baru: absensi_guru

Import file absensi_guru.sql

Konfigurasi koneksi database pada file:
includes/db.php

$host = "localhost";
$dbname = "absensi_guru";
$user = "root";
$pass = "";


Pastikan server Apache & MySQL berjalan.

b. Cara Menjalankan Project

Jalankan XAMPP / Laragon.

Akses aplikasi melalui browser:

http://localhost/absensi_guru


Login menggunakan akun guru atau admin.

c. Instalasi di Hosting

Upload semua file project ke:

public_html/


Buat database MySQL di cPanel.

Import file SQL.

Sesuaikan konfigurasi koneksi database:

hostname

username

password

database

Selesai.

## 7. Akun Demo

Berikut akun demo untuk pengujian sistem:

Dashboard Guru

Nama: Guru Demo

Email: p@gmail.com

Password: 12345

Dashboard Admin

Email: admin123@gmail.com

Password: 12345

## 8. Link Deployment

(Opsional sesuai kebutuhan)

Link Website Absensi Guru:
absensi.fwh.is

## 9 Catatan Tambahan
Keterbatasan Sistem (Versi Awal)

Absensi pulang manual atau berdasarkan jam sistem.

Fitur Opsional Pengembangan

Face Recognition


Petunjuk Penting

Jika ingin clone aplikasi pastikan:

Import database

Pastikan folder vendor tersedia jika menggunakan framework

Sesuaikan konfigurasi koneksi database

## 11. Keterangan Tugas
Project ini ditujukan untuk memenuhi *Tugas Final Project Mata Kuliah Rekayasa Perangkat Lunak*.
*Dosen Pengampu:* Dila Nurlaila, M.Kom

---
Copyright © 2025 Kelompok 4 - Absensi Guru
