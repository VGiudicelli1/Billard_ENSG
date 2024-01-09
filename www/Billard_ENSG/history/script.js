function update() {
  fetch_api("get_history.php", {
    player: document.querySelector("input").value
  }).then(r => {
    console.log(r);
    let table = document.querySelector("table");
    table.querySelectorAll("tr:not(.title_table)").forEach((tr, i) => {
      //tr.remove();
    });

    r.history.forEach((line, i) => {
      let tr = document.createElement("tr");
      let td_name = document.createElement("td");
      td_name.innerText = line.name;
      tr.appendChild(td_name);

      let td_date = document.createElement("td");
      td_date.innerText = line.date;
      tr.appendChild(td_date);

      let td_lElo = document.createElement("td");
      td_lElo.innerText = Math.round(line.last_elo*100)/100;
      tr.appendChild(td_lElo);

      let td_dElo = document.createElement("td");
      td_dElo.innerText = Math.round(line.delta_elo*100)/100;
      tr.appendChild(td_dElo);

      let td_nElo = document.createElement("td");
      td_nElo.innerText = Math.round(line.new_elo*100)/100;
      tr.appendChild(td_nElo);

      table.appendChild(tr);
    });


  })
}

update();
