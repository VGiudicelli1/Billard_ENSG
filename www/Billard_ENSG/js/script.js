const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);

function shake_DOM_elmt(target) {
  if (target.nbShake == null) target.nbShake = 0;
  target.nbShake += 1;
  target.classList.remove("shake");
  setTimeout(() => {
    target.classList.add("shake");
  }, Math.round(Math.random()*150));
  setTimeout(() => {
    target.nbShake -= 1;
    if (target.nbShake == 0) target.classList.remove("shake");
  }, 1000);
  target.focus();
}

function fetch_api(url, data = {}) {
  return fetch("/Billard_ENSG/API/" + url, {
    method: "post",
    body: JSON.stringify(data)
  }).then(r => {
    return r.json();
  }).then(r => {
    if (r.result == "done") return r;
    else throw r.reason;
  });
}
