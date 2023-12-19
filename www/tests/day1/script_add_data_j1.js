function log(data, key) {
  let div = document.createElement("div");
  div.classList.add("log");
  div.classList.add(key);
  div.innerText = data;
  document.querySelector("div.content").appendChild(div);
}

function logInfo(data) {
  log(data, "info");
}

function logResult(data) {
  log(data, "result");
}

function logDone(data) {
  log(data, "done");
}

function logError(data) {
  log(data, "error");
}

let classe = {
  1: "TSI",
  2: "DESIGEO",
  3: "IGAST",
  4: "DDMEG",
  5: "PPMD",
  6: "ING1",
  7: "ING2",
  8: "G1",
  9: "G2"
};

function add_class() {
  id += 1;
  if (classe[id] == null) {
    id = 0;
    step += 1;
    return doNext();
  }
  logInfo("Add class " + id + " (" + classe[id] + ")");
  fetch_api("add_class.php", {
    name: classe[id]
  }).then(r => {
    if (r.id_class != id) {
      logError("Wrong id returned. Get " + r.id_class + "   attend " + id);
    } else {
      logDone("Class added");
      doNext();
    }
  }).catch(err => {
    if (err & ERROR_INTERN) {
      logError("Erreur interne");
    } else if (err & ERROR_WRONG_VALUE) {
      logError("Mauvaise valeur (" + err + ")");
    } else {
      logError(err);
    }
  });
}

let players = {
  1: ["Paul", 2],
  2: ["Noé", 2],
  3: ["Baptiste", 3],
  4: ["Tristan", 3],
  5: ["Vincent", 1],
  6: ["Arthur", 3],
  7: ["Lauris", 3],
  8: ["Lilian", 4]
};

function add_player() {
  id += 1;
  if (players[id] == null) {
    id = 0;
    step += 1;
    return doNext();
  }
  logInfo("Add player " + id + " (" + players[id] + ")");
  fetch_api("add_player.php", {
    name: players[id][0],
    class_id: players[id][1]
  }).then(r => {
    if (r.id_player != id) {
      logError("Wrong id returned. Get " + r.id_player + "   attend " + id);
    } else {
      logDone("Player added");
      doNext();
    }
  }).catch(err => {
    if (err & ERROR_INTERN) {
      logError("Erreur interne");
    } else if (err & ERROR_WRONG_VALUE) {
      logError("Mauvaise valeur (" + err + ")");
    } else {
      logError(err);
    }
  });
}

function getPlayerId(player) {
  for (var id in players) {
    if (players[id][0] == player) {
      return id;
    }
  }
}

let games = [
  ["2023-12-18 12:30:00", "Paul", null, "Noé", null],
  ["2023-12-18 12:40:00", "Baptiste", "Tristan", "Vincent", "Arthur"],
  ["2023-12-18 12:50:00", "Vincent", "Lauris", "Tristan", "Baptiste"],
  ["2023-12-18 13:00:00", "Arthur", "Lilian", "Vincent", "Lauris"],
  ["2023-12-18 13:10:00", "Lauris", null, "Baptiste", null]
];

function add_game() {
  if (games[id] == null) {
    id = 0;
    step += 1;
    return doNext();
  }
  logInfo("Add game " + id + " (" + games[id] + ")");
  fetch_api("add_game.php", {
    date: games[id][0],
    j1: getPlayerId(games[id][1]),
    j2: getPlayerId(games[id][2]),
    j3: getPlayerId(games[id][3]),
    j4: getPlayerId(games[id][4])
  }).then(r => {
    logDone("Game added");
    doNext();
  }).catch(err => {
    if (err & ERROR_INTERN) {
      logError("Erreur interne");
    } else if (err & ERROR_WRONG_VALUE) {
      logError("Mauvaise valeur (" + err + ")");
    } else if (err & ERROR_NOT_DEVELOPED) {
      logError("Non développé");
    } else {
      logError(err);
    }
  });
  id += 1;
}

step = 1;
id = 0;
function doNext() {
  switch (step) {
    case 1: return add_class();
    case 2: return add_player();
    case 3: return add_game();
  }
}

doNext();
