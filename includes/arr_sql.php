<?php
# ============================================================
# ARRAY SQL
# ============================================================
$arr_sql = [];

# ============================================================
# DATA SETS
# ============================================================
$rset = [
  'fakultas' => $rfakultas,
  'jenjang' => $rjenjang,
  'role' => $rrole,
  'id_shift' => $rshift
];
$dataset = [];
foreach ($rset as $set => $arr) {
  $dataset[$set] = '';
  foreach ($arr as $key => $value) {
    if ($key) {
      $koma = $dataset[$set] ? ',' : '';
      $dataset[$set] .= "$koma'$key'";
    }
  }
}


# ============================================================
# ta
# ============================================================
$arr_sql['ta'] = "CREATE TABLE IF NOT EXISTS tb_ta (
    id SMALLINT(5) PRIMARY KEY,
    nama SMALLINT(5) NOT NULL UNIQUE,
    awal DATE DEFAULT CURRENT_TIMESTAMP, 
    akhir DATE DEFAULT CURRENT_TIMESTAMP,
    CHECK (nama = id),
    CHECK (nama >= $min_ta_ganjil),
    CHECK (nama <= $max_ta_genap),
    CHECK (id >= $min_ta_ganjil),
    CHECK (id <= $max_ta_genap)
  );
";

# ============================================================
# prodi
# ============================================================
$arr_sql['prodi'] = "CREATE TABLE IF NOT EXISTS tb_prodi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL UNIQUE,
    singkatan VARCHAR(3) NOT NULL UNIQUE,
    fakultas SET($dataset[fakultas]) NOT NULL,
    jenjang SET($dataset[jenjang]) NOT NULL,
    jumlah_semester SET('6','8') NOT NULL
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
  id_prodi INT NOT NULL,
  kode VARCHAR(10) NOT NULL UNIQUE,     
  nama VARCHAR(100) NOT NULL,           
  sks SET('1', '2', '3', '4', '5', '6') NOT NULL,
  semester SET('1', '2', '3', '4', '5', '6', '7', '8') NOT NULL COMMENT 'rekomendasi',
  no tinyint(3) UNSIGNED DEFAULT NULL,
  deskripsi TEXT NULL,                     
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  FOREIGN KEY (id_prodi) REFERENCES tb_prodi(id) ON DELETE RESTRICT
  )
";

# ============================================================
# kumk
# ============================================================
$arr_sql['kumk'] = "CREATE TABLE IF NOT EXISTS tb_kumk 
  (
    id varchar(20) NOT NULL COMMENT 'KU-MK' PRIMARY KEY,
    id_kurikulum int(11) NOT NULL,
    id_mk int(11) NOT NULL,
    no tinyint(3) UNSIGNED DEFAULT NULL,
    assign_date timestamp NOT NULL DEFAULT current_timestamp(),
    semester set('1','2','3','4','5','6','7','8') NOT NULL COMMENT 'assignment',

    CONSTRAINT tb_kumk_KURIKULUM FOREIGN KEY (id_kurikulum) REFERENCES tb_kurikulum (id),
    CONSTRAINT tb_kumk_MK FOREIGN KEY (id_mk) REFERENCES tb_mk (id)
  )
";


