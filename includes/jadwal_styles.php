<style>
  * {
    margin: 0;
    padding: 0;
  }

  .hideit {
    display: none;
  }

  header nav {
    background: linear-gradient(#efe, #cfc);
    padding: 10px 0;
    border-radius: 5px;
  }

  header nav ul {
    display: flex;
    /* justify-content: space-between; */
    gap: 10px;
    /* border: solid 1px red; */
    margin: 0;
  }

  header nav ul li {
    list-style: none;
  }

  header nav ul li a {
    text-decoration: none;
    text-transform: uppercase;
    font-size: 12px;
    transition: .2s;
  }

  header nav ul li a:hover {
    font-weight: bold;
    color: darkblue;
  }

  .img_icon {
    cursor: pointer;
    transition: .2s;
  }

  .img_icon:hover {
    transform: scale(1.2)
  }


  /*--------------------------------------------------------------
  # InSho Table
  --------------------------------------------------------------*/
  table thead {
    background: linear-gradient(#eff, #cff);
  }

  table thead th {
    text-transform: uppercase;
  }

  tfoot tr th {
    vertical-align: top;
  }



  /*--------------------------------------------------------------
  # Sections General
  --------------------------------------------------------------*/
  section {
    padding: 60px 0;
    overflow: hidden;
  }

  .section-bg {
    background-color: #f7fbfe;
  }

  .section-title {
    text-align: center;
    padding-bottom: 30px;
  }

  .section-title h2 {
    font-size: 26px;
    font-weight: bold;
    text-transform: uppercase;
    position: relative;
    color: #222222;
  }

  .section-title h2::before,
  .section-title h2::after {
    content: "";
    width: 30px;
    height: 2px;
    background: #3498db;
    display: inline-block;
  }

  .section-title h2::before {
    margin: 0 15px 10px 0;
  }

  .section-title h2::after {
    margin: 0 0 10px 15px;
  }

  .section-title p {
    margin: 15px 0 0 0;
  }
</style>