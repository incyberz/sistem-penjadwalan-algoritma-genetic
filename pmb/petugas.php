<?php
petugas_only();

include '../includes/eta.php';
include '../includes/img_icon.php';
include 'tahun_pmb.php';
include 'gelombang_aktif.php';
include 'info_hari_ini.php';
// include 'session_manager.php';


$today_show = date('d-M-Y');
$img_home = img_icon('home', 30);
$img_manage = img_icon('manage', 30);
$img_verified = img_icon('verified', 30);
$img_logout = img_icon('logout', 30);
$img_help = img_icon('help', 30);

$rnav = [
  'left-side' => [
    'home' => [
      'title' => 'Back to SIAKAD',
      'confirm' => 'Kembali ke Home SIAKAD?',
      'href' => '../',
    ],
    'help' => [
      'title' => 'Panduan Petugas',
      'href' => '?panduan_petugas',
    ],
    'manage' => [
      'title' => 'Opsi PMB',
      'href' => '?opsi_pmb',
    ],
  ],
  'right-side' => [
    'user-info' => null,
    'logout' => [
      'title' => null,
      'confirm' => 'Logout?',
      'href' => '?logout_pmb',
    ],
  ],
];

$navs = '';
foreach ($rnav as $posisi => $arr) {
  $sub_navs = '';
  foreach ($arr as $icon => $v) {
    if ($icon == 'user-info') {
      $sub_navs .= "
        <div class='sub-nav'>
          <a href='?petugas&username=$username' class='hover'>
            $username
          </a>
        </div> 
      ";
    } else {
      $img_icon = img_icon($icon, 30);
      $is_confirm = $v['confirm'] ?? null;
      $confirm = !$is_confirm ? '' : "onclick='return confirm(`$v[confirm]`)'";
      $sub_navs .= "
        <div class='sub-nav'>
          <a href='$v[href]' $confirm class='hover nav-petugas' id='nav-petugas--$icon'>
            <div class=''>$img_icon</div>
            <div id='nav-title--$icon' class='hideit nav-title'>$v[title]</div>
          </a>
        </div> 
      ";
    }
  }
  $navs .= "
    <div class='d-flex gap-2'>
      $sub_navs
    </div> 
  ";
}

echo "
  <h2 class='mb-4 tengah'>Dashboard Petugas PMB</h2>
  <div class='d-flex flex-between mb-3'>
    $navs
  </div>
  $info_hari_ini
";
?>

<div class='d-lg-none'>
  <b class=red>Dashboard PMB hanya dapat diakses via laptop, minimal 992 pixel lebar layar.</b>
</div>

<div class='d-none d-lg-block'>
  <?php
  include 'petugas-dashboard.php';
  ?>
</div>

<style>
  .sub-nav {
    position: relative;
  }

  .nav-title {
    position: absolute;
    top: -30px;
    width: 200px;
  }
</style>

<script>
  $(function() {
    $('.nav-petugas').mouseenter(function() {
      let tid = $(this).prop('id');
      let rid = tid.split('--');
      let aksi = rid[0];
      let id = rid[1];
      console.log(aksi, id);
      $('.nav-title').hide();
      $('#nav-title--' + id).slideDown();
    });
    $('.nav-petugas').mouseleave(function() {
      $('.nav-title').slideUp();
    });
  })
</script>