# ============================================================
# dosen
# ============================================================
$arr_sql['dosen'] = "CREATE TABLE IF NOT EXISTS tb_dosen (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(30) NOT NULL,
  nidn VARCHAR(16) UNIQUE NOT NULL,
  gelar_depan VARCHAR(20) NULL DEFAULT NULL,
  gelar_belakang VARCHAR(20) NULL DEFAULT NULL,
  id_prodi INT NULL DEFAULT NULL, -- NULL = DOSEN LB
  whatsapp VARCHAR(14) UNIQUE NOT NULL,
  alamat TEXT,
  status TINYINT(1) NULL DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  FOREIGN KEY (id_prodi) REFERENCES tb_prodi(id) ON DELETE RESTRICT,

  CHECK (CHAR_LENGTH(whatsapp) BETWEEN 11 AND 14),
  CHECK (whatsapp LIKE '628%'),
  CHECK (whatsapp REGEXP '^[0-9]+$'),

  CHECK (nidn REGEXP '^[0-9]+$'),
  CHECK (CHAR_LENGTH(nidn) BETWEEN 10 AND 16),

  CHECK (nama REGEXP '^[a-zA-Z ]+$'), -- nama ada curek nya ZZZ
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
# sesi
# ============================================================
$arr_sql['sesi'] = "CREATE TABLE IF NOT EXISTS tb_sesi (
  id SMALLINT UNSIGNED PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  awal TIME NOT NULL,
  akhir TIME NOT NULL,
  shift SET($dataset[id_shift]) NOT NULL,
  is_break BOOLEAN NULL DEFAULT NULL, -- true = waktu shalat
  bookable BOOLEAN NULL DEFAULT TRUE,
  info VARCHAR(100) NULL DEFAULT NULL
  );
";

# ============================================================
# kelas
# ============================================================
$arr_sql['kelas'] = "CREATE TABLE IF NOT EXISTS tb_kelas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(50) NOT NULL UNIQUE,
  kapasitas TINYINT UNSIGNED NOT NULL DEFAULT 40,
  id_prodi INT NOT NULL,
  id_ta SMALLINT(5) NOT NULL,
  semester SET('1', '2', '3', '4', '5', '6', '7', '8') NOT NULL,
  shift SET($dataset[id_shift]) NOT NULL,
  counter SET('A', 'B', 'C', 'D', 'E') NULL DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

  CONSTRAINT KAPASITAS_KELAS CHECK (kapasitas <= 500), -- AULA > 40 
  CONSTRAINT TA_KELAS FOREIGN KEY (id_ta) REFERENCES tb_ta(id) ON DELETE RESTRICT,
  CONSTRAINT PRODI_KELAS FOREIGN KEY (id_prodi) REFERENCES tb_prodi(id) ON DELETE RESTRICT
  );
";

# ============================================================
# petugas
# ============================================================
$arr_sql['petugas'] = "CREATE TABLE IF NOT EXISTS tb_petugas (
  id int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nama varchar(30) NOT NULL,
  username varchar(20) NOT NULL,
  password varchar(200) DEFAULT NULL,
  created_at timestamp NOT NULL DEFAULT current_timestamp(),
  whatsapp varchar(14) NOT NULL,
  role SET($dataset[role]) NOT NULL,
  image varchar(100) DEFAULT NULL,
  CONSTRAINT chk_username CHECK (username REGEXP '^[a-z0-9]+$'),
  CONSTRAINT chk_whatsapp CHECK (whatsapp LIKE '628%'),
  CONSTRAINT chk_nama CHECK (nama REGEXP '^[A-Z'' ]+$')
  );
";


# ============================================================
# st
# ============================================================
$arr_sql['st'] = "CREATE TABLE IF NOT EXISTS tb_st (
    id varchar(20) NOT NULL COMMENT 'TA DS' PRIMARY KEY,
    id_dosen int(11) NOT NULL,
    id_ta SMALLINT(5) NOT NULL,
    tanggal timestamp NOT NULL DEFAULT current_timestamp(),
    id_petugas int(11) NOT NULL,
    verif_by int(11) UNSIGNED DEFAULT NULL,
    verif_date timestamp NULL DEFAULT NULL,
    CONSTRAINT VERIF_BY FOREIGN KEY (verif_by) REFERENCES tb_petugas(id),
    CONSTRAINT ST_DOSEN FOREIGN KEY (id_dosen) REFERENCES tb_dosen(id),
    CONSTRAINT ST_TA FOREIGN KEY (id_ta) REFERENCES tb_ta(id)
  );
";

