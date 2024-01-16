let form = document.querySelector("form");

function submit() {
  let data = {
    j1: form.j1.value,
    j2: form.j2.value == "NULL" ? null : form.j2.value,
    j3: form.j3.value,
    j4: form.j4.value == "NULL" ? null : form.j4.value,
  };
  /***********************   VERIFY COHERENCE PLAYERS   ***********************/
  if (data.j1 == "NULL") {
    shake_DOM_elmt(form.j1);
    return;
  }
  if (data.j3 == "NULL") {
    shake_DOM_elmt(form.j3);
    return;
  }
  if (data.j1 == data.j2) {
    shake_DOM_elmt(form.j2);
    return;
  }
  if (data.j1 == data.j3 || data.j2 == data.j3) {
    shake_DOM_elmt(form.j3);
    return;
  }
  if (data.j4 != null && (data.j1 == data.j4 || data.j2 == data.j4 || data.j3 == data.j4)) {
    shake_DOM_elmt(form.j4);
    return;
  }

  console.log(data);

  /***********************          SEND TO API         ***********************/

  fetch_api("add_game.php", data, FLAG_DEBUG).then(r => {
    document.location.href = "..";
  }).catch(err => {
    if (err & ERROR_WRONG_VALUE) {
      console.log("err wrong value");
    } else {
      throw err;
    }
  });
}
