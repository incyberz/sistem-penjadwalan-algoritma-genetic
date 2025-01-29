<style>
  * {
    /* margin: 0;
    padding: 0; */
    font-family: 'Century Gothic', 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
  }

  body {
    background: linear-gradient(#eee, #ccf) !important;
    min-height: 100vh;
  }

  .screen_login {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    display: flex;
    justify-content: center;
    border: solid 1px red;
    height: 100%;
    text-align: center;
  }

  .form_login {
    margin: auto;
    max-width: 400px;
    border: solid 1px #ccc;
    border-radius: 15px;
    padding: 30px 15px;
    background: linear-gradient(#fff, #efe);
  }

  .logo {
    width: 150px;
  }

  .login-input input {
    /* background: #eef */
    text-align: center;
  }

  .nama_universitas,
  .judul_sim {
    /* font-weight: bold; */
    letter-spacing: 1px;
    font-size: 20px;
    color: blue
  }

  .span_login {
    display: block;
    letter-spacing: 2px;
    font-size: 30px;
    margin: 15px 0;
  }

  .deskripsi {
    font-size: 12px;
    color: gray;
    font-style: italic;
    margin: 12px 0 40px 0
  }

  .wa_preview {
    font-size: 12px;
    color: #888;
    font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
  }

  .mdl-card {
    width: 550px;
    min-height: 0;
    margin: 10px auto;
  }

  .mdl-card__supporting-text {
    width: 100%;
    padding: 0;
  }

  .mdl-stepper-horizontal-alternative .mdl-stepper-step {
    width: 25%;
    /* 100 / no_of_steps */
  }


  /* Begin actual mdl-stepper css styles */

  .mdl-stepper-horizontal-alternative {
    display: table;
    width: 100%;
    margin: 0 auto;
  }

  .mdl-stepper-horizontal-alternative .mdl-stepper-step {
    display: table-cell;
    position: relative;
    padding: 24px;
  }

  .mdl-stepper-horizontal-alternative .mdl-stepper-step:hover,
  .mdl-stepper-horizontal-alternative .mdl-stepper-step:active {
    background-color: rgba(0, 0, 0, .06);
  }

  .mdl-stepper-horizontal-alternative .mdl-stepper-step:active {
    border-radius: 15% / 75%;
  }

  .mdl-stepper-horizontal-alternative .mdl-stepper-step:first-child:active {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
  }

  .mdl-stepper-horizontal-alternative .mdl-stepper-step:last-child:active {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
  }

  .mdl-stepper-horizontal-alternative .mdl-stepper-step:hover .mdl-stepper-circle {
    background-color: #757575;
  }

  .mdl-stepper-horizontal-alternative .mdl-stepper-step:first-child .mdl-stepper-bar-left,
  .mdl-stepper-horizontal-alternative .mdl-stepper-step:last-child .mdl-stepper-bar-right {
    display: none;
  }

  .mdl-stepper-horizontal-alternative .mdl-stepper-step .mdl-stepper-circle {
    width: 24px;
    height: 24px;
    margin: 0 auto;
    background-color: #9E9E9E;
    border-radius: 50%;
    text-align: center;
    line-height: 2em;
    font-size: 12px;
    color: white;
  }

  .mdl-stepper-horizontal-alternative .mdl-stepper-step.active-step .mdl-stepper-circle {
    background-color: rgb(33, 150, 243);
  }

  .mdl-stepper-horizontal-alternative .mdl-stepper-step.step-done .mdl-stepper-circle:before {
    content: "\2714";
  }

  .mdl-stepper-horizontal-alternative .mdl-stepper-step.step-done .mdl-stepper-circle *,
  .mdl-stepper-horizontal-alternative .mdl-stepper-step.editable-step .mdl-stepper-circle * {
    display: none;
  }

  .mdl-stepper-horizontal-alternative .mdl-stepper-step.editable-step .mdl-stepper-circle {
    -moz-transform: scaleX(-1);
    /* Gecko */
    -o-transform: scaleX(-1);
    /* Opera */
    -webkit-transform: scaleX(-1);
    /* Webkit */
    transform: scaleX(-1);
    /* Standard */
  }

  .mdl-stepper-horizontal-alternative .mdl-stepper-step.editable-step .mdl-stepper-circle:before {
    content: "\270E";
  }

  .mdl-stepper-horizontal-alternative .mdl-stepper-step .mdl-stepper-title {
    margin-top: 16px;
    font-size: 14px;
    font-weight: normal;
  }

  .mdl-stepper-horizontal-alternative .mdl-stepper-step .mdl-stepper-title,
  .mdl-stepper-horizontal-alternative .mdl-stepper-step .mdl-stepper-optional {
    text-align: center;
    color: rgba(0, 0, 0, .26);
  }

  .mdl-stepper-horizontal-alternative .mdl-stepper-step.active-step .mdl-stepper-title {
    font-weight: 500;
    color: rgba(0, 0, 0, .87);
  }

  .mdl-stepper-horizontal-alternative .mdl-stepper-step.active-step.step-done .mdl-stepper-title,
  .mdl-stepper-horizontal-alternative .mdl-stepper-step.active-step.editable-step .mdl-stepper-title {
    font-weight: 300;
  }

  .mdl-stepper-horizontal-alternative .mdl-stepper-step .mdl-stepper-optional {
    font-size: 12px;
  }

  .mdl-stepper-horizontal-alternative .mdl-stepper-step.active-step .mdl-stepper-optional {
    color: rgba(0, 0, 0, .54);
  }

  .mdl-stepper-horizontal-alternative .mdl-stepper-step .mdl-stepper-bar-left,
  .mdl-stepper-horizontal-alternative .mdl-stepper-step .mdl-stepper-bar-right {
    position: absolute;
    top: 36px;
    height: 1px;
    border-top: 1px solid #BDBDBD;
  }

  .mdl-stepper-horizontal-alternative .mdl-stepper-step .mdl-stepper-bar-right {
    right: 0;
    left: 50%;
    margin-left: 20px;
  }

  .mdl-stepper-horizontal-alternative .mdl-stepper-step .mdl-stepper-bar-left {
    left: 0;
    right: 50%;
    margin-right: 20px;
  }
</style>