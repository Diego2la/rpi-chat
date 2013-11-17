CREATE TABLE IF NOT EXISTS `webchat_users` (
	`id`     INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
	`name`   varchar(16)                       NOT NULL,
	`last_activity` DATE DEFAULT (datetime('now','localtime')),
	UNIQUE (`name`)
);

INSERT INTO webchat_users (name) VALUES ('Veronika');
INSERT INTO webchat_users (name) VALUES ('Veronika2');
INSERT INTO webchat_users (name) VALUES ('Veronika3');

SELECT COUNT(*) FROM webchat_users WHERE name = 'Veronika';

INSERT OR REPLACE INTO webchat_users (name, last_activity) VALUES ('Veronika3', datetime('now'));
INSERT OR REPLACE INTO webchat_users (name, last_activity) VALUES ('Veronika4', datetime('now'));

INSERT OR IGNORE INTO webchat_users (name) VALUES ('Veronika4');
UPDATE webchat_users SET last_activity = datetime('now') WHERE name = 'Veronika4'		

DELETE FROM webchat_users WHERE name = 'Veronika3';
SELECT * FROM webchat_users ORDER BY name ASC LIMIT 10;
SELECT COUNT(*) as cnt FROM webchat_users;

DELETE FROM webchat_users WHERE (strftime('%M','now') - strftime('%M',last_activity)) > 1;
SELECT * FROM webchat_users;