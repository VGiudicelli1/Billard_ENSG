const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);

const FLAG_DEBUG = 1;


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

function fetch_api(url, data = {}, flags=0) {
  return fetch("/Billard_ENSG/API/" + url, {
    method: "post",
    body: JSON.stringify(data)
  }).then(r => {
    if (flags&FLAG_DEBUG) {

      url_ = new URL("http://a/Billard_ENSG/API/"+url);
      for (var key in data) {
        url_.searchParams.set(key, data[key]);
      }

      r.clone().text().then(r => {
        console.log({
          url:url_.href.substr(8),
          data: data,
          response: r
        });
      });
    }
    return r.json();
  }).then(r => {
    if (r.result == "done") return r;
    else throw r.reason;
  });
}
