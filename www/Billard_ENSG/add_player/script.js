let form = document.querySelector("form");

function submit() {
  let data = {
    name: form.name.value,
    class_id: form.classe.value
  };

  fetch_api("add_player.php", data).then(r => {
    document.location.href = "..";
  }).catch(err => {
    if (err & ERROR_WRONG_VALUE) {
      if (err & VALUE_NAME) {
        shake_DOM_elmt(form.name);
      }
      if (err & VALUE_CLASS) {
        shake_DOM_elmt(form.classe);
      }
    } else {
      throw err;
    }
  });
}
