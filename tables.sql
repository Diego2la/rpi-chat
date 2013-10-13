--
-- Структура таблицы `webchat_lines`
--

CREATE TABLE IF NOT EXISTS `webchat_lines` (
  `id` int(10) PRIMARY KEY AUTOINCREMENT NOT NULL,
  `author` varchar(16) NOT NULL,
  `avatar` int NOT NULL,
  `text` varchar(255) NOT NULL,
  'ts' DATE DEFAULT datetime('now','localtime'),
  KEY `ts` (`ts`)
) DEFAULT CHARSET=utf8;



-- --------------------------------------------------------

--
-- Структура таблицы `webchat_users`
--

CREATE TABLE `webchat_users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(16) NOT NULL,
  `avatar` int NOT NULL,
  `last_activity` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `last_activity` (`last_activity`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
