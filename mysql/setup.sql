DROP DATABASE IF EXISTS passwords;
CREATE DATABASE passwords DEFAULT CHARACTER SET utf8mb4;
USE passwords;

DROP USER IF EXISTS 'passwords_db_user'@'localhost';
CREATE USER 'passwords_db_user'@'localhost';
GRANT ALL PRIVILEGES ON passwords.* TO 'passwords_db_user'@'localhost';
FLUSH PRIVILEGES;

SET block_encryption_mode = 'aes-256-cbc';
SET @key_str = UNHEX(SHA2('nothing to see here', 256));
SET @init_vector = UNHEX('1234567890ABCDEF');

CREATE TABLE IF NOT EXISTS user (
  user_id      SMALLINT(5)  NOT NULL AUTO_INCREMENT,
  first_name   VARCHAR(128) NOT NULL,
  last_name    VARCHAR(128) NOT NULL,
  email        VARCHAR(128) NOT NULL,
  PRIMARY KEY (user_id)
);

CREATE TABLE IF NOT EXISTS website (
  site_id      SMALLINT(5)  NOT NULL AUTO_INCREMENT,
  site_name    VARCHAR(256) NOT NULL,
  domain       VARCHAR(256) NOT NULL,
  PRIMARY KEY (site_id)
);

CREATE TABLE IF NOT EXISTS password (
  user_id      SMALLINT(5)    NOT NULL,
  site_id      SMALLINT(5)    NOT NULL,
  username     VARCHAR(128)   NOT NULL,
  password     VARBINARY(256) NOT NULL,
  time_created TIMESTAMP      NOT NULL,
  comment      MEDIUMTEXT     NOT NULL,
  PRIMARY KEY (user_id, site_id)
);

INSERT INTO user (first_name, last_name, email) VALUES ('James', 'Harden', 'JHard94@gmail.com');
INSERT INTO user (first_name, last_name, email) VALUES ('Hatfield', 'Mackey', 'Mkay@yahoo.com');
INSERT INTO user (first_name, last_name, email) VALUES ('Harvey', 'Buzz', 'lightyear@gmail.com');
INSERT INTO user (first_name, last_name, email) VALUES ('Saul', 'Stew', 'goodman@gmail.com');

INSERT INTO website (site_name, domain) VALUES ('X', 'https://twitter.com');
INSERT INTO website (site_name, domain) VALUES ('Gmail', 'https://mail.google.com/');
INSERT INTO website (site_name, domain) VALUES ('Amazon', 'https://www.amazon.com/');

INSERT INTO password (user_id, site_id, username, password, time_created, comment) VALUES (1, 1, 'jhard94', AES_ENCRYPT('16buffal0s!', @key_str, @init_vector), NOW(), '');
INSERT INTO password (user_id, site_id, username, password, time_created, comment) VALUES (1, 2, 'jhard94', AES_ENCRYPT('32buff4los?', @key_str, @init_vector), NOW(), '');
INSERT INTO password (user_id, site_id, username, password, time_created, comment) VALUES (2, 1, 'mkay_999', AES_ENCRYPT('thatSjusTnoTmkaY12!', @key_str, @init_vector), NOW(), '');
INSERT INTO password (user_id, site_id, username, password, time_created, comment) VALUES (2, 3, 'mkay', AES_ENCRYPT('thatSjusTnoTmkaY12!', @key_str, @init_vector), NOW(), '');
INSERT INTO password (user_id, site_id, username, password, time_created, comment) VALUES (3, 2, 'lightyear', AES_ENCRYPT('TireShop44_44', @key_str, @init_vector), NOW(), '');
INSERT INTO password (user_id, site_id, username, password, time_created, comment) VALUES (3, 3, 'lightyear', AES_ENCRYPT('44_44TireShop', @key_str, @init_vector), NOW(), '');
INSERT INTO password (user_id, site_id, username, password, time_created, comment) VALUES (4, 1, 'slippinjimmy', AES_ENCRYPT('2B_or!2B', @key_str, @init_vector), NOW(), '');
INSERT INTO password (user_id, site_id, username, password, time_created, comment) VALUES (4, 2, 'slippinjimmy', AES_ENCRYPT('2B_or!2B', @key_str, @init_vector), NOW(), '');
INSERT INTO password (user_id, site_id, username, password, time_created, comment) VALUES (4, 3, 'slippinjimmy', AES_ENCRYPT('2B_or!2B', @key_str, @init_vector), NOW(), '');
