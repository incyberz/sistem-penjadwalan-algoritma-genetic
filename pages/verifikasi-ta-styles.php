<style>
  .item_tgl {
    height: 80px;
    width: 80px;
    text-align: center;
    border: solid 1px #eee;
    border-radius: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: .2s;
    cursor: pointer;
  }

  .item_tgl_header {
    width: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .item_tgl:hover,
  .item_tgl_active {
    border: solid 4px blue;
    letter-spacing: .5px;
    color: blue;
    font-weight: bold;
  }

  .me_no {
    color: darkblue;
    font-size: 30px;
    width: 50px;
    text-align: right;
  }

  .me_no_header {
    width: 50px;
  }

  .bln {
    font-size: 9px;
  }

  .info {
    border: solid 1px #ddd;
    border-radius: 5px;
    width: 200px;
    height: 100%;
    padding: 10px;
    font-size: 10px;
  }

  .info_header {
    width: 200px
  }

  .pekan_ujian,
  .minggu_tenang {
    border: solid 2px darkred;
    border-radius: 10px;
    padding: 15px 15px 15px 0;
  }

  .minggu_tenang {
    border: solid 2px #ccc;
  }
</style>