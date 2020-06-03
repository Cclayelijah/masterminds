
CREATE DATABASE masterminds CHARACTER SET utf8 COLLATE utf8_general_ci;

USE masterminds;

CREATE TABLE users (
user_id MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
first_name VARCHAR(20) NOT NULL,
last_name VARCHAR(40) NOT NULL,
email VARCHAR(60) NOT NULL,
pass CHAR(40) NOT NULL,
registration_date DATETIME NOT NULL,
PRIMARY KEY (user_id)
) ENGINE = INNODB;

CREATE TABLE ideas (
idea_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
user_id MEDIUMINT UNSIGNED NOT NULL,
name VARCHAR(100) NOT NULL,
description LONGTEXT NOT NULL,
image_path VARCHAR(1024) NOT NULL,
date_entered DATETIME NOT NULL,
likes INT NOT NULL DEFAULT 0,
pursuer VARCHAR(61) NOT NULL DEFAULT "",
PRIMARY KEY (idea_id),
FOREIGN KEY (user_id) REFERENCES users (user_id) ON DELETE NO ACTION
) ENGINE = INNODB;

CREATE TABLE comments (
comment_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
message LONGTEXT NOT NULL,
date_entered DATETIME NOT NULL,
user_id MEDIUMINT UNSIGNED NOT NULL,
idea_id INT UNSIGNED NOT NULL,
PRIMARY KEY (comment_id),
FOREIGN KEY (user_id) REFERENCES users (user_id) ON DELETE CASCADE ON UPDATE NO ACTION,
FOREIGN KEY (idea_id) REFERENCES ideas (idea_id) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE = INNODB;
