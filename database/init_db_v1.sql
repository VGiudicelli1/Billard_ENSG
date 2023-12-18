/*
 * Create date: 2023-12-18
 * Last edit  : 2023-12-18
 * Publied    : --
 */

DROP TABLE IF EXISTS `player_game`;
DROP TABLE IF EXISTS `game`;
DROP TABLE IF EXISTS `player`;
DROP TABLE IF EXISTS `class`;

CREATE TABLE `class` (
  `id` SERIAL PRIMARY KEY,          -- index PK
  `name` VARCHAR (256) NOT NULL     -- index UNIQUE
);
ALTER TABLE `class` ADD UNIQUE `class_name` (`name`);

CREATE TABLE `player` (
  `id` SERIAL PRIMARY KEY,          -- index PK
  `name` VARCHAR (256) NOT NULL,    -- index UNIQUE
  `class` BIGINT UNSIGNED NOT NULL  -- index FK
);

ALTER TABLE `player` ADD UNIQUE `player_name` (`name`);
ALTER TABLE `player`
  ADD CONSTRAINT `fk_player_class`
  FOREIGN KEY (`class`)
  REFERENCES `class`(`id`);

CREATE TABLE `game` (
  `id` SERIAL PRIMARY KEY,          -- index PK
  `date` DATE                       -- index timestamp
);

CREATE TABLE `player_game` (
  `player` BIGINT UNSIGNED NOT NULL, -- index PK && FK
  `game` BIGINT UNSIGNED NOT NULL,  -- index PK && FK
  `delta_elo` REAL,                   -- no index
  `new_elo` REAL                      -- no index
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
