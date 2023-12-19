/*
 * Create date: 2023-12-19
 * Last edit  : 2023-12-19
 * Publied    : --
 */

/*
Joueurs:
Paul, Noé, Baptiste, Tristan, Vincent, Arthur, Lauris, Lilian
*/
INSERT INTO `class` (`id`, `name`) VALUES
  (1, "TSI"), (2, "DESIGEO"), (3, "IGAST"), (4, "DDMEG"), (5, "PPMD"),
  (6, "ING1"), (7, "ING2"), (8, "G1"), (9, "G2")
;

INSERT INTO `player` (`id`, `name`, `class`) VALUES
  (1, "Paul", 2), (2, "Noé", 2), (3, "Baptiste", 3), (4, "Tristan", 3),
  (5, "Vincent", 1), (6, "Arthur", 3), (7, "Lauris", 3), (8, "Lilian", 3)
;

INSERT INTO `game` (`id`, `date`) VALUES
  (1, "2023-12-18 12:30:00"),
  (2, "2023-12-18 12:40:00"),
  (3, "2023-12-18 12:50:00"),
  (4, "2023-12-18 13:00:00"),
  (5, "2023-12-18 13:10:00")
;

INSERT INTO `player_game` (`player`, `game`, `delta_elo`, `new_elo`) VALUES
  (1, 1, 1, 1),
  (2, 1, -1, -1),
  (3, 2, 1, 1),
  (4, 2, 1, 1),
  (5, 2, -1, -1),
  (6, 2, -1, -1),
  (5, 3, 1, 0),
  (7, 3, 1, 1),
  (4, 3, -1, 0),
  (3, 3, -1, 0),
  (6, 4, 1, 0),
  (8, 4, 1, 1),
  (5, 4, -1, -1),
  (7, 4, -1, 0),
  (7, 5, 1, 1),
  (3, 5, -1, -1)
;
/*
Paul - Noe
Baptiste Tristan - Vincent Arthur
Vincent Lauris - Tristan Baptiste
Arthur Lilian - Vincent Lauris
Lauris - Baptiste
*/
