<?php
$tr_print = '';
if ($print) {
  $i = 0;
  foreach ($arr_print as $nama_mk => $arr_mk) {
    $i++;
    $tr_print .= "
      <tr>
        <td>$i</td>
        <td>$nama_mk</td>
        <td>$arr_mk[programs]</td>
        <td>$arr_mk[semester]</td>
        <td>$arr_mk[sks]</td>
        <td>$arr_mk[count_kelas] ($arr_mk[shifts])</td>
        <td>$arr_mk[sum_sks]</td>
      </tr>
    ";
  }
}

# ============================================================
# FINAL ECHO PRINT SURAT TUGAS
# ============================================================
set_title("$st[nama_dosen] - Print Surat Tugas");
$awal_perkuliahan = "3 Februari 2025"; // harusnya diambil dari data TA ZZZ
$Bapak = 'Bapak'; /// harusnya sesuaikan dengan Gender Dosen ZZZ
$nama_dekan = 'Haekal Pirous, S.T., M.A.B'; // ZZZ
$tanggal_surat = '20 Januari 2025'; // ZZZ

echo "
  <div class=bg_hitam>
    <h2>Print Preview Surat Tugas</h2> 
    Format Kertas A4, Margin: 2-2-2-2
    <div class=kertas_a4>
      <div class=blok_kop_surat>  
        <img src=custom/kop_surat.jpg class=img-fluid>
      </div>
      <div class='f22 bold tengah'><u>SURAT TUGAS PENGAJARAN</u></div>
      <div class='f18 tengah consolas mb4'>No. $nomor_st</div>
      

      $menugaskan_kepada

      <table class=table>
        <thead>
          <th>No</th>
          <th>MK</th>
          <th>Program</th>
          <th>Semester</th>
          <th>SKS</th>
          <th>Kelas</th>
          <th>Jumlah</th>
        </thead>
        $tr_print
        <tr class='gradasi-toska bold'>
          <td colspan=6 class=kanan>TOTAL SKS</td>
          <td>$total_sks</td>
          <td>&nbsp;</td>
        </tr>
      </table>

      <p>Perlu kami sampaikan bahwa perkuliahan <b class=darkblue>Semester $Gg</b> 
      tahun akademik $tahun_akademik 
      akan dimulai tanggal <b class=darkblue>$awal_perkuliahan</b>, 
      untuk itu kami mohon $Bapak dapat
      mempersiapkan <b class=darkblue>Rencana Pembelajaran Semester (RPS)</b> 
      dan Bahan Ajar untuk matakuliah
      tersebut di atas, dan menyampaikannya ke Fakultas Komputer Universitas Ma'soem 
      <b class=darkblue>paling lambat diminggu pertama perkuliahan</b>.</p>

      <p>Atas perhatiannya kami ucapkan terimakasih.</p>

      <div class='ttd titimangsa'>$lokasi_titimangsa, $tanggal_surat</div>
      <div class='ttd verifikator_ttd'>$nama_dekan</div>



    </div>
  </div>
";
