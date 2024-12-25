<?php
# ============================================================
# ARRAY SQL
# ============================================================
$arr_sql = [];

# ============================================================
# ta
# ============================================================
$arr_sql['ta'] = "CREATE TABLE IF NOT EXISTS tb_ta (
    id SMALLINT(5) PRIMARY KEY,
    nama SMALLINT(5) NOT NULL UNIQUE,
    awal DATE DEFAULT CURRENT_TIMESTAMP, 
    akhir DATE DEFAULT CURRENT_TIMESTAMP,
    CHECK (nama = id),
    CHECK (nama >= $awal_ta),
    CHECK (nama <= $akhir_ta),
    CHECK (id >= $awal_ta),
    CHECK (id <= $akhir_ta)
  );
";

# ============================================================
# prodi
# ============================================================
$arr_sql['prodi'] = "CREATE TABLE IF NOT EXISTS tb_prodi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL UNIQUE,
    singkatan VARCHAR(3) NOT NULL UNIQUE,
    jenjang SET('D3', 'S1', 'S2', 'S3') NOT NULL,
    jumlah_semester SET('2','4','6','8') NOT NULL
  );
";

# ============================================================
# kurikulum
# ============================================================
$arr_sql['kurikulum'] = "CREATE TABLE IF NOT EXISTS tb_kurikulum (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_ta SMALLINT(5) NOT NULL,
    id_prodi INT NOT NULL,
    nama VARCHAR(100) NOT NULL UNIQUE,

    FOREIGN KEY (id_ta) REFERENCES tb_ta(id) ON DELETE RESTRICT,
    FOREIGN KEY (id_prodi) REFERENCES tb_prodi(id) ON DELETE RESTRICT

  );
";

# ============================================================
# mk
# ============================================================
$arr_sql['mk'] = "CREATE TABLE IF NOT EXISTS tb_mk (
  id INT AUTO_INCREMENT PRIMARY KEY,       
  id_kurikulum INT NOT NULL,
  kode VARCHAR(10) NOT NULL UNIQUE,     
  nama VARCHAR(100) NOT NULL,           
  sks TINYINT NOT NULL CHECK (sks > 0),    
  semester TINYINT NOT NULL CHECK (semester > 0), 
  deskripsi TEXT NULL,                     
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  FOREIGN KEY (id_kurikulum) REFERENCES tb_kurikulum(id) ON DELETE RESTRICT
  )
";


# ============================================================
# dosen
# ============================================================
$arr_sql['dosen'] = "CREATE TABLE IF NOT EXISTS tb_dosen (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(30) NOT NULL,
  nidn VARCHAR(16) UNIQUE NOT NULL,
  whatsapp VARCHAR(14) UNIQUE NOT NULL,
  alamat TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  CHECK (CHAR_LENGTH(whatsapp) BETWEEN 11 AND 14),
  CHECK (whatsapp LIKE '628%'),
  CHECK (whatsapp REGEXP '^[0-9]+$'),

  CHECK (nidn REGEXP '^[0-9]+$'),
  CHECK (CHAR_LENGTH(nidn) BETWEEN 10 AND 16),

  CHECK (nama REGEXP '^[a-zA-Z ]+$'),
  CHECK (CHAR_LENGTH(nama) BETWEEN 3 AND 30)
  )
";


# ============================================================
# ruang
# ============================================================
$arr_sql['ruang'] = "CREATE TABLE IF NOT EXISTS tb_ruang (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  kapasitas INT NOT NULL,
  lokasi VARCHAR(255) DEFAULT NULL,
  jenis SET('kelas', 'lab', 'aula', 'studio') NOT NULL
  );
";

# ============================================================
# kelas
# ============================================================
$arr_sql['kelas'] = "CREATE TABLE IF NOT EXISTS tb_kelas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(50) NOT NULL UNIQUE,
  kapasitas TINYINT UNSIGNED NOT NULL CHECK (kapasitas <= 50),
  id_ta SMALLINT(5) NOT NULL ,
  id_prodi INT NOT NULL,
  FOREIGN KEY (id_prodi) REFERENCES tb_prodi(id) ON DELETE RESTRICT,
  FOREIGN KEY (id_ta) REFERENCES tb_ta(id) ON DELETE RESTRICT
  );
";

# ============================================================
# jadwal
# ============================================================
$arr_sql['jadwal'] = "CREATE TABLE IF NOT EXISTS tb_jadwal (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_kelas INT NOT NULL,
    id_mk INT NOT NULL,
    id_dosen INT NOT NULL,
    id_ruang INT NOT NULL,
    kode_hari TINYINT UNSIGNED NOT NULL CHECK (kode_hari BETWEEN 0 AND 6),
    jam_mulai TIME NOT NULL,
    jam_selesai TIME NOT NULL,
    FOREIGN KEY (id_kelas) REFERENCES tb_kelas(id) ON DELETE RESTRICT,
    FOREIGN KEY (id_mk) REFERENCES tb_mk(id) ON DELETE RESTRICT,
    FOREIGN KEY (id_dosen) REFERENCES tb_dosen(id) ON DELETE RESTRICT,
    FOREIGN KEY (id_ruang) REFERENCES tb_ruang(id) ON DELETE RESTRICT,
    CHECK (jam_mulai < jam_selesai)
  );
";