# ============================================================
# st_mk
# ============================================================
// $arr_sql['st_mk'] = "CREATE TABLE IF NOT EXISTS tb_st_detail (
//     id varchar(20) NOT NULL COMMENT 'TA DS KU MK' PRIMARY KEY,
//     id_st varchar(20) NOT NULL,
//     id_kumk varchar(20) NOT NULL,
//     unik_kumk varchar(20) NOT NULL UNIQUE,

//     CONSTRAINT tb_st_mk__kumk FOREIGN KEY (id_kumk) REFERENCES tb_kumk(id),
//     CONSTRAINT tb_st_mk__st FOREIGN KEY (id_st) REFERENCES tb_st(id)

//   );
// ";

# ============================================================
# st_mk_kelas
# ============================================================
// $arr_sql['st_mk_kelas'] = "CREATE TABLE IF NOT EXISTS tb_st_mk_kelas (
//     id varchar(20) NOT NULL COMMENT 'TA DS KU MK KLS' PRIMARY KEY,
//     id_st_detail varchar(20) NOT NULL,
//     id_kelas int(11) NOT NULL,
//     unique_check VARCHAR(30) NOT NULL COMMENT 'TA-MK-Kelas' UNIQUE,
//     id_dosen INT NOT NULL,
//     CONSTRAINT PARENT_DOSEN FOREIGN KEY (id_dosen) REFERENCES tb_dosen(id),
//     CONSTRAINT PARENT_ST_MK FOREIGN KEY (id_st_detail) REFERENCES tb_st_detail(id),
//     CONSTRAINT PARENT_KELAS FOREIGN KEY (id_kelas) REFERENCES tb_kelas(id)

//   );
// ";


# ============================================================
# jadwal
# ============================================================
$arr_sql['jadwal'] = "CREATE TABLE IF NOT EXISTS tb_jadwal (
    id VARCHAR(20) PRIMARY KEY, -- as id_st_mk_kelas
    id_ruang INT NOT NULL,
    id_sesi_at_book SMALLINT UNSIGNED NOT NULL,
    weekday TINYINT UNSIGNED NOT NULL,
    jam_mulai TIME NOT NULL,
    jam_selesai TIME NOT NULL,
    assign_by INT NOT NULL,
    assign_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT JADWAL_ID FOREIGN KEY (id) REFERENCES tb_st_mk_kelas(id) ON DELETE RESTRICT,
    CONSTRAINT JADWAL_RUANG FOREIGN KEY (id_ruang) REFERENCES tb_ruang(id) ON DELETE RESTRICT,
    CONSTRAINT JADWAL_SESI FOREIGN KEY (id_sesi_at_book) REFERENCES tb_sesi(id) ON DELETE RESTRICT,
    CONSTRAINT JADWAL_JAM_MULAI CHECK (jam_mulai < jam_selesai),
    CONSTRAINT JADWAL_WEEKDAY CHECK (weekday BETWEEN 0 AND 6)
  );
";

# ============================================================
# pemakaian_ruang
# ============================================================
$arr_sql['pemakaian_ruang'] = "CREATE TABLE IF NOT EXISTS tb_pemakaian_ruang (
    id VARCHAR(30) PRIMARY KEY, -- as id_st_mk_kelas + id_sesi
    id_st_mk_kelas VARCHAR(20) NOT NULL, 
    id_ruang INT NOT NULL,
    id_sesi SMALLINT UNSIGNED NOT NULL, 
    unik_dosen VARCHAR(30) NOT NULL COMMENT 'TA-Dosen-W-S' UNIQUE,
    CONSTRAINT PEMAKAIAN_ST_MK_KELAS FOREIGN KEY (id_st_mk_kelas) REFERENCES tb_st_mk_kelas(id) ON DELETE RESTRICT,
    CONSTRAINT PEMAKAIAN_RUANG FOREIGN KEY (id_ruang) REFERENCES tb_ruang(id) ON DELETE RESTRICT
  );
";
