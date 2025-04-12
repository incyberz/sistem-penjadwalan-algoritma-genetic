function rekap_respon() {
  let respon_count = 0;
  let jawabans = "";
  $(".feedback").each((value, index) => {
    let respon = index.value;
    if (respon) {
      respon_count++;
      let tid = index.id;
      let rid = tid.split("--");
      let skala = rid[0];
      let id = rid[1];
      let sub_jawaban = "";
      if (skala == "skala") {
        // pilihan skala likert
        let val2 = $("#input--" + id).val();
        if (val2) {
          sub_jawaban = "--" + val2; // tambahkan sub jawaban
        }
      }

      jawabans += "|" + tid + "--" + respon + sub_jawaban;
      document.cookie = "jawabans=" + jawabans;
      $("#jawabans").val(jawabans);
    }
  });
}

function verif_jawaban(id) {
  if (id) {
    let minlength = parseInt($("#minlength--" + id).text());
    let jawaban = $("#input--" + id).val();

    if (jawaban.length >= minlength) {
      return jawaban;
    } else {
      setTimeout(function () {
        $("#input--" + id).val(""); // anggap tidak memberi input
        $("#input--" + id).focus();
      }, 1000);
    }
  }
  return false;
}

function hitung_terisi(id) {
  if (id) {
    // console.log('hitung id:', id);

    // animate UI
    let skala = parseInt($("#skala--" + id).val()); // tmp skala likert 1 - 5
    let tipe = $("#tipe--" + id).text();

    let input = "-"; // ketikan input dari user, default true/strip
    let minlength = parseInt($("#minlength--" + id).text());

    if (tipe == "rating") {
      input = $("#skala--" + id).val();
    } else if (tipe == "pilihan") {
      let sub_pertanyaan = $("#sub_pertanyaan--" + id).text();
      if (sub_pertanyaan) {
        if (skala == 1) {
          // show feedback tambahan jika skala 1 (sangat jelek)
          $("#blok_sub_pertanyaan--" + id).slideDown();
          $("#input--" + id).prop("required", 1);
          input = verif_jawaban(id);
        } else {
          // hide feedback tambahan jika skala > 1 (cukup, dst)
          $("#blok_sub_pertanyaan--" + id).slideUp();
          $("#input--" + id).prop("required", 0);
          $("#input--" + id).val("");
          input = "-"; // kembalikan ke nilai true, default
        }
      } // end if jika ada sub_pertanyaan
    } else if (tipe == "komentar" || tipe == "input") {
      input = verif_jawaban(id);
    } else {
      alert(`Belum ada script handler untuk tipe: [${tipe}]`);
      return;
    }
    // console.log(
    //   'hitung: skala', skala,
    //   ' sub_pertanyaan:', sub_pertanyaan,
    //   ' input:', input,
    //   ' minlength:', minlength
    // );

    if (input) {
      $("#blok_feedback--" + id).removeClass("belum_terisi");
    } else {
      $("#blok_feedback--" + id).addClass("belum_terisi");
    }

    // animate progress
    let total = parseInt($("#jumlah_feedback").text());
    let belum_terisi = $(".belum_terisi").length;
    let terisi = total - belum_terisi;
    $("#feedback_terisi").text(terisi);
    let persen = Math.round((terisi * 100) / total);
    $("#progress").prop("style", `width:${persen}%`);
    $("#progress").text(`${persen}%`);
  }

  // ==================================
  // REKAP JAWABAN
  // ==================================
  rekap_respon();

  // ==================================
  // COOKIE JAWABAN
  // ==================================
  document.cookie = "jawabans=1:1:input alasan";
  let dc = document.cookie.split("; ");
  dc.forEach((v) => {
    if (v.substring(0, 9) == "jawabans=") {
      v = v.substring(9);
      // console.log('cookie: ', v);
    }
  });
}
$(function () {
  hitung_terisi(0);

  $(".radio").click(function () {
    let tid = $(this).prop("id");
    let rid = tid.split("--");
    let id = rid[1];
    let skala = parseInt($(this).val()); // skala likert 1 - 5
    $("#skala--" + id).val(skala);

    hitung_terisi(id);
  });
  $(".input").focusout(function () {
    let len = $(this).val().length;
    if (len) {
      let tid = $(this).prop("id");
      let rid = tid.split("--");
      let id = rid[1];
      hitung_terisi(id);
      // console.log('call hitung id:', id);
    }
  });
  $(".input").keyup(function () {
    $(this).val(
      $(this)
        .val()
        .replace("  ", " ") // no double spasi
        .replace("--", "-") // digunakan oleh separator
        .replace("'", "`") // tanda petik berbahaya bagi SQL
        .replace('"', "`")
        .replace(/[^A-Za-z0-9.,-/ `']/g, "")
    );
  });
});
