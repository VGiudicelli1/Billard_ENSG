/*
 * Create date: 2023-12-18
 * Last edit  : 2023-12-20
 * Publied    : 2023-12-20
 */

/*
 * clean database
 */

DROP VIEW IF EXISTS `view_statistics_day`;
DROP VIEW IF EXISTS `view_statistics_week`;
DROP VIEW IF EXISTS `view_statistics_all`;
DROP VIEW IF EXISTS `player_game_date`;

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
  `date` DATETIME NOT NULL          -- index timestamp & unique
);

ALTER TABLE `game` ADD UNIQUE `game_date` (`date`);

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
    COUNT(`pg`.`game`) AS `games`,
    COUNT(case `pg`.`delta_elo` > 0 when 1 then 1 else null end) AS `W`,
  	COUNT(case `pg`.`delta_elo` <= 0 when 1 then 1 else null end) AS `L`,
    SUM(`pg`.`delta_elo`) AS `delta_elo`,
    `p`.`elo` AS `last_elo`
  FROM `player` AS `p`
  JOIN `class` AS `c` ON `c`.`id` = `p`.`class`
  JOIN `player_game` AS `pg` ON `pg`.`player` = `p`.`id`
  JOIN `game` AS `g` ON `pg`.`game` = `g`.`id`
  WHERE DATE(`g`.`date`) = CURRENT_DATE()
  GROUP BY `p`.`name`
  ORDER BY `elo` DESC
;

CREATE VIEW `view_statistics_week` AS
  SELECT
  	`p`.`name` AS `player`,
    `c`.`name` AS `class`,
    COUNT(`pg`.`game`) AS `games`,
    COUNT(case `pg`.`delta_elo` > 0 when 1 then 1 else null end) AS `W`,
  	COUNT(case `pg`.`delta_elo` <= 0 when 1 then 1 else null end) AS `L`,
    SUM(`pg`.`delta_elo`) AS `delta_elo`,
    `p`.`elo` AS `last_elo`
  FROM `player` AS `p`
  JOIN `class` AS `c` ON `c`.`id` = `p`.`class`
  JOIN `player_game` AS `pg` ON `pg`.`player` = `p`.`id`
  JOIN `game` AS `g` ON `pg`.`game` = `g`.`id`
  WHERE WEEK(`g`.`date`) = WEEK(CURRENT_DATE())
  GROUP BY `p`.`name`
  ORDER BY `elo` DESC
;


CREATE VIEW `view_statistics_all` AS
  SELECT
  	`p`.`name` AS `player`,
    `c`.`name` AS `class`,
    COUNT(`pg`.`game`) AS `games`,
    COUNT(case `pg`.`delta_elo` > 0 when 1 then 1 else null end) AS `W`,
  	COUNT(case `pg`.`delta_elo` <= 0 when 1 then 1 else null end) AS `L`,
    SUM(`pg`.`delta_elo`) AS `delta_elo`,
    `p`.`elo` AS `last_elo`
  FROM `player` AS `p`
  JOIN `class` AS `c` ON `c`.`id` = `p`.`class`
  LEFT JOIN `player_game` AS `pg` ON `pg`.`player` = `p`.`id`
  LEFT JOIN `game` AS `g` ON `pg`.`game` = `g`.`id`
  GROUP BY `p`.`name`
  ORDER BY `elo` DESC
;


CREATE VIEW `player_game_date` AS
  SELECT
    `pg`.`player` AS `player`,
    `pg`.`game` AS `game`,
    `pg`.`delta_elo` AS `delta_elo`,
    `pg`.`new_elo` AS `new_elo`,
    `g`.`date` AS `date`
  FROM `player_game` AS `pg`
  JOIN `game` AS `g` ON `pg`.`game` = `g`.`id`
;
