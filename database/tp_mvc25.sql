-- Database untuk repo TP8DPBO2526C2 (aplikasi CRUD Lecturers, PHP MVC + mysqli)
-- Nama database mengikuti model/connection.php: tp_mvc25
--
-- Relasi:
--   lecturers (1) ----< courses (1) ----< schedules
--   Satu dosen mengampu banyak mata kuliah, satu mata kuliah punya banyak jadwal.

CREATE DATABASE IF NOT EXISTS tp_mvc25
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE tp_mvc25;

-- ---------------------------------------------------------------
-- Tabel dosen (kolom mengikuti query di model/Lecturer.php)
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS lecturers (
  id        INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name      VARCHAR(100) NOT NULL,
  nidn      VARCHAR(20)  NOT NULL,
  phone     VARCHAR(20)  NOT NULL,
  join_date DATE         NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_lecturers_nidn (nidn)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------
-- Tabel mata kuliah
-- lecturer_id boleh NULL dan memakai ON DELETE SET NULL, sehingga
-- tombol Delete dosen di aplikasi tetap berjalan tanpa error:
-- mata kuliahnya tetap ada, hanya tidak punya dosen pengampu.
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS courses (
  id          INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  code        VARCHAR(10)      NOT NULL,
  name        VARCHAR(100)     NOT NULL,
  credits     TINYINT UNSIGNED NOT NULL DEFAULT 2,
  lecturer_id INT UNSIGNED     NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_courses_code (code),
  KEY idx_courses_lecturer (lecturer_id),
  CONSTRAINT fk_courses_lecturer
    FOREIGN KEY (lecturer_id) REFERENCES lecturers (id)
    ON DELETE SET NULL
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------
-- Tabel jadwal kuliah
-- Jadwal ikut terhapus jika mata kuliahnya dihapus (ON DELETE CASCADE).
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS schedules (
  id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  course_id   INT UNSIGNED NOT NULL,
  day_of_week ENUM('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu') NOT NULL,
  start_time  TIME         NOT NULL,
  end_time    TIME         NOT NULL,
  room        VARCHAR(30)  NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_schedules_slot (course_id, day_of_week, start_time, end_time, room),
  KEY idx_schedules_course (course_id),
  CONSTRAINT fk_schedules_course
    FOREIGN KEY (course_id) REFERENCES courses (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT chk_schedules_time CHECK (end_time > start_time)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------
-- Data contoh (opsional, boleh dihapus). Import ulang tidak menggandakan data.
-- Relasi diisi lewat subquery NIDN / kode mata kuliah, jadi tidak
-- bergantung pada nomor id yang dihasilkan auto increment.
-- ---------------------------------------------------------------
INSERT INTO lecturers (name, nidn, phone, join_date) VALUES
  ('Dr. Budi Santoso, M.Kom.', '0412058801', '081234567890', '2015-08-17'),
  ('Siti Rahmawati, M.T.',     '0425079002', '081298765432', '2018-02-01'),
  ('Prof. Ahmad Fauzi, Ph.D.', '0403067503', '082111222333', '2010-09-01'),
  ('Dewi Lestari, M.Cs.',      '0430128504', '085712345678', '2020-01-13')
ON DUPLICATE KEY UPDATE nidn = VALUES(nidn);

INSERT INTO courses (code, name, credits, lecturer_id) VALUES
  ('IF2101', 'Pemrograman Berorientasi Objek', 3, (SELECT id FROM lecturers WHERE nidn = '0412058801')),
  ('IF2102', 'Basis Data',                     3, (SELECT id FROM lecturers WHERE nidn = '0425079002')),
  ('IF2103', 'Struktur Data',                  3, (SELECT id FROM lecturers WHERE nidn = '0403067503')),
  ('IF2104', 'Pemrograman Web',                3, (SELECT id FROM lecturers WHERE nidn = '0430128504')),
  ('IF2105', 'Rekayasa Perangkat Lunak',       2, (SELECT id FROM lecturers WHERE nidn = '0412058801'))
ON DUPLICATE KEY UPDATE code = VALUES(code);

INSERT INTO schedules (course_id, day_of_week, start_time, end_time, room) VALUES
  ((SELECT id FROM courses WHERE code = 'IF2101'), 'Senin',  '08:00:00', '10:30:00', 'Lab 1'),
  ((SELECT id FROM courses WHERE code = 'IF2102'), 'Selasa', '10:00:00', '12:30:00', 'R. 301'),
  ((SELECT id FROM courses WHERE code = 'IF2103'), 'Rabu',   '13:00:00', '15:30:00', 'R. 205'),
  ((SELECT id FROM courses WHERE code = 'IF2104'), 'Kamis',  '08:00:00', '10:30:00', 'Lab 2'),
  ((SELECT id FROM courses WHERE code = 'IF2104'), 'Jumat',  '13:00:00', '14:40:00', 'Lab 2'),
  ((SELECT id FROM courses WHERE code = 'IF2105'), 'Jumat',  '08:00:00', '09:40:00', 'R. 102')
ON DUPLICATE KEY UPDATE course_id = VALUES(course_id);
