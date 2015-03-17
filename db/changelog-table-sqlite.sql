CREATE TABLE `changelog` (
  `id` INT PRIMARY KEY,
  `file` TEXT NOT NULL,
  `description` TEXT DEFAULT NULL,
  `query` TEXT NOT NULL,
  `error` TEXT DEFAULT NULL,
  `executed` INT DEFAULT 0,
  `ins_timestamp` INT DEFAULT NULL,
  `ins_dt` datetime NOT NULL,
  `upd_dt` timestamp NOT NULL,
  `del_flag` INT
);
