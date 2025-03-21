<?php
set_title('Memilih Jurusan');
include 'daftar-step6-memilih_jurusan-styles.php';

$id_prodi_terpilih = $pmb['id_prodi'] ?? null;
$terpilih = $id_prodi_terpilih;

$sql_id_prodi = $terpilih ? "id=$terpilih" : '1';

$s = "SELECT * FROM tb_prodi a 
WHERE $sql_id_prodi 
ORDER BY nomor, fakultas";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$rprodi = [];
$styles_blok_prodi = '';
$prodis = '';
$i = 0;
$jumlah_prodi = mysqli_num_rows($q);
while ($d = mysqli_fetch_assoc($q)) {
  $i++;
  $styles_blok_prodi .= "
    .blok-prodi-$d[singkatan]{background: #$d[warna_hexa];}
  ";
  $rpemahaman = explode(',', $d['pemahaman']);
  $li_pemahaman = '';
  foreach ($rpemahaman as $v) {
    $v = trim($v);
    $li_pemahaman .= "<li>$v</li>";
  }
  $rbidang_ilmu = explode(',', $d['bidang_ilmu']);
  $li_bidang_ilmu = '';
  foreach ($rbidang_ilmu as $v) {
    $v = trim($v);
    $li_bidang_ilmu .= "<div class='bidang bidang-ilmu'>$v</div>";
  }
  $rsoftskill = explode(',', $d['softskill']);
  $li_softskill = '';
  foreach ($rsoftskill as $v) {
    $v = trim($v);
    $li_softskill .= "<div class='bidang bidang-softskill'>$v</div>";
  }

  $d['deskripsi'] = $d['deskripsi'] ?? 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Facilis in quod illo alias distinctio inventore aut dolorem laboriosam suscipit, aperiam laborum blanditiis quisquam eum animi reiciendis recusandae, perferendis officia eos!';

  $btn_prev = $i == 1 ? "<span class='btn btn-secondary w-100' disabled>Prev</span>" :
    "<span class='btn btn-info w-100 btn_nav' id=prev__$i>Prev</span>";
  $btn_next = $i == $jumlah_prodi ? "<span class='btn btn-secondary w-100' disabled>Next</span>" :
    "<span class='btn btn-info w-100 btn_nav' id=next__$i>Next</span>";

  $nav_prodi = $terpilih ? "
    <form method=post>
      <button class='btn btn-secondary mb2 w-100' onclick='return confirm(`Pilih Ulang Jurusan?`)' name=btn_pilih_ulang_jurusan value=$d[id]>Pilih Ulang</button>
    </form>
  " : "
    <div class='mb-3 d-flex gap-2'>
      <div>
        $btn_prev
      </div>
      <div class=flex-fill>
        <form method=post>
          <button class='btn btn-primary w-100' onclick='return confirm(`Yakin dengan Jurusan ini?`)' name=btn_pilih_jurusan value=$d[id]>Pilih Jurusan ini</button>
        </form>
      </div>
      <div>
        $btn_next
      </div>
    </div>
  ";

  $prodi_of = $terpilih ? "
    <div class='nama-fakultas'>Prodi Pilihan Anda:</div>
    <div class='f20'>$d[nama] ($d[jenjang])</div>
    <div class='mt2 f12 mb2'>Silahkan Next Steps atau boleh Pilih Ulang</div>
  " : "
    <div class='nama-fakultas mb-1'>Prodi $i of $jumlah_prodi - $d[fakultas] - $d[jenjang]</div>
    $d[nama]
  ";


  $prodis .= "
    <div class='hideit blok-prodi blok-prodi-$d[singkatan]' id=blok-prodi-$i>
      <h3 class='mt-4 text-center nama-prodi'>
        $prodi_of
        
      </h3>

      <p class='text-center deskripsi-prodi'>
        $d[deskripsi]
      </p>

      <div class='mt-4 d-flex justify-content-center gap-2 flex-wrap'>$li_bidang_ilmu</div>
      <div class='mt-4 d-flex justify-content-center gap-2 flex-wrap'>$li_softskill</div>

      <div class=navigasi>
        $nav_prodi

        <div class='text-center more-info btn_aksi' id=more_info$d[singkatan]__toggle>More info...</div>
      </div>
      <div id=more_info$d[singkatan] class='hideit'>
        <div class=card>
          <div class='card-body'>
            <ul>$li_pemahaman</ul>
          </div>
        </div>
      </div>
    </div>
  ";
}

echo "
  <style>$styles_blok_prodi</style>
  $prodis
";

?>
<script>
  $(function() {
    $('.btn_aksi').click(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let id = rid[1];
      console.log(aksi, id);
      if (id == 'toggle') {
        $('#' + aksi).slideToggle();
      } else {
        alert(`Belum ada handler untuk btn_aksi event [${id}]`);
      }
    });

    $('#blok-prodi-1').fadeIn();

    $('.btn_nav').click(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('__');
      let aksi = rid[0];
      let id = parseInt(rid[1]);
      console.log(aksi, id);
      $('.blok-prodi').slideUp();
      if (aksi == 'next') {
        id++;
      } else if (aksi == 'prev') {
        id--;
      }
      $('#blok-prodi-' + id).slideDown();
    })
  })
</script>