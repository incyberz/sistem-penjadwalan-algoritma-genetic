<style>
  .blok_jadwal,
  .nama_hari {
    border: solid 2px #ccf;
  }

  .blok_jadwal thead {
    border-bottom: solid 2px #ccf;

  }

  .nama_hari {
    background: yellow;
    margin: 30px 0 15px 0;
  }

  .label_mk_dosen {
    display: block;
    border-top: solid 1px #ccc;
    padding: 10px;
    cursor: pointer;
    transition: .2s;
  }

  .label_mk_dosen:hover {
    background: #ffa;
    font-weight: bold;
  }

  .blok_radio_ruang {
    border-top: solid 5px #cfc;
    margin-top: 15px;
    padding-top: 15px;
  }

  .label_ruang {
    display: inline-block;
    padding: 3px 8px;
    min-width: 100px;
    color: green;
    font-weight: bold;
  }

  .ruang_terpakai {
    color: gray;
    font-weight: normal;
    font-style: italic;
  }

  .nav_hover {
    cursor: pointer;
    transition: .2s;
  }

  .nav_hover:hover {
    color: blue;
    /* font-weight: bold; */
    letter-spacing: .5px;
    text-decoration: underline;
  }

  .nav_jadwal {
    cursor: pointer;
    border: solid 1px #ccc;
    padding: 5px 12px;
    border-radius: 5px;
  }

  .nav_aktif,
  .nav_kelas_active {
    background: linear-gradient(#dff, #bfb);
  }

  .nav_kelas:hover {
    background: linear-gradient(#cfc, #afa);
  }

  .kumk_count {
    display: inline-block;
    height: 20px;
    width: 20px;
    text-align: center;
    background: red;
    color: white;
    border-radius: 50%;
    font-size: 12px;
    font-weight: bold;
    vertical-align: bottom;
    /* padding-top: 5px; */
  }

  .tr_mine {
    border: solid 2px blue;
    font-weight: bold;
    background: greenyellow;
  }

  .separator_top {
    border-top: solid 8px #fcf !important;
  }
</style>