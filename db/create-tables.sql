DROP DATABASE IF EXISTS todo;

CREATE DATABASE todo;
use todo;

CREATE TABLE `todos` (
  `id` int(11) NOT NULL,
  `owner` int(11) NOT NULL,
  `todo` text NOT NULL,
  `c_date` date DEFAULT CURRENT_TIMESTAMP NOT NULL,
  `due_date` date DEFAULT NULL,
  `done` int(11) NOT NULL DEFAULT 0,
  `memo` text NULL,
  `org_filename` varchar(256) DEFAULT NULL,
  `real_filename` varchar(256) DEFAULT NULL,
  `url` varchar(256) DEFAULT NULL,
  `url_text` varchar(256) DEFAULT NULL,
  `public` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `todos` (`id`, `owner`, `todo`, `c_date`, `due_date`, `done`, `org_filename`, `real_filename`, `public`) VALUES
(1, 1, 'パソコンを買う',   CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 DAY), 0, NULL, NULL, 1),
(2, 2, '依頼の原稿を書く', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 7 DAY), 0, 'memo.txt', 'memo.txt', 1),
(3, 1, '政府高官との会食アポ', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 3 DAY), 0, NULL, NULL, 0);

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `userid` varchar(64) NOT NULL,
  `pwd` varchar(6) NOT NULL,
  `email` varchar(64) NOT NULL,
  `icon` varchar(128) NOT NULL,
  `super` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`id`, `userid`, `pwd`, `email`, `icon`, `super`) VALUES
(1, 'admin', 'passwd', 'root@example.jp', 'ockeghem.png', 1),
(2, 'wasbook', 'wasboo', 'wasbook@example.jp', 'elephant.png', 0);

CREATE TABLE `session` (
  `id` varchar(255) NOT NULL,
  `expire` int NOT NULL,
  `data` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;
