fetch_api("statistics.php").then(r => {
  let table = document.querySelector("table[name=stat_day]");
  r.day_stats.forEach((item, i) => {
    console.log(item);
    let row = document.createElement("tr");

    let player = document.createElement("td");
    player.innerText = item.player;
    row.appendChild(player);

    let classe = document.createElement("td");
    classe.innerText = item.class;
    row.appendChild(classe);

    let parties = document.createElement("td");
    parties.innerText = item.games;
    row.appendChild(parties);

    let victoires = document.createElement("td");
    victoires.innerText = item.W;
    row.appendChild(victoires);

    let defaites = document.createElement("td");
    defaites.innerText = item.L;
    row.appendChild(defaites);

    let ratio = document.createElement("td");
    ratio.innerText = Math.round(10000*item.W/item.games)/100 + " %";
    row.appendChild(ratio);

    let dElo = document.createElement("td");
    dElo.innerText = item.delta_elo;
    row.appendChild(dElo);

    let elo = document.createElement("td");
    elo.innerText = item.last_elo;
    row.appendChild(elo);

    table.appendChild(row);
  });

});
