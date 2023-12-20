function createRow(data) {
  let row = document.createElement("tr");

  let player = document.createElement("td");
  player.innerText = data.player;
  row.appendChild(player);

  let classe = document.createElement("td");
  classe.innerText = data.class;
  row.appendChild(classe);

  let parties = document.createElement("td");
  parties.innerText = data.games;
  row.appendChild(parties);

  let victoires = document.createElement("td");
  victoires.innerText = data.W;
  row.appendChild(victoires);

  let defaites = document.createElement("td");
  defaites.innerText = data.games - data.W;
  row.appendChild(defaites);

  let ratio = document.createElement("td");
  ratio.innerText = Math.round(10000*data.W/data.games)/100 + " %";
  row.appendChild(ratio);

  let dElo = document.createElement("td");
  dElo.innerText = Math.round(10*data.delta_elo)/10;
  row.appendChild(dElo);

  let elo = document.createElement("td");
  elo.innerText = Math.round(10*data.last_elo)/10;
  row.appendChild(elo);

  return row;
}

fetch_api("statistics.php").then(r => {
  let tableDay = document.querySelector("table[name=stat_day]");
  r.day_stats.forEach((item, i) => {
    tableDay.appendChild(createRow(item));
  });

  let tableAll = document.querySelector("table[name=stat_all]");
  r.all_stats.forEach((item, i) => {
    tableAll.appendChild(createRow(item));
  });

  let tableWeek = document.querySelector("table[name=stat_week]");
  r.week_stats.forEach((item, i) => {
    tableWeek.appendChild(createRow(item));
  });
});
