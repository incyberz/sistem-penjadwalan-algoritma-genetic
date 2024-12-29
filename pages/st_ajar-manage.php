<?php
set_h2('Manage Surat Tugas', $dkur['nama']);

$id_st = $_GET['id_st'] ?? udef('id_st');
$s = "SELECT a.* ,
d.nama as nama_mk,
d.semester,
d.sks,
c.nidn, 
c.nama as nama_dosen 
FROM tb_st a 
JOIN tb_st_mk b ON a.id=b.id_st 
JOIN tb_dosen c ON a.id_dosen=c.id 
JOIN tb_mk d ON b.id_mk=d.id
WHERE a.id = '$id_st'";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$divs = '';
$nama_dosen = '';
$nidn = '';
$i = 0;
while ($d = mysqli_fetch_assoc($q)) {
  $i++;
  $nama_dosen = $d['nama_dosen'];
  $nidn = $d['nidn'];
  $divs .= "
  <div class='border-top p1 f14'>
    <div class=row>
      <div class='col-md-4'>$i. $d[nama_mk]</div>
      <div class='col-md-2'>SM$d[semester]</div>
      <div class='col-md-2'>$d[sks] sks</div>
      <div class='col-md-4'>
        <div class='f12 bold'>Untuk kelas:</div>
        <div class='mt1 mb2'>
          <div>
            <label>
              <input type=checkbox id=kelas nama=kelas /> KELAS ZZZ
            </label>
          </div>
        </div>
      </div>
    </div>
  </div>
  ";
}
echo "
  <p>
    Yang bertanda tangan di bawah ini Dekan Fakultas Komputer, menugaskan kepada:
  </p>
  <ul id=dosen_selected>
    <li><b>Nama:</b> <span id=nama_dosen_selected>$nama_dosen</span></li>
    <li><b>NIDN:</b> <span id=nidn_dosen_selected>$nidn</span></li>
  </ul>

  <p>Untuk mengampu matakuliah sebagai berikut:</p>
  $divs
";
