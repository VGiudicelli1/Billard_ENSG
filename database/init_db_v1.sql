/*
 * Create date: 2023-12-18
 * Last edit  : 2023-12-19
 * Publied    : --
 */

/*
 * clean database
 */

DROP VIEW IF EXISTS `view_statistics_day`;
DROP VIEW IF EXISTS `view_statistics_all`;

DROP TABLE IF EXISTS `player_game`;
DROP TABLE IF EXISTS `game`;
DROP TABLE IF EXISTS `player`;
DROP TABLE IF EXISTS `class`;


/*
 * Create tables structure
 */

CREATE TABLE `class` (
  `id` SERIAL PRIMARY KEY,          -- index PK
  `name` VARCHAR (256) NOT NULL     -- index UNIQUE
);
ALTER TABLE `class` ADD UNIQUE `class_name` (`name`);

CREATE TABLE `player` (
  `id` SERIAL PRIMARY KEY,          -- index PK
  `name` VARCHAR (256) NOT NULL,    -- index UNIQUE
  `class` BIGINT UNSIGNED NOT NULL, -- index FK
  `elo` DOUBLE NOT NULL DEFAULT 470   -- no index
);

ALTER TABLE `player` ADD UNIQUE `player_name` (`name`);
ALTER TABLE `player`
  ADD CONSTRAINT `fk_player_class`
  FOREIGN KEY (`class`)
  REFERENCES `class`(`id`);

CREATE TABLE `game` (
  `id` SERIAL PRIMARY KEY,          -- index PK
  `date` DATETIME NOT NULL          -- index timestamp
);

CREATE TABLE `player_game` (
  `player` BIGINT UNSIGNED NOT NULL, -- index PK && FK
  `game` BIGINT UNSIGNED NOT NULL,  -- index PK && FK
  `delta_elo` DOUBLE NOT NULL,          -- no index
  `new_elo` DOUBLE NOT NULL             -- no index
);

ALTER TABLE `player_game`
ADD CONSTRAINT `fk_player_game_player`
FOREIGN KEY (`player`)
REFERENCES `player`(`id`);

ALTER TABLE `player_game`
ADD CONSTRAINT `fk_player_game_game`
FOREIGN KEY (`game`)
REFERENCES `game`(`id`);

ALTER TABLE `player_game`
ADD PRIMARY KEY (`player`, `game`);

/*
 * Add views
 */

CREATE VIEW `view_statistics_day` AS
  SELECT
  	`p`.`name` AS `player`,
    `c`.`name` AS `class`,
    COUNT(*) AS `games`,
    COUNT(case `pg`.`delta_elo` > 0 when 1 then 1 else null end) AS `W`,
  	COUNT(case `pg`.`delta_elo` <= 0 when 1 then 1 else null end) AS `L`,
    SUM(`pg`.`delta_elo`) AS `delta_elo`,
    `p`.`elo` AS `last_elo`
  FROM `player_game` AS `pg`
  JOIN `game` AS `g` ON `pg`.`game` = `g`.`id`
  JOIN `player` AS `p` ON `pg`.`player` = `p`.`id`
  JOIN `class` AS `c` ON `c`.`id` = `p`.`class`
  WHERE DATE(`g`.`date`) = CURDATE()
  GROUP BY `p`.`name`
  ORDER BY `delta_elo` DESC
;

CREATE VIEW `view_statistics_all` AS
  SELECT
  	`p`.`name` AS `player`,
    `c`.`name` AS `class`,
    COUNT(*) AS `games`,
    COUNT(case `pg`.`delta_elo` > 0 when 1 then 1 else null end) AS `W`,
  	COUNT(case `pg`.`delta_elo` <= 0 when 1 then 1 else null end) AS `L`,
    SUM(`pg`.`delta_elo`) AS `delta_elo`,
    `p`.`elo` AS `last_elo`
  FROM `player_game` AS `pg`
  JOIN `game` AS `g` ON `pg`.`game` = `g`.`id`
  JOIN `player` AS `p` ON `pg`.`player` = `p`.`id`
  JOIN `class` AS `c` ON `c`.`id` = `p`.`class`
  GROUP BY `p`.`name`
  ORDER BY `delta_elo` DESC
;
