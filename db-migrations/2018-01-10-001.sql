
drop table task_list;
drop table task_sent;

CREATE TABLE IF NOT EXISTS `task_list` (
  `task_id` BIGINT NOT NULL AUTO_INCREMENT,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `scheduled` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `scheduled_orig` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `method` CHAR(20) NOT NULL,
  `url` TEXT NOT NULL,
  `body` TEXT NOT NULL,
  `headers` TEXT NOT NULL,
  `curl_opts` TEXT NOT NULL,
  `options` TEXT NOT NULL,
  `worker` CHAR(40) NOT NULL DEFAULT "",
  `assigned` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `retry_count` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`task_id`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `task_sent` (
  `task_id` BIGINT NOT NULL,
  `created` DATETIME NOT NULL,
  `scheduled` DATETIME NOT NULL,
  `scheduled_orig` DATETIME NOT NULL,
  `method` CHAR(20) NOT NULL,
  `url` TEXT NOT NULL,
  `body` TEXT NOT NULL,
  `headers` TEXT NOT NULL,
  `curl_opts` TEXT NOT NULL,
  `options` TEXT NOT NULL,
  `worker` CHAR(40) NOT NULL,
  `assigned` DATETIME NOT NULL,
  `retry_count` INT NOT NULL,
  `sent` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `resp_code` INT NOT NULL,
  `resp_headers` TEXT NOT NULL,
  `resp_body` TEXT NOT NULL,
  `resp_time` FLOAT NOT NULL,
  `resp_info` TEXT NOT NULL)
ENGINE = InnoDB;